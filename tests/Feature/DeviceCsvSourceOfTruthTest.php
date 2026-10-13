<?php

namespace Tests\Feature;

use App\Models\Device;
use App\Services\DeviceCsvService;
use Tests\Feature\Concerns\CreatesDeviceTables;
use Tests\TestCase;

/**
 * devices.csv adalah DAFTAR RESMI perangkat. Tabel `device` hanya menyimpan
 * state operasional, jadi angka di seluruh halaman harus mengikuti CSV —
 * bukan seluruh isi tabel.
 */
class DeviceCsvSourceOfTruthTest extends TestCase
{
    use CreatesDeviceTables;

    protected function setUp(): void
    {
        parent::setUp();

        $this->createDeviceTables();

        Device::create([
            'name' => 'POP SBY', 'ip_address' => '10.0.0.1', 'pop_name' => 'Surabaya', 'status' => 'up',
        ]);
        Device::create([
            'name' => 'OLT KEN', 'ip_address' => '10.0.0.2', 'pop_name' => 'Gresik', 'status' => 'down',
        ]);
        // Tidak ada di devices.csv -> tidak boleh jadi perangkat resmi.
        Device::create([
            'name' => 'Dummy Note', 'ip_address' => '10.0.0.99', 'pop_name' => 'Semarang', 'status' => 'down',
        ]);
    }

    protected function service(string $csv): DeviceCsvService
    {
        $path = tempnam(sys_get_temp_dir(), 'registry-').'.csv';
        file_put_contents($path, $csv);

        $this->beforeApplicationDestroyed(fn () => is_file($path) && @unlink($path));

        return new DeviceCsvService()->usePath($path);
    }

    protected function registry(): string
    {
        return "NAMA,IP ADD,TYPE,DESKRIPSI,GAMBAR,STATUS\n"
            ."POP SBY,10.0.0.1,ICMP,,,,up\n"
            ."OLT KEN,10.0.0.2,ICMP,,,,down\n"
            ."\n";
    }

    public function test_official_devices_excludes_rows_missing_from_csv(): void
    {
        $devices = $this->service($this->registry())->officialDevices();

        $this->assertCount(2, $devices);
        $this->assertSame(['10.0.0.1', '10.0.0.2'], $devices->pluck('ip_address')->all());
    }

    public function test_header_row_is_never_counted_as_a_device(): void
    {
        $service = $this->service($this->registry());

        $this->assertSame(2, $service->officialSummary()['csv_rows']);
        $this->assertSame(2, $service->officialDevices()->count());
    }

    public function test_duplicate_ip_in_csv_keeps_one_row_per_csv_line(): void
    {
        $csv = "NAMA,IP ADD,TYPE,DESKRIPSI,GAMBAR,STATUS\n"
            ."POP SBY,10.0.0.1,ICMP,,,,up\n"
            ."POP SBY Backup,10.0.0.1,ICMP,,,,up\n";

        $service = $this->service($csv);
        $summary = $service->officialSummary();

        $this->assertSame(2, $summary['csv_rows'], 'dua baris CSV = dua perangkat resmi');
        $this->assertSame(1, $summary['csv_unique_ips'], 'tapi hanya satu IP unik');
        $this->assertCount(2, $service->officialDevices());
    }

    public function test_official_summary_counts_status_from_table_for_official_rows_only(): void
    {
        $summary = $this->service($this->registry())->officialSummary();

        $this->assertSame(2, $summary['total']);
        $this->assertSame(1, $summary['up']);
        $this->assertSame(1, $summary['down']);
        $this->assertSame(0, $summary['unknown']);
        $this->assertSame(3, $summary['db_rows'], 'tabel device tetap menyimpan semua baris');
    }

    public function test_sync_report_separates_known_drift_from_extraneous_rows(): void
    {
        $report = $this->service($this->registry())->syncReport();

        $this->assertSame(2, $report['csv_rows']);
        $this->assertSame(3, $report['db_rows']);
        $this->assertSame([], $report['missing_in_db'], 'semua IP CSV ada di tabel');
        $this->assertCount(1, $report['extra_in_db']);
        $this->assertSame('10.0.0.99', $report['extra_in_db'][0]['ip']);
        $this->assertSame('Dummy Note', $report['extra_in_db'][0]['name']);
        $this->assertFalse($report['in_sync']);
    }

    public function test_sync_report_flags_name_drift_and_ignores_csv_type_as_protocol(): void
    {
        Device::where('ip_address', '10.0.0.1')->update(['name' => 'POP Lama']);

        $report = $this->service($this->registry())->syncReport();

        $this->assertCount(1, $report['name_drift']);
        $this->assertSame('POP Lama', $report['name_drift'][0]['db']);
        $this->assertSame('POP SBY', $report['name_drift'][0]['csv']);

        // Kolom TYPE di CSV = protocol (ICMP), bukan tipe perangkat (Server/Router).
        $this->assertSame([], $report['protocol_drift']);
    }

    public function test_csv_row_without_matching_table_row_is_reported_not_silently_created(): void
    {
        $csv = $this->registry()."Router Baru,10.0.0.7,ICMP,,,,up\n";

        $service = $this->service($csv);
        $report = $service->syncReport();

        $this->assertSame(3, $report['csv_rows']);
        $this->assertSame(['10.0.0.7'], array_column($report['missing_in_db'], 'ip'));
        $this->assertCount(2, $service->officialDevices(), 'tidak ada baris baru yang dibuat');
        $this->assertSame(3, Device::count());
    }
}
