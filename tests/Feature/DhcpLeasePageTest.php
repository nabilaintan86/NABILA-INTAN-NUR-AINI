<?php

namespace Tests\Feature;

use App\Models\Device;
use App\Models\DhcpLease;
use App\Services\DeviceCsvService;
use App\Services\DhcpImporter;
use Illuminate\Support\Facades\Config;
use Tests\Feature\Concerns\AuthenticatesAdmin;
use Tests\Feature\Concerns\CreatesDhcpTables;
use Tests\TestCase;

class DhcpLeasePageTest extends TestCase
{
    use AuthenticatesAdmin;
    use CreatesDhcpTables;

    protected function setUp(): void
    {
        parent::setUp();
        $this->createDhcpTables();
        $this->authenticateAsAdmin();
    }

    public function test_dhcp_lease_page_renders_title_kpi_filter_table_and_links(): void
    {
        // Known device nyata di registry (bukan hardcoded di halaman)
        $device = Device::create([
            'name' => 'POP SBY',
            'ip_address' => '192.168.1.1',
            'pop_name' => 'Surabaya',
        ]);

        // Lease A - IP cocok dengan known device -> known, aktif, punya link detail
        DhcpLease::create([
            'device_id' => $device->id,
            'classification' => 'known',
            'mac_address' => 'AA:BB:CC:00:00:01',
            'ip_address' => '192.168.1.1',
            'hostname' => 'pop-sby',
            'client_id' => '01:AA:BB:CC:00:00:01',
            'vendor' => 'MikroTik',
            'location' => 'Surabaya',
            'lease_start' => now()->subDays(2),
            'lease_end' => now()->addDays(5),
        ]);

        // Lease B - tidak dikenal tapi punya identitas hostname/vendor -> new, aktif
        DhcpLease::create([
            'classification' => 'new',
            'mac_address' => 'AA:BB:CC:00:00:02',
            'ip_address' => '192.168.1.50',
            'hostname' => 'wifi-printer',
            'vendor' => 'Canon',
            'router' => 'Router A',
            'lease_start' => now()->subDay(),
            'lease_end' => now()->addDay(),
        ]);

        // Lease C - tidak dikenal dan tanpa identitas -> unknown, expired
        DhcpLease::create([
            'classification' => 'unknown',
            'mac_address' => 'AA:BB:CC:00:00:03',
            'ip_address' => '192.168.1.99',
            'lease_start' => now()->subDays(10),
            'lease_end' => now()->subDays(3),
        ]);

        $response = $this->get(route('admin.dhcp-lease'));

        $response->assertStatus(200);
        $html = $response->getContent();

        // Judul halaman & header atas (bukan lagi "Dashboard"),
        // memakai page head global yang sama dengan halaman lain
        $this->assertStringContainsString('DHCP Lease', $html);
        $this->assertStringContainsString('admin-page-head-title', $html);

        // KPI + filter + pagination
        $this->assertStringContainsString('Total Lease', $html);
        $this->assertStringContainsString('Aktif', $html);
        $this->assertStringContainsString('Expired', $html);
        $this->assertStringContainsString('Unknown', $html);
        $this->assertStringContainsString('Lokasi / POP', $html);
        $this->assertStringContainsString('Vendor', $html);
        $this->assertStringContainsString('Cari nama device...', $html);
        $this->assertStringContainsString('Reset Filter', $html);
        $this->assertStringContainsString('Known Device', $html);
        $this->assertStringContainsString('01:AA:BB:CC:00:00:01', $html);
        $this->assertStringContainsString('/ halaman', $html);
        $this->assertStringNotContainsString('Upload devices.csv', $html);
        $this->assertStringNotContainsString('Import Sekarang', $html);
        $this->assertStringNotContainsString('uploadModal', $html);
        $this->assertStringContainsString('modal-backdrop-dhcp', $html);
        $this->assertStringContainsString('rgba(15, 23, 42, .45)', $html);
        $this->assertStringContainsString('z-index: 1050', $html);
        $this->assertStringContainsString('max-height: 65vh', $html);
        $this->assertStringContainsString('translateY(20px)', $html);
        $this->assertStringContainsString('Client ID', $html);

        // Isi tabel & klasifikasi
        $this->assertStringContainsString('pop-sby', $html);
        $this->assertStringContainsString('wifi-printer', $html);
        $this->assertStringContainsString('Surabaya', $html);
        $this->assertStringContainsString('row-unknown', $html);

        // Lease known ber-link ke halaman detail device resmi
        $this->assertStringContainsString('/admin/device/'.$device->id.'/detail', $html);

        // Filter status, lokasi, dan vendor
        $filtered = $this->get(route('admin.dhcp-lease', [
            'status' => 'aktif',
            'location' => 'Surabaya',
            'vendor' => 'MikroTik',
        ]));
        $filtered->assertStatus(200);
        $filteredHtml = $filtered->getContent();
        $this->assertStringContainsString('pop-sby', $filteredHtml);
        $this->assertStringNotContainsString('wifi-printer', $filteredHtml);
        $this->assertStringContainsString('Filter aktif:', $filteredHtml);
        $this->assertStringContainsString('Lokasi / POP:', $filteredHtml);
        $this->assertStringContainsString('Vendor:', $filteredHtml);

        // Pencarian hanya berdasarkan hostname
        $searched = $this->get(route('admin.dhcp-lease', ['q' => 'printer']));
        $searched->assertStatus(200);
        $this->assertStringContainsString('wifi-printer', $searched->getContent());

        $ipSearched = $this->get(route('admin.dhcp-lease', ['q' => '192.168.1.50']));
        $ipSearched->assertStatus(200);
        $this->assertStringNotContainsString('wifi-printer', $ipSearched->getContent());
    }

