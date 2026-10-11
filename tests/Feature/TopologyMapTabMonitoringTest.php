<?php

namespace Tests\Feature;

use App\Models\Device;
use App\Models\TopologyMap;
use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class TopologyMapTabMonitoringTest extends TestCase
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
                $table->string('protocol')->default('ICMP');
                $table->string('router_version')->nullable();
                $table->integer('pos_x')->nullable();
                $table->integer('pos_y')->nullable();
                $table->boolean('map_positioned')->default(false);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('device_connections')) {
            Schema::create('device_connections', function (Blueprint $table) {
                $table->id();
                $table->foreignId('from_device_id');
                $table->foreignId('to_device_id');
                $table->string('connection_type')->default('ethernet');
                $table->string('direction')->default('forward');
                $table->foreignId('from_position_id')->nullable();
                $table->foreignId('to_position_id')->nullable();
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

        if (!Schema::hasTable('topology_maps')) {
            Schema::create('topology_maps', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('slug')->nullable();
                $table->text('description')->nullable();
                $table->foreignId('created_by')->nullable();
                $table->boolean('is_active')->default(true);
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

        if (!Schema::hasTable('device_notifications')) {
            Schema::create('device_notifications', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('device_id')->nullable();
                $table->string('device_name')->nullable();
                $table->string('ip_address')->nullable();
                $table->string('event_type')->nullable();
                $table->string('event')->nullable();
                $table->string('status')->nullable();
                $table->text('message')->nullable();
                $table->integer('latency')->nullable();
                $table->boolean('is_read')->default(false);
                $table->timestamp('checked_at')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('topology_map_devices')) {
            Schema::create('topology_map_devices', function (Blueprint $table) {
                $table->id();
                $table->foreignId('topology_map_id');
                $table->foreignId('device_id');
                $table->foreignId('topology_map_area_id')->nullable();
                $table->integer('pos_x')->default(100);
                $table->integer('pos_y')->default(100);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('topology_map_areas')) {
            Schema::create('topology_map_areas', function (Blueprint $table) {
                $table->id();
                $table->foreignId('topology_map_id');
                $table->string('name');
                $table->integer('pos_x')->default(0);
                $table->integer('pos_y')->default(0);
                $table->integer('width')->default(300);
                $table->integer('height')->default(200);
                $table->string('border_color')->default('#1a8024');
                $table->string('bg_color')->default('#f8fafc');
                $table->string('title_color')->default('#1a8024');
                $table->integer('sort_order')->default(0);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('topology_map_connections')) {
            Schema::create('topology_map_connections', function (Blueprint $table) {
                $table->id();
                $table->foreignId('topology_map_id');
                $table->foreignId('from_map_device_id');
                $table->foreignId('to_map_device_id');
                $table->string('connection_type')->default('ethernet');
                $table->string('direction')->default('forward');
                $table->timestamps();
            });
        }
    }

    private function authenticateUser(): User
    {
        $user = User::first() ?? User::create([
            'name'     => 'Admin User',
            'email'    => 'admin@example.test',
            'role'     => 'super_admin',
            'password' => bcrypt('password'),
        ]);

        $this->actingAs($user);

        return $user;
    }

    public function test_monitoring_page_shows_primary_map_tab_and_new_map_button(): void
    {
        $this->authenticateUser();

        $response = $this->get('/admin/monitoring');

        $response->assertStatus(200);
        $response->assertSee('Map: RADNET Wireless Network 2026', false);
        $response->assertSee('data-bs-target="#modalQuickAddMap"', false);
    }

    public function test_custom_topology_map_renders_as_tab_on_monitoring(): void
    {
        $this->authenticateUser();

        $map = TopologyMap::create([
            'name'        => 'Gedung Cabang Timur',
            'description' => 'Monitoring cabang timur',
        ]);

        $response = $this->get('/admin/monitoring');
        $response->assertStatus(200);
        $response->assertSee('Gedung Cabang Timur', false);
        $response->assertSee(route('admin.monitoring', ['map' => $map->id]), false);
    }

    public function test_switching_to_custom_map_tab_renders_top_view_without_leaving_monitoring(): void
    {
        $this->authenticateUser();

        $map = TopologyMap::create([
            'name'        => 'POP Surabaya Selatan',
            'description' => 'Peta wilayah selatan',
        ]);

        $response = $this->get('/admin/monitoring?map=' . $map->id);

        $response->assertStatus(200);
        $response->assertSee('Map: POP Surabaya Selatan', false);
        // Memastikan endpoint data mengarah ke map tersebut
        $response->assertSee(route('admin.monitoring.data', ['map' => $map->id]), false);
    }

    public function test_accessing_legacy_show_url_redirects_to_monitoring_tab(): void
    {
        $this->authenticateUser();

        $map = TopologyMap::create([
            'name' => 'Branch Network',
        ]);

        $response = $this->get('/admin/topology-maps/' . $map->id);

        $response->assertRedirect(route('admin.monitoring', ['map' => $map->id]));
    }

    public function test_creating_new_map_redirects_directly_to_monitoring_tab(): void
    {
        $this->authenticateUser();

        $response = $this->post('/admin/topology-maps', [
            'name'        => 'Peta Baru Jakarta',
            'description' => 'Koneksi data center',
        ]);

        $newMap = TopologyMap::where('name', 'Peta Baru Jakarta')->first();
        $this->assertNotNull($newMap);

        $response->assertRedirect(route('admin.monitoring', ['map' => $newMap->id]));
    }

    public function test_root_url_redirects_to_login(): void
    {
        $response = $this->get('/');
        $response->assertRedirect(route('login'));
    }

    public function test_device_is_unique_to_its_map_and_not_shown_in_other_maps(): void
    {
        $this->authenticateUser();

        $map1 = TopologyMap::create(['name' => 'Gedung A']);
        $map2 = TopologyMap::create(['name' => 'Gedung B']);

        $deviceA = Device::create([
            'name'       => 'Router-Alpha',
            'type'       => 'Router',
            'ip_address' => '10.10.1.1',
            'status'     => 'up',
        ]);

        $deviceB = Device::create([
            'name'       => 'Switch-Beta',
            'type'       => 'Switch',
            'ip_address' => '10.10.1.2',
            'status'     => 'up',
        ]);

        // Masukkan Device A ke Map 1
        $mapDeviceA = \App\Models\TopologyMapDevice::create([
            'topology_map_id' => $map1->id,
            'device_id'       => $deviceA->id,
            'pos_x'           => 120,
            'pos_y'           => 150,
        ]);

        // Cek device list untuk Map 2: Device A TIDAK BOLEH muncul
        $respMap2 = $this->getJson('/admin/monitoring/devices?map_id=' . $map2->id);
        $respMap2->assertStatus(200);
        $devicesInMap2 = collect($respMap2->json('devices'));

        $this->assertNull(
            $devicesInMap2->firstWhere('id', $deviceA->id),
            'Device A yang sudah masuk Map 1 tidak boleh muncul di Map 2'
        );

        $deviceBInMap2 = $devicesInMap2->firstWhere('id', $deviceB->id);
        $this->assertNotNull($deviceBInMap2, 'Device B yang belum dipakai harus muncul di Map 2 sebagai unplaced');
        $this->assertFalse($deviceBInMap2['placed']);

        // Cek device list untuk Main Map: Device A TIDAK BOLEH muncul
        $respMain = $this->getJson('/admin/monitoring/devices');
        $respMain->assertStatus(200);
        $devicesInMain = collect($respMain->json('devices'));

        $this->assertNull(
            $devicesInMain->firstWhere('id', $deviceA->id),
            'Device A yang sudah masuk Map 1 tidak boleh muncul di Main Map'
        );
    }

    public function test_removing_device_from_map_makes_it_available_for_other_maps(): void
    {
        $this->authenticateUser();

        $map1 = TopologyMap::create(['name' => 'Area 1']);
        $map2 = TopologyMap::create(['name' => 'Area 2']);

        $device = Device::create([
            'name'       => 'Router-Pindah',
            'type'       => 'Router',
            'ip_address' => '10.20.1.1',
            'status'     => 'up',
        ]);

        // Tempatkan di Map 1
        $mapDevice = \App\Models\TopologyMapDevice::create([
            'topology_map_id' => $map1->id,
            'device_id'       => $device->id,
            'pos_x'           => 200,
            'pos_y'           => 200,
        ]);

        // Hapus (keluarkan) dari Map 1
        $deleteResp = $this->deleteJson('/admin/monitoring/position/' . $mapDevice->id . '?map_id=' . $map1->id);
        $deleteResp->assertStatus(200);
        $deleteResp->assertJson(['success' => true]);

        $this->assertDatabaseMissing('topology_map_devices', [
            'id' => $mapDevice->id,
        ]);

        // Sekarang cek Map 2: Device harus kembali tersedia sebagai unplaced
        $respMap2 = $this->getJson('/admin/monitoring/devices?map_id=' . $map2->id);
        $devicesInMap2 = collect($respMap2->json('devices'));
        $foundInMap2 = $devicesInMap2->firstWhere('id', $device->id);

        $this->assertNotNull($foundInMap2, 'Device yang telah dihapus dari Map 1 harus tersedia kembali di Map 2');
        $this->assertFalse($foundInMap2['placed']);

        // Sekarang tempatkan Device di Map 2
        $placeResp = $this->postJson('/admin/monitoring/device/' . $device->id . '/position?map_id=' . $map2->id, [
            'pos_x' => 350,
            'pos_y' => 250,
        ]);
        $placeResp->assertStatus(200);
        $placeResp->assertJson(['success' => true]);

        // Verifikasi bahwa Device sekarang berstatus placed di Map 2
        $respMap2Updated = $this->getJson('/admin/monitoring/devices?map_id=' . $map2->id);
        $devicesInMap2Updated = collect($respMap2Updated->json('devices'));
        $foundInMap2Updated = $devicesInMap2Updated->firstWhere('id', $device->id);

        $this->assertNotNull($foundInMap2Updated);
        $this->assertTrue($foundInMap2Updated['placed']);

        // Dan di Map 1, Device tidak lagi muncul
        $respMap1Updated = $this->getJson('/admin/monitoring/devices?map_id=' . $map1->id);
        $devicesInMap1Updated = collect($respMap1Updated->json('devices'));
        $this->assertNull($devicesInMap1Updated->firstWhere('id', $device->id));
    }

    public function test_admin_role_can_manage_devices_and_connections_on_monitoring(): void
    {
        $admin = User::create([
            'name'     => 'Regular Admin',
            'email'    => 'regadmin@example.test',
            'role'     => 'admin',
            'password' => bcrypt('password'),
        ]);

        $this->actingAs($admin);

        // 1. Create Device
        $createResp = $this->postJson('/admin/monitoring/device', [
            'name'       => 'Router-Test-Admin',
            'type'       => 'Router',
            'protocol'   => 'ICMP',
            'ip_address' => '172.16.0.1',
        ]);
        $createResp->assertStatus(201);
        $createResp->assertJson(['success' => true]);
        $devId = $createResp->json('device.id');

        // 2. Update Position (drag and drop)
        $posResp = $this->postJson('/admin/monitoring/device/' . $devId . '/position', [
            'pos_x' => 180,
            'pos_y' => 220,
        ]);
        $posResp->assertStatus(200);
        $posResp->assertJson(['success' => true]);

        // 3. Edit Device
        $updateResp = $this->putJson('/admin/monitoring/device/' . $devId, [
            'name'       => 'Router-Test-Admin-Updated',
            'type'       => 'Router',
            'protocol'   => 'ICMP',
            'ip_address' => '172.16.0.2',
        ]);
        $updateResp->assertStatus(200);
        $updateResp->assertJson(['success' => true]);

        // 4. Create another device and connect them
        $dev2 = Device::create([
            'name'       => 'Device-B',
            'type'       => 'Switch',
            'ip_address' => '172.16.0.3',
        ]);
        $connResp = $this->postJson('/admin/monitoring/connection', [
            'from_device_id' => $devId,
            'to_device_id'   => $dev2->id,
            'direction'      => 'forward',
        ]);
        $connResp->assertStatus(200);
        $connResp->assertJson(['success' => true]);
        $connId = $connResp->json('connection.id');

        // 5. Delete Connection
        $delConnResp = $this->deleteJson('/admin/monitoring/connection/' . $connId);
        $delConnResp->assertStatus(200);
        $delConnResp->assertJson(['success' => true]);
    }

    public function test_custom_map_edges_contain_scalar_from_and_to_ids(): void
    {
        $this->authenticateUser();

        $map = TopologyMap::create(['name' => 'Campus Map']);

        $dev1 = Device::create([
            'name'       => 'Core-1',
            'type'       => 'Router',
            'ip_address' => '192.168.10.1',
        ]);
        $dev2 = Device::create([
            'name'       => 'Dist-1',
            'type'       => 'Switch',
            'ip_address' => '192.168.10.2',
        ]);

        $md1 = \App\Models\TopologyMapDevice::create([
            'topology_map_id' => $map->id,
            'device_id'       => $dev1->id,
            'pos_x'           => 100,
            'pos_y'           => 100,
        ]);
        $md2 = \App\Models\TopologyMapDevice::create([
            'topology_map_id' => $map->id,
            'device_id'       => $dev2->id,
            'pos_x'           => 300,
            'pos_y'           => 100,
        ]);

        $conn = \App\Models\TopologyMapConnection::create([
            'topology_map_id'    => $map->id,
            'from_map_device_id' => $md1->id,
            'to_map_device_id'   => $md2->id,
            'connection_type'    => 'fiber',
            'direction'          => 'forward',
        ]);

        $dataResp = $this->getJson('/admin/monitoring/data?map=' . $map->id);
        $dataResp->assertStatus(200);

        $edges = $dataResp->json('edges');
        $this->assertCount(1, $edges);

        // Pastikan 'from' dan 'to' adalah scalar integer, bukan array/object
        $this->assertIsInt($edges[0]['from']);
        $this->assertIsInt($edges[0]['to']);
        $this->assertEquals($md1->id, $edges[0]['from']);
        $this->assertEquals($md2->id, $edges[0]['to']);
    }
}
