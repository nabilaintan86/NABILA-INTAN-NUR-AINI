<?php

namespace Database\Seeders;

use App\Models\Device;
use Illuminate\Database\Seeder;

class PopMappingSeeder extends Seeder
{
    protected array $areaDevices = [
        'Plaza BRI'    => [8, 51, 52, 24, 46, 82, 25, 26],
        'Bumi Mandiri' => [78, 79, 80, 81],
        'Intiland'     => [2, 12, 37, 42, 83, 58, 3, 44, 59, 53, 5, 65, 66, 55, 56, 4, 6, 7],
        'EPON-BU'      => [85, 86, 87],
        'POP SMK-YPM'  => [10, 11, 19, 20, 21, 38, 39, 40, 43, 62, 63, 71, 88, 89, 90, 91],
        'POP Sham FM'  => [92, 48, 57, 113, 47, 68, 114, 69, 70, 115, 49, 84, 45, 112],
        'POP SMK8'     => [72, 73, 74],
        'POP Rajasa'   => [61, 67, 77],
        'POP Gresik'   => [1],
        'POP Yapalis'  => [93, 94, 13, 96],
        'POP SMK2'     => [18, 50],
        'POP Vuri'     => [75, 76],
        'POP SMKN 1'   => [108, 109, 110, 111],
        'GRUP HIJAU'   => [64, 16, 36, 15, 14, 17, 106, 105, 22, 23, 95, 104, 107, 101, 102, 103, 98, 99, 100, 9, 60, 97],
        'Hotel Bumi'   => [27, 28, 29, 30, 31, 32, 33, 34, 35, 54],
    ];

    public function run(): void
    {
        $idToPop = [];
        foreach ($this->areaDevices as $popName => $ids) {
            foreach ($ids as $id) {
                $idToPop[$id] = $popName;
            }
        }

        foreach (Device::all() as $device) {
            $pop = $idToPop[$device->id] ?? 'Lainnya';
            $device->update([
                'pop_name' => $pop,
            ]);
        }
    }
}
