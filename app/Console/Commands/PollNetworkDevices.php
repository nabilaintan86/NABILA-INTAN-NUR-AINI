<?php

namespace App\Console\Commands;

use App\Console\Commands\Concerns\HasSharedPollLock;
use App\Models\ActivityLog;
use App\Models\Device;
use App\Services\DeviceCsvService;
use App\Services\DeviceStatusNotifier;
use App\Services\SnmpInspectionService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Str;

class PollNetworkDevices extends Command
{
    use HasSharedPollLock;

    private const WARNING_LATENCY_THRESHOLD_MS = 150;

    protected $signature = 'network:poll {--no-log : Jangan simpan riwayat log aktivitas} {--no-snmp : Lewati pemeriksaan SNMP (data tambahan, tidak memengaruhi status ICMP)}';

    protected $description = 'Poll all network devices via ICMP ping and update status';

    public function handle(DeviceCsvService $source, SnmpInspectionService $snmp, DeviceStatusNotifier $notifier): int
    {
        // Kunci bersama dengan monitor:ping: hanya satu siklus polling berat
        // yang boleh jalan dalam satu waktu.
        if (! $this->acquirePollLock()) {
            return self::SUCCESS;
        }

        try {
        // Hanya perangkat resmi (devices.csv) yang dipoll, satu kali per IP.
        $devices = $source->officialDevicesUnique();

        if ($devices->isEmpty()) {
            $this->warn('Tidak ada perangkat resmi yang terdaftar di devices.csv.');

            return self::SUCCESS;
        }

        $this->info("Memulai polling untuk {$devices->count()} device...");
        $log = ! $this->option('no-log');
        $inspectSnmp = ! $this->option('no-snmp');
        $snmp->beginCycle();

        foreach ($devices as $device) {
            $ip = trim((string) $device->ip_address);
            $this->line("  Polling {$device->name} ({$ip})...");

            $previousStatus = strtolower((string) $device->status);
            $processError = null;
            $raw = '';
            $exitCode = 1;

            if ($ip === '') {
                $raw = 'Invalid IP Address';
                $exitCode = 2;
            } else {
                try {
                    $result = Process::timeout(20)->run('ping -4 -c 1 -W 3 '.escapeshellarg($ip));

                    $exitCode = $result->exitCode();
                    $raw = trim($result->output()."\n".$result->errorOutput());
                } catch (\Throwable $e) {
                    $processError = $e->getMessage();
                    $this->warn("    Proses ping bermasalah untuk {$ip}: {$processError}");
                }
            }

            $device->increment('polls');

            if ($exitCode === 0) {
                $latency = $this->extractLatency($raw);
                $status = $latency !== null && $latency > self::WARNING_LATENCY_THRESHOLD_MS
                    ? 'warning'
                    : 'up';
                $reason = $status === 'warning' ? 'High Latency' : 'Normal';

                // SNMP hanya data tambahan: status di atas tetap hasil ICMP.
                $snmpInspection = $inspectSnmp
                    ? $snmp->inspectIfEligible($device, $status)
                    : $this->emptySnmpInspection();
                $logReason = Str::limit($snmp->decorateReason($reason, $snmpInspection), 255, '');

                if ($previousStatus !== $status) {
                    $device->increment('alerts_count');
                }

                $device->update([
                    'status' => $status,
                    'avg_delay' => $latency ?? 0,
                    'min_delay' => $latency !== null
                        ? min($device->min_delay !== 0 ? $device->min_delay : PHP_INT_MAX, $latency)
                        : 0,
                    'max_delay' => max($device->max_delay, $latency ?? 0),
                    'responded_percentage' => round($device->responded_percentage < 100
                        ? $device->responded_percentage + 0.02
                        : 100, 2),
                    'missed_percentage' => round($device->missed_percentage > 0
                        ? $device->missed_percentage - 0.02
                        : 0, 2),
                ]);

                if ($log) {
                    $this->writeLog($device, $status, $logReason, $latency);
                }

                $this->recordStatusNotification(
                    $notifier,
                    $device,
                    $previousStatus,
                    $status,
                    $latency,
                    $reason
                );

                $outputTag = $status === 'warning' ? 'comment' : 'info';
                $detail = $latency !== null ? $latency.' ms' : 'merespons';
                $this->line("    <{$outputTag}>{$status}</{$outputTag}> — {$detail}");

                $this->reportSnmpInspection($snmpInspection);
            } else {
                $reason = $processError !== null
                    ? 'Probe Error'
                    : $this->resolveFailureReason($raw, $exitCode);
                $status = $reason === 'Timed Out' ? 'warning' : 'down';

                if ($previousStatus !== $status) {
                    $device->increment('alerts_count');
                }

                $device->update([
                    'status' => $status,
                    'avg_delay' => 0,
                    'responded_percentage' => round($device->responded_percentage > 1
                        ? $device->responded_percentage - 0.05
                        : 0, 2),
                    'missed_percentage' => round($device->missed_percentage < 99
                        ? $device->missed_percentage + 0.05
                        : 100, 2),
                ]);

                if ($log) {
                    $this->writeLog($device, $status, $reason, null);
                }

                $this->recordStatusNotification(
                    $notifier,
                    $device,
                    $previousStatus,
                    $status,
                    null,
                    $reason
                );

                $outputTag = $status === 'warning' ? 'comment' : 'error';
                $this->line("    <{$outputTag}>{$status}</{$outputTag}> — {$reason}");
            }
        }

        $this->info('Polling selesai.');

        if ($inspectSnmp) {
            $this->line(sprintf(
                'SNMP: %d perangkat diperiksa, %d dilewati (mode: %s).',
                $snmp->probedCount(),
                $snmp->skippedCount(),
                $snmp->mode()
            ));
        }

        return self::SUCCESS;
        } finally {
            $this->releasePollLock();
        }
    }

