<?php

namespace Database\Seeders;

use App\Models\ActivityLog;
use App\Models\Device;
use Illuminate\Database\Seeder;

class ActivityLogSeeder extends Seeder
{
    public function run(): void
    {
        $devices = Device::all();

        if ($devices->isEmpty()) {
            $this->command?->warn('Tidak ada device, lewati Seeder ActivityLog.');
            return;
        }

        ActivityLog::query()->delete();

        $now = now();

        $entries = [
            ['device' => 'POP Gresik',   'status' => 'up',      'reason' => 'Normal',                        'latency' => 4],
            ['device' => 'Intiland 1',   'status' => 'up',      'reason' => 'Normal',                        'latency' => 0],
            ['device' => 'PJB',          'status' => 'up',      'reason' => 'Normal',                        'latency' => 5],
            ['device' => 'Border',       'status' => 'down',    'reason' => 'Host Unreachable',              'latency' => null],
            ['device' => 'SMKN-1',       'status' => 'up',      'reason' => 'Normal',                        'latency' => 3],
            ['device' => 'SBY-JKT',      'status' => 'down',    'reason' => 'Timed Out',                     'latency' => null],
            ['device' => 'IIX-JI',       'status' => 'warning', 'reason' => 'IP_TTL_EXPIRED_TRANSIT',        'latency' => null],
            ['device' => '56:17',        'status' => 'up',      'reason' => 'Normal',                        'latency' => 2],
            ['device' => 'DNS 1',        'status' => 'up',      'reason' => 'Normal',                        'latency' => 1],
            ['device' => '93.11',        'status' => 'up',      'reason' => 'Normal',                        'latency' => 7],
            ['device' => 'POP YPM',      'status' => 'up',      'reason' => 'Normal',                        'latency' => 6],
            ['device' => 'SM-FM',        'status' => 'down',    'reason' => 'Host Unreachable',              'latency' => null],
            ['device' => 'Pop Krian',    'status' => 'up',      'reason' => 'Normal',                        'latency' => 4],
        ];

        foreach ($entries as $i => $entry) {
            $device = $devices->firstWhere('name', $entry['device']);

            if (!$device) {
                continue;
            }

            ActivityLog::create([
                'device_id'   => $device->id,
                'device_name' => $device->name,
                'ip_address'  => $device->ip_address,
                'status'      => $entry['status'],
                'reason'      => $entry['reason'],
                'latency'     => $entry['latency'],
                'log_time'    => $now->copy()->subMinutes($i * 5 + 7),
            ]);
        }
    }
}