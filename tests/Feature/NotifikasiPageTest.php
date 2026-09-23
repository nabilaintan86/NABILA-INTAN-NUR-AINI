<?php

namespace Tests\Feature;

use App\Models\Device;
use App\Models\DeviceNotification;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Tests\TestCase;

class NotifikasiPageTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        if (!Schema::hasTable('device')) {
            Schema::create('device', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('type')->default('ICMP');
                $table->string('ip_address');
                $table->string('status')->default('up');
                $table->integer('polls')->default(0);
                $table->integer('alerts_count')->default(0);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('device_notifications')) {
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
            'name'       => 'Router-01',
            'type'       => 'ICMP',
            'ip_address' => '192.168.1.1',
            'status'     => 'down',
        ]);

        DeviceNotification::create([
            'device_id'   => $device->id,
            'device_name' => $device->name,
            'ip_address'  => $device->ip_address,
            'event_type'  => 'down',
            'event'       => 'DOWN',
            'status'      => 'down',
            'message'     => 'Device tidak merespons ping. Host unreachable.',
            'is_read'     => false,
            'checked_at'  => now(),
        ]);

        $response = $this->get(route('admin.notifikasi'));

        $response->assertStatus(200);
        $response->assertSee('Router-01');
        $response->assertSee('192.168.1.1');
    }

    public function test_mark_as_read_endpoint_updates_notification(): void
    {
        $notification = DeviceNotification::create([
            'device_name' => 'Router-01',
            'ip_address'  => '192.168.1.1',
            'event_type'  => 'down',
            'event'       => 'DOWN',
            'status'      => 'down',
            'is_read'     => false,
            'checked_at'  => now(),
        ]);

        $this->patch(route('admin.notifikasi.read', $notification))
            ->assertOk()
            ->assertJson(['ok' => true]);

        $this->assertTrue(
            DeviceNotification::find($notification->id)->is_read
        );
    }
}