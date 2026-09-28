<?php

namespace Database\Seeders;

use App\Helpers\DeviceType;
use App\Models\Device;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DeviceSeeder extends Seeder
{
    public function run(): void
    {
        $csvPath = base_path('devices.csv');
        if (! file_exists($csvPath)) {
            return;
        }

        // Down and warning devices matching the WhatsUp Gold network diagram
        $downIps = [
            '202.154.4.3',    // Border
            '202.154.0.29',   // SBY-JKT
            '202.154.4.83',   // SMKN2
            '192.168.94.3',   // to SMKN8
            '192.168.94.2',   // to SMK2
            '202.154.4.82',   // R.SMK8
            '192.168.94.67',  // AP SMK8
            '192.168.93.30',  // Perbanas
            '192.168.92.35',  // 92.35
            '192.168.92.36',  // Karantina
            '192.168.36.10',  // LT 22
            '192.168.93.194', // To SMK Nasional
        ];

        $warnIps = [
            '218.100.32.1',   // IIX-JI
        ];

        $file = fopen($csvPath, 'r');
        fgetcsv($file); // skip header

        $devices = [];
        $id = 1;

        while (($row = fgetcsv($file)) !== false) {
            if (empty($row[0])) {
                continue;
            }

            $rawName = trim((string) $row[0]);
            $ip = trim((string) ($row[1] ?? ''));
            $cleanName = preg_replace('/\s+/u', ' ', trim(
                preg_replace('/\(\s*[\d.:]+\s*\)\s*$/u', '',
                    preg_replace('/\*+/u', '',
                        str_replace(["\xc2\xa0", "\xa0"], ' ', $rawName)
                    )
                )
            ));

            $type = DeviceType::inferType($cleanName);
            $protocol = 'ICMP';
            $status = in_array($ip, $downIps, true)
                ? 'down'
                : (in_array($ip, $warnIps, true) ? 'warning' : 'up');

            $devices[] = [
                'id'                   => $id,
                'name'                 => $cleanName,
                'ip_address'           => $ip,
                'type'                 => $type,
                'protocol'             => $protocol,
                'icon_type'            => DeviceType::iconKey($type),
                'status'               => $status,
                'polls'                => 15238,
                'responded_percentage' => $status === 'down' ? 0.00 : ($status === 'warning' ? 88.50 : 99.85),
                'missed_percentage'    => $status === 'down' ? 100.00 : ($status === 'warning' ? 11.50 : 0.15),
                'down_time'            => $status === 'down' ? '12:45:00' : '00:00:00',
                'period'               => '30d 0h 30m',
                'alerts_count'         => $status === 'down' ? 8 : ($status === 'warning' ? 2 : 0),
                'avg_delay'            => $status === 'down' ? 0 : ($status === 'warning' ? 120 : 4),
                'min_delay'            => $status === 'down' ? 0 : 1,
                'max_delay'            => $status === 'down' ? 0 : ($status === 'warning' ? 450 : 120),
                'created_at'           => now(),
                'updated_at'           => now(),
            ];

            $id++;
        }

        fclose($file);

        if (DB::getDriverName() === 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            Device::truncate();
            Device::insert($devices);
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        } else {
            Device::truncate();
            Device::insert($devices);
        }
    }
}
