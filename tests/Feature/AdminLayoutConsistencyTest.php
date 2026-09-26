<?php

namespace Tests\Feature;

use App\Models\Device;
use App\Models\User;
use DOMDocument;
use DOMElement;
use DOMXPath;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

/**
 * Regression test untuk standar layout global admin.
 *
 * Semua halaman admin harus:
 *  - memakai SATU container global (.admin-content) tanpa wrapper container tambahan,
 *  - memakai komponen page head yang sama (.admin-page-head),
 *  - tidak memuat breadcrumb "Home",
 *  - menghasilkan HTML yang well-formed (tag seIMbang).
 */
class AdminLayoutConsistencyTest extends TestCase
{
    /** @var array<string, array{0: string, 1: string}> route => [label, pageTitle] */
    private const PAGES = [
        'admin.dashboard'          => ['Dashboard', 'Dashboard'],
        'admin.monitoring'         => ['Monitoring Jaringan', 'Monitoring Jaringan'],
        'admin.log'                => ['Log History', 'Log History'],
        'admin.notifikasi'         => ['Notifikasi', 'Notifikasi'],
        'admin.kelola-router'      => ['Kelola Router', 'Kelola Router'],
        'admin.device.notes.report' => ['Report Catatan Perangkat', 'Report Catatan'],
        'admin.dhcp-lease'         => ['DHCP Lease', 'DHCP Lease'],
        'admin.laporan'            => ['Laporan &amp; Grafik', 'Laporan & Grafik'],
        'admin.tools'              => ['Network Tools', 'Tools'],
        'admin.kelola-user'        => ['Kelola User', 'Kelola User'],
        'admin.kelola-user.create' => ['Tambah User', 'Tambah User'],
        'admin.telegram-users'     => ['Telegram Users', 'Telegram Users'],
    ];

    protected function setUp(): void
    {
        parent::setUp();
        $this->createSchema();
    }

    public function test_every_admin_page_uses_the_same_global_layout(): void
    {
        $user = $this->superAdmin();
        $device = Device::create([
            'name' => 'POP SBY',
            'ip_address' => '10.0.0.1',
            'type' => 'Router',
            'status' => 'up',
        ]);

        $routes = self::PAGES;
        $routes['admin.device.detail'] = ['POP SBY', 'Detail Device'];

        foreach ($routes as $route => [$pageTitle, $headerTitle]) {
            $parameters = $route === 'admin.device.detail' ? [$device] : [];

            $response = $this->actingAs($user)->get(route($route, $parameters));
            $response->assertStatus(200);

            $html = $response->getContent();
            $dom = $this->parse($html, $route);
            $xpath = new DOMXPath($dom);

            // 1. Header ikut halaman aktif.
            $this->assertStringContainsString(
                '<h5 class="mb-0">'.e($headerTitle).'</h5>',
                $html,
                $route.': judul header tidak dinamis'
            );

            // 2. Tepat satu container global.
            $this->assertSame(
                1,
                $xpath->query('//div[contains(concat(" ", normalize-space(@class), " "), " admin-content ")]')->length,
                $route.': harus tepat satu .admin-content'
            );

            // 3. Konten halaman berada di dalam .admin-page.
            $this->assertSame(
                1,
                $xpath->query('//div[contains(concat(" ", normalize-space(@class), " "), " admin-content ")]/div[contains(concat(" ", normalize-space(@class), " "), " admin-page ")]')->length,
                $route.': konten harus di dalam .admin-page'
            );

            // 4. Semua section/card berada di dalam .admin-page (bukan meleset
            //    ke container sendiri).
            $this->assertSame(
                0,
                $xpath->query('//div[contains(concat(" ", normalize-space(@class), " "), " admin-content ")]//div[contains(concat(" ", normalize-space(@class), " "), " container ")]')->length,
                $route.': halaman tidak boleh menambah container sendiri'
            );

            // 5. Memakai page head global yang sama.
            $this->assertSame(
                1,
                $xpath->query('//div[contains(concat(" ", normalize-space(@class), " "), " admin-page-head ")]')->length,
                $route.': harus memakai satu komponen .admin-page-head'
            );

            $this->assertSame(
                1,
                $xpath->query('//div[contains(concat(" ", normalize-space(@class), " "), " admin-page-head ")]//h1')->length,
                $route.': page head harus punya tepat satu judul'
            );

            $this->assertStringContainsString($pageTitle, $html, $route.': judul halaman tidak tampil');

            // 6. Breadcrumb "Home" tidak boleh ada di halaman internal.
            $this->assertSame(
                0,
                $xpath->query('//ol[contains(concat(" ", normalize-space(@class), " "), " breadcrumb ")]/li[normalize-space(text()) = "Home" or normalize-space(text()) = "Beranda"]')->length,
                $route.': breadcrumb "Home" harus dihapus'
            );

            // 7. Breadcrumb (bila ada) memakai container yang sama.
            foreach ($xpath->query('//div[contains(concat(" ", normalize-space(@class), " "), " admin-page ")]//ol[contains(concat(" ", normalize-space(@class), " "), " breadcrumb ")]') as $crumb) {
                $this->assertTrue(
                    $this->closestInsidePage($crumb, $xpath),
                    $route.': breadcrumb berada di luar .admin-page'
                );
            }
        }
    }

