<?php

namespace Tests\Feature;

use App\Models\ActivityLog;
use App\Models\Device;
use App\Models\DeviceNotification;
use App\Models\MonitoringLog;
use App\Models\User;
use App\Services\Laporan\LaporanPerangkatService;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Tests\Feature\Concerns\InteractsWithDeviceRegistry;
use Tests\TestCase;

/**
 * Laporan & Grafik: identitas halaman, kejujuran data (tanpa dummy), dan dua
 * jenis export PDF (keseluruhan + per perangkat).
 */
class LaporanPerangkatExportTest extends TestCase
{
    use InteractsWithDeviceRegistry;

    protected function setUp(): void
    {
        parent::setUp();

        $this->createSchema();
        $this->treatDeviceTableAsOfficialRegistry();
    }

    protected function createSchema(): void
    {
        foreach ([
            'devices', 'monitoring_logs', 'log_archives', 'activity_logs',
            'device_notifications', 'users', 'device_notes',
        ] as $table) {
            Schema::dropIfExists($table);
        }

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->enum('role', ['super_admin', 'staff'])->default('staff');
            $table->rememberToken();
            $table->timestamps();
        });

        // Kolom `devices` disamakan dengan migration asli agar laporan
        // per perangkat bisa membaca identitas lengkap.
        Schema::create('devices', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('ip_address')->nullable();
            $table->string('type')->nullable();
            $table->string('protocol')->nullable();
            $table->text('deskripsi')->nullable();
            $table->text('penyebab')->nullable();
            $table->string('gambar')->nullable();
            $table->string('status')->default('unknown');
            $table->string('icon_type')->nullable();
            $table->string('pop_name')->nullable();
            $table->integer('pos_x')->nullable();
            $table->integer('pos_y')->nullable();
            $table->boolean('map_positioned')->default(false);
            $table->unsignedInteger('polls')->nullable();
            $table->float('responded_percentage')->default(0);
            $table->float('missed_percentage')->default(0);
            $table->unsignedInteger('down_time')->default(0);
            $table->string('period')->nullable();
            $table->unsignedInteger('alerts_count')->default(0);
            $table->float('avg_delay')->default(0);
            $table->float('min_delay')->default(0);
            $table->float('max_delay')->default(0);
            $table->timestamps();
        });

        Schema::create('monitoring_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('device_id')->nullable();
            $table->string('device_name')->nullable();
            $table->string('ip_address')->nullable();
            $table->string('status')->default('unknown');
            $table->integer('response_time')->nullable();
            $table->timestamp('checked_at')->nullable();
            $table->timestamps();
        });

        Schema::create('log_archives', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('device_id')->nullable();
            $table->string('device_name')->nullable();
            $table->string('ip_address')->nullable();
            $table->string('status')->default('unknown');
            $table->integer('response_time')->nullable();
            $table->timestamp('checked_at')->nullable();
            $table->timestamps();
        });

        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('device_id')->nullable();
            $table->string('device_name')->nullable();
            $table->string('ip_address')->nullable();
            $table->string('status');
            $table->integer('latency')->nullable();
            $table->timestamp('log_time')->nullable();
            $table->timestamps();
        });

        Schema::create('device_notifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('device_id')->nullable();
            $table->string('device_name')->nullable();
            $table->string('ip_address')->nullable();
            $table->string('event_type');
            $table->string('event');
            $table->string('status');
            $table->text('message')->nullable();
            $table->integer('latency')->nullable();
            $table->boolean('is_read')->default(false);
            $table->timestamp('checked_at')->nullable();
            $table->timestamps();
        });

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

    protected function makeDevice(array $overrides = []): Device
    {
        return Device::create(array_merge([
            'name'       => 'POP Gresik',
            'ip_address' => '10.10.0.1',
            'type'       => 'Router',
            'protocol'   => 'ICMP',
            'status'     => 'up',
            'pop_name'   => 'POP Gresik',
            'polls'      => 60,
        ], $overrides));
    }

    /**
     * Awal jam yang sudah lewat — log monitoring tidak boleh bertanggal masa
     * depan karena periode laporan dibatasi sampai "sekarang".
     */
    protected function pastHour(): \Illuminate\Support\Carbon
    {
        return now()->subHours(2)->startOfHour();
    }

    protected function pdfBody($response): string
    {
        $base = $response->baseResponse;

        return method_exists($base, 'streamedContent')
            ? ($base->streamedContent() ?: $base->getContent())
            : $base->getContent();
    }

    protected function log(Device $device, string $status, ?int $response, $at): void
    {
        MonitoringLog::create([
            'device_id'     => $device->id,
            'device_name'   => $device->name,
            'ip_address'    => $device->ip_address,
            'status'        => $status,
            'response_time' => $response,
            'checked_at'    => $at,
        ]);
    }

    protected function actingAsAdmin()
    {
        return $this->actingAs(User::create([
            'name'     => 'Super Admin',
            'email'    => 'super.admin@example.test',
            'password' => bcrypt('secret'),
            'role'     => 'super_admin',
        ]));
    }

    /*
    |--------------------------------------------------------------------------
    | Identitas halaman
    |--------------------------------------------------------------------------
    */

    public function test_halaman_menggunakan_identitas_laporan_dan_grafik(): void
    {
        $device = $this->makeDevice();
        $this->log($device, 'up', 12, $this->pastHour());

        $html = $this->actingAsAdmin()->get(route('admin.laporan'))->getContent();

        $this->assertStringContainsString('Laporan &amp; Grafik', $html);
        $this->assertStringNotContainsString('Laporan Monitoring', $html);
    }

    public function test_halaman_tidak_lagi_memakai_angka_chart_dummy(): void
    {
        $device = $this->makeDevice();
        $this->log($device, 'up', 12, $this->pastHour());

        $html = $this->actingAsAdmin()->get(route('admin.laporan'))->getContent();

        $this->assertStringNotContainsString('chartData.dummy', $html);
        $this->assertStringNotContainsString('dummyAvail', $html);
        $this->assertStringNotContainsString('dummyResp', $html);

        // Angka dummy lama tidak boleh muncul sebagai data grafik.
        $this->assertStringNotContainsString('99.87', $html);
        $this->assertStringNotContainsString('99.92', $html);
    }

    public function test_layout_tidak_punya_gap_ekstra_di_bawah_header(): void
    {
        $device = $this->makeDevice();
        $this->log($device, 'up', 12, $this->pastHour());

        $html = $this->actingAsAdmin()->get(route('admin.laporan'))->getContent();

        // Elemen fixed (overlay loading / modal) tidak boleh berada di depan
        // page-head: itu menambah margin-top 24px dan membuat header turun.
        $this->assertMatchesRegularExpression(
            '/<div class="nk-content-body admin-page">\s*<div class="admin-page-head"/',
            $html,
            'Page-head harus menjadi anak pertama .admin-page agar spacing konsisten dengan halaman lain.'
        );
    }

    public function test_halaman_menampilkan_empty_state_chart_tanpa_data(): void
    {
        $device = $this->makeDevice();
        // Ada data (UP) tapi tanpa response time sama sekali.
        $this->log($device, 'up', null, $this->pastHour());

        $html = $this->actingAsAdmin()->get(route('admin.laporan'))->getContent();

        $this->assertStringContainsString('id="responseEmpty"', $html);
        $this->assertStringContainsString('Belum ada data response time', $html);
    }

    /*
    |--------------------------------------------------------------------------
    | Export per perangkat
    |--------------------------------------------------------------------------
    */

    public function test_halaman_menawarkan_pemilih_perangkat_dan_tombol_export(): void
    {
        $this->makeDevice(['name' => 'POP Gresik', 'ip_address' => '10.10.0.1']);

        $html = $this->actingAsAdmin()->get(route('admin.laporan'))->getContent();

        $this->assertStringContainsString('id="perangkatSelect"', $html);
        $this->assertStringContainsString('id="btnExportPerangkat"', $html);
        $this->assertStringContainsString('POP Gresik (10.10.0.1)', $html);
        $this->assertStringContainsString(route('admin.laporan.perangkat.pdf', ['device' => '__DEVICE__']), $html);
    }

    public function test_export_pdf_perangkat_menghasilkan_pdf(): void
    {
        $device = $this->makeDevice();

        $this->log($device, 'up', 10, $this->pastHour());
        $this->log($device, 'up', 20, $this->pastHour()->addMinutes(5));
        $this->log($device, 'down', null, $this->pastHour()->addMinutes(10));

        $response = $this->actingAsAdmin()
            ->get(route('admin.laporan.perangkat.pdf', ['device' => $device->id]));

        $response->assertOk();
        $response->assertHeader('content-type', 'application/pdf');

        $body = $this->pdfBody($response);
        $this->assertStringStartsWith('%PDF', $body);
    }

    public function test_metrik_laporan_perangkat_dihitung_dari_data_nyata(): void
    {
        $device = $this->makeDevice();

        $base = $this->pastHour();
        $this->log($device, 'up', 10, $base);
        $this->log($device, 'up', 20, $base->copy()->addMinutes(5));
        $this->log($device, 'up', 30, $base->copy()->addMinutes(10));
        $this->log($device, 'down', null, $base->copy()->addMinutes(15));
        $this->log($device, 'up', 40, $base->copy()->addMinutes(20));

        // Satu episode UNREACHABLE dari activity_logs.
        ActivityLog::create([
            'device_id'   => $device->id,
            'device_name' => $device->name,
            'ip_address'  => $device->ip_address,
            'status'      => 'warning',
            'log_time'    => $base->copy()->addMinutes(25),
        ]);
        ActivityLog::create([
            'device_id'   => $device->id,
            'device_name' => $device->name,
            'ip_address'  => $device->ip_address,
            'status'      => 'up',
            'log_time'    => $base->copy()->addMinutes(30),
        ]);

        DeviceNotification::create([
            'device_id'   => $device->id,
            'device_name' => $device->name,
            'ip_address'  => $device->ip_address,
            'event_type'  => 'down',
            'event'       => 'DOWN',
            'status'      => 'down',
            'checked_at'  => $base->copy()->addMinutes(15),
        ]);

        $data = app(LaporanPerangkatService::class)->report($device, [
            'start'       => $base->copy()->subHour(),
            'end'         => now(),
            'granularity' => 'hour',
            'type'        => 'daily',
            'label'       => 'test',
        ]);

        $this->assertSame('POP Gresik', $data['device']['name']);
        $this->assertSame('10.10.0.1', $data['device']['ip']);
        $this->assertSame('Router', $data['device']['type']);
        $this->assertSame('ICMP Ping', $data['device']['protocol']);
        $this->assertSame('POP Gresik', $data['device']['location']);

        $m = $data['metrics'];
        $this->assertSame(5, $m['checks']);
        $this->assertSame(4, $m['up']);
        $this->assertSame(1, $m['down']);
        $this->assertSame(80.0, $m['availability']);
        $this->assertSame(25, $m['avg_response']);
        $this->assertSame(10, $m['min_response']);
        $this->assertSame(40, $m['max_response']);
        $this->assertSame(1, $m['incident_down']);
        $this->assertSame(1, $m['incident_unreach']);
        $this->assertSame(2, $m['incidents']);
        $this->assertSame(1, $m['alerts']);
        $this->assertSame(1, $m['recovered']);
        $this->assertTrue($data['hasData']);
    }

    public function test_laporan_perangkat_tidak_mencampur_device_lain(): void
    {
        $a = $this->makeDevice(['name' => 'POP A', 'ip_address' => '10.10.0.1']);
        $b = $this->makeDevice(['name' => 'POP B', 'ip_address' => '10.10.0.2']);

        $base = $this->pastHour();
        $this->log($a, 'up', 10, $base);
        $this->log($a, 'down', null, $base->copy()->addMinute());
        $this->log($b, 'up', 99, $base);
        $this->log($b, 'up', 99, $base->copy()->addMinute());

        $data = app(LaporanPerangkatService::class)->report($a, [
            'start'       => $base->copy()->subHour(),
            'end'         => now(),
            'granularity' => 'hour',
            'type'        => 'daily',
            'label'       => 'test',
        ]);

        $this->assertSame(2, $data['metrics']['checks']);
        $this->assertSame(50.0, $data['metrics']['availability']);
        $this->assertSame(10, $data['metrics']['avg_response']);
    }

    public function test_metrik_kosong_tidak_diperas_jadi_nol(): void
    {
        $device = $this->makeDevice();

        $data = app(LaporanPerangkatService::class)->report($device, [
            'start'       => now()->subDay(),
            'end'         => now(),
            'granularity' => 'day',
            'type'        => 'daily',
            'label'       => 'test',
        ]);

        $m = $data['metrics'];

        $this->assertSame(0, $m['checks']);
        $this->assertNull($m['availability']);
        $this->assertNull($m['avg_response']);
        $this->assertNull($m['min_response']);
        $this->assertNull($m['max_response']);
        $this->assertFalse($data['hasData']);
        $this->assertSame([], $data['history']);
    }

    public function test_export_pdf_perangkat_404_untuk_id_tidak_dikenal(): void
    {
        $this->makeDevice();

        $this->actingAsAdmin()
            ->get(route('admin.laporan.perangkat.pdf', ['device' => 999999]))
            ->assertNotFound();
    }

    /*
    |--------------------------------------------------------------------------
    | Export keseluruhan (route lama harus tetap jalan)
    |--------------------------------------------------------------------------
    */

    public function test_route_laporan_pdf_lama_tetap_berfungsi(): void
    {
        $device = $this->makeDevice();
        $this->log($device, 'up', 12, $this->pastHour());

        $response = $this->actingAsAdmin()->get(route('admin.laporan.pdf'));

        $response->assertOk();
        $response->assertHeader('content-type', 'application/pdf');

        $body = $this->pdfBody($response);
        $this->assertStringStartsWith('%PDF', $body);
    }

    public function test_daftar_perangkat_mengikuti_device_resmi(): void
    {
        $this->makeDevice(['name' => 'POP Resmi', 'ip_address' => '10.10.0.1']);
        $this->makeDevice(['name' => 'POP Ekstra', 'ip_address' => '10.10.9.9']);

        $options = app(LaporanPerangkatService::class)->options();

        $names = array_column($options, 'name');

        $this->assertContains('POP Resmi', $names);
        $this->assertContains('POP Ekstra', $names, 'DeviceTableAsRegistry menjadikan seluruh tabel device sebagai registry.');
    }
}
