<?php

namespace Tests\Feature;

use App\Models\Device;
use App\Services\DeviceCsvService;
use Tests\Feature\Concerns\AuthenticatesAdmin;
use Tests\Feature\Concerns\CreatesDeviceTables;
use Tests\Feature\Concerns\CreatesNetworkAreaTables;
use Tests\TestCase;

/**
 * Dashboard, Monitoring, dan DHCP Lease harus melaporkan jumlah yang sama:
 * hanya perangkat yang terdaftar di devices.csv.
 */
class OfficialDeviceCountPagesTest extends TestCase
{
    use AuthenticatesAdmin;
    use CreatesDeviceTables;
    use CreatesNetworkAreaTables;

    protected function setUp(): void
    {
        parent::setUp();

        $this->createDeviceTables();
        $this->createNetworkAreaTables();

        // 2 perangkat resmi
        Device::create(['name' => 'POP SBY', 'ip_address' => '10.0.0.1', 'status' => 'up', 'avg_delay' => 10]);
        Device::create(['name' => 'OLT KEN', 'ip_address' => '10.0.0.2', 'status' => 'down', 'avg_delay' => 30]);

        // 3 perangkat di luar daftar resmi — tidak boleh dihitung
        foreach (['10.0.0.97', '10.0.0.98', '10.0.0.99'] as $index => $ip) {
            Device::create([
                'name' => 'Dummy '.$index,
                'ip_address' => $ip,
                'status' => 'down',
                'avg_delay' => 999,
            ]);
        }

        $path = tempnam(sys_get_temp_dir(), 'registry-').'.csv';
        file_put_contents($path, "NAMA,IP ADD,TYPE,DESKRIPSI,GAMBAR,STATUS\nPOP SBY,10.0.0.1,ICMP,,,,up\nOLT KEN,10.0.0.2,ICMP,,,,down\n");
        $this->beforeApplicationDestroyed(fn () => is_file($path) && @unlink($path));

        $this->app->singleton(DeviceCsvService::class, fn () => new DeviceCsvService()->usePath($path));

        $this->authenticateAsAdmin();
    }

    public function test_dashboard_summary_json_ignores_rows_outside_the_csv(): void
    {
        $response = $this->getJson(route('admin.dashboard.data'))->assertOk();

        $this->assertSame(2, $response->json('summary.total'), 'total = perangkat resmi, bukan 5 baris tabel');
        $this->assertSame(1, $response->json('summary.up'));
        $this->assertSame(1, $response->json('summary.down'));
        $this->assertSame(0, $response->json('summary.warning'));

        // Rata-rata latensi hanya dari perangkat resmi (bukan 999 dari dummy).
        $this->assertEqualsWithDelta(20.0, $response->json('summary.averageLatency'), 0.01);

        $this->assertSame(5, $response->json('deviceSource.db_rows'));
        $this->assertSame(3, $response->json('deviceSource.db_extra_rows'));
    }

    public function test_monitoring_page_counts_only_official_devices(): void
    {
        $html = $this->get(route('admin.monitoring'))->assertOk()->getContent();

        $this->assertCount(2, app(DeviceCsvService::class)->officialDevices());
        $this->assertCount(5, Device::all(), 'tabel device tetap menyimpan semua baris');

        // AngkaMonitoring harus sama dengan Dashboard: 2 perangkat resmi,
        // bukan 5 baris tabel. Tiga dummy di luar daftar resmi tidak boleh
        // menambah satu pun angka.
        $summary = app(DeviceCsvService::class)->officialSummary();
        $this->assertSame(2, $summary['up'] + $summary['down'] + $summary['warning'] + $summary['unknown']);

        $this->assertSame(3, $summary['db_extra_rows']);

        foreach (['10.0.0.97' => 'Dummy 0', '10.0.0.99' => 'Dummy 2'] as $ip => $name) {
            $this->assertStringNotContainsString($name, $html, 'perangkat di luar daftar resmi tidak boleh tampil');
            $this->assertStringNotContainsString($ip, $html, 'perangkat di luar daftar resmi tidak boleh tampil');
        }
    }

    public function test_dashboard_and_monitoring_report_the_same_official_total(): void
    {
        $dashboard = $this->getJson(route('admin.dashboard.data'))->assertOk();
        $source = app(DeviceCsvService::class)->officialSummary();

        $this->assertSame($source['total'], $dashboard->json('summary.total'));
        $this->assertSame(2, $source['total']);
        $this->assertSame(5, $source['db_rows']);
    }
}
