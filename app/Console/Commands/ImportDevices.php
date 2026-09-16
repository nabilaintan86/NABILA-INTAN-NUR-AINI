<?php

namespace App\Console\Commands;

use App\Models\Device;
use Illuminate\Console\Command;

class ImportDevices extends Command
{
    protected $signature = 'import:devices';
    protected $description = 'Import data device dari file CSV';

    public function handle()
    {
        // Taruh file devices.csv di folder utama project (sejajar dengan folder app, routes, dll)
        $filePath = base_path('devices.csv');

        if (!file_exists($filePath)) {
            $this->error("File CSV tidak ditemukan di: {$filePath}");
            return;
        }

        $file = fopen($filePath, 'r');
        
        // Skip baris pertama (header)
        fgetcsv($file);

        $count = 0;
        $this->info("Memulai import data...");

        while (($row = fgetcsv($file)) !== false) {
            // Lewati baris yang kosong
            if (empty($row[0])) continue;

            // Mapping kolom CSV ke kolom database
            // CSV: [0] NAMA, [1] IP ADD, [2] TYPE, [3] DESKRIPSI, [4] GAMBAR, [5] STATUS
            Device::create([
                'name'       => trim($row[0]),
                'ip_address' => trim($row[1]),
                'type'       => trim($row[2] ?? 'ICMP'),
                'status'     => 'unknown', // Default status
            ]);

            $count++;
        }

        fclose($file);
        $this->info("Berhasil mengimpor {$count} devices ke database!");
    }
}