<?php

namespace Tests\Feature;

use App\Models\Device;
use App\Models\DeviceNotification;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Facades\Schema;
use Tests\Feature\Concerns\InteractsWithDeviceRegistry;
use Tests\TestCase;

class NotifikasiPageTest extends TestCase
{
    use InteractsWithDeviceRegistry;

    protected function setUp(): void
    {
        parent::setUp();

        // Test ini menguji notifikasi, bukan sumber data: tabel device = daftar resmi.
        $this->treatDeviceTableAsOfficialRegistry();

        if (! Schema::hasTable('device')) {
            Schema::create('device', function (Blueprint $table) {
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
            ->assertJson(['ok' => true]);

        $this->assertTrue(
            DeviceNotification::find($notification->id)->is_read
        );
    }
}
