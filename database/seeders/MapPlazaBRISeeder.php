<?php

namespace Database\Seeders;

use App\Models\Device;
use App\Models\DeviceConnection;
use Illuminate\Database\Seeder;

class MapPlazaBRISeeder extends Seeder
{
    public function run(): void
    {
        $devices = [
            [
                'name' => '56.17',
                'type' => 'ICMP',
                'ip_address' => '202.154.56.17',
                'icon_type' => 'router',
                'pop_name' => 'Plaza BRI',
                'pos_x' => 100,
                'pos_y' => 100,
                'status' => 'up',
            ],
            [
                'name' => 'Dst BRI',
                'type' => 'ICMP',
                'ip_address' => '202.154.4.20',
                'icon_type' => 'server',
                'pop_name' => 'Plaza BRI',
                'pos_x' => 100,
                'pos_y' => 250,
                'status' => 'up',
            ],
            [
                'name' => 'TPS 1',
                'type' => 'ICMP',
                'ip_address' => '202.154.4.21',
                'icon_type' => 'wifi',
                'pop_name' => 'Plaza BRI',
                'pos_x' => 100,
                'pos_y' => 400,
                'status' => 'up',
            ],
            [
                'name' => 'TPS 2',
                'type' => 'ICMP',
                'ip_address' => '202.154.4.22',
                'icon_type' => 'wifi',
                'pop_name' => 'Plaza BRI',
                'pos_x' => 250,
                'pos_y' => 400,
                'status' => 'up',
            ],
            [
                'name' => 'EBAY',
                'type' => 'ICMP',
                'ip_address' => '202.154.4.23',
                'icon_type' => 'switch',
                'pop_name' => 'Plaza BRI',
                'pos_x' => 250,
                'pos_y' => 250,
                'status' => 'up',
            ],
            [
                'name' => 'Switch SFP',
                'type' => 'ICMP',
                'ip_address' => '202.154.4.24',
                'icon_type' => 'switch',
                'pop_name' => 'Plaza BRI',
                'pos_x' => 400,
                'pos_y' => 250,
                'status' => 'up',
            ],
        ];

        $map = [];

        foreach ($devices as $index => $device) {
            $created = Device::updateOrCreate(
                ['ip_address' => $device['ip_address']],
                $device
            );

            $map[$device['icon_type'].'_'.$device['name']] = $created->id;
        }

        $from = [
            'router_56.17',
            'server_Dst BRI',
            'switch_EBAY',
            'switch_EBAY',
            'switch_EBAY',
        ];

        $to = [
            'server_Dst BRI',
            'switch_EBAY',
            'wifi_TPS 1',
            'wifi_TPS 2',
            'switch_Switch SFP',
        ];

        foreach ($from as $i => $fromKey) {
            DeviceConnection::updateOrCreate(
                [
                    'from_device_id' => $map[$fromKey],
                    'to_device_id' => $map[$to[$i]],
                ],
                ['connection_type' => 'ethernet']
            );
        }
    }
}
