<?php

namespace App\Console\Commands;

use App\Models\ActivityLog;
use App\Models\Device;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Process;

class PollNetworkDevices extends Command
{
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
        $log = !$this->option('no-log');

        foreach ($devices as $device) {
            $ip = $device->ip_address;
            $this->line("  Polling {$device->name} ({$ip})...");

            $raw     = '';
            $exitCode = 1;

            try {
                $result = Process::timeout(20)->run('ping -4 -c 1 -W 3 '.escapeshellarg($ip));

                $exitCode = $result->exitCode();
                $raw      = trim($result->output());
            } catch (\Throwable $e) {
                $this->warn("    Proses ping bermasalah untuk {$ip}: {$e->getMessage()}");
            }

            $device->increment('polls');

            if ($exitCode === 0) {
                $latency = $this->extractLatency($raw);

                $device->update([
                    'status'                => 'up',
                    'avg_delay'             => $latency ?? 0,
                    'min_delay'             => $latency !== null ? min($device->min_delay !== 0 ? $device->min_delay : PHP_INT_MAX, $latency) : 0,
                    'max_delay'             => max($device->max_delay, $latency ?? 0),
                    'responded_percentage'  => round($device->responded_percentage < 100
                        ? $device->responded_percentage + 0.02
                        : 100, 2),
                    'missed_percentage'     => round($device->missed_percentage > 0
                        ? $device->missed_percentage - 0.02
                        : 0, 2),
                ]);

                if ($log) {
                    $this->writeLog($device, 'up', 'Normal', $latency);
                }

                $this->line("    <info>UP</info> — ".($latency !== null ? $latency.' ms' : 'merespons'));
            } else {
                $reason = $this->resolveFailureReason($raw, $exitCode);
                $status = $reason === 'Timed Out' ? 'warning' : 'down';

                if ($device->status !== $status) {
                    $device->increment('alerts_count');
                }

                $device->update([
                    'status'                => $status,
                    'avg_delay'             => 0,
                    'responded_percentage'  => round($device->responded_percentage > 1
                        ? $device->responded_percentage - 0.05
                        : 0, 2),
                    'missed_percentage'     => round($device->missed_percentage < 99
                        ? $device->missed_percentage + 0.05
                        : 100, 2),
                ]);

                if ($log) {
                    $this->writeLog($device, $status, $reason, null);
                }

                $this->line("    <error>{$status}</error> — {$reason}");
            }
        }

        $this->info('Polling selesai.');
        return self::SUCCESS;
    }

    protected function extractLatency(string $raw): ?int
    {
        if (preg_match('/time=([\d.]+)\s?ms/', $raw, $matches)) {
            return (int) round((float) $matches[1]);
        }

        if (preg_match('/\b([\d.]+)\s+ms\b.*?\b([\d.]+)\s+ms\b.*?\b([\d.]+)\s+ms\b/', $raw, $matches)) {
            return (int) round((float) $matches[3]);
        }

        return null;
    }

    protected function writeLog(Device $device, string $status, string $reason, ?int $latency): void
    {
        ActivityLog::create([
            'device_id'   => $device->id,
            'device_name' => $device->name,
            'ip_address'  => $device->ip_address,
            'status'      => $status,
            'reason'      => $reason,
            'latency'     => $latency,
            'log_time'    => now(),
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
            'request timeout'              => 'Timed Out',
            'ttl expired'                  => 'IP_TTL_EXPIRED_TRANSIT',
            'network is unreachable'       => 'Network Unreachable',
            'no answer'                    => 'No Answer',
            'name or service not known'    => 'DNS Resolution Failed',
        ];

        foreach ($patterns as $needle => $label) {
            if (str_contains($lower, $needle)) {
                return $label;
            }
        }

        return 'Timed Out';
    }
}