    public function test_detail_router_has_no_add_note_button(): void
    {
        $user = $this->superAdmin();
        $device = Device::create([
            'name' => 'POP SBY',
            'ip_address' => '10.0.0.1',
            'type' => 'Router',
            'status' => 'up',
        ]);

        $html = $this->actingAs($user)
            ->get(route('admin.device.detail', $device))
            ->assertStatus(200)
            ->getContent();

        $this->assertStringNotContainsString('Tambah Catatan', $html);
        $this->assertStringNotContainsString('reportNoteModal', $html);
    }

    public function test_global_layout_stylesheet_is_the_only_source_of_container(): void
    {
        $layout = view('layouts.admin')->with('pageTitle', 'Dashboard')->render();

        $this->assertStringContainsString('.admin-content {', $layout);
        $this->assertStringContainsString('max-width: var(--admin-max);', $layout);
        $this->assertStringContainsString('nk-content-body admin-page', $layout);
    }

    private function closestInsidePage(DOMElement $node, DOMXPath $xpath): bool
    {
        $parent = $node->parentNode;

        while ($parent instanceof DOMElement) {
            if (in_array('admin-page', preg_split('/\s+/', trim($parent->getAttribute('class'))) ?: [], true)) {
                return true;
            }
            $parent = $parent->parentNode;
        }

        return false;
    }

    private function parse(string $html, string $route): DOMDocument
    {
        $dom = new DOMDocument();
        $previous = libxml_use_internal_errors(true);
        $dom->loadHTML('<?xml encoding="utf-8" ?>'.$html);
        $errors = libxml_get_errors();
        libxml_clear_errors();
        libxml_use_internal_errors($previous);

        $this->assertSame(
            [],
            array_map(fn ($error) => trim($error->message), $errors),
            $route.': HTML tidak well-formed'
        );

        return $dom;
    }

    private function superAdmin(): User
    {
        return User::create([
            'name' => 'Layout Admin',
            'email' => 'layout-admin@example.test',
            'role' => 'super_admin',
            'password' => bcrypt('password'),
        ]);
    }

