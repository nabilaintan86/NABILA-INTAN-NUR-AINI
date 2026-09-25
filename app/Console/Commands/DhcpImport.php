<?php

namespace App\Console\Commands;

use App\Services\DhcpImporter;
use App\Services\DhcpRecognition;
use Illuminate\Console\Command;

class DhcpImport extends Command
{
    protected $signature = 'dhcp:import
        {file=storage/app/dhcp/devices.csv : Path ke file devices.csv (daftar device real Radnext)}
        {--source= : Label sumber data (default: nama file)}';

    protected $description = 'Import data lease dari devices.csv dan klasifikasikan terhadap tabel device';

    public function handle(): int
    {
        $argument = $this->argument('file');
        $path = $this->isAbsolutePath($argument)
            ? $argument
            : base_path($argument);

        if (!is_file($path)) {
            $this->error('File devices.csv tidak ditemukan di storage/app/dhcp/');
            $this->line("Path absolut yang dicari: {$path}");
            $this->line('Letakkan devices.csv di folder tersebut (atau lewati argumen file), lalu jalankan ulang.');

            return self::FAILURE;
        }

        try {
            $recognition = new DhcpRecognition();
            $this->info("Registry device dikenal: {$recognition->knownCount()} perangkat dari tabel device.");
            $this->info("Membaca file: {$path}");

            $result = (new DhcpImporter())->importFile(
                $path,
                $this->option('source'),
                function (int $index, int $total) {
                    $this->output->write(sprintf("\rImporting %d/%d... ", $index, $total));
                }
            );

            $this->newLine();
            $this->info('Selesai.');
            $this->info(sprintf(
                '%d lease berhasil di-import, %d Known, %d New, %d Unknown.',
                $result['imported'],
                $result['known'],
                $result['new'],
                $result['unknown']
            ));

            if ($result['skipped'] > 0) {
                $this->warn("Baris dilewati (tidak valid / tanpa IP address): {$result['skipped']}");
            }

            return self::SUCCESS;
        } catch (\InvalidArgumentException $e) {
            $this->error($e->getMessage());
            $this->line('Import dibatalkan, tidak ada perubahan data.');

            return self::FAILURE;
        }
    }

    protected function isAbsolutePath(string $path): bool
    {
        return str_starts_with($path, DIRECTORY_SEPARATOR)
            || preg_match('/^[a-zA-Z]:[\\\\\/]/', $path) === 1;
    }
}