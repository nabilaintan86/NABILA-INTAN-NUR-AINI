<?php

namespace Database\Seeders;

use App\Models\Device;
use Illuminate\Database\Seeder;

class RealDeviceSeeder extends Seeder
{
    public function run(): void
    {
        $devices = [
            [
                'name'       => 'POP Gresik',
                'hostname'   => 'pop-gresik',
                'type'       => 'router',
                'ip_address' => '202.154.4.254',
                'site'       => 'Plaza BRI',
                'status'     => 'down',
            ],
            [
                'name'       => 'Intiland 1',
                'hostname'   => 'intiland-1',
                'type'       => 'router',
                'ip_address' => '202.154.4.4',
                'site'       => 'Plaza BRI',
                'status'     => 'down',
            ],
            [
                'name'       => 'PJB',
                'hostname'   => 'pjb',
                'type'       => 'router',
                'ip_address' => '202.154.4.134',
                'site'       => 'Plaza BRI',
                'status'     => 'down',
            ],
            [
                'name'       => 'Border',
                'hostname'   => 'border',
                'type'       => 'router',
                'ip_address' => '202.154.4.3',
                'site'       => 'Plaza BRI',
                'status'     => 'down',
            ],
            [
                'name'       => 'SMKN-1',
                'hostname'   => 'smkn-1',
                'type'       => 'router',
                'ip_address' => '202.154.4.241',
                'site'       => 'Plaza BRI',
                'status'     => 'down',
            ],
            [
                'name'       => 'SBY-JKT',
                'hostname'   => 'sby-jkt',
                'type'       => 'router',
                'ip_address' => '202.154.0.29',
                'site'       => 'Plaza BRI',
                'status'     => 'down',
            ],
            [
                'name'       => 'IIX-JI',
                'hostname'   => 'iix-ji',
                'type'       => 'router',
                'ip_address' => '218.100.32.1',
                'site'       => 'Plaza BRI',
                'status'     => 'down',
            ],
            [
                'name'       => '56.17',
                'hostname'   => '56-17',
                'type'       => 'router',
                'ip_address' => '202.154.56.17',
                'site'       => 'Plaza BRI',
                'status'     => 'down',
            ],
        ];

        foreach ($devices as $device) {
            Device::updateOrCreate(
                ['ip_address' => $device['ip_address']],
                $device
            );
        }
    }
}
