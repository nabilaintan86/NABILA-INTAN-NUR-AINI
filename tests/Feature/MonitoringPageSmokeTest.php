<?php

namespace Tests\Feature;

use App\Models\Device;
use App\Models\DeviceConnection;
use App\Services\DeviceCsvService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Concerns\AuthenticatesAdmin;
use Tests\TestCase;

/**
 * Smoke test halaman Monitoring.
 *
 * Skema diambil dari migration asli lewat RefreshDatabase, bukan tabel yang
 * ditulis manual: MonitoringController membaca banyak tabel sekaligus
 * (device, network_areas, map_settings, monitoring_logs, notifikasi) dan
 * dulu gagal 500 di test karena satu per satu tabelnya kelupaan dibuat.
 * Dengan migration yang jadi acuan, test ini gagal kalau skema produksi
 * berubah — bukan kalau ada tabel yang tidak sengaja terlewat.
 */
class MonitoringPageSmokeTest extends TestCase
{
    use AuthenticatesAdmin;
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed device ids yang dipakai topology (sesuai NetworkTopologyService).
        $ids = array_keys([
            25, 26, 8, 51, 46, 52, 24, 82,
            78, 79, 80, 81,
            2, 3, 58, 59, 44, 65, 42, 5, 53, 55, 56, 6, 4, 7, 66, 12, 37,
            11, 10, 71, 43, 88, 62, 63, 40, 38, 19, 89, 20, 90, 21, 91,
            73, 72, 74,
            92, 48, 57, 113, 47, 68, 114, 69, 70, 115, 49, 84, 45, 112,
            86, 85, 87,
            93, 94, 13, 96,
            75, 76,
            111, 110, 109, 108,
            18, 50,
            1,
            61, 83, 67, 77,
            64, 16, 36, 15, 14, 17, 106, 105, 22, 23, 95, 104, 107, 101, 102, 103, 98, 99, 100, 9, 60, 97,
            28, 29, 35, 30, 31, 32, 33, 34, 54, 27,
        ]);

        foreach ($ids as $i => $id) {
            Device::updateOrCreate(
                ['id' => $id],
                [
                    'name'       => 'DEV-' . $id,
                    'ip_address' => '10.0.0.' . $id,
                    'status'     => 'up',
                    'icon_type'  => $i % 4 === 0 ? 'router' : 'switch',
                    'pop_name'   => 'POP',
                ]
            );
        }

        /* DeviceCsvService adalah sumber resmi: Monitoring dan peta hanya
           menampilkan perangkat yang ada di devices.csv. Supaya test ini
           tidak bergantung pada isi CSV asli proyek, daftarkan perangkat
           hasil seed di atas ke CSV sementara yang sama. */
        $csv = "NAMA,IP ADD,TYPE,DESKRIPSI,GAMBAR,STATUS\n";
        foreach ($ids as $id) {
            $csv .= 'DEV-'.$id.',10.0.0.'.$id.",ICMP,,,up\n";
        }

        $path = tempnam(sys_get_temp_dir(), 'monitoring-').'.csv';
        file_put_contents($path, $csv);
        $this->beforeApplicationDestroyed(fn () => is_file($path) && @unlink($path));

        $this->app->singleton(DeviceCsvService::class, fn () => new DeviceCsvService()->usePath($path));

        if (DeviceConnection::count() === 0) {
            DeviceConnection::create(['from_device_id' => 11, 'to_device_id' => 10, 'connection_type' => 'ethernet']);
            DeviceConnection::create(['from_device_id' => 11, 'to_device_id' => 43, 'connection_type' => 'ethernet']);
            DeviceConnection::create(['from_device_id' => 28, 'to_device_id' => 29, 'connection_type' => 'ethernet']);
        }

        $this->authenticateAsAdmin();
    }

    public function test_monitoring_page_returns_200(): void
    {
        // Role harus salah satu dari nilai yang diizinkan enum users
        // (super_admin / staff) — nilai lain ditolak oleh CHECK constraint.
        $this->assertTrue($this->adminUser->isSuperAdmin());

        $this->get('/admin/monitoring')
            ->assertStatus(200)
            ->assertSee('RADNET Wireless Network 2026', false);
    }

    public function test_monitoring_data_is_valid_and_contained(): void
    {
        $json = $this->getJson('/admin/monitoring/data');
        $json->assertStatus(200)
             ->assertJsonCount(15, 'areas')
             ->assertJsonStructure(['viewBox', 'areas', 'edges']);

        foreach ($json->json('areas') as $area) {
            foreach ($area['nodes'] as $n) {
                [$x1, $y1, $x2, $y2] = [$area['x'], $area['y'], $area['x'] + $area['w'], $area['y'] + $area['h']];
                $this->assertTrue($x1 <= $n['x'], "{$area['name']}: node {$n['id']} keluar kiri");
                $this->assertTrue($n['x'] <= $x2, "{$area['name']}: node {$n['id']} keluar kanan");
                $this->assertTrue($y1 <= $n['y'], "{$area['name']}: node {$n['id']} keluar atas");
                $this->assertTrue($n['y'] <= $y2, "{$area['name']}: node {$n['id']} keluar bawah");
            }
        }
    }
}