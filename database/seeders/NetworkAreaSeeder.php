<?php

namespace Database\Seeders;

use App\Models\Device;
use App\Models\NetworkArea;
use Illuminate\Database\Seeder;

class NetworkAreaSeeder extends Seeder
{
    /**
     * Konfigurasi awal 15 card area POP WhatsUp Gold (RADNET 2026)
     * Lengkap dengan koordinat X, Y, ukuran Width, Height, dan palet warna asli.
     */
    protected array $areas = [
        'Plaza BRI' => [
            'pos_x'        => 8,
            'pos_y'        => 20,
            'width'        => 282,
            'height'       => 212,
            'border_color' => '#8b1e1e',
            'bg_color'     => '#f8fafc',
            'title_color'  => '#8b1e1e',
            'sort_order'   => 1,
            'device_ids'   => [8, 24, 25, 26, 46, 51, 52, 82],
        ],
        'Intiland' => [
            'pos_x'        => 320,
            'pos_y'        => 18,
            'width'        => 282,
            'height'       => 470,
            'border_color' => '#1a8024',
            'bg_color'     => '#f8fafc',
            'title_color'  => '#1a8024',
            'sort_order'   => 2,
            'device_ids'   => [2, 3, 4, 5, 6, 7, 12, 37, 42, 44, 53, 55, 56, 58, 59, 65, 66, 83],
        ],
        'POP Yapalis' => [
            'pos_x'        => 622,
            'pos_y'        => 18,
            'width'        => 146,
            'height'       => 212,
            'border_color' => '#0000ff',
            'bg_color'     => '#f8fafc',
            'title_color'  => '#0000ff',
            'sort_order'   => 3,
            'device_ids'   => [13, 93, 94, 96],
        ],
        'POP SMK2' => [
            'pos_x'        => 788,
            'pos_y'        => 18,
            'width'        => 212,
            'height'       => 126,
            'border_color' => '#cc0000',
            'bg_color'     => '#f8fafc',
            'title_color'  => '#cc0000',
            'sort_order'   => 4,
            'device_ids'   => [18, 50],
        ],
        'GRUP HIJAU' => [
            'pos_x'        => 1020,
            'pos_y'        => 18,
            'width'        => 166,
            'height'       => 1092,
            'border_color' => '#1a8024',
            'bg_color'     => '#f8fafc',
            'title_color'  => '#1a8024',
            'sort_order'   => 5,
            'device_ids'   => [9, 14, 15, 16, 17, 22, 23, 36, 60, 64, 95, 97, 98, 99, 100, 101, 102, 103, 104, 105, 106, 107],
        ],
        'Hotel Bumi' => [
            'pos_x'        => 1207,
            'pos_y'        => 21,
            'width'        => 367,
            'height'       => 535,
            'border_color' => '#1a8024',
            'bg_color'     => '#f8fafc',
            'title_color'  => '#1a8024',
            'sort_order'   => 6,
            'device_ids'   => [27, 28, 29, 30, 31, 32, 33, 34, 35, 54],
        ],
        'Bumi Mandiri' => [
            'pos_x'        => 14,
            'pos_y'        => 251,
            'width'        => 282,
            'height'       => 384,
            'border_color' => '#0033cc',
            'bg_color'     => '#f8fafc',
            'title_color'  => '#0033cc',
            'sort_order'   => 7,
            'device_ids'   => [78, 79, 80, 81],
        ],
        'POP Vuri' => [
            'pos_x'        => 320,
            'pos_y'        => 508,
            'width'        => 281,
            'height'       => 168,
            'border_color' => '#9c27b0',
            'bg_color'     => '#f8fafc',
            'title_color'  => '#9c27b0',
            'sort_order'   => 8,
            'device_ids'   => [75, 76],
        ],
        'POP SMKN 1' => [
            'pos_x'        => 622,
            'pos_y'        => 250,
            'width'        => 147,
            'height'       => 428,
            'border_color' => '#7b1fa2',
            'bg_color'     => '#f8fafc',
            'title_color'  => '#7b1fa2',
            'sort_order'   => 9,
            'device_ids'   => [108, 109, 110, 111],
        ],
        'POP SMK-YPM' => [
            'pos_x'        => 788,
            'pos_y'        => 164,
            'width'        => 212,
            'height'       => 616,
            'border_color' => '#001f66',
            'bg_color'     => '#f8fafc',
            'title_color'  => '#001f66',
            'sort_order'   => 10,
            'device_ids'   => [10, 11, 19, 20, 21, 38, 39, 40, 43, 62, 63, 71, 88, 89, 90, 91],
        ],
        'POP Gresik' => [
            'pos_x'        => 18,
            'pos_y'        => 654,
            'width'        => 282,
            'height'       => 126,
            'border_color' => '#6d4c41',
            'bg_color'     => '#f8fafc',
            'title_color'  => '#6d4c41',
            'sort_order'   => 11,
            'device_ids'   => [1],
        ],
        'EPON-BU' => [
            'pos_x'        => 18,
            'pos_y'        => 800,
            'width'        => 114,
            'height'       => 318,
            'border_color' => '#8b1e1e',
            'bg_color'     => '#f8fafc',
            'title_color'  => '#8b1e1e',
            'sort_order'   => 12,
            'device_ids'   => [85, 86, 87],
        ],
        'POP SMK8' => [
            'pos_x'        => 152,
            'pos_y'        => 800,
            'width'        => 122,
            'height'       => 318,
            'border_color' => '#00838f',
            'bg_color'     => '#f8fafc',
            'title_color'  => '#00838f',
            'sort_order'   => 13,
            'device_ids'   => [72, 73, 74],
        ],
        'POP Rajasa' => [
            'pos_x'        => 294,
            'pos_y'        => 800,
            'width'        => 138,
            'height'       => 318,
            'border_color' => '#00838f',
            'bg_color'     => '#f8fafc',
            'title_color'  => '#00838f',
            'sort_order'   => 14,
            'device_ids'   => [61, 67, 77],
        ],
        'POP Sham FM' => [
            'pos_x'        => 452,
            'pos_y'        => 800,
            'width'        => 548,
            'height'       => 318,
            'border_color' => '#8b1e1e',
            'bg_color'     => '#f8fafc',
            'title_color'  => '#8b1e1e',
            'sort_order'   => 15,
            'device_ids'   => [45, 47, 48, 49, 57, 68, 69, 70, 84, 92, 112, 113, 114, 115],
        ],
        'FUri' => [
            'pos_x'        => 1206,
            'pos_y'        => 578,
            'width'        => 380,
            'height'       => 293,
            'border_color' => '#1a8024',
            'bg_color'     => '#f8fafc',
            'title_color'  => '#1a8024',
            'sort_order'   => 16,
            'device_ids'   => [],
        ],
    ];

    public function run(): void
    {
        foreach ($this->areas as $name => $data) {
            $deviceIds = $data['device_ids'] ?? [];
            unset($data['device_ids']);

            $area = NetworkArea::updateOrCreate(
                ['name' => $name],
                $data
            );

            // Hubungkan seluruh device di area ini
            if (!empty($deviceIds)) {
                Device::whereIn('id', $deviceIds)->update([
                    'network_area_id' => $area->id,
                    'pop_name'        => $name,
                ]);
            }
        }
    }
}

