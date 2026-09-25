<?php

namespace App\Console\Commands;

use App\Models\ActivityLog;
use App\Models\Device;
use App\Models\DeviceNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Process;

class PollNetworkDevices extends Command
{
    private const WARNING_LATENCY_THRESHOLD_MS = 150;

    protected $signature = 'network:poll {--no-log : Jangan simpan riwayat log aktivitas}';

    protected $description = 'Poll all network devices via ICMP ping and update status';

    public function handle(): int
    {
        $devices = Device::all();

        if ($devices->isEmpty()) {
            $this->warn('Tidak ada device yang terdaftar di database.');

            return self::SUCCESS;
        }

        $this->info("Memulai polling untuk {$devices->count()} device...");
        $log = ! $this->option('no-log');

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
                    $this->writeLog($device, $status, $reason, $latency);
                }

                $this->recordStatusNotification(
                    $device,
                    $previousStatus,
                    $status,
                    $latency,
                    $reason
                );

                $outputTag = $status === 'warning' ? 'comment' : 'info';
                $detail = $latency !== null ? $latency.' ms' : 'merespons';
                $this->line("    <{$outputTag}>{$status}</{$outputTag}> — {$detail}");
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

        return self::SUCCESS;
    }

    private function recordStatusNotification(
        Device $device,
        string $previousStatus,
        string $status,
        ?int $latency,
        string $reason
    ): void {
        $shouldRecord = $previousStatus !== '' && $previousStatus !== $status;

        if (! $shouldRecord && $status === 'warning') {
            $shouldRecord = ! DeviceNotification::query()
                ->where('device_id', $device->id)
                ->where('status', 'warning')
                ->exists();
        }

        if (! $shouldRecord) {
            return;
        }

        [$eventType, $event, $message] = match ($status) {
            'warning' => [
                'warning',
                'WARNING',
                $latency !== null
                    ? "Ping berhasil, tetapi latency {$latency} ms melebihi threshold 150 ms."
                    : "Paket ping terakhir tidak dijawab ({$reason}).",
            ],
            'down' => [
                'down',
                'DOWN',
                "Tidak merespons ping: {$reason}.",
            ],
            default => [
                'recovered',
                'BACK ONLINE',
                $latency !== null
                    ? "Device kembali merespons ping dengan latency {$latency} ms."
                    : 'Device kembali merespons ping.',
            ],
        };

        DeviceNotification::create([
            'device_id' => $device->id,
            'device_name' => $device->name,
            'ip_address' => $device->ip_address,
            'event_type' => $eventType,
            'event' => $event,
            'status' => $status === 'up' ? 'online' : $status,
            'message' => $message,
            'latency' => $latency,
            'is_read' => false,
            'checked_at' => now(),
        ]);
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
