<?php

namespace Tests\Feature;

use App\Models\Device;
use App\Models\DhcpLease;
use App\Services\DeviceCsvService;
use Illuminate\Http\UploadedFile;
use Tests\Feature\Concerns\CreatesDhcpTables;
use Tests\Feature\Concerns\AuthenticatesAdmin;
use Tests\TestCase;

class DhcpImportTest extends TestCase
{
    use CreatesDhcpTables;
    use AuthenticatesAdmin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->createDhcpTables();

        $this->authenticateAsAdmin();
    }

    /**
     * Known Device hanya berasal dari devices.csv, jadi test harus memberi tahu
     * IP mana yang terdaftar resmi — bukan mengandalkan isi tabel device.
     */
    protected function useRegistryCsv(string $content): \Closure
    {
        $path = tempnam(sys_get_temp_dir(), 'registry-').'.csv';
        file_put_contents($path, $content);
        $this->beforeApplicationDestroyed(fn () => is_file($path) && @unlink($path));

        $this->app->singleton(DeviceCsvService::class, fn () => new DeviceCsvService()->usePath($path));

        return function () use ($path) {
            if (is_file($path)) {
                @unlink($path);
            }
        };
    }

    public function test_dhcp_import_command_imports_and_classifies_rows(): void
    {
        Device::create([
            'name' => 'POP SBY',
            'ip_address' => '192.168.1.1',
            'pop_name' => 'Surabaya',
        ]);

        $restoreRegistry = $this->useRegistryCsv(
            "NAMA,IP ADD,TYPE,DESKRIPSI,GAMBAR,STATUS\nPOP SBY,192.168.1.1,ICMP,,,,up\n"
        );

        $csv = [
            'mac_address,ip_address,hostname,client_id,vendor,router,location,lease_start,lease_end,first_seen,last_seen',
            'AA:BB:CC:00:00:11,192.168.1.1,pop-sby,client-01,MikroTik,Router A,,2026-09-01 10:00:00,2026-10-01 10:00:00,2026-09-01 10:00:00,2026-09-25 08:00:00',
            'AA:BB:CC:00:00:12,192.168.1.77,printer-canon,client-02,Canon,Router A,,2026-09-24 21:00:00,,,2026-09-25 08:00:00',
            'AA:BB:CC:00:00:13,192.168.1.88,,,,Router A,,,2026-08-02 00:00:00,,',
        ];

        $path = tempnam(sys_get_temp_dir(), 'dhcp_').'.csv';
        file_put_contents($path, implode("\n", $csv)."\n");

        try {
            $this->artisan('dhcp:import', ['file' => $path])
                ->expectsOutputToContain('3 lease berhasil di-import, 1 Known, 1 New, 1 Unknown.')
                ->assertExitCode(0);
        } finally {
            @unlink($path);
            $restoreRegistry();
        }

        $this->assertSame(3, DhcpLease::count());
        $this->assertSame(1, DhcpLease::where('classification', 'known')->count());
        $this->assertSame(1, DhcpLease::where('classification', 'new')->count());
        $this->assertSame(1, DhcpLease::where('classification', 'unknown')->count());

        // Lease known tersambung ke device resmi via relasi (bukan duplikasi device)
        $known = DhcpLease::where('ip_address', '192.168.1.1')->first();
        $this->assertSame('known', $known->classification);
        $this->assertSame('Surabaya', $known->location);
        $this->assertSame('client-01', $known->client_id);
        $this->assertNotNull($known->device_id);
        $this->assertSame('POP SBY', $known->device->name);

        // Tanpa lease_end, waktu turunan dari last_seen (+24 jam)
        $new = DhcpLease::where('ip_address', '192.168.1.77')->first();
        $this->assertSame('new', $new->classification);
        $this->assertNotNull($new->lease_end);

        @unlink($path);
    }

    /**
     * Ekspor DHCP server umum memakai header ber-spasi atau ber-hyphen
     * (mis. "MAC Address", "mac-address", "IP Address", "address"), bukan
     * bentuk snake_case. Header-header ini harus tetap terdeteksi.
     */
    public function test_dhcp_import_command_resolves_spaced_and_hyphenated_headers(): void
    {
        Device::create([
            'name' => 'POP SBY',
            'ip_address' => '192.168.1.1',
            'pop_name' => 'Surabaya',
        ]);

        $restoreRegistry = $this->useRegistryCsv(
            "NAMA,IP ADD,TYPE,DESKRIPSI,GAMBAR,STATUS\nPOP SBY,192.168.1.1,ICMP,,,,up\n"
        );

        $csv = [
            'MAC Address,IP Address,Host Name,Vendor,Router,Location,First Seen,Last Seen',
            'AA:BB:CC:00:00:41,192.168.1.1,known-device,MikroTik,Router A,Surabaya,2026-09-01 10:00:00,2026-09-25 08:00:00',
            'AA:BB:CC:00:00:42,192.168.1.77,guest-wifi,Canon,Router A,Surabaya,2026-09-25 08:00:00,2026-09-25 09:00:00',
        ];

        $path = tempnam(sys_get_temp_dir(), 'dhcp_').'.csv';
        file_put_contents($path, implode("\n", $csv)."\n");

        try {
            $this->artisan('dhcp:import', ['file' => $path])
                ->expectsOutputToContain('2 lease berhasil di-import, 1 Known, 1 New, 0 Unknown.')
                ->assertExitCode(0);
        } finally {
            @unlink($path);
            $restoreRegistry();
        }

        $this->assertSame(2, DhcpLease::count());
        $this->assertSame(1, DhcpLease::where('classification', 'known')->count());
        $this->assertSame(1, DhcpLease::where('classification', 'new')->count());
    }

    /**
     * Ekspor MikroTik memakai "mac-address" / "address" / "host-name".
     */
    public function test_dhcp_import_command_resolves_mikrotik_style_headers(): void
    {
        Device::create([
            'name' => 'POP SBY',
            'ip_address' => '192.168.1.1',
            'pop_name' => 'Surabaya',
        ]);

        $restoreRegistry = $this->useRegistryCsv(
            "NAMA,IP ADD,TYPE,DESKRIPSI,GAMBAR,STATUS\nPOP SBY,192.168.1.1,ICMP,,,,up\n"
        );

        $csv = [
            'mac-address,address,host-name,vendor,router,location,lease_start,lease_end,last_seen',
            'AA:BB:CC:00:00:51,192.168.1.1,known-device,MikroTik,Router A,Surabaya,2026-09-01 10:00:00,2026-10-01 10:00:00,2026-09-25 08:00:00',
            'AA:BB:CC:00:00:52,192.168.1.99,unknown-host,Canon,Router A,Surabaya,2026-09-25 08:00:00,,,',
        ];

        $path = tempnam(sys_get_temp_dir(), 'dhcp_').'.csv';
        file_put_contents($path, implode("\n", $csv)."\n");

        try {
            $this->artisan('dhcp:import', ['file' => $path])
                ->expectsOutputToContain('2 lease berhasil di-import, 1 Known, 1 New, 0 Unknown.')
                ->assertExitCode(0);
        } finally {
            @unlink($path);
            $restoreRegistry();
        }

        $known = DhcpLease::where('ip_address', '192.168.1.1')->first();
        $this->assertSame('known', $known->classification);
        $this->assertSame('Surabaya', $known->location);
    }

    public function test_dhcp_import_command_rejects_missing_mac_address_column(): void
    {
        $path = tempnam(sys_get_temp_dir(), 'dhcp_').'.csv';
        file_put_contents($path, "ip_address,hostname\n192.168.1.9,somehost\n");

        $this->artisan('dhcp:import', ['file' => $path])
            ->assertExitCode(1);

        $this->assertSame(0, DhcpLease::count());

        @unlink($path);
    }

    public function test_dhcp_import_command_reports_missing_file(): void
    {
        $this->artisan('dhcp:import', ['file' => '/tmp/opencode/not-exists.csv'])
            ->assertExitCode(1);
    }

    public function test_dhcp_lease_upload_endpoint_imports_devices_csv(): void
    {
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
            'AA:BB:CC:00:00:21,192.168.1.1,pop-sby,MikroTik,Router A,',
            'AA:BB:CC:00:00:22,192.168.1.60,guest-wifi,,Router A,Surabaya',
        ];

        $file = UploadedFile::fake()->createWithContent('devices.csv', implode("\n", $csv)."\n");

        try {
            $response = $this->post(route('admin.dhcp-lease.upload'), ['csv_file' => $file]);

            $response->assertOk()
                ->assertJsonStructure(['success', 'message', 'counts'])
                ->assertJsonPath('counts.total', 2)
                ->assertJsonPath('counts.known', 1);

            $this->assertStringContainsString('Berhasil import', $response->json('message'));
            $this->assertSame(2, DhcpLease::count());
        } finally {
            $restoreRegistry();
        }
    }

    public function test_dhcp_lease_upload_endpoint_rejects_non_csv_and_bad_columns(): void
    {
        $this->post(route('admin.dhcp-lease.upload'), [
            'csv_file' => UploadedFile::fake()->createWithContent('devices.txt', "a,b\n1,2\n"),
        ])->assertStatus(422);

        $this->post(route('admin.dhcp-lease.upload'), [
            'csv_file' => UploadedFile::fake()->createWithContent('devices.csv', "ip_address,hostname\n192.168.1.9,x\n"),
        ])->assertStatus(422)
            ->assertJsonPath('message', 'Kolom mac_address tidak ditemukan. Pastikan file devices.csv memiliki kolom: mac_address, ip_address, hostname, vendor, router, location, lease_start, lease_end, first_seen, last_seen.');

        $this->assertSame(0, DhcpLease::count());
    }
}
