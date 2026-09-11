<?php

namespace Database\Seeders;

use App\Models\Device;
use App\Models\TroubleshootingLog;
use Illuminate\Database\Seeder;

class TroubleshootingLogSeeder extends Seeder
{
    public function run(): void
    {
        $devices = Device::all();

        if ($devices->isEmpty()) {
            $this->command?->warn('Tidak ada device, lewati Seeder TroubleshootingLog.');
            return;
        }

        TroubleshootingLog::query()->delete();

        $now = now();

        $entries = [
            ['device' => 'Border',   'status' => 'down',    'latency' => null, 'error' => 'Destination Host Unreachable'],
            ['device' => 'IIX-JI',   'status' => 'warning', 'latency' => 320,  'error' => 'Request timeout pada probe ke-2'],
            ['device' => 'SBY-JKT',  'status' => 'down',    'latency' => null, 'error' => '100% packet loss (3/3 probe waktu habis)'],
            ['device' => 'Border',   'status' => 'warning', 'latency' => 180,  'error' => 'Latency tinggi, 1 probe tidak dijawab'],
            ['device' => 'IIX-JI',   'status' => 'up',      'latency' => 12,   'error' => null],
            ['device' => 'SBY-JKT',  'status' => 'up',      'latency' => 9,    'error' => null],
            ['device' => 'Border',   'status' => 'down',    'latency' => null, 'error' => 'No route to host'],
            ['device' => 'IIX-JI',   'status' => 'up',      'latency' => 8,    'error' => null],
            ['device' => 'SBY-JKT',  'status' => 'warning', 'latency' => 205,  'error' => 'RTO pada probe terakhir'],
            ['device' => 'Border',   'status' => 'up',      'latency' => 14,   'error' => null],
        ];

        foreach ($entries as $i => $entry) {
            $device = $devices->firstWhere('name', $entry['device']);

            if (!$device) {
                continue;
            }

            $output = $entry['status'] === 'up'
                ? "PING {$device->ip_address} 56(84) bytes of data.\n64 bytes from {$device->ip_address}: icmp_seq=1 ttl=60 time={$entry['latency']}.0 ms\n\n--- {$device->ip_address} ping statistics ---\n3 packets transmitted, 3 received, 0% packet loss"
                : "PING {$device->ip_address} 56(84) bytes of data.\n\n--- {$device->ip_address} ping statistics ---\n3 packets transmitted, 0 received, 100% packet loss";

            TroubleshootingLog::create([
                'device_id'    => $device->id,
                'device_name'  => $device->name,
                'ip_address'   => $device->ip_address,
                'status'       => $entry['status'],
                'latency'      => $entry['latency'],
                'diagnostic'   => 'ICMP Ping (3 probe)',
                'action'       => 'Run Instant Diagnostic',
                'error'        => $entry['error'],
                'output'       => $output,
                'duration_ms'  => rand(180, 1200),
                'checked_at'   => $now->copy()->subMinutes($i * 22 + 6),
            ]);
        }
    }
}