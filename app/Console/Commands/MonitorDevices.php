<?php

namespace App\Console\Commands;

use App\Models\Device;
use App\Models\MonitoringLog;
use App\Services\DeviceCsvService;
use App\Services\DeviceStatusNotifier;
use App\Services\SnmpInspectionService;
use App\Services\TelegramService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class MonitorDevices extends Command
{
    protected $signature = 'monitor:ping {--no-snmp : Lewati pemeriksaan SNMP (data tambahan, tidak memengaruhi status ICMP)}';

    protected $description = 'Ping semua device dan update status ke database';

    public function handle(DeviceCsvService $source, SnmpInspectionService $snmp)
    {
        // Hanya perangkat resmi (devices.csv) yang diping, satu kali per IP.
        $devices = $source->officialDevicesUnique();

        if ($devices->isEmpty()) {
            $this->warn('Tidak ada device resmi yang terdaftar di devices.csv.');

            return self::SUCCESS;
        }

        $this->info("Memulai monitoring untuk {$devices->count()} devices...");

        $inspectSnmp = ! $this->option('no-snmp');
        $snmp->beginCycle();

        $bar = $this->output->createProgressBar($devices->count());
        $bar->start();

        $upCount = 0;
        $downCount = 0;

        foreach ($devices as $device) {
            try {
                $isUp = $this->pingDevice($device, $inspectSnmp ? $snmp : null);

                if ($isUp) {
                    $upCount++;
                } else {
                    $downCount++;
                }
            } catch (\Throwable $e) {
                $this->newLine();

                $this->error(
                    "Gagal memproses {$device->name} ({$device->ip_address}): {$e->getMessage()}"
                );

                $downCount++;
            }

            $bar->advance();
        }

        $bar->finish();

        $this->newLine(2);

        $this->info('Monitoring selesai!');
        $this->line("UP   : {$upCount}");
        $this->line("DOWN : {$downCount}");
        $this->line("TOTAL: {$devices->count()}");

        if ($inspectSnmp) {
            $this->line(sprintf(
                'SNMP : diperiksa %d, dilewati %d (mode: %s)',
                $snmp->probedCount(),
                $snmp->skippedCount(),
                $snmp->mode()
            ));
        }

        return self::SUCCESS;
    }

    protected function pingDevice(Device $device, ?SnmpInspectionService $snmp = null): bool
    {
        $ip = trim($device->ip_address);

        if (empty($ip)) {
            $this->warn("IP kosong untuk device: {$device->name}");

            return false;
        }

        $escapedIp = escapeshellarg($ip);

        $output = [];
        $resultCode = 0;

        /*
        |--------------------------------------------------------------------------
        | Ping berdasarkan operating system
        |--------------------------------------------------------------------------
        */

        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            exec(
                "ping -n 3 -w 1000 {$escapedIp}",
                $output,
                $resultCode
            );
        } else {
            exec(
                "ping -c 3 -W 1 {$escapedIp} 2>&1",
                $output,
                $resultCode
            );
        }

        $outputStr = implode("\n", $output);
        $now = now();

        /*
         * Simpan status lama SEBELUM diupdate oleh hasil ping terbaru.
         * Dipakai sebagai pembanding untuk mendeteksi perubahan status.
         */
        $previousStatus = strtolower((string) $device->status);

        /*
        |--------------------------------------------------------------------------
        | DEVICE UP
        |--------------------------------------------------------------------------
        */

        if ($resultCode === 0) {
            $latency = $this->parseLatency($outputStr);

            /*
             * Kalau ping berhasil tetapi latency gagal dibaca,
             * tetap dianggap UP, tetapi latency dibuat 0.
             */
            $latency = max(0, $latency);

            MonitoringLog::create([
                'device_id' => $device->id,
                'device_name' => $device->name,
                'ip_address' => $device->ip_address,
                'status' => 'up',
                'response_time' => $latency,
                'checked_at' => $now,
            ]);

            $this->updateDeviceStatistics(
                $device,
                'up',
                $latency,
                $now
            );

            $this->handleStatusTransition(
                $device,
                $previousStatus,
                'up',
                $latency,
                $now
            );

            /*
             * SNMP purely informatif: hanya dicatat di log aplikasi & console,
             * tidak menyentuh tabel monitoring_logs maupun notifikasi.
             */
            $this->collectSnmpInsight($device, 'up', $snmp);

            return true;
        }

        /*
        |--------------------------------------------------------------------------
        | DEVICE DOWN
        |--------------------------------------------------------------------------
        */

        MonitoringLog::create([
            'device_id' => $device->id,
            'device_name' => $device->name,
            'ip_address' => $device->ip_address,
            'status' => 'down',
            'response_time' => null,
            'checked_at' => $now,
        ]);

        $this->updateDeviceStatistics(
            $device,
            'down',
            null,
            $now
        );

        $this->handleStatusTransition(
            $device,
            $previousStatus,
            'down',
            null,
            $now
        );

        $this->collectSnmpInsight($device, 'down', $snmp);

        return false;
    }

    /**
     * Kumpulkan informasi SNMP sebagai pelengkap status ICMP.
     *
     * Tidak pernah melempar exception dan tidak pernah mengubah status device,
     * sehingga monitoring tetap berjalan meski SNMP gagal/tidak tersedia.
     */
    private function collectSnmpInsight(Device $device, string $icmpStatus, ?SnmpInspectionService $snmp): void
    {
        if ($snmp === null) {
            return;
        }

        try {
            $inspection = $snmp->inspectIfEligible($device, $icmpStatus);
        } catch (\Throwable $e) {
            Log::warning('SNMP enrichment gagal diproses', [
                'device' => $device->name,
                'ip' => $device->ip_address,
                'error' => $e->getMessage(),
            ]);

            return;
        }

        if (empty($inspection['summary'])) {
            return;
        }

        Log::info('SNMP monitoring insight', [
            'device' => $device->name,
            'ip' => $device->ip_address,
            'icmp_status' => $icmpStatus,
            'snmp' => $inspection['summary'],
            'reachable' => (bool) ($inspection['result']['reachable'] ?? false),
        ]);
    }

    private function updateDeviceStatistics(
        Device $device,
        string $status,
        ?int $latency,
        $now
    ): void {
        /*
        |--------------------------------------------------------------------------
        | Hitung statistik berdasarkan seluruh histori device
        |--------------------------------------------------------------------------
        */

        $statistics = MonitoringLog::where('device_id', $device->id)
            ->selectRaw("
                COUNT(*) as total,
                SUM(CASE WHEN status = 'up' THEN 1 ELSE 0 END) as successful,
                SUM(CASE WHEN status = 'down' THEN 1 ELSE 0 END) as failed,
                AVG(CASE WHEN status = 'up' THEN response_time END) as average_latency,
                MIN(CASE WHEN status = 'up' THEN response_time END) as minimum_latency,
                MAX(CASE WHEN status = 'up' THEN response_time END) as maximum_latency
            ")
            ->first();

        $total = (int) ($statistics->total ?? 0);
        $successful = (int) ($statistics->successful ?? 0);
        $failed = (int) ($statistics->failed ?? 0);

        $respondedPercentage = $total > 0
            ? round(($successful / $total) * 100, 2)
            : 0;

        $missedPercentage = $total > 0
            ? round(($failed / $total) * 100, 2)
            : 0;

        /*
        |--------------------------------------------------------------------------
        | Update data utama device
        |--------------------------------------------------------------------------
        */

        $updateData = [
            'status' => $status,
            'polls' => $total,
            'responded_percentage' => $respondedPercentage,
            'missed_percentage' => $missedPercentage,
            'avg_delay' => (int) round($statistics->average_latency ?? 0),
            'min_delay' => (int) ($statistics->minimum_latency ?? 0),
            'max_delay' => (int) ($statistics->maximum_latency ?? 0),
            'period' => $now->format('H:i'),
        ];

        /*
        |--------------------------------------------------------------------------
        | Kalau DOWN, tambah alert
        |--------------------------------------------------------------------------
        */

        if ($status === 'down') {
            $updateData['alerts_count'] = (int) $device->alerts_count + 1;

            if (empty($device->down_time) || $device->status !== 'down') {
                $updateData['down_time'] = $now->format('Y-m-d H:i:s');
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Kalau kembali UP setelah sebelumnya DOWN
        |--------------------------------------------------------------------------
        */

        if ($status === 'up' && $device->status === 'down') {
            $updateData['down_time'] = null;
        }

        $device->update($updateData);
    }

    /**
     * Deteksi perubahan status dan kirim notifikasi ke web + Telegram.
     *
     * Hanya transisi UP -> DOWN dan DOWN -> UP yang memicu notifikasi.
     * Status yang tidak berubah (UP -> UP / DOWN -> DOWN), status kosong/null,
     * atau status yang tidak dikenal tidak akan memicu notifikasi apa pun.
     */
    private function handleStatusTransition(
        Device $device,
        ?string $previousStatus,
        string $newStatus,
        ?int $latency,
        $now
    ): void {
        /*
        |--------------------------------------------------------------------------
        | Edge case: device belum pernah punya status (null / kosong).
        | Jangan kirim alert palsu saat monitoring pertama kali dijalankan.
        |--------------------------------------------------------------------------
        */

        if (empty($previousStatus)) {
            return;
        }

        if (! in_array($previousStatus, ['up', 'down'], true)) {
            return;
        }

        if (! in_array($newStatus, ['up', 'down'], true)) {
            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Tidak ada perubahan status -> tidak ada notifikasi
        | UP -> UP : skip
        | DOWN -> DOWN : skip
        |--------------------------------------------------------------------------
        */

        if ($previousStatus === $newStatus) {
            return;
        }

        $waktu = $now->format('Y-m-d H:i:s');

        $this->recordWebNotification($device, $previousStatus, $newStatus, $latency);
        $this->sendTelegramNotification($device, $previousStatus, $newStatus, $waktu);
    }

    private function recordWebNotification(
        Device $device,
        string $previousStatus,
        string $newStatus,
        ?int $latency
    ): void {
        $notifier = app(DeviceStatusNotifier::class);
        $isRecovery = $newStatus === 'up';

        $notification = $notifier->record(
            $device,
            $previousStatus,
            $newStatus,
            $isRecovery ? $latency : null,
            'Host unreachable',
            $isRecovery
                ? ($latency !== null
                    ? "Device kembali aktif, respons {$latency} ms."
                    : 'Device kembali aktif dan merespons ping.')
                : 'Device tidak merespons ping. Host unreachable.'
        );

        // Transisi yang sama sudah tercatat: tidak perlu notifikasi kedua.
        if ($notification === null) {
            return;
        }

        $this->info("Notifikasi web: {$device->name} -> {$notification->event}");
    }

    private function sendTelegramNotification(
        Device $device,
        string $previousStatus,
        string $newStatus,
        string $waktu
    ): void {
        try {
            $telegram = app(TelegramService::class);

            $data = [
                'device' => $device->name,
                'ip' => $device->ip_address,
                'type' => $device->display_protocol,
                'time' => $waktu,
            ];

            if ($previousStatus === 'up' && $newStatus === 'down') {
                if (! $telegram->broadcastDeviceDown($data)) {
                    Log::warning('Telegram broadcast DOWN gagal terkirim ke semua penerima', [
                        'device' => $device->name,
                        'ip' => $device->ip_address,
                        'time' => $waktu,
                    ]);
                }
            } elseif ($previousStatus === 'down' && $newStatus === 'up') {
                if (! $telegram->broadcastDeviceRecovered($data)) {
                    Log::warning('Telegram broadcast RECOVERED gagal terkirim ke semua penerima', [
                        'device' => $device->name,
                        'ip' => $device->ip_address,
                        'time' => $waktu,
                    ]);
                }
            }
        } catch (\Throwable $e) {
            Log::error('Telegram notification error untuk device', [
                'device' => $device->name,
                'ip' => $device->ip_address,
                'error' => $e->getMessage(),
            ]);

            $this->error(
                "Gagal mengirim Telegram untuk {$device->name}: {$e->getMessage()}"
            );
        }
    }

    private function parseLatency(string $output): int
    {
        /*
        |--------------------------------------------------------------------------
        | Linux
        | rtt min/avg/max/mdev = 0.045/0.052/0.065/0.008 ms
        |--------------------------------------------------------------------------
        */

        if (
            preg_match(
                '/rtt min\/avg\/max\/mdev = [\d.]+\/([\d.]+)\//',
                $output,
                $matches
            )
        ) {
            return (int) round((float) $matches[1]);
        }

        if (
            preg_match(
                '/round-trip min\/avg\/max\/stddev = [\d.]+\/([\d.]+)\//',
                $output,
                $matches
            )
        ) {
            return (int) round((float) $matches[1]);
        }

        /*
        |--------------------------------------------------------------------------
        | Windows
        |--------------------------------------------------------------------------
        */

        if (
            preg_match(
                '/Average = (\d+)ms/i',
                $output,
                $matches
            )
        ) {
            return (int) $matches[1];
        }

        return 0;
    }
}
