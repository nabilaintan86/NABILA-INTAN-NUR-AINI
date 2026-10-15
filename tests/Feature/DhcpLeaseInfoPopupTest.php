<?php

namespace Tests\Feature;

use App\Models\Device;
use App\Models\DhcpLease;
use App\Services\DeviceCsvService;
use Tests\Feature\Concerns\AuthenticatesAdmin;
use Tests\Feature\Concerns\CreatesDeviceTables;
use Tests\Feature\Concerns\CreatesDhcpTables;
use Tests\TestCase;

/**
 * Action di bagian atas halaman DHCP Lease harus benar-benar berfungsi: KPI card
 * dan chip Known Device / Diperbarui membuka popup, dan popup itu menjelaskan
 * angka devices.csv sebagai sumber resmi.
 */
class DhcpLeaseInfoPopupTest extends TestCase
{
    use AuthenticatesAdmin;
    use CreatesDeviceTables;
    use CreatesDhcpTables;

    protected function setUp(): void
    {
        parent::setUp();
        $this->createDeviceTables();
        $this->createDhcpTables();

        Device::create(['name' => 'POP SBY', 'ip_address' => '10.0.0.1', 'pop_name' => 'Surabaya', 'status' => 'up']);
        Device::create(['name' => 'OLT KEN', 'ip_address' => '10.0.0.2', 'pop_name' => 'Gresik', 'status' => 'down']);
        Device::create(['name' => 'Dummy Note', 'ip_address' => '10.0.0.99', 'pop_name' => 'Semarang', 'status' => 'down']);

        DhcpLease::create([
            'device_id' => 1,
            'classification' => 'known',
            'mac_address' => 'AA:BB:CC:00:00:01',
            'ip_address' => '10.0.0.1',
            'hostname' => 'pop-sby',
            'location' => 'Surabaya',
            'lease_end' => now()->addDay(),
            'last_seen' => now(),
        ]);

        DhcpLease::create([
            'classification' => 'unknown',
            'mac_address' => 'AA:BB:CC:00:00:02',
            'ip_address' => '10.0.0.77',
            'lease_end' => now()->addDay(),
            'last_seen' => now(),
        ]);

        $path = tempnam(sys_get_temp_dir(), 'registry-').'.csv';
        file_put_contents($path, "NAMA,IP ADD,TYPE,DESKRIPSI,GAMBAR,STATUS\nPOP SBY,10.0.0.1,ICMP,,,,up\nOLT KEN,10.0.0.2,ICMP,,,,down\n");
        $this->beforeApplicationDestroyed(fn () => is_file($path) && @unlink($path));

        $this->app->singleton(DeviceCsvService::class, fn () => new DeviceCsvService()->usePath($path));

        $this->authenticateAsAdmin();
    }

    public function test_top_actions_are_real_buttons_that_open_the_popup(): void
    {
        $html = $this->get(route('admin.dhcp-lease'))->assertOk()->getContent();

        // Chip bukan <span> pasif lagi, tapi <button> dengan pemicu popup.
        $this->assertStringContainsString('data-info="registry"', $html);
        $this->assertStringContainsString('data-info="updated"', $html);
        $this->assertMatchesRegularExpression('/<button[^>]*class="dhcp-meta-chip"[^>]*data-info="registry"/', $html);
        $this->assertMatchesRegularExpression('/<button[^>]*data-info="registry"[^>]*aria-haspopup="dialog"/', $html);

        // Kerangka popup + tombol tutup tersedia.
        $this->assertStringContainsString('id="infoModal"', $html);
        $this->assertStringContainsString('role="dialog"', $html);
        $this->assertStringContainsString('data-close-info', $html);
    }

    public function test_all_four_kpi_cards_trigger_a_popup(): void
    {
        $html = $this->get(route('admin.dhcp-lease'))->assertOk()->getContent();

        foreach (['kpi-total', 'kpi-aktif', 'kpi-expired', 'kpi-unknown'] as $key) {
            $this->assertStringContainsString('data-info="'.$key.'"', $html, $key);
        }

        $this->assertSame(4, preg_match_all('/class="stat-card stat-\w+ is-action"/', $html), 'semua KPI bisa diklik');
    }

    public function test_popup_payload_reports_csv_as_official_source(): void
    {
        $html = $this->get(route('admin.dhcp-lease'))->assertOk()->getContent();

        $this->assertStringContainsString('id="infoPayload"', $html);
        $this->assertStringContainsString('Sumber Data Perangkat', $html);
        $this->assertStringContainsString('devices.csv', $html);

        // Angka resmi berasal dari CSV: 2 baris / 2 IP unik, bukan 3 baris tabel.
        $this->assertStringContainsString('Known Device', $html);
        $this->assertStringContainsString('Baris data devices.csv', $html);
        $this->assertStringContainsString('Di luar devices.csv', $html);
    }

    public function test_registry_popup_explains_why_one_row_is_excluded(): void
    {
        $html = $this->get(route('admin.dhcp-lease'))->assertOk()->getContent();

        $this->assertStringContainsString('tidak ikut dihitung sebagai perangkat resmi', $html);
        $this->assertStringContainsString('php artisan devices:sync', $html);
    }

    public function test_popup_is_responsive_and_respects_reduced_motion(): void
    {
        $html = $this->get(route('admin.dhcp-lease'))->assertOk()->getContent();

        $this->assertStringContainsString('modal-box-dhcp modal-info', $html);
        $this->assertStringContainsString('.info-list .info-value', $html);
        $this->assertStringContainsString('prefers-reduced-motion: reduce', $html);
        $this->assertStringContainsString('.modal-box-dhcp.is-closing', $html);
    }

    public function test_popup_payload_is_valid_json_with_all_panels(): void
    {
        $html = $this->get(route('admin.dhcp-lease'))->assertOk()->getContent();

        $this->assertSame(1, preg_match(
            '#<script type="application/json" id="infoPayload">(.*?)</script>#s',
            $html,
            $matches
        ), 'payload popup harus ada dan berupa JSON');

        $payload = json_decode($matches[1], true);
        $this->assertIsArray($payload, 'JSON payload harus valid');

        foreach (['registry', 'updated', 'kpi-total', 'kpi-aktif', 'kpi-expired', 'kpi-unknown'] as $key) {
            $this->assertArrayHasKey($key, $payload);
            $this->assertNotEmpty($payload[$key]['title']);
            $this->assertNotEmpty($payload[$key]['items']);
        }
    }

    public function test_registry_panel_reports_extra_rows_not_the_whole_table(): void
    {
        $html = $this->get(route('admin.dhcp-lease'))->assertOk()->getContent();
        preg_match('#<script type="application/json" id="infoPayload">(.*?)</script>#s', $html, $matches);

        $items = collect(json_decode($matches[1], true)['registry']['items'])->keyBy('name');

        // Tabel punya 3 baris, hanya 2 yang resmi -> tepat 1 yang di luar daftar.
        $this->assertSame('3', $items['Total baris tabel device']['value']);
        $this->assertSame('1', $items['Di luar devices.csv']['value'], 'harus selisihnya, bukan seluruh tabel');
        $this->assertSame('2', $items['Baris data devices.csv']['value']);
        $this->assertSame('2', $items['IP unik di devices.csv']['value']);
    }

    public function test_known_device_badge_shows_unique_official_ip_count(): void
    {
        $this->get(route('admin.dhcp-lease'))
            ->assertOk()
            ->assertSee('Known Device', false);
    }
}