    public function test_dhcp_lease_page_shows_empty_state_when_no_data(): void
    {
        // Matikan auto-sync supaya empty state tetap tampil meski file CSV
        // exist di storage/app/dhcp/ di environment development.
        Config::set('dhcp_auto_sync', false);

        $this->get(route('admin.dhcp-lease'))
            ->assertStatus(200)
            ->assertSee('Belum Ada Data Lease DHCP')
            ->assertSee('php artisan dhcp:import')
            ->assertDontSee('Upload devices.csv')
            ->assertDontSee('Import Sekarang')
            ->assertDontSee('uploadModal');
    }

    public function test_dhcp_lease_sync_endpoint_imports_default_devices_csv(): void
    {
        Device::create([
            'name' => 'POP SBY',
            'ip_address' => '192.168.1.1',
            'pop_name' => 'Surabaya',
        ]);

        // Hanya 192.168.1.1 yang ada di daftar resmi devices.csv, jadi hanya
        // lease itu yang diklasifikasikan Known.
        $restoreRegistry = $this->useRegistryCsv(
            "NAMA,IP ADD,TYPE,DESKRIPSI,GAMBAR,STATUS\nPOP SBY,192.168.1.1,ICMP,,,,up\n"
        );

        $csv = [
            'mac_address,ip_address,hostname,vendor,router,location,first_seen,last_seen',
            'AA:BB:CC:00:00:31,192.168.1.1,pop-sby,MikroTik,Router A,,2026-09-20 08:00:00,2026-09-25 08:00:00',
            'AA:BB:CC:00:00:32,192.168.1.70,printer-lobi,,Router A,Intiland,2026-09-24 08:00:00,2026-09-25 08:00:00',
        ];

        $restore = $this->installDefaultCsv(implode("\n", $csv)."\n");
        try {
            $response = $this->post(route('admin.dhcp-lease.import'));

            $response->assertOk()
                ->assertJsonStructure(['success', 'message', 'counts'])
                ->assertJsonPath('success', true)
                ->assertJsonPath('counts.total', 2)
                ->assertJsonPath('counts.known', 1)
                ->assertJsonPath('counts.new', 1);

            $this->assertStringContainsString(
                '2 lease berhasil di-import, 1 Known, 1 New, 0 Unknown.',
                $response->json('message')
            );
            $this->assertSame(2, DhcpLease::count());
        } finally {
            $restore();
            $restoreRegistry();
        }
    }

