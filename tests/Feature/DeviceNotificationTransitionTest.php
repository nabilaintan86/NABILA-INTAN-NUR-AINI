<?php

namespace Tests\Feature;

use App\Models\Device;
use App\Models\DeviceNotification;
use App\Services\DeviceStatusNotifier;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Facades\Schema;
use Tests\Feature\Concerns\InteractsWithDeviceRegistry;
use Tests\TestCase;

/**
 * Notifikasi harus berbasis transisi status, bukan berdasarkan jumlah polling.
 */
class DeviceNotificationTransitionTest extends TestCase
{
    use InteractsWithDeviceRegistry;

    protected function setUp(): void
    {
        parent::setUp();
        $this->treatDeviceTableAsOfficialRegistry();

        if (! Schema::hasTable('devices')) {
            Schema::create('devices', function (Blueprint $table): void {
                $table->id();
                $table->string('name');
                $table->string('type')->default('Router');
                $table->string('protocol', 32)->default('ICMP');
                $table->string('ip_address')->nullable();
                $table->string('status')->default('up');
                $table->unsignedBigInteger('polls')->default(0);
                $table->unsignedBigInteger('alerts_count')->default(0);
                $table->integer('avg_delay')->default(0);
                $table->integer('min_delay')->default(0);
                $table->integer('max_delay')->default(0);
                $table->decimal('responded_percentage', 6, 2)->default(100);
                $table->decimal('missed_percentage', 6, 2)->default(0);
                $table->string('down_time')->nullable();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('device_notifications')) {
            Schema::create('device_notifications', function (Blueprint $table): void {
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

        if (! Schema::hasTable('activity_logs')) {
            Schema::create('activity_logs', function (Blueprint $table): void {
                $table->id();
                $table->unsignedBigInteger('device_id')->nullable();
                $table->string('device_name')->nullable();
                $table->string('action')->nullable();
                $table->string('status')->nullable();
                $table->text('reason')->nullable();
                $table->string('ip_address')->nullable();
                $table->timestamps();
            });
        }
    }

    private function device(string $name = 'Router-Transisi', string $ip = '10.10.0.1', string $status = 'up'): Device
    {
        $device = Device::create([
            'name' => $name,
            'type' => 'Router',
            'protocol' => 'ICMP',
            'ip_address' => $ip,
            'status' => $status,
        ]);

        $this->registerOfficialDevice($name, $ip, $status);

        return $device;
    }

    private function pollReachable(int $latency = 20): void
    {
        Process::fake([
            '*' => Process::result(
                output: "1 packets transmitted, 1 received, 0% packet loss\nrtt min/avg/max/mdev = {$latency}.000/{$latency}.000/{$latency}.000/0.000 ms",
                errorOutput: '',
                exitCode: 0
            ),
        ]);
    }

    /** Ping timeout (exit code 1) -> status 'warning' (degraded). */
    private function pollTimeout(): void
    {
        Process::fake([
            '*' => Process::result(
                output: '1 packets transmitted, 0 received, 100% packet loss',
                errorOutput: '',
                exitCode: 1
            ),
        ]);
    }

    /** Host benar-benar tidak merespons (exit code > 1) -> status 'down'. */
    private function pollDown(): void
    {
        Process::fake([
            '*' => Process::result(
                output: 'ping: sendmsg: Network is unreachable',
                errorOutput: '',
                exitCode: 2
            ),
        ]);
    }

    private function runPoll(): void
    {
        $this->artisan('network:poll', ['--no-log' => true])->assertExitCode(0);
    }

    private function eventsFor(Device $device): array
    {
        return DeviceNotification::query()
            ->where('device_id', $device->id)
            ->orderBy('id')
            ->pluck('event_type')
            ->all();
    }

    // =========================================================
    // KASUS 1-6 SESUAI SPESIFIKASI
    // =========================================================

    /** CASE 1 + 2 + 3: UP -> DOWN lalu tetap DOWN. */
    public function test_case_1_to_3_up_then_down_only_notifies_once(): void
    {
        $device = $this->device();

        $this->pollDown();
        $this->runPoll();

        $this->assertSame(['down'], $this->eventsFor($device), 'UP -> DOWN harus membuat 1 notifikasi DOWN');

        $this->runPoll();
        $this->runPoll();

        $this->assertSame(['down'], $this->eventsFor($device), 'DOWN -> DOWN tidak boleh menambah notifikasi');
        $this->assertSame(1, DeviceNotification::where('device_id', $device->id)->count());
    }

    /** CASE 4: DOWN -> UP membuat 1 notifikasi recovery. */
    public function test_case_4_down_then_up_creates_single_recovery(): void
    {
        $device = $this->device();

        $this->pollDown();
        $this->runPoll();
        $this->assertSame(['down'], $this->eventsFor($device));

        $this->pollReachable();
        $this->runPoll();

        $this->assertSame(['down', 'recovered'], $this->eventsFor($device));
        $this->assertSame(
            'BACK ONLINE',
            DeviceNotification::where('device_id', $device->id)->where('event_type', 'recovered')->value('event')
        );
    }

    /** CASE 5: UP -> UP tidak membuat notifikasi. */
    public function test_case_5_up_stays_up_never_notifies(): void
    {
        $device = $this->device();

        $this->pollReachable();
        $this->runPoll();
        $this->runPoll();
        $this->runPoll();

        $this->assertSame([], $this->eventsFor($device));
        $this->assertSame(0, DeviceNotification::count());
    }

    /** CASE 6: siklus lengkap UP -> DOWN -> UP -> DOWN menghasilkan tepat 3 notifikasi. */
    public function test_case_6_full_lifecycle_creates_three_notifications(): void
    {
        $device = $this->device();

        $this->pollDown();          // UP -> DOWN   : 1 notifikasi
        $this->runPoll();
        $this->runPoll();                  // DOWN -> DOWN : 0

        $this->pollReachable();            // DOWN -> UP   : 1 notifikasi
        $this->runPoll();
        $this->runPoll();                  // UP -> UP     : 0

        $this->pollDown();          // UP -> DOWN   : 1 notifikasi
        $this->runPoll();
        $this->runPoll();                  // DOWN -> DOWN : 0

        $this->assertSame(['down', 'recovered', 'down'], $this->eventsFor($device));
        $this->assertSame(3, DeviceNotification::where('device_id', $device->id)->count());
    }

    // =========================================================
    // EDGE CASE
    // =========================================================

    /** Status sebelumnya belum pernah diketahui: jangan mengarang notifikasi. */
    public function test_unknown_previous_status_does_not_notify(): void
    {
        $device = $this->device('Router-Kosong', '10.10.0.2', '');
        $this->assertSame('', (string) $device->status);

        $this->pollDown();
        $this->runPoll();

        $this->assertSame(0, DeviceNotification::where('device_id', $device->id)->count());
        $this->assertSame('down', $device->fresh()->status, 'Status tetap disimpan di tabel device');
    }

    /**
     * Timeout -> 'warning' -> tetap timeout tidak boleh spam.
     * Pemulihan dari 'warning' pun tidak boleh membuat "recovered" palsu,
     * karena device tidak pernah tercatat DOWN.
     */
    public function test_timeout_warning_flapping_does_not_spam(): void
    {
        $device = $this->device('Router-Flap', '10.10.0.3', 'up');

        // 1) timeout pertama: satu warning
        $this->pollTimeout();
        $this->runPoll();
        $this->assertSame(['warning'], $this->eventsFor($device));
        $this->assertSame('warning', $device->fresh()->status);

        // 2) timeout berikutnya: tidak ada notifikasi baru
        $this->runPoll();
        $this->runPoll();
        $this->assertSame(['warning'], $this->eventsFor($device));

        // 3) sempat merespons lagi (tapi tidak tercatat DOWN): tidak ada "recovered"
        $this->pollReachable();
        $this->runPoll();
        $this->assertSame(['warning'], $this->eventsFor($device));
        $this->assertSame('up', $device->fresh()->status);

        // 4) timeout lagi setelah sempat pulih: warning tidak diulang, karena device
        //    belum pernah punya notifikasi DOWN/RECOVERED (episode masih sama).
        $this->pollTimeout();
        $this->runPoll();
        $this->assertSame(['warning'], $this->eventsFor($device));

        // 5) benar-benar DOWN (alasan selain timeout): notifikasi DOWN
        $this->pollDown();
        $this->runPoll();
        $this->assertSame(['warning', 'down'], $this->eventsFor($device));

        // 6) kembali merespons: satu notifikasi RECOVERED
        $this->pollReachable();
        $this->runPoll();
        $this->assertSame(['warning', 'down', 'recovered'], $this->eventsFor($device));

        // 7) episode baru: timeout setelah recovery boleh membuat warning lagi
        $this->pollTimeout();
        $this->runPoll();
        $this->assertSame(['warning', 'down', 'recovered', 'warning'], $this->eventsFor($device));

        // 8) timeout lagi langsung sesudahnya: tidak ada tambahan
        $this->runPoll();
        $this->assertSame(4, DeviceNotification::where('device_id', $device->id)->count());
    }

    /** Device dengan latency tinggi tidak boleh membuat DOWN/RECOVERED bolak-balik. */
    public function test_high_latency_does_not_create_down_recovered_loop(): void
    {
        $device = $this->device('Router-Latency', '10.10.0.4', 'up');

        $this->pollReachable(180);
        $this->runPoll();
        $this->assertSame(['warning'], $this->eventsFor($device));

        // masih lambat
        $this->pollReachable(190);
        $this->runPoll();
        $this->assertSame(['warning'], $this->eventsFor($device));

        // sudah normal lagi: tidak ada recovered karena tidak pernah DOWN
        $this->pollReachable(20);
        $this->runPoll();
        $this->assertSame(['warning'], $this->eventsFor($device));
        $this->assertSame(0, DeviceNotification::where('event_type', 'recovered')->count());
    }

    // =========================================================
    // ANTI-DUPLIKAT
    // =========================================================

    /**
     * Dua poller yang berebut mencatat transisi yang sama hanya menghasilkan
     * satu notifikasi (dicek di dalam transaksi + row lock device).
     */
    public function test_concurrent_pollers_do_not_duplicate_same_transition(): void
    {
        $device = $this->device('Router-Race', '10.10.0.5', 'up');
        $notifier = app(DeviceStatusNotifier::class);

        // Dua proses "lolos"读到 status lama yang sama sebelum ada yang menulis.
        $first = $notifier->record($device, 'up', 'down', null, 'Host unreachable');
        $second = $notifier->record($device, 'up', 'down', null, 'Host unreachable');

        $this->assertNotNull($first);
        $this->assertNull($second, 'Transisi yang sama tidak boleh dicatat dua kali');
        $this->assertSame(1, DeviceNotification::where('device_id', $device->id)->count());
    }

    public function test_duplicate_guard_uses_existing_notification_history(): void
    {
        $device = $this->device('Router-Guard', '10.10.0.6', 'up');

        // Notifikasi DOWN lama (dari episode sebelumnya) sudah tercatat.
        DeviceNotification::create([
            'device_id' => $device->id,
            'device_name' => $device->name,
            'ip_address' => $device->ip_address,
            'event_type' => 'down',
            'event' => 'DOWN',
            'status' => 'down',
            'message' => 'lama',
            'is_read' => true,
            'checked_at' => now()->subHours(3),
        ]);

        $this->pollDown();
        $this->runPoll();

        $this->assertSame(1, DeviceNotification::count(), 'DOWN -> DOWN tidak boleh menambah notifikasi');
    }

    // =========================================================
    // HEADER / OFFCANVAS / HALAMAN NOTIFIKASI
    // =========================================================

    public function test_all_read_after_mark_all_then_no_new_notification_for_stable_status(): void
    {
        $device = $this->device('Router-Read', '10.10.0.7', 'up');

        $this->pollDown();
        $this->runPoll();
        $this->assertSame(1, (new DeviceNotification)->unreadCount());

        $this->patch(route('admin.notifikasi.read-all'))->assertOk();
        $this->assertSame(0, (new DeviceNotification)->unreadCount(), 'Baca semua harus mengosongkan badge');

        // Polling berikutnya saat device tetap DOWN: tidak ada notifikasi baru.
        $this->runPoll();
        $this->runPoll();

        $this->assertSame(1, DeviceNotification::count());
        $this->assertSame(0, (new DeviceNotification)->unreadCount(), 'Badge tidak muncul kembali tanpa transisi baru');
    }
}
