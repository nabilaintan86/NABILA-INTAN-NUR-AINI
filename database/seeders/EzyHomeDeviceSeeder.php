<?php

namespace Database\Seeders;

use App\Models\EzyHomeArea;
use App\Models\EzyHomeDevice;
use App\Models\EzyHomePosition;
use Illuminate\Database\Seeder;

class EzyHomeDeviceSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Bikin 1 area default EzyHome
        $area = EzyHomeArea::firstOrCreate(
            ['name' => 'EzyHome'],
            [
                'code'         => 'EZYHOME',
                'description'  => 'Jaringan EzyHome',
                'pos_x'        => 20,
                'pos_y'        => 20,
                'width'        => 500,
                'height'       => 400,
                'border_color' => '#2563eb',
                'bg_color'     => '#f8fafc',
                'title_color'  => '#2563eb',
                'sort_order'   => 1,
                'is_active'    => true,
            ]
        );

        // 2. Baca devices.csv, ambil 50 baris pertama
        $csvPath = base_path('devices.csv');
        if (! is_file($csvPath)) {
            $this->command->error("File devices.csv tidak ditemukan di: {$csvPath}");
            return;
        }

        $handle = fopen($csvPath, 'r');
        if ($handle === false) {
            $this->command->error('Gagal membuka devices.csv');
            return;
        }

        $rows = [];
        $isHeader = true;

        while (($row = fgetcsv($handle, 0, ',', '"', '\\')) !== false) {
            if ($isHeader) {
                $isHeader = false;
                continue;
            }

            if (empty($row[0])) continue;

            $rows[] = $row;
            if (count($rows) >= 50) break;
        }

        fclose($handle);

        // 3. Insert device + position
        $created = 0;
        $cols = 5; // 5 kolom per baris grid
        $gapX = 80;
        $gapY = 100;

        foreach ($rows as $i => $row) {
            $rawName = trim((string) ($row[0] ?? ''));
            $ip      = trim((string) ($row[1] ?? ''));
            $type    = trim((string) ($row[2] ?? 'ICMP'));

            // Bersihkan nama: buang "(ip)" di akhir
            $name = preg_replace('/\s*\([^)]*\)\s*$/', '', $rawName);
            $name = trim($name);

            if ($name === '') continue;

            // Normalisasi type -> Router/Switch/Server/Other
            $typeNorm = 'Other';
            $typeLower = strtolower($type);
            if (str_contains($typeLower, 'router')) $typeNorm = 'Router';
            elseif (str_contains($typeLower, 'switch')) $typeNorm = 'Switch';
            elseif (str_contains($typeLower, 'server')) $typeNorm = 'Server';
            elseif (str_contains($typeLower, 'olt')) $typeNorm = 'OLT';
            elseif (str_contains($typeLower, 'epon')) $typeNorm = 'EPON';
            elseif (str_contains($typeLower, 'wifi') || str_contains($typeLower, 'access')) $typeNorm = 'Access Point';

            $device = EzyHomeDevice::create([
                'name'       => $name,
                'ip_address' => $ip ?: null,
                'type'       => $typeNorm,
                'protocol'   => 'ICMP',
                'status'     => 'unknown',
                'source'     => 'db',
                'pop_name'   => 'EzyHome',
            ]);

            // Posisi grid di dalam area
            $col = $i % $cols;
            $rowN = intdiv($i, $cols);
            $posX = $area->pos_x + 40 + ($col * $gapX);
            $posY = $area->pos_y + 60 + ($rowN * $gapY);

            EzyHomePosition::create([
                'ezyhome_device_id' => $device->id,
                'ezyhome_area_id'   => $area->id,
                'pos_x'             => $posX,
                'pos_y'             => $posY,
            ]);

            $created++;
        }

        $this->command->info("✓ Berhasil membuat {$created} device EzyHome.");
    }
}