    public function test_dhcp_lease_sync_endpoint_reports_missing_csv(): void
    {
        Config::set('dhcp_auto_sync', true);

        $path = DhcpImporter::defaultFilePath();
        $moveDir = dirname($path).'/__missing_csv_test_tmp__';
        if (is_file($path)) {
            @mkdir($moveDir, 0777, true);
            @rename($path, $moveDir.'/devices.csv.bak');
        }

        try {
            $this->post(route('admin.dhcp-lease.import'))
                ->assertStatus(422)
                ->assertJsonPath('success', false)
                ->assertJsonPath('message', 'File devices.csv belum ditemukan di storage/app/dhcp/devices.csv. Letakkan file tersebut lalu jalankan php artisan dhcp:import.');
        } finally {
            if (is_file($moveDir.'/devices.csv.bak')) {
                @rename($moveDir.'/devices.csv.bak', $path);
                @rmdir($moveDir);
            }
        }
    }

    public function test_dhcp_lease_page_auto_syncs_when_empty_and_csv_present(): void
    {
        Config::set('dhcp_auto_sync', true);

        Device::create([
            'name' => 'POP SBY',
            'ip_address' => '192.168.1.1',
            'pop_name' => 'Surabaya',
        ]);

        $restoreRegistry = $this->useRegistryCsv(
            "NAMA,IP ADD,TYPE,DESKRIPSI,GAMBAR,STATUS\nPOP SBY,192.168.1.1,ICMP,,,,up\n"
        );

        $csv = [
            'mac_address,ip_address,hostname,vendor,router,location',
            'AA:BB:CC:00:00:41,192.168.1.1,pop-sby,MikroTik,Router A,Surabaya',
        ];

        $restore = $this->installDefaultCsv(implode("\n", $csv)."\n");
        try {
            $response = $this->get(route('admin.dhcp-lease'));

            $response->assertStatus(200);
            $html = $response->getContent();

            // Auto-sync terjadi: tabel terisi & halaman tampilkan data (bukan empty state).
            $this->assertSame(1, DhcpLease::count());
            $this->assertStringContainsString('pop-sby', $html);
            $this->assertStringContainsString('Total Lease', $html);
            $this->assertStringContainsString('dikenali', $html);
            $this->assertStringNotContainsString('Belum Ada Data Lease DHCP', $html);

            // Data berasal dari klasifikasi known (terhubung ke devices.csv).
            $lease = DhcpLease::first();
            $this->assertSame('known', $lease->classification);

            // Known Device mengikuti devices.csv (IP unik), bukan semua isi tabel.
            $response->assertSee('Known Device', false);
        } finally {
            $restore();
            $restoreRegistry();
        }
    }

    /**
     * Arahkan DeviceCsvService ke devices.csv sementara berisi daftar resmi
     * yang dipakai test, lalu kembalikan closure untuk memulihkan.
     */
    protected function useRegistryCsv(string $content): \Closure
    {
        $path = tempnam(sys_get_temp_dir(), 'registry-').'.csv';
        file_put_contents($path, $content);

        $this->app->singleton(DeviceCsvService::class, fn () => new DeviceCsvService()->usePath($path));

        return function () use ($path) {
            if (is_file($path)) {
                @unlink($path);
            }
        };
    }

    protected function installDefaultCsv(string $content): \Closure
    {
        $path = DhcpImporter::defaultFilePath();
        $hadFile = is_file($path);
        $backup = $hadFile ? (string) file_get_contents($path) : null;

        $dir = dirname($path);
        if (! is_dir($dir)) {
            @mkdir($dir, 0777, true);
        }

        file_put_contents($path, $content);

        return function () use ($path, $hadFile, $backup) {
            if ($hadFile) {
                file_put_contents($path, $backup);
            } elseif (is_file($path)) {
                @unlink($path);
            }
        };
    }
}
