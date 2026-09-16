<?php

namespace Database\Seeders;

use App\Models\Device;
use Illuminate\Database\Seeder;

class PopMappingSeeder extends Seeder
{
    protected array $popMapping = [
        'Plaza BRI' => [
            '202.154.56.17',
            '202.154.4.4',
            '192.168.90.13',
            '192.168.35.21',
        ],
        'Intiland' => [
            '192.168.168.2',
            '192.168.168.3',
            '202.154.59.38',
            '202.154.4.250',
            '192.168.87.3',
            '192.168.206.2',
            '192.168.205.2',
        ],
        'POP Yapalis' => [
            '202.154.56.126',
            '202.154.56.74',
            '192.168.92.26',
        ],
        'POP SMK2' => [
            '202.154.4.83',
            '202.154.4.241',
            '192.168.94.2',
        ],
        'Bumi Mandiri' => [
            '192.168.35.5',
            '10.100.0.0',
            '203.153.114.121',
        ],
        'POP SMK 1' => [
            '202.154.4.238',
            '192.168.93.60',
            '192.168.93.61',
            '192.168.93.59',
        ],
        'POP SMK-YRM' => [
            '192.168.93.11',
            '192.168.96.10',
            '202.154.57.84',
            '202.154.58.28',
            '103.150.151.152',
        ],
        'Hotel Bumi' => [
            '10.100.0.2',
            '192.168.36.5',
            '192.168.36.6',
            '192.168.36.7',
            '192.168.36.8',
            '192.168.36.9',
            '192.168.36.10',
        ],
        'POP Gresik' => [
            '202.154.4.254',
        ],
        'POP SMK 8' => [
            '202.154.4.82',
            '192.168.94.67',
            '192.168.94.66',
            '192.168.94.3',
        ],
        'POP Rajasa' => [
            '192.168.94.12',
            '202.154.4.246',
        ],
        'POP Syam FM' => [
            '202.154.4.222',
            '192.168.93.35',
        ],
    ];

    public function run(): void
    {
        $ipToPop = [];

        foreach ($this->popMapping as $popName => $ips) {
            foreach ($ips as $ip) {
                $ipToPop[$ip] = $popName;
            }
        }

        foreach (Device::all() as $device) {
            $device->update([
                'pop_name' => $ipToPop[$device->ip_address] ?? 'Lainnya',
                'icon_type' => $this->guessIconType($device->name),
            ]);
        }
    }

    protected function guessIconType(string $name): string
    {
        $name = strtolower($name);

        if (str_contains($name, 'sw') || str_contains($name, 'switch')) {
            return 'switch';
        }

        if (str_contains($name, 'router') || str_contains($name, 'core')) {
            return 'router';
        }

        if (str_contains($name, 'ap') || str_contains($name, 'lt') || str_contains($name, 'wifi')) {
            return 'wifi';
        }

        if (str_contains($name, 'dns') || str_contains($name, 'pop') || str_contains($name, 'server')) {
            return 'server';
        }

        return 'server';
    }
}
