<?php

namespace Tests\Feature;

use App\Models\Device;
use App\Models\MonitoringLog;
use App\Models\Note;
use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class LaporanPageTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        if (! Schema::hasTable('monitoring_logs')) {
            Schema::create('monitoring_logs', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('device_id')->nullable();
                $table->string('device_name');
                $table->string('ip_address');
                $table->string('status');
                $table->integer('response_time')->nullable();
                $table->timestamp('checked_at');
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

        if (! Schema::hasTable('log_archives')) {
            Schema::create('log_archives', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('device_id')->nullable();
                $table->string('device_name');
                $table->string('ip_address');
                $table->string('status');
                $table->integer('response_time')->nullable();
                $table->timestamp('checked_at');
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('activity_logs')) {
            Schema::create('activity_logs', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('device_id')->nullable();
                $table->string('device_name')->nullable();
                $table->string('ip_address')->nullable();
                $table->string('status');
                $table->integer('latency')->nullable();
                $table->timestamp('log_time');
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('users')) {
            Schema::create('users', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('email')->unique();
                $table->timestamp('email_verified_at')->nullable();
                $table->string('password');
                $table->rememberToken();
                $table->enum('role', ['super_admin', 'staff'])->default('staff');
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('device')) {
            Schema::create('device', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('ip_address')->nullable();
                $table->text('penyebab')->nullable();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('device_notes')) {
            Schema::create('device_notes', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('device_id');
                $table->unsignedBigInteger('user_id')->nullable();
                $table->dateTime('tanggal');
                $table->text('catatan');
                $table->string('priority', 20)->default('Normal');
                $table->timestamps();
            });
        }
    }

    public function test_laporan_page_renders_with_real_monitoring_data(): void
    {
        $checkedAt = now()->startOfHour();

        MonitoringLog::create([
            'device_name' => 'Router-01',
            'ip_address' => '192.168.1.1',
            'status' => 'up',
            'response_time' => 10,
            'checked_at' => $checkedAt,
        ]);

        MonitoringLog::create([
            'device_name' => 'Router-02',
            'ip_address' => '192.168.1.2',
            'status' => 'down',
            'response_time' => null,
            'checked_at' => $checkedAt,
        ]);

        $response = $this->get(route('admin.laporan'));

        $response->assertStatus(200);

        $html = $response->getContent();

        $this->assertStringContainsString('availability', $html);
        $this->assertStringContainsString('response', $html);
        $this->assertStringContainsString('Router', $html.' x');

        $this->assertStringNotContainsString('Incident Summary', $html);
        $this->assertStringNotContainsString('incident-list', $html);
        $this->assertStringNotContainsString('incident-item', $html);
    }

    public function test_legacy_penyebab_form_is_replaced_by_note_history(): void
    {
        $staff = User::factory()->create(['role' => 'staff']);
        $device = Device::create([
            'name' => 'Router-99',
            'ip_address' => '10.0.0.99',
        ]);
        Note::create([
            'device_id' => $device->id,
            'user_id' => $staff->id,
            'tanggal' => '2026-09-25 18:30:00',
            'catatan' => 'Perangkat tidak merespons ICMP sejak 18:19.',
            'priority' => 'Urgent',
        ]);

        $response = $this->actingAs($staff)->get(route('admin.device.detail', $device));

        $response->assertOk();
        $response->assertSee('Riwayat Catatan');
        $response->assertSee('Perangkat tidak merespons ICMP sejak 18:19.');
        $response->assertDontSee('Catatan Penyebab Masalah');
        $response->assertDontSee('name="penyebab"');
    }
}