    /**
     * @return array{inspected: bool, skipped_reason: ?string, summary: ?string, result: array<string, mixed>}
     */
    private function emptySnmpInspection(): array
    {
        return [
            'inspected' => false,
            'skipped_reason' => 'nonaktif (--no-snmp)',
            'summary' => null,
            'result' => [],
        ];
    }

    /**
     * Cetak satu baris informasi SNMP. Status ICMP tidak pernah diubah di sini.
     *
     * @param  array{inspected: bool, skipped_reason: ?string, summary: ?string, result: array<string, mixed>}  $inspection
     */
    private function reportSnmpInspection(array $inspection): void
    {
        if (empty($inspection['summary'])) {
            return;
        }

        $reachable = (bool) ($inspection['result']['reachable'] ?? false);
        $tag = $reachable ? 'info' : 'comment';

        $this->line("    <{$tag}>SNMP</{$tag}> — ".$inspection['summary']);
    }

    /**
     * Catat notifikasi HANYA ketika status benar-benar berubah.
     *
     * Keputusan transisi diambil DeviceStatusNotifier supaya tidak ada notifikasi
     * berulang untuk device yang statusnya tetap (DOWN -> DOWN / UP -> UP), dan
     * tetap ada satu notifikasi setiap siklus DOWN -> UP -> DOWN.
     */
    private function recordStatusNotification(
        DeviceStatusNotifier $notifier,
        Device $device,
        string $previousStatus,
        string $status,
        ?int $latency,
        string $reason
    ): void {
        $notification = $notifier->record(
            $device,
            $previousStatus,
            $status,
            $latency,
            $reason
        );

        if ($notification === null) {
            return;
        }

        $this->line(sprintf(
            '    <comment>Notifikasi</comment> — %s (%s)',
            $notification->event,
            $notification->status
        ));
    }

    protected function extractLatency(string $raw): ?int
    {
        if (preg_match('/time=([\d.]+)\s?ms/i', $raw, $matches)) {
            return (int) round((float) $matches[1]);
        }

        if (preg_match('/rtt\s+min\/avg\/max\/mdev\s*=\s*[\d.]+\/([\d.]+)\/[\d.]+\/[\d.]+\s*ms/i', $raw, $matches)) {
            return (int) round((float) $matches[1]);
        }

        if (preg_match('/round-trip\s+min\/avg\/max\/stddev\s*=\s*[\d.]+\/([\d.]+)\/[\d.]+\/[\d.]+\s*ms/i', $raw, $matches)) {
            return (int) round((float) $matches[1]);
        }

        if (preg_match('/Average\s*=\s*([\d.]+)\s*ms/i', $raw, $matches)) {
            return (int) round((float) $matches[1]);
        }

        return null;
    }

    protected function writeLog(Device $device, string $status, string $reason, ?int $latency): void
    {
        ActivityLog::create([
            'device_id' => $device->id,
            'device_name' => $device->name,
            'ip_address' => $device->ip_address,
            'status' => $status,
            'reason' => $reason,
            'latency' => $latency,
            'log_time' => now(),
        ]);
    }

    protected function resolveFailureReason(string $raw, int $exitCode): string
    {
        if ($exitCode > 1) {
            return 'Host Not Responding';
        }

        $lower = strtolower($raw);

        $patterns = [
            'destination host unreachable' => 'Host Unreachable',
            'request timeout' => 'Timed Out',
            'ttl expired' => 'IP_TTL_EXPIRED_TRANSIT',
            'network is unreachable' => 'Network Unreachable',
            'no answer' => 'No Answer',
            'name or service not known' => 'DNS Resolution Failed',
        ];

        foreach ($patterns as $needle => $label) {
            if (str_contains($lower, $needle)) {
                return $label;
            }
        }

        return 'Timed Out';
    }
}
