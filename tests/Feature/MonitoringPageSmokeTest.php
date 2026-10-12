<?php

namespace Tests\Feature;

use App\Models\Device;
use App\Models\DeviceConnection;
use App\Models\User;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Tests\TestCase;

class MonitoringPageSmokeTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        if (!Schema::hasTable('users')) {
            Schema::create('users', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('email')->unique();
                $table->string('role')->default('admin');
                $table->timestamp('email_verified_at')->nullable();
                $table->string('password');
                $table->rememberToken();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('sessions')) {
            Schema::create('sessions', function (Blueprint $table) {
                $table->string('id')->primary();
                $table->foreignId('user_id')->nullable()->index();
                $table->string('ip_address', 45)->nullable();
                $table->text('user_agent')->nullable();
                $table->longText('payload');
                $table->integer('last_activity')->index();
            });
        }

        if (!Schema::hasTable('devices')) {
            Schema::create('devices', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('type')->default('ICMP');
                $table->string('ip_address');
                $table->string('status')->default('up');
                $table->string('icon_type')->nullable();
                $table->string('pop_name')->nullable();
                $table->integer('polls')->default(0);
                $table->integer('alerts_count')->default(0);
                $table->foreignId('network_area_id')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('device_connections')) {
            Schema::create('device_connections', function (Blueprint $table) {
                $table->id();
                $table->foreignId('from_device_id');
                $table->foreignId('to_device_id');
                $table->string('connection_type')->default('ethernet');
                $table->foreignId('from_position_id')->nullable();
                $table->foreignId('to_position_id')->nullable();
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

        if (!Schema::hasTable('network_areas')) {
            Schema::create('network_areas', function (Blueprint $table) {
                $table->id();
                $table->string('name')->unique();
                $table->string('code')->nullable();
                $table->text('description')->nullable();
                $table->integer('pos_x')->default(0);
                $table->integer('pos_y')->default(0);
                $table->integer('width')->default(200);
                $table->integer('height')->default(150);
                $table->string('border_color')->default('#1a8024');
                $table->string('bg_color')->default('#f8fafc');
                $table->string('title_color')->default('#1a8024');
                $table->integer('sort_order')->default(0);
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('device_positions')) {
            Schema::create('device_positions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('device_id');
                $table->foreignId('network_area_id')->nullable();
                $table->integer('pos_x')->default(0);
                $table->integer('pos_y')->default(0);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('map_settings')) {
            Schema::create('map_settings', function (Blueprint $table) {
                $table->id();
                $table->string('key')->unique();
                $table->json('value')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('topology_maps')) {
            Schema::create('topology_maps', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('slug')->nullable();
                $table->text('description')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('monitoring_logs')) {
            Schema::create('monitoring_logs', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('device_id')->nullable();
                $table->string('status')->default('up');
                $table->integer('latency')->nullable();
                $table->timestamp('checked_at')->nullable();
                $table->timestamps();
            });
        }

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

        if (DeviceConnection::count() === 0) {
            DeviceConnection::create(['from_device_id' => 11, 'to_device_id' => 10, 'connection_type' => 'ethernet']);
            DeviceConnection::create(['from_device_id' => 11, 'to_device_id' => 43, 'connection_type' => 'ethernet']);
            DeviceConnection::create(['from_device_id' => 28, 'to_device_id' => 29, 'connection_type' => 'ethernet']);
        }
    }

    public function test_monitoring_page_returns_200(): void
    {
        $user = User::first() ?? User::create([
            'name'     => 'Smoke',
            'email'    => 'smoke@example.test',
            'role'     => 'admin',
            'password' => bcrypt('password'),
        ]);

        $this->actingAs($user);

        $this->get('/admin/monitoring')
            ->assertStatus(200)
            ->assertSee('RADNET Wireless Network', false);
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