<?php

namespace Tests\Feature;

use App\Models\Device;
use App\Models\DeviceNotification;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Facades\Schema;
use Tests\Feature\Concerns\AuthenticatesAdmin;
use Tests\Feature\Concerns\InteractsWithDeviceRegistry;
use Tests\TestCase;

class NotifikasiPageTest extends TestCase
{
    use AuthenticatesAdmin;
    use InteractsWithDeviceRegistry;

    protected function setUp(): void
    {
        parent::setUp();

        // Test ini menguji notifikasi, bukan sumber data: tabel device = daftar resmi.
        $this->treatDeviceTableAsOfficialRegistry();

        if (! Schema::hasTable('devices')) {
            Schema::create('devices', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('type')->default('ICMP');
                $table->string('ip_address');
                $table->string('status')->default('up');
                $table->integer('polls')->default(0);
                $table->decimal('responded_percentage', 6, 2)->default(100);
                $table->decimal('missed_percentage', 6, 2)->default(0);
                $table->integer('avg_delay')->default(0);
                $table->integer('min_delay')->default(0);
                $table->integer('max_delay')->default(0);
                $table->integer('alerts_count')->default(0);
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('device_notifications')) {
            Schema::create('device_notifications', function (Blueprint $table) {
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
            });
        }

        $this->authenticateAsAdmin();
    }

    public function test_notifikasi_page_renders_device_notifications(): void
    {
        $device = Device::create([
            'name' => 'Router-01',
            'type' => 'ICMP',
            'ip_address' => '192.168.1.1',
            'status' => 'down',
        ]);

        DeviceNotification::create([
            'device_id' => $device->id,
            'device_name' => $device->name,
            'ip_address' => $device->ip_address,
            'event_type' => 'down',
            'event' => 'DOWN',
            'status' => 'down',
            'message' => 'Device tidak merespons ping. Host unreachable.',
            'is_read' => false,
            'checked_at' => now(),
        ]);

        $response = $this->get(route('admin.notifikasi'));

        $response->assertStatus(200);
        $response->assertSee('Router-01');
        $response->assertSee('192.168.1.1');
        $response->assertSee('id="notifFilterModal"', false);
        $response->assertSee('Daftar Notifikasi');
        $response->assertSee('id="statusGuideToggle"', false);
        $response->assertSee('id="statusGuideModal"', false);
        $response->assertSee('status-guide__trigger', false);
        $response->assertSee('status-guide-modal__grid', false);
        $response->assertSee('status-guide-modal__item--online', false);
        $response->assertSee('status-guide-modal__item--down', false);
        $response->assertSee('status-guide-modal__item--warning', false);
        $response->assertSee('status-guide-modal__item--transition', false);
        $response->assertSee('max-height: 65vh', false);
        $response->assertSee('background: rgba(15,23,42,.45)', false);
        $response->assertSee('UP / BACK ONLINE');
        $response->assertSee('TRANSISI / RECOVERED');
        $response->assertSee('Saya mengerti');
        $response->assertDontSee('backdrop-filter', false);
        $response->assertDontSee('id="statusGuideContent"', false);
        $response->assertDontSee('class="status-guide__content"', false);
    }

    public function test_network_poll_creates_real_warning_notification(): void
    {
        $device = Device::create([
            'name' => 'Router-Warning',
            'type' => 'ICMP',
            'ip_address' => '192.168.1.2',
            'status' => 'up',
        ]);

        Process::fake([
            '*' => Process::result(
                output: '1 packets transmitted, 0 received, 100% packet loss',
                errorOutput: '',
                exitCode: 1
            ),
        ]);

        $this->artisan('network:poll', ['--no-log' => true])
            ->assertExitCode(0);

        $notification = DeviceNotification::where('device_name', $device->name)->sole();

        $this->assertSame('warning', $notification->status);
        $this->assertSame('WARNING', $notification->event);
        $this->assertSame('warning', $notification->event_type);
        $this->assertStringContainsString('Timed Out', $notification->message);
        $this->assertFalse($notification->is_read);
        $this->assertSame('warning', $device->fresh()->status);

        $this->get(route('admin.notifikasi'))
            ->assertOk()
            ->assertSee('Router-Warning')
            ->assertSee('Paket ping terakhir tidak dijawab (Timed Out).');
    }

    public function test_high_latency_is_saved_as_real_warning_notification(): void
    {
        $device = Device::create([
            'name' => 'Router-High-Latency',
            'type' => 'ICMP',
            'ip_address' => '192.168.1.3',
            'status' => 'up',
        ]);

        Process::fake([
            '*' => Process::result(
                output: 'rtt min/avg/max/mdev = 180.000/180.000/180.000/0.000 ms',
                errorOutput: '',
                exitCode: 0
            ),
        ]);

        $this->artisan('network:poll', ['--no-log' => true])
            ->assertExitCode(0);

        $notification = DeviceNotification::where('device_name', $device->name)->sole();

        $this->assertSame('warning', $notification->status);
        $this->assertSame(180, $notification->latency);
        $this->assertStringContainsString('threshold 150 ms', $notification->message);
        $this->assertSame('warning', $device->fresh()->status);
    }

    public function test_stable_warning_does_not_create_duplicate_notifications(): void
    {
        Device::create([
            'name' => 'Router-Stable-Warning',
            'type' => 'ICMP',
            'ip_address' => '192.168.1.4',
            'status' => 'warning',
        ]);

        Process::fake([
            '*' => Process::result(
                output: '1 packets transmitted, 0 received, 100% packet loss',
                errorOutput: '',
                exitCode: 1
            ),
        ]);

        $this->artisan('network:poll', ['--no-log' => true])->assertExitCode(0);
        $this->artisan('network:poll', ['--no-log' => true])->assertExitCode(0);

        $this->assertDatabaseCount('device_notifications', 1);
    }

    public function test_mark_as_read_endpoint_updates_notification(): void
    {
        $notification = DeviceNotification::create([
            'device_name' => 'Router-01',
            'ip_address' => '192.168.1.1',
            'event_type' => 'down',
            'event' => 'DOWN',
            'status' => 'down',
            'is_read' => false,
            'checked_at' => now(),
        ]);

        $this->patch(route('admin.notifikasi.read', $notification))
            ->assertOk()
            ->assertJson(['ok' => true, 'unread' => 0]);

        $this->assertTrue(
            DeviceNotification::find($notification->id)->is_read
        );
    }

    public function test_bell_badge_shows_unread_count(): void
    {
        $this->createNotifications(3, unread: true);

        $html = $this->get(route('admin.notifikasi'))
            ->assertOk()
            ->assertSee('js-notification-badge', false)
            ->getContent();

        $this->assertStringContainsString('data-unread="3"', $html);
        $this->assertStringNotContainsString('js-notification-badge d-none', $html);
    }

    public function test_bell_badge_caps_at_99_plus(): void
    {
        $this->createNotifications(120, unread: true);

        $html = $this->get(route('admin.notifikasi'))
            ->assertOk()
            ->getContent();

        $this->assertStringContainsString('data-unread="120"', $html);
        $this->assertStringContainsString('>99+<', $html);
    }

    public function test_bell_badge_is_hidden_when_all_notifications_are_read(): void
    {
        $this->createNotifications(2, unread: false);

        $html = $this->get(route('admin.notifikasi'))
            ->assertOk()
            ->getContent();

        $this->assertStringContainsString('js-notification-badge d-none', $html);
        $this->assertStringContainsString('data-unread="0"', $html);
        $this->assertStringNotContainsString('>99+<', $html);
    }

    public function test_unread_count_endpoint_reports_pending_notifications(): void
    {
        $this->createNotifications(4, unread: true);
        $this->createNotifications(3, unread: false);

        $this->get(route('admin.notifikasi.unread-count'))
            ->assertOk()
            ->assertJson(['unread' => 4]);
    }

    public function test_recent_notification_panel_exposes_displayed_ids(): void
    {
        $created = $this->createNotifications(8, unread: true);

        $html = $this->get(route('admin.notifikasi'))
            ->assertOk()
            ->getContent();

        $this->assertStringContainsString('id="notificationOffcanvasList"', $html);

        // Hanya 6 notifikasi terbaru yang ditampilkan pada panel.
        $this->assertSame(6, substr_count($html, 'data-notification-id='));

        foreach ($this->panelIds($created) as $id) {
            $this->assertStringContainsString('data-notification-id="'.$id.'"', $html);
        }

        // Dua notifikasi tertua tidak ikut ditampilkan.
        foreach (array_slice($created, 0, 2) as $notification) {
            $this->assertStringNotContainsString('data-notification-id="'.$notification->id.'"', $html);
        }
    }

    public function test_opening_panel_persists_read_state_and_returns_new_unread_count(): void
    {
        $created = $this->createNotifications(8, unread: true);
        $displayedIds = $this->panelIds($created);
        $untouchedIds = array_map(fn ($n) => $n->id, array_slice($created, 0, 2));

        $this->patch(route('admin.notifikasi.read-displayed'), ['ids' => $displayedIds])
            ->assertOk()
            ->assertJson(['ok' => true, 'unread' => 2]);

        // Status read tersimpan di database.
        $this->assertSame(6, DeviceNotification::whereIn('id', $displayedIds)->where('is_read', true)->count());
        $this->assertSame(2, DeviceNotification::whereIn('id', $untouchedIds)->where('is_read', false)->count());

        // Pindah halaman / refresh: badge memakai hitungan unread terbaru.
        $html = $this->get(route('admin.notifikasi'))->assertOk()->getContent();
        $this->assertStringContainsString('data-unread="2"', $html);

        // Buka panel lagi: dua notifikasi yang tersisa ikut ditandai terbaca.
        $this->patch(route('admin.notifikasi.read-displayed'), ['ids' => $untouchedIds])
            ->assertOk()
            ->assertJson(['ok' => true, 'unread' => 0]);

        $html = $this->get(route('admin.notifikasi'))->assertOk()->getContent();
        $this->assertStringContainsString('js-notification-badge d-none', $html);
        $this->assertStringContainsString('data-unread="0"', $html);
    }

    public function test_mark_displayed_as_read_ignores_invalid_ids(): void
    {
        $notification = $this->createNotifications(1, unread: true)[0];

        $this->patch(route('admin.notifikasi.read-displayed'), [
            'ids' => [$notification->id, 'abc', null, -1],
        ])
            ->assertOk()
            ->assertJson(['ok' => true, 'unread' => 0]);

        $this->assertTrue($notification->fresh()->is_read);
    }

    public function test_mark_all_read_button_is_hidden_when_no_unread_notification(): void
    {
        $this->createNotifications(3, unread: false);

        $html = $this->get(route('admin.notifikasi'))
            ->assertOk()
            ->assertSee('id="notificationMarkAllRead"', false)
            ->getContent();

        $this->assertStringContainsString('id="notificationMarkAllRead"', $html);
        $this->assertStringContainsString('notificationMarkAllRead', $html);
        $this->assertMatchesRegularExpression(
            '/id="notificationMarkAllRead"[^>]*class="[^"]*d-none/',
            $html,
            'Tombol Baca Semua harus disembunyikan saat unread = 0'
        );
    }

    public function test_mark_all_read_button_is_visible_when_unread_notification_exists(): void
    {
        $this->createNotifications(5, unread: true);

        $html = $this->get(route('admin.notifikasi'))
            ->assertOk()
            ->getContent();

        $this->assertMatchesRegularExpression(
            '/id="notificationMarkAllRead"[^>]*class="btn btn-sm btn-outline-primary"/',
            $html,
            'Tombol Baca Semua harus tampil saat unread > 0'
        );
        $this->assertMatchesRegularExpression(
            '/id="notificationMarkAllRead"[^>]*data-unread="5"/',
            $html
        );
        $this->assertStringContainsString('Baca Semua', $html);

        // Feedback visual untuk "semua notifikasi sudah dibaca".
        $this->assertStringContainsString('id="notificationReadAllDone"', $html);
        $this->assertStringContainsString('Semua notifikasi sudah dibaca.', $html);
    }

    public function test_mark_all_read_button_visible_together_with_99_plus_badge(): void
    {
        $this->createNotifications(120, unread: true);

        $html = $this->get(route('admin.notifikasi'))
            ->assertOk()
            ->getContent();

        $this->assertStringContainsString('data-unread="120"', $html);
        $this->assertStringContainsString('>99+<', $html);
        $this->assertMatchesRegularExpression(
            '/id="notificationMarkAllRead"[^>]*class="btn btn-sm btn-outline-primary"/',
            $html
        );
    }

    public function test_mark_all_read_endpoint_marks_every_unread_notification(): void
    {
        // 120 unread, sedangkan panel hanya menampilkan 6.
        $this->createNotifications(120, unread: true);

        $this->assertSame(120, DeviceNotification::query()->unread()->count());

        $this->patch(route('admin.notifikasi.read-all'))
            ->assertOk()
            ->assertJson(['ok' => true, 'unread' => 0]);

        // SELURUH notifikasi unread menjadi read, bukan hanya 6 yang tampil.
        $this->assertSame(0, DeviceNotification::query()->unread()->count());
        $this->assertSame(120, DeviceNotification::query()->where('is_read', true)->count());

        // Badge hilang setelah refresh karena database sudah berubah.
        $html = $this->get(route('admin.notifikasi'))->assertOk()->getContent();
        $this->assertStringContainsString('js-notification-badge d-none', $html);
        $this->assertStringContainsString('data-unread="0"', $html);
        $this->assertStringNotContainsString('>99+<', $html);

        // Tombol ikut disembunyikan.
        $this->assertMatchesRegularExpression(
            '/id="notificationMarkAllRead"[^>]*class="[^"]*d-none/',
            $html
        );

        $this->get(route('admin.notifikasi.unread-count'))
            ->assertOk()
            ->assertJson(['unread' => 0]);
    }

    public function test_mark_all_read_endpoint_marks_small_unread_count(): void
    {
        $this->createNotifications(5, unread: true);

        $this->patch(route('admin.notifikasi.read-all'))
            ->assertOk()
            ->assertJson(['ok' => true, 'unread' => 0]);

        $this->assertSame(0, DeviceNotification::query()->unread()->count());

        $html = $this->get(route('admin.notifikasi'))->assertOk()->getContent();
        $this->assertStringContainsString('js-notification-badge d-none', $html);
    }

    public function test_mark_all_read_endpoint_is_idempotent(): void
    {
        $this->createNotifications(4, unread: true);

        // Klik berkali-kali tidak boleh error maupun mengubah hasil akhir.
        $this->patch(route('admin.notifikasi.read-all'))->assertOk()->assertJson(['unread' => 0]);
        $this->patch(route('admin.notifikasi.read-all'))->assertOk()->assertJson(['unread' => 0]);
        $this->patch(route('admin.notifikasi.read-all'))->assertOk()->assertJson(['unread' => 0]);

        $this->assertSame(0, DeviceNotification::query()->unread()->count());
    }

    public function test_mark_all_read_endpoint_keeps_already_read_notifications_untouched(): void
    {
        $read = $this->createNotifications(2, unread: false);
        $this->createNotifications(3, unread: true);

        $before = DeviceNotification::query()
            ->whereIn('id', array_map(fn ($n) => $n->id, $read))
            ->pluck('updated_at', 'id')
            ->all();

        $this->patch(route('admin.notifikasi.read-all'))->assertOk();

        $this->assertSame(0, DeviceNotification::query()->unread()->count());

        foreach ($read as $notification) {
            $this->assertTrue($notification->fresh()->is_read);
            $this->assertEquals(
                $before[$notification->id],
                $notification->fresh()->updated_at,
                'Notifikasi yang sudah read tidak boleh ikut diubah'
            );
        }
    }

    public function test_mark_all_read_route_does_not_conflict_with_other_notification_routes(): void
    {
        $this->assertNotSame(
            route('admin.notifikasi.read-all'),
            route('admin.notifikasi.read', ['notification' => 1])
        );
        $this->assertNotSame(
            route('admin.notifikasi.read-all'),
            route('admin.notifikasi.read-displayed')
        );

        $notification = $this->createNotifications(1, unread: true)[0];

        // Endpoint read-all tidak boleh tertangkap route {notification}/read.
        $this->patch(route('admin.notifikasi.read-all'))->assertOk();
        $this->assertTrue($notification->fresh()->is_read);

        // Endpoint {notification}/read tetap bekerja untuk satu record.
        $other = $this->createNotifications(1, unread: true)[0];
        $this->patch(route('admin.notifikasi.read', $other))
            ->assertOk()
            ->assertJson(['ok' => true, 'unread' => 0]);
        $this->assertTrue($other->fresh()->is_read);
    }

    public function test_header_search_icon_is_removed(): void
    {
        $html = $this->get(route('admin.notifikasi'))
            ->assertOk()
            ->getContent();

        $this->assertStringNotContainsString('search-inline', $html);
        $this->assertStringNotContainsString('ni ni-search', $html);
        $this->assertStringNotContainsString('Type Query', $html);

        // Ikon lonceng & avatar tetap ada dan rapi di kanan header.
        $this->assertStringContainsString('ni ni-bell', $html);
        $this->assertStringContainsString('nk-quick-nav', $html);
        $this->assertSame(
            2,
            substr_count($html, 'data-bs-target="#notificationOffcanvas"')
        );
    }

    /**
     * Id notifikasi yang tampil di panel Recent Notification (6 terbaru).
     *
     * @param  array<int, DeviceNotification>  $created  urutan lama -> baru
     * @return array<int, int>
     */
    private function panelIds(array $created): array
    {
        return array_map(fn ($n) => $n->id, array_slice($created, -6));
    }

    /**
     * @return array<int, DeviceNotification>
     */
    private function createNotifications(int $total, bool $unread): array
    {
        $created = [];

        for ($i = 0; $i < $total; $i++) {
            $created[] = DeviceNotification::create([
                'device_name' => 'Router-'.($i + 1),
                'ip_address' => '192.168.1.'.($i + 1),
                'event_type' => 'down',
                'event' => 'DOWN',
                'status' => 'down',
                'message' => 'Device tidak merespons ping.',
                'is_read' => ! $unread,
                'checked_at' => now()->subMinutes($total - $i),
            ]);
        }

        return $created;
    }
}
