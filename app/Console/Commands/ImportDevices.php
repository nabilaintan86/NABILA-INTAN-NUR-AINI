<?php

namespace App\Console\Commands;

use App\Helpers\DeviceType;
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

        if (! file_exists($filePath)) {
            $this->error("File CSV tidak ditemukan di: {$filePath}");

            return;
        }

        $file = fopen($filePath, 'r');

        // Skip baris pertama (header)
        fgetcsv($file);

        $count = 0;
        $this->info('Memulai import data...');

        while (($row = fgetcsv($file)) !== false) {
            // Lewati baris yang kosong
            if (empty($row[0])) {
                continue;
            }

            $name = trim($row[0]);
            $rawType = trim($row[2] ?? '');
            $protocol = DeviceType::normalizeProtocol($rawType);
            $type = in_array(strtoupper($rawType), DeviceType::protocols(), true)
                ? DeviceType::inferType($name)
                : DeviceType::normalizeType($rawType);

            Device::create([
                'name' => $name,
                'ip_address' => trim($row[1]),
                'type' => $type,
                'protocol' => $protocol,
                'status' => 'unknown',
            ]);

            $count++;
        }

        fclose($file);
        $this->info("Berhasil mengimpor {$count} devices ke database!");
    }
}
