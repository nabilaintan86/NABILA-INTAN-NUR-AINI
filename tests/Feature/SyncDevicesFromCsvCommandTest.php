<?php

namespace Tests\Feature;

use App\Models\Device;
use App\Services\DeviceCsvService;
use Tests\Feature\Concerns\CreatesDeviceTables;
use Tests\TestCase;

/**
 * `php artisan devices:sync` harus AUDIT dulu. Tidak boleh menambah device
 * untuk mengejar angka, dan tidak boleh menghapus apa pun tanpa --prune.
 */
class SyncDevicesFromCsvCommandTest extends TestCase
{
    use CreatesDeviceTables;

    protected function setUp(): void
    {
        parent::setUp();

        $this->createDeviceTables();

        Device::create(['name' => 'POP Lama', 'ip_address' => '10.0.0.1', 'status' => 'up']);
        Device::create(['name' => 'OLT KEN', 'ip_address' => '10.0.0.2', 'status' => 'down']);
        Device::create(['name' => 'Dummy Note', 'ip_address' => '10.0.0.99', 'status' => 'down']);
    }

    protected function useRegistry(string $csv): void
    {
        $path = tempnam(sys_get_temp_dir(), 'registry-').'.csv';
        file_put_contents($path, $csv);
        $this->beforeApplicationDestroyed(fn () => is_file($path) && @unlink($path));

        $this->app->singleton(DeviceCsvService::class, fn () => new DeviceCsvService()->usePath($path));
    }

    protected function registry(string $extra = ''): string
    {
        return "NAMA,IP ADD,TYPE,DESKRIPSI,GAMBAR,STATUS\n"
            ."POP SBY,10.0.0.1,ICMP,,,,up\n"
            ."OLT KEN,10.0.0.2,ICMP,,,,down\n"
            .$extra;
    }

    public function test_dry_run_reports_discrepancy_without_touching_the_database(): void
    {
        $this->useRegistry($this->registry());

        $this->artisan('devices:sync')
            ->expectsOutputToContain('AUDIT SUMBER DATA PERANGKAT')
            ->expectsOutputToContain('DRY-RUN')
            ->assertSuccessful();

        $this->assertSame(3, Device::count(), 'tidak ada baris yang ditambah');
        $this->assertSame('POP Lama', Device::where('ip_address', '10.0.0.1')->value('name'), 'nama tidak diubah');
        $this->assertDatabaseHas('device', ['ip_address' => '10.0.0.99', 'name' => 'Dummy Note']);
    }

    public function test_dry_run_lists_csv_row_missing_from_database(): void
    {
        $this->useRegistry($this->registry("Router Baru,10.0.0.7,ICMP,,,,up\n"));

        $this->artisan('devices:sync')
            ->expectsOutputToContain('TIDAK ada di database')
            ->expectsOutputToContain('10.0.0.7')
            ->assertSuccessful();

        // Tidak otomatis menambah device hanya agar angka cocok.
        $this->assertDatabaseMissing('device', ['ip_address' => '10.0.0.7']);
    }

    public function test_apply_updates_names_to_follow_the_csv(): void
    {
        $this->useRegistry($this->registry());

        $this->artisan('devices:sync --apply')->assertSuccessful();

        $this->assertSame('POP SBY', Device::where('ip_address', '10.0.0.1')->value('name'));
        $this->assertSame(3, Device::count(), 'apply tanpa --prune tidak menambah/menghapus baris');
        $this->assertDatabaseHas('device', ['ip_address' => '10.0.0.99', 'name' => 'Dummy Note']);
    }

    public function test_prune_requires_apply_and_removes_only_rows_outside_the_csv(): void
    {
        $this->useRegistry($this->registry());

        $this->artisan('devices:sync --prune')
            ->expectsOutputToContain('--prune hanya boleh dipakai bersama --apply')
            ->assertFailed();

        $this->assertSame(3, Device::count(), 'gagal tanpa efek samping');

        $this->artisan('devices:sync --apply --prune')->assertSuccessful();

        $this->assertSame(2, Device::count());
        $this->assertDatabaseMissing('device', ['ip_address' => '10.0.0.99']);
        $this->assertDatabaseHas('device', ['ip_address' => '10.0.0.1', 'name' => 'POP SBY']);
    }

    public function test_command_fails_clearly_when_the_csv_is_missing(): void
    {
        $this->app->singleton(DeviceCsvService::class, fn () => new DeviceCsvService()->usePath('/tmp/tidak-ada-devices.csv'));

        $this->artisan('devices:sync')
            ->expectsOutputToContain('devices.csv tidak ditemukan')
            ->assertFailed();
    }
}