    private function createSchema(): void
    {
        $tables = [
            'users' => function (Blueprint $table): void {
                $table->id();
                $table->string('name');
                $table->string('email')->unique();
                $table->string('role')->default('admin');
                $table->timestamp('email_verified_at')->nullable();
                $table->string('password');
                $table->unsignedBigInteger('telegram_chat_id')->nullable();
                $table->rememberToken();
                $table->timestamps();
            },
            'sessions' => function (Blueprint $table): void {
                $table->string('id')->primary();
                $table->foreignId('user_id')->nullable()->index();
                $table->string('ip_address', 45)->nullable();
                $table->text('user_agent')->nullable();
                $table->longText('payload');
                $table->integer('last_activity')->index();
            },
            'device' => function (Blueprint $table): void {
                $table->id();
                $table->string('name');
                $table->string('ip_address')->nullable();
                $table->string('hostname')->nullable();
                $table->string('type')->default('Other');
                $table->string('protocol', 32)->default('ICMP');
                $table->text('deskripsi')->nullable();
                $table->text('penyebab')->nullable();
                $table->string('gambar')->nullable();
                $table->string('icon_type')->nullable();
                $table->string('pop_name')->nullable();
                $table->string('map_area')->nullable();
                $table->integer('map_x')->nullable();
                $table->integer('map_y')->nullable();
                $table->bigInteger('polls')->default(0);
                $table->decimal('responded_percentage', 6, 2)->default(100);
                $table->decimal('missed_percentage', 6, 2)->default(0);
                $table->string('down_time')->nullable();
                $table->string('period')->nullable();
                $table->integer('alerts_count')->default(0);
                $table->integer('avg_delay')->default(0);
                $table->integer('min_delay')->default(0);
                $table->integer('max_delay')->default(0);
                $table->string('status')->default('up');
                $table->timestamps();
            },
            'device_notes' => function (Blueprint $table): void {
                $table->id();
                $table->foreignId('device_id');
                $table->foreignId('user_id')->nullable();
                $table->dateTime('tanggal')->nullable();
                $table->time('waktu')->nullable();
                $table->string('priority')->default('Normal');
                $table->text('catatan');
                $table->timestamps();
            },
            'device_notifications' => function (Blueprint $table): void {
                $table->id();
                $table->unsignedBigInteger('device_id')->nullable();
                $table->string('device_name');
                $table->string('ip_address');
                $table->string('event_type');
                $table->string('event');
                $table->string('status');
                $table->text('message')->nullable();
                $table->integer('latency')->nullable();
                $table->boolean('is_read')->default(false);
                $table->timestamp('checked_at');
                $table->timestamps();
            },
            'device_connections' => function (Blueprint $table): void {
                $table->id();
                $table->foreignId('from_device_id');
                $table->foreignId('to_device_id');
                $table->string('connection_type')->default('ethernet');
                $table->timestamps();
            },
            'monitoring_logs' => function (Blueprint $table): void {
                $table->id();
                $table->foreignId('device_id')->nullable();
                $table->string('device_name')->nullable();
                $table->string('ip_address')->nullable();
                $table->string('status')->nullable();
                $table->integer('response_time')->nullable();
                $table->timestamp('checked_at')->nullable();
                $table->timestamps();
            },
            'log_archives' => function (Blueprint $table): void {
                $table->id();
                $table->unsignedBigInteger('device_id')->nullable();
                $table->string('device_name')->nullable();
                $table->string('ip_address')->nullable();
                $table->string('status')->nullable();
                $table->integer('response_time')->nullable();
                $table->timestamp('checked_at')->nullable();
                $table->timestamp('archived_at')->nullable();
                $table->timestamps();
            },
            'ping_histories' => function (Blueprint $table): void {
                $table->id();
                $table->foreignId('device_id')->nullable();
                $table->integer('response_time')->nullable();
                $table->string('status')->nullable();
                $table->timestamp('checked_at')->nullable();
                $table->timestamps();
            },
            'activity_logs' => function (Blueprint $table): void {
                $table->id();
                $table->foreignId('user_id')->nullable();
                $table->string('action')->nullable();
                $table->text('description')->nullable();
                $table->timestamps();
            },
            'dhcp_leases' => function (Blueprint $table): void {
                $table->id();
                $table->unsignedBigInteger('device_id')->nullable()->index();
                $table->string('mac_address')->nullable();
                $table->string('ip_address');
                $table->string('hostname')->nullable();
                $table->string('client_id')->nullable();
                $table->string('vendor')->nullable();
                $table->string('router')->nullable();
                $table->string('location')->nullable();
                $table->timestamp('lease_start')->nullable();
                $table->timestamp('lease_end')->nullable();
                $table->string('classification')->default('unknown');
                $table->timestamp('first_seen')->nullable();
                $table->timestamp('last_seen')->nullable();
                $table->string('source')->nullable();
                $table->text('comment')->nullable();
                $table->timestamps();
            },
            'telegram_users' => function (Blueprint $table): void {
                $table->id();
                $table->unsignedBigInteger('user_id')->nullable();
                $table->string('telegram_id')->nullable();
                $table->string('first_name')->nullable();
                $table->string('last_name')->nullable();
                $table->string('username')->nullable();
                $table->string('status')->default('pending');
                $table->string('role')->nullable();
                $table->timestamp('last_seen_at')->nullable();
                $table->timestamps();
            },
            'telegram_logs' => function (Blueprint $table): void {
                $table->id();
                $table->unsignedBigInteger('telegram_user_id')->nullable();
                $table->string('command')->nullable();
                $table->text('response')->nullable();
                $table->timestamps();
            },
            'telegram_subscribers' => function (Blueprint $table): void {
                $table->id();
                $table->string('chat_id');
                $table->string('name')->nullable();
                $table->string('status')->default('active');
                $table->timestamps();
            },
        ];

        foreach ($tables as $name => $definition) {
            if (! Schema::hasTable($name)) {
                Schema::create($name, $definition);
            }
        }
    }
}
