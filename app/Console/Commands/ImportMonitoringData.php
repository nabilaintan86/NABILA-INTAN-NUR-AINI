<?php

namespace App\Console\Commands;

use Database\Seeders\ActivityLogSeeder;
use Database\Seeders\DeviceConnectionSeeder;
use Database\Seeders\DeviceSeeder;
use Database\Seeders\NetworkAreaSeeder;
use Database\Seeders\PopMappingSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ImportMonitoringData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'monitoring:import
                            {--fresh : Jalankan migrate:fresh sebelum import data}
                            {--sql : Import langsung dari file database/sql/monitoring_jaringan.sql}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import dan sinkronisasi seluruh data Monitoring WhatsUp Gold 2026 (Users, Devices, Card Area POP, Relasi Koneksi)';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('===========================================================');
        $this->info('  IMPORT DATA MONITORING JARINGAN (WHATSUP GOLD 2026)      ');
        $this->info('===========================================================');

        $isFresh = $this->option('fresh');
        $isSql = $this->option('sql');

        if ($isFresh) {
            $this->warn('Menjalankan php artisan migrate:fresh ...');
            Artisan::call('migrate:fresh', ['--force' => true], $this->output);
        } else {
            $this->info('Menjalankan php artisan migrate ...');
            Artisan::call('migrate', ['--force' => true], $this->output);
        }

        $sqlPath = database_path('sql/monitoring_jaringan.sql');

        if ($isSql && File::exists($sqlPath)) {
            $this->info('Mengimpor data langsung dari: ' . $sqlPath);
            try {
                DB::unprepared(File::get($sqlPath));
                $this->info('✓ Data SQL berhasil diimpor ke database!');
            } catch (\Throwable $e) {
                $this->error('Gagal mengimpor SQL: ' . $e->getMessage());
                $this->warn('Melanjutkan via Laravel Seeders...');
                $this->runSeeders();
            }
        } else {
            $this->runSeeders();
        }

        $deviceCount = DB::table('devices')->count();
        $areaCount = DB::table('network_areas')->count();
        $connCount = DB::table('device_connections')->count();
        $userCount = DB::table('users')->count();

        $this->newLine();
        $this->info('-----------------------------------------------------------');
        $this->info('  RINGKASAN DATA BERHASIL DI-IMPORT / TERSEDIA:            ');
        $this->info('-----------------------------------------------------------');
        $this->line(sprintf('  • Users              : %d akun', $userCount));
        $this->line(sprintf('  • Perangkat (Device) : %d node', $deviceCount));
        $this->line(sprintf('  • Card Area (POP)    : %d area pembatas', $areaCount));
        $this->line(sprintf('  • Relasi Koneksi     : %d link (dengan direction)', $connCount));
        $this->info('-----------------------------------------------------------');
        $this->info('✓ Sukses! Sistem siap digunakan di pull / environment ini.');
        $this->info('===========================================================');

        return Command::SUCCESS;
    }

    protected function runSeeders(): void
    {
        $this->info('Menjalankan seeder data...');

        $this->line('  1/5 Menjalankan UserSeeder ...');
        $this->call(UserSeeder::class);

        $this->line('  2/5 Menjalankan DeviceSeeder ...');
        $this->call(DeviceSeeder::class);

        $this->line('  3/5 Menjalankan PopMappingSeeder ...');
        $this->call(PopMappingSeeder::class);

        $this->line('  4/5 Menjalankan NetworkAreaSeeder (16 Area POP 2026) ...');
        $this->call(NetworkAreaSeeder::class);

        $this->line('  5/5 Menjalankan DeviceConnectionSeeder (63 Relasi + Arah Panah) ...');
        $this->call(DeviceConnectionSeeder::class);
    }
}

