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
            'pos_x'        => 18,
            'pos_y'        => 18,
            'width'        => 282,
            'height'       => 212,
            'border_color' => '#8b1e1e',
            'bg_color'     => '#f8fafc',
            'title_color'  => '#8b1e1e',
            'sort_order'   => 1,
            'device_ids'   => [8, 51, 52, 24, 46, 82, 25, 26],
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
            'device_ids'   => [2, 12, 37, 42, 83, 58, 3, 44, 59, 53, 5, 65, 66, 55, 56, 4, 6, 7],
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
            'device_ids'   => [93, 94, 13, 96],
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
            'width'        => 142,
            'height'       => 1118,
            'border_color' => '#1a8024',
            'bg_color'     => '#f8fafc',
            'title_color'  => '#1a8024',
            'sort_order'   => 5,
            'device_ids'   => [64, 16, 36, 15, 14, 17, 106, 105, 22, 23, 95, 104, 107, 101, 102, 103, 98, 99, 100, 9, 60, 97],
        ],
        'Hotel Bumi' => [
            'pos_x'        => 1182,
            'pos_y'        => 18,
            'width'        => 146,
            'height'       => 1118,
            'border_color' => '#1a8024',
            'bg_color'     => '#f8fafc',
            'title_color'  => '#1a8024',
            'sort_order'   => 6,
            'device_ids'   => [27, 28, 29, 30, 31, 32, 33, 34, 35, 54],
        ],
        'Bumi Mandiri' => [
            'pos_x'        => 18,
            'pos_y'        => 250,
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
            'width'        => 282,
            'height'       => 272,
            'border_color' => '#9c27b0',
            'bg_color'     => '#f8fafc',
            'title_color'  => '#9c27b0',
            'sort_order'   => 8,
            'device_ids'   => [75, 76],
        ],
        'POP SMKN 1' => [
            'pos_x'        => 622,
            'pos_y'        => 250,
            'width'        => 146,
            'height'       => 530,
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
            'device_ids'   => [92, 48, 57, 113, 47, 68, 114, 69, 70, 115, 49, 84, 45, 112],
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

