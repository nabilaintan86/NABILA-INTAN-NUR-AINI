<?php

namespace Tests\Feature;

use App\Models\Device;
use App\Models\SpeedTestLog;
use App\Models\User;
use App\Services\SpeedTestService;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Process\PendingProcess;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Tests\Feature\Concerns\InteractsWithDeviceRegistry;
use Tests\TestCase;

/**
 *_speed test_
 *
 * Menjugate dua hal:
 *
 *  1. Halaman Speed Test dan endpoint backend-nya HANYA boleh dipakai akun
 *     Super Admin. Staff harus dapat 403, tamu dialihkan ke login, dan tidak
 *     boleh ada satu baris pun yang masuk ke speed_test_logs.
 *  2. Pemrosesan speed test membaca JSON speedtest-cli dengan benar (bandwidth
 *     byte/detik harus jadi Mbps) dan tetap jalan saat CLI tidak ada.
 */
class SpeedTestAccessTest extends TestCase
{
    use InteractsWithDeviceRegistry;

    protected function setUp(): void
    {
        parent::setUp();
        $this->treatDeviceTableAsOfficialRegistry();

        $this->createSchema('users', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('telegram_chat_id')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->enum('role', ['super_admin', 'staff'])->default('staff');
            $table->string('avatar')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        $this->createSchema('speed_test_logs', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->nullable();
            $table->foreignId('device_id')->nullable();
            $table->string('device_name', 191)->nullable();
            $table->string('device_ip_address', 45)->nullable();
            $table->string('target_type', 16)->default('local');
            $table->string('target_host', 253)->nullable();
            $table->decimal('download_mbps', 10, 2)->nullable();
            $table->decimal('upload_mbps', 10, 2)->nullable();
            $table->decimal('ping_ms', 8, 2)->nullable();
            $table->string('source', 16)->default('curl');
            $table->string('server', 191)->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->unsignedInteger('duration_ms')->nullable();
            $table->string('status', 16)->default('ok');
            $table->string('message', 255)->nullable();
            $table->timestamp('tested_at')->nullable();
            $table->timestamps();
        });

        // Tabel device jadi sumber daftar target di panel kiri Speed Test.
        $this->createSchema('device', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('type')->nullable();
            $table->string('protocol')->nullable();
            $table->string('ip_address')->nullable();
            $table->string('pop_name')->nullable();
            $table->string('status')->nullable();
            $table->timestamps();
        });

        // Tabel yang ikut dipakai layout/header/sidebar saat render.
        $this->createSchema('device_notifications', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('device_id')->nullable();
            $table->string('device_name');
            $table->string('ip_address')->nullable();
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

    /* ══ 1 · Halaman hanya untuk Super Admin ═══════════════ */

    public function test_super_admin_can_open_speed_test_page(): void
    {
        $this->actingAs($this->superAdmin())
            ->get(route('admin.speed-test'))
            ->assertOk()
            ->assertSee('Speed Test')
            ->assertSee('Mulai Speed Test')
            ->assertSee('Ping')
            ->assertSee('Download')
            ->assertSee('Upload');
    }

    /* ══ 1b · Gauge melingkar & tombolnya ═════════════════ */

    public function test_page_renders_the_circular_gauge(): void
    {
        $html = $this->actingAs($this->superAdmin())
            ->get(route('admin.speed-test'))
            ->assertOk()
            ->getContent();

        // Busur utama, cincin progres, dan ujung busur (jarum) wajib ada:
        // tanpa ketiganya tidak ada lingkaran yang bisa bergerak.
        $this->assertStringContainsString('id="stgArc"', $html);
        $this->assertStringContainsString('id="stgProgress"', $html);
        $this->assertStringContainsString('id="stgTip"', $html);

        // Angka real-time dan statusTahapan di tengah lingkaran.
        $this->assertStringContainsString('id="stgValue"', $html);
        $this->assertStringContainsString('id="stgStatus"', $html);

        // Lingkaran harus benar-benar digambar dengan SVG, bukan kotak CSS.
        $this->assertMatchesRegularExpression('/<svg[^>]+viewBox="0 0 320 320"/', $html);
    }

    public function test_run_button_disables_itself_while_testing(): void
    {
        $html = $this->actingAs($this->superAdmin())
            ->get(route('admin.speed-test'))
            ->assertOk()
            ->getContent();

        // Tombol harus dinonaktifkan selama pengujian berjalan supaya tidak
        // bisa ditekan dua kali, dan teksnya berubah jadi "Sedang Menguji...".
        $this->assertStringContainsString('runBtn.disabled = true', $html);
        $this->assertStringContainsString('Sedang Menguji...', $html);
        $this->assertStringContainsString('Jalankan Lagi', $html);
    }

    public function test_page_measures_ping_and_download_against_cloudflare(): void
    {
        $html = $this->actingAs($this->superAdmin())
            ->get(route('admin.speed-test'))
            ->assertOk()
            ->getContent();

        // Ping dan unduh diukur sungguhan lewat endpoint Cloudflare yang
        // mengizinkan CORS. Kalau keduanya hilang, angka hanya simulasi.
        $this->assertStringContainsString('cloudflare.com/cdn-cgi/trace', $html);
        $this->assertStringContainsString('speed.cloudflare.com/__down', $html);
    }

    public function test_page_posts_upload_to_the_first_party_sink(): void
    {
        $html = $this->actingAs($this->superAdmin())
            ->get(route('admin.speed-test'))
            ->assertOk()
            ->getContent();

        // Browser tidak boleh mengunggah ke CDN luar (CORS), jadi laju unggah
        // harus diukur ke endpoint milik sendiri yang muncul di HTML.
        $this->assertStringContainsString(route('admin.speed-test.upload-sink'), $html);
        $this->assertStringContainsString("method: 'POST'", $html);
    }

    public function test_page_reports_failure_instead_of_inventing_numbers(): void
    {
        $html = $this->actingAs($this->superAdmin())
            ->get(route('admin.speed-test'))
            ->assertOk()
            ->getContent();

        // Tidak ada mode simulasi. Kalau CDN atau endpoint sendiri tidak dapat
        // dijangkau, halaman harus bilang gagal dan membiarkan kotaknya kosong
        // — bukan mengisi angka tebakan yang menyamar sebagai hasil ukur.
        $this->assertStringNotContainsString('simulatedSpeed', $html);
        $this->assertStringNotContainsString('simulatedPing', $html);
        $this->assertStringNotContainsString('runSimulated', $html);
        $this->assertStringNotContainsString('mode simulasi', $html);
        $this->assertStringNotContainsString('Perkiraan', $html);

        // Yang ditampilkan sebagai gantinya: alasan gagalnya.
        $this->assertStringContainsString('Gagal diukur', $html);
        $this->assertStringContainsString('bukan angka yang ditebak', $html);
    }

    public function test_staff_is_forbidden_from_speed_test_page(): void
    {
        $this->actingAs($this->staff())
            ->get(route('admin.speed-test'))
            ->assertForbidden();
    }

    public function test_guest_is_redirected_to_login_from_speed_test_page(): void
    {
        $this->get(route('admin.speed-test'))
            ->assertRedirect(route('login'));
    }

    /* ══ 1c · Tong unggah milik sendiri ══════════════════ */

    public function test_upload_sink_counts_the_bytes_it_received(): void
    {
        $payload = str_repeat('x', 8192);

        $this->actingAs($this->superAdmin())
            ->call(
                'POST',
                route('admin.speed-test.upload-sink'),
                [],
                [],
                [],
                ['CONTENT_TYPE' => 'application/octet-stream'],
                $payload
            )
            ->assertOk()
            ->assertJsonPath('success', true)
            // Browser membandingkan angka ini dengan ukuran payload yang ia
            // kirim, jadi penghitungannya harus tepat.
            ->assertJsonPath('received', 8192);
    }

    public function test_upload_sink_accepts_an_empty_body(): void
    {
        // Unggahan yang gagal di tengah jalan boleh berarti nol byte; endpoint
        // tetap harus menjawab normal, bukan error.
        $this->actingAs($this->superAdmin())
            ->call('POST', route('admin.speed-test.upload-sink'))
            ->assertOk()
            ->assertJsonPath('received', 0);
    }

    public function test_staff_cannot_reach_the_upload_sink(): void
    {
        $this->actingAs($this->staff())
            ->postJson(route('admin.speed-test.upload-sink'))
            ->assertForbidden();
    }

    public function test_guest_cannot_reach_the_upload_sink(): void
    {
        $this->postJson(route('admin.speed-test.upload-sink'))
            ->assertUnauthorized();
    }

    public function test_upload_sink_does_not_persist_the_payload(): void
    {
        $before = SpeedTestLog::count();

        $this->actingAs($this->superAdmin())
            ->call(
                'POST',
                route('admin.speed-test.upload-sink'),
                [],
                [],
                [],
                ['CONTENT_TYPE' => 'application/octet-stream'],
                str_repeat('y', 4096)
            )
            ->assertOk();

        // Isi unggahan adalah data acak milik user: tidak boleh tersimpan
        // menjadi riwayat pengujian pun.
        $this->assertSame($before, SpeedTestLog::count());
    }

    /* ══ 2 · Backend JSON tidak bisa ditembak langsung ════ */

    public function test_staff_cannot_execute_speed_test_from_backend(): void
    {
        $this->actingAs($this->staff())
            ->postJson(route('admin.speed-test.run'))
            ->assertForbidden();

        // Staff tidak boleh menyisakan jejak apa pun, termasuk log.
        $this->assertSame(0, SpeedTestLog::count());
    }

    public function test_staff_cannot_read_speed_test_history_endpoint(): void
    {
        $this->actingAs($this->staff())
            ->getJson(route('admin.speed-test.history'))
            ->assertForbidden();
    }

    public function test_guest_cannot_execute_speed_test_from_backend(): void
    {
        $this->postJson(route('admin.speed-test.run'))
            ->assertUnauthorized();

        $this->assertSame(0, SpeedTestLog::count());
    }

    public function test_guest_cannot_read_speed_test_history_endpoint(): void
    {
        $this->getJson(route('admin.speed-test.history'))
            ->assertUnauthorized();
    }

    /* ══ 3 · Menu navigasi ════════════════════════════════ */

    public function test_sidebar_shows_speed_test_menu_only_for_super_admin(): void
    {
        $url = route('admin.speed-test');

        // Partial sidebar dirender langsung supaya test ini tidak ikut
        // bergantung pada tabel milik halaman lain.
        $this->actingAs($this->superAdmin());
        $this->assertStringContainsString($url, view('layouts.partials.sidebar')->render());

        $this->actingAs($this->staff());
        $this->assertStringNotContainsString($url, view('layouts.partials.sidebar')->render());
    }

    /* ══ 4 · Super Admin boleh eksekusi & log tersimpan ═══ */

    public function test_super_admin_can_run_speed_test_and_log_is_stored(): void
    {
        $this->fakeCli(['ping' => ['latency' => 12.5], 'download' => ['bandwidth' => 98765432]]);

        $user = $this->superAdmin();

        $response = $this->actingAs($user)->postJson(route('admin.speed-test.run'));

        $response->assertOk()
            ->assertJsonPath('status', 'ok')
            // 98.765.432 byte/detik -> 790,12 Mbps
            ->assertJsonPath('download_mbps', 790.12)
            ->assertJsonPath('ping_ms', 12.5)
            ->assertJsonPath('source', 'cli');

        $this->assertSame(1, SpeedTestLog::count());

        $log = SpeedTestLog::first();
        $this->assertSame($user->id, $log->user_id, 'log harus mencatat siapa yang menguji');
        $this->assertSame('ok', $log->status);
        $this->assertSame(790.12, $log->download_mbps);
        $this->assertNotNull($log->tested_at);
    }

    public function test_super_admin_can_read_history_endpoint(): void
    {
        $this->actingAs($this->superAdmin());

        SpeedTestLog::create([
            'user_id' => auth()->id(),
            'download_mbps' => 90.5,
            'upload_mbps' => 40.25,
            'ping_ms' => 15.0,
            'source' => 'cli',
            'status' => 'ok',
            'tested_at' => now(),
        ]);

        $this->getJson(route('admin.speed-test.history'))
            ->assertOk()
            ->assertJsonCount(1, 'logs')
            ->assertJsonPath('logs.0.download_mbps', 90.5);
    }

    /* ══ 5 · Throttle protects backend from abuse ════════ */

    public function test_run_endpoint_is_rate_limited(): void
    {
        $user = $this->superAdmin();
        $this->fakeCli(['ping' => ['latency' => 10.0]]);

        // Batas route: throttle:6,1 (6 permintaan per menit).
        for ($i = 1; $i <= 6; $i++) {
            $this->actingAs($user)->postJson(route('admin.speed-test.run'))->assertOk();
        }

        $this->actingAs($user)->postJson(route('admin.speed-test.run'))->assertStatus(429);

        $this->assertSame(6, SpeedTestLog::count(), 'permintaan ke-7 tidak boleh dicatat');
    }

    public function test_remote_test_is_refused_when_ssh_is_not_configured(): void
    {
        // Config default di test: SPEEDTEST_SSH_ENABLED tidak diaktifkan.
        config(['ssh.enabled' => false]);

        $device = Device::create(['name' => 'POP Gresik', 'ip_address' => '202.154.4.254']);

        $this->fakeCli(['ping' => ['latency' => 9.0]]);

        $this->actingAs($this->superAdmin())
            ->postJson(route('admin.speed-test.run'), ['device_id' => $device->id])
            ->assertStatus(422)
            ->assertJsonPath('status', 'gagal')
            ->assertJsonPath('source', 'tidak-ada');

        // Walaupun gagal, riwayat tetap menandai perangkat mana yang dicoba.
        $log = SpeedTestLog::first();
        $this->assertSame($device->id, $log->device_id);
        $this->assertSame('POP Gresik', $log->device_name);
        $this->assertSame('202.154.4.254', $log->device_ip_address);
        $this->assertSame('gagal', $log->status);
    }

    public function test_run_on_device_logs_the_external_device_name_and_ip(): void
    {
        $this->enableSshWithFakeKey();

        $device = Device::create(['name' => 'POP Gresik', 'ip_address' => '202.154.4.254']);

        $this->fakeRemoteCli(['ping' => ['latency' => 14.0], 'download' => ['bandwidth' => 24691358]]);

        $this->actingAs($this->superAdmin())
            ->postJson(route('admin.speed-test.run'), ['device_id' => $device->id])
            ->assertOk()
            ->assertJsonPath('source', 'cli')
            // 24.691.358 byte/detik -> 197,53 Mbps
            ->assertJsonPath('download_mbps', 197.53)
            ->assertJsonPath('target_label', 'POP Gresik (202.154.4.254)');

        $log = SpeedTestLog::first();
        $this->assertSame($device->id, $log->device_id);
        $this->assertSame('POP Gresik', $log->device_name);
        $this->assertSame('202.154.4.254', $log->device_ip_address);
        $this->assertSame(197.53, $log->download_mbps);
    }

    public function test_run_on_device_builds_a_safe_ssh_command_for_the_selected_device(): void
    {
        $this->enableSshWithFakeKey();

        $device = Device::create(['name' => 'POP Gresik', 'ip_address' => '202.154.4.254']);

        $this->fakeRemoteCli(['ping' => ['latency' => 12.0]]);

        $this->actingAs($this->superAdmin())
            ->postJson(route('admin.speed-test.run'), ['device_id' => $device->id])
            ->assertOk();

        // Setiap perintah harus lewat ssh non-interaktif ke IP perangkat itu,
        // dan tidak boleh pernah jadi string shell yang dirangkai bebas.
        $total = 0;

        Process::assertRan(function (PendingProcess $process) use (&$total): bool {
            $total++;

            $command = implode(' ', (array) $process->command);

            $this->assertStringStartsWith('ssh ', $command);
            $this->assertStringContainsString('BatchMode=yes', $command, 'ssh tidak boleh interaktif');
            $this->assertStringContainsString('StrictHostKeyChecking=accept-new', $command);
            $this->assertStringContainsString('noc@202.154.4.254', $command);

            return true;
        });

        $this->assertGreaterThan(0, $total, 'harus ada perintah ssh yang dijalankan');
    }

    public function test_local_run_still_logs_target_as_the_noc_server(): void
    {
        Device::create(['name' => 'POP Gresik', 'ip_address' => '202.154.4.254']);

        $this->fakeCli(['ping' => ['latency' => 10.0], 'download' => ['bandwidth' => 12500000]]);

        $this->actingAs($this->superAdmin())
            ->postJson(route('admin.speed-test.run'), ['device_id' => 0])
            ->assertOk()
            ->assertJsonPath('target_label', 'Server NOC');

        $log = SpeedTestLog::first();
        $this->assertNull($log->device_id);
        $this->assertNull($log->device_name);
    }

    public function test_unknown_device_id_falls_back_to_local_test(): void
    {
        $this->fakeCli(['ping' => ['latency' => 10.0]]);

        $this->actingAs($this->superAdmin())
            ->postJson(route('admin.speed-test.run'), ['device_id' => 999999])
            ->assertOk()
            ->assertJsonPath('target_label', 'Server NOC');

        $this->assertNull(SpeedTestLog::first()->device_id);
    }

    /* ══ 7 · Target manual (IP / hostname diketik sendiri) ═ */

    public function test_manual_target_runs_over_ssh_and_is_logged(): void
    {
        $this->enableSshWithFakeKey();
        $this->fakeRemoteCli(['ping' => ['latency' => 11.0], 'download' => ['bandwidth' => 24691358]]);

        $this->actingAs($this->superAdmin())
            ->postJson(route('admin.speed-test.run'), [
                'target_type' => 'manual',
                'custom_host' => '202.154.7.7',
            ])
            ->assertOk()
            ->assertJsonPath('source', 'cli')
            ->assertJsonPath('target_label', '202.154.7.7');

        $log = SpeedTestLog::first();

        // Target manual tidak punya device_id; alamatnya disimpan di kolom
        // terpisah supaya nama host yang panjang tetap muat.
        $this->assertNull($log->device_id);
        $this->assertSame('manual', $log->target_type);
        $this->assertSame('202.154.7.7', $log->target_host);
        $this->assertSame('202.154.7.7', $log->targetLabel());
    }

    public function test_manual_target_outside_allowed_cidrs_is_refused(): void
    {
        $this->enableSshWithFakeKey(); // hanya 202.154.0.0/16 yang diizinkan
        $this->fakeRemoteCli(['ping' => ['latency' => 11.0]]);

        $this->actingAs($this->superAdmin())
            ->postJson(route('admin.speed-test.run'), [
                'target_type' => 'manual',
                'custom_host' => '10.0.0.1',
            ])
            ->assertStatus(422)
            ->assertJsonPath('status', 'gagal')
            ->assertJsonPath('source', 'tidak-ada');

        // Tidak boleh ada perintah ssh yang benar-benar dijalankan.
        Process::assertNothingRan();

        // Gagalnya tetap tercatat supaya ada jejak bahwa alamat itu dicoba.
        $this->assertSame('gagal', SpeedTestLog::first()->status);
    }

    public function test_manual_target_is_refused_when_no_cidr_is_configured(): void
    {
        $this->enableSshWithFakeKey();
        config(['ssh.allowed_cidrs' => []]);

        $this->fakeRemoteCli(['ping' => ['latency' => 11.0]]);

        $this->actingAs($this->superAdmin())
            ->postJson(route('admin.speed-test.run'), [
                'target_type' => 'manual',
                'custom_host' => '202.154.7.7',
            ])
            ->assertStatus(422)
            ->assertJsonPath('status', 'gagal');

        Process::assertNothingRan();
    }

    public function test_manual_target_rejects_shell_metacharacters(): void
    {
        $this->enableSshWithFakeKey();
        $this->fakeRemoteCli(['ping' => ['latency' => 11.0]]);

        // Alamat berisi tanda baca shell harus ditolak outright, bukan
        // diteruskan ke ssh dalam bentuk apa pun.
        $this->actingAs($this->superAdmin())
            ->postJson(route('admin.speed-test.run'), [
                'target_type' => 'manual',
                'custom_host' => '10.0.0.1; rm -rf /',
            ])
            ->assertStatus(422)
            ->assertJsonPath('status', 'gagal');

        Process::assertNothingRan();
    }

    public function test_manual_target_accepts_a_bare_ip_outside_cidr_when_listed_exactly(): void
    {
        $this->enableSshWithFakeKey();
        // Satu IP polos tanpa CIDR juga boleh dipakai.
        config(['ssh.allowed_cidrs' => ['203.0.113.9']]);

        $this->fakeRemoteCli(['ping' => ['latency' => 11.0]]);

        $this->actingAs($this->superAdmin())
            ->postJson(route('admin.speed-test.run'), [
                'target_type' => 'manual',
                'custom_host' => '203.0.113.9',
            ])
            ->assertOk()
            ->assertJsonPath('source', 'cli');

        Process::assertRan(function (PendingProcess $process): bool {
            return str_contains(implode(' ', (array) $process->command), '203.0.113.9');
        });
    }

    public function test_staff_cannot_run_a_manual_target(): void
    {
        $this->enableSshWithFakeKey();
        $this->fakeRemoteCli(['ping' => ['latency' => 11.0]]);

        $this->actingAs($this->staff())
            ->postJson(route('admin.speed-test.run'), [
                'target_type' => 'manual',
                'custom_host' => '202.154.7.7',
            ])
            ->assertForbidden();

        Process::assertNothingRan();
        $this->assertSame(0, SpeedTestLog::count());
    }

    public function test_manual_target_without_address_is_rejected_instead_of_testing_local(): void
    {
        $this->fakeCli(['ping' => ['latency' => 10.0]]);

        // Kalau alamatnya kosong, sistem harus menolak — bukan diam-diam
        // menguji server NOC dan melaporkannya seolah-olah target manual.
        $this->actingAs($this->superAdmin())
            ->postJson(route('admin.speed-test.run'), [
                'target_type' => 'manual',
                'custom_host' => '   ',
            ])
            ->assertStatus(422)
            ->assertJsonValidationErrors('custom_host');

        Process::assertNothingRan();
        $this->assertSame(0, SpeedTestLog::count());
    }

    public function test_unknown_target_type_is_rejected(): void
    {
        $this->fakeCli(['ping' => ['latency' => 10.0]]);

        $this->actingAs($this->superAdmin())
            ->postJson(route('admin.speed-test.run'), ['target_type' => 'server-penyusup'])
            ->assertStatus(422)
            ->assertJsonValidationErrors('target_type');

        Process::assertNothingRan();
    }

    public function test_history_reports_the_target_kind_of_each_row(): void
    {
        $this->enableSshWithFakeKey();
        $this->fakeRemoteCli(['ping' => ['latency' => 11.0]]);

        // Satu akun dipakai untuk semua permintaan supaya tidak bentrok di
        // constraint email yang unik.
        $superAdmin = $this->superAdmin();

        $this->actingAs($superAdmin)
            ->postJson(route('admin.speed-test.run'), [
                'target_type' => 'manual',
                'custom_host' => '202.154.7.7',
            ])
            ->assertOk();

        $this->fakeCli(['ping' => ['latency' => 10.0]]);

        $this->actingAs($superAdmin)
            ->postJson(route('admin.speed-test.run'), ['target_type' => 'local'])
            ->assertOk();

        $labels = $this->actingAs($superAdmin)
            ->getJson(route('admin.speed-test.history'))
            ->assertOk()
            ->json('logs.*.target_label');

        // Riwayat newest-first: local di atas, manual di bawahnya.
        $this->assertSame(['Server NOC', '202.154.7.7'], $labels);
    }

    /* ══ 8 · Pengujian bertahap (polling per detik) ═══ */

    public function test_staged_run_reveals_metrics_one_stage_at_a_time(): void
    {
        // CLI sengaja TIDAK tersedia, supaya tiap tahap benar-benar mengukur
        // sendiri lewat curl/ping dan angka muncul bertahap, bukan sekaligus.
        $this->fakeFallbackOnly(['ping' => ['latency' => 9.0]]);

        $user = $this->superAdmin();

        $start = $this->actingAs($user)
            ->postJson(route('admin.speed-test.start'))
            ->assertOk()
            ->assertJsonPath('success', true);

        $runId = $start->json('run_id');
        $this->assertNotEmpty($runId);

        // Backend harus memberi tahu frontend tahap apa saja yang ada.
        $stages = $start->json('stages');
        $this->assertSame(['ping', 'download', 'upload'], array_column($stages, 'key'));

        // --- Tahap 1: ping. Angka ping harus langsung terlihat. ---
        $ping = $this->actingAs($user)
            ->postJson(route('admin.speed-test.step'), ['run_id' => $runId])
            ->assertOk()
            ->assertJsonPath('done', false)
            ->assertJsonPath('completed_stage', 'ping')
            ->assertJsonPath('next_stage', 'download')
            ->json();

        // assertSame dipakai agar tipe ikut diperiksa: float, bukan integer
        // yang kebetulan nilainya sama.
        $this->assertEqualsWithDelta(9.0, $ping['metrics']['ping_ms'], 0.001);
        $this->assertSame(33, $ping['progress']);
        // Download/upload belum diukur pada tahap ini, jadi belum tampil.
        $this->assertNull($ping['metrics']['download_mbps']);

        // --- Tahap 2: download. Muncul tanpa menunggu tahap upload. ---
        $download = $this->actingAs($user)
            ->postJson(route('admin.speed-test.step'), ['run_id' => $runId])
            ->assertOk()
            ->assertJsonPath('completed_stage', 'download')
            ->assertJsonPath('next_stage', 'upload')
            ->json();

        // 12.500.000 byte/detik = 100 Mbps.
        $this->assertEqualsWithDelta(100.0, $download['metrics']['download_mbps'], 0.001);

        // --- Tahap 3: upload. Panggilan ini sekaligus menutup pengujian. ---
        $final = $this->actingAs($user)
            ->postJson(route('admin.speed-test.step'), ['run_id' => $runId])
            ->assertOk()
            ->assertJsonPath('done', true)
            ->assertJsonPath('status', 'ok')
            ->assertJsonPath('progress', 100);

        $this->assertEqualsWithDelta(100.0, $final['upload_mbps'], 0.001);

        // Tiga tahap, tapi tetap satu baris riwayat.
        $this->assertSame(1, SpeedTestLog::count());
        $this->assertEqualsWithDelta(100.0, SpeedTestLog::first()->download_mbps, 0.001);
    }

    public function test_staged_run_stops_counting_after_it_is_finished(): void
    {
        $this->fakeFallbackOnly(['ping' => ['latency' => 8.0]]);

        $user = $this->superAdmin();

        $runId = $this->actingAs($user)
            ->postJson(route('admin.speed-test.start'))
            ->assertOk()
            ->json('run_id');

        foreach (SpeedTestService::STAGES as $ignored) {
            $this->actingAs($user)->postJson(route('admin.speed-test.step'), ['run_id' => $runId])->assertOk();
        }

        // Polling yang telat atau di-refresh tidak boleh membuat log kedua.
        $this->actingAs($user)
            ->postJson(route('admin.speed-test.step'), ['run_id' => $runId])
            ->assertStatus(422)
            ->assertJsonPath('status', 'gagal');

        $this->assertSame(1, SpeedTestLog::count());
    }

    public function test_staged_run_refuses_a_session_belonging_to_another_account(): void
    {
        $this->fakeFallbackOnly(['ping' => ['latency' => 8.0]]);

        $owner = $this->superAdmin();

        $runId = $this->actingAs($owner)
            ->postJson(route('admin.speed-test.start'))
            ->assertOk()
            ->json('run_id');

        // Akun Super Admin lain tidak boleh bisa melanjutkan sesi ini walau
        // run_id-nya diketahui.
        $other = $this->makeUser('Super Lain', 'speed-other@example.test', 'super_admin');

        $this->actingAs($other)
            ->postJson(route('admin.speed-test.step'), ['run_id' => $runId])
            ->assertStatus(422)
            ->assertJsonPath('status', 'gagal');

        $this->assertSame(0, SpeedTestLog::count());
    }

    public function test_staged_run_refuses_staff(): void
    {
        $runId = (string) Str::uuid();

        $this->actingAs($this->staff())
            ->postJson(route('admin.speed-test.start'))
            ->assertForbidden();

        $this->actingAs($this->staff())
            ->postJson(route('admin.speed-test.step'), ['run_id' => $runId])
            ->assertForbidden();

        Process::assertNothingRan();
        $this->assertSame(0, SpeedTestLog::count());
    }

    public function test_staged_run_refuses_guest(): void
    {
        $runId = (string) Str::uuid();

        // Dipisah dari test staff supaya benar-benar tanpa sesi login — pada
        // test yang sama, actingAs() masih menempel ke request berikutnya.
        $this->postJson(route('admin.speed-test.start'))->assertUnauthorized();

        $this->postJson(route('admin.speed-test.step'), ['run_id' => $runId])->assertUnauthorized();

        Process::assertNothingRan();
        $this->assertSame(0, SpeedTestLog::count());
    }

    public function test_staged_start_reports_an_unusable_target_before_measuring(): void
    {
        config(['ssh.enabled' => false]);

        $device = Device::create(['name' => 'POP Gresik', 'ip_address' => '202.154.4.254']);

        $this->fakeRemoteCli(['ping' => ['latency' => 9.0]]);

        // Kegagalan konfigurasi harus muncul di panggilan pertama, bukan
        // setelah pengujian berjalan setengah jalan.
        $this->actingAs($this->superAdmin())
            ->postJson(route('admin.speed-test.start'), ['device_id' => $device->id])
            ->assertStatus(422)
            ->assertJsonPath('status', 'gagal')
            ->assertJsonPath('source', 'tidak-ada');

        $this->assertSame(0, SpeedTestLog::count(), 'belum ada pengujian yang jalan');
    }

    public function test_staged_start_validates_a_manual_target(): void
    {
        $this->enableSshWithFakeKey();
        $this->fakeRemoteCli(['ping' => ['latency' => 9.0]]);

        // Kosong ditolak oleh validasi, dengan bentuk 422 seperti endpoint run.
        $this->actingAs($this->superAdmin())
            ->postJson(route('admin.speed-test.start'), [
                'target_type' => 'manual',
                'custom_host' => '   ',
            ])
            ->assertStatus(422)
            ->assertJsonValidationErrors('custom_host');

        // Al_valid di luar CIDR ditolak saat Transport dibangun.
        $this->actingAs($this->superAdmin())
            ->postJson(route('admin.speed-test.start'), [
                'target_type' => 'manual',
                'custom_host' => '10.0.0.1',
            ])
            ->assertStatus(422)
            ->assertJsonPath('status', 'gagal');

        Process::assertNothingRan();
    }

    public function test_staged_run_polls_faster_than_the_run_endpoint_throttle_allows(): void
    {
        $this->fakeFallbackOnly(['ping' => ['latency' => 8.0]]);

        $user = $this->superAdmin();

        // Satu pengujian bertahap butuh beberapa request polling. Kalau batasnya
        // disamakan dengan endpoint run (6/menit), polling satu detik akan
        // kena 429 di tengah jalan — jadi endpoint step harus punya batasnya
        // sendiri yang longgar.
        for ($i = 0; $i < 3; $i++) {
            $runId = $this->actingAs($user)
                ->postJson(route('admin.speed-test.start'))
                ->assertOk()
                ->json('run_id');

            foreach (SpeedTestService::STAGES as $ignored) {
                $this->actingAs($user)
                    ->postJson(route('admin.speed-test.step'), ['run_id' => $runId])
                    ->assertOk();
            }
        }

        $this->assertSame(3, SpeedTestLog::count());
    }

    /* ══ 9 · Cuplikan live per detik (probe) ═══ */

    public function test_probe_returns_a_real_sample_for_the_running_stage(): void
    {
        $this->fakeFallbackOnly(['ping' => ['latency' => 9.0]]);

        $user = $this->superAdmin();

        $runId = $this->actingAs($user)
            ->postJson(route('admin.speed-test.start'))
            ->assertOk()
            ->json('run_id');

        // Sesi baru dibuka, jadi tahap yang sedang berjalan adalah 'ping'.
        $probe = $this->actingAs($user)
            ->postJson(route('admin.speed-test.probe'), ['run_id' => $runId])
            ->assertOk()
            ->assertJsonPath('live', true)
            ->assertJsonPath('stage', 'ping')
            ->assertJsonPath('provisional', true)
            ->json();

        // Angka cuplikan HARUS ada: kalau tidak, fitur "real-time per detik"
        // hanya jadi hiasan. Dihitung dari keluaran ping sungguhan yang
        // dipalsukan: 9,0 ms.
        $this->assertEqualsWithDelta(9.0, $probe['metrics']['ping_ms'], 0.001);

        // Cuplikan bukan hasil resmi, jadi tidak boleh ikut terisi di tahap
        // lain yang belum diukur.
        $this->assertNull($probe['metrics']['download_mbps']);
        $this->assertNull($probe['metrics']['upload_mbps']);
    }

    public function test_probe_measures_the_download_stage_in_mbps(): void
    {
        $this->fakeFallbackOnly(['ping' => ['latency' => 9.0]]);

        $user = $this->superAdmin();

        $runId = $this->actingAs($user)
            ->postJson(route('admin.speed-test.start'))
            ->assertOk()
            ->json('run_id');

        // Majukan satu tahap supaya sesi berada di tahap 'download'.
        $this->actingAs($user)
            ->postJson(route('admin.speed-test.step'), ['run_id' => $runId])
            ->assertOk()
            ->assertJsonPath('completed_stage', 'ping');

        $probe = $this->actingAs($user)
            ->postJson(route('admin.speed-test.probe'), ['run_id' => $runId])
            ->assertOk()
            ->assertJsonPath('stage', 'download')
            ->json();

        // 12.500.000 byte/detik = 100 Mbps, sama seperti angka resminya.
        $this->assertEqualsWithDelta(100.0, $probe['metrics']['download_mbps'], 0.001);
        $this->assertNull($probe['metrics']['upload_mbps']);
    }

    public function test_probe_never_touches_the_stored_history_numbers(): void
    {
        // Angka sengaja dibuat berbeda antara cuplikan live dan pengukuran
        // resmi, supaya kebocorannya kelihatan kalau terjadi.
        // Kalau cuplikan bocor ke state, angka di riwayat ikut berubah.
        Process::fake([
            '*which speedtest*' => Process::result(exitCode: 1, output: ''),
            '*command -v speedtest*' => Process::result(exitCode: 1, output: ''),
            '*which curl*' => Process::result(exitCode: 0, output: "/usr/bin/curl\n"),
            '*which ping*' => Process::result(exitCode: 0, output: "/bin/ping\n"),
            '*command -v curl*' => Process::result(exitCode: 0, output: "/usr/bin/curl\n"),
            '*command -v ping*' => Process::result(exitCode: 0, output: "/bin/ping\n"),
            // Pengukuran resmi: 25.000.000 byte/detik = 200 Mbps.
            // Cuplikan live dibedakan oleh --max-time 1 (resmi memakai 45).
            // Polanya harus menyertakan tanda kutip: Symfony membungkus SETIAP
            // argumen perintah dengan tanda kutip tunggal, jadi polanya tidak
            // bisa sekadar menempel pada "--max-time 1".
            "*'--max-time' '1' '--connect-timeout' '1'*" => Process::result(exitCode: 0, output: '6250000'),
            '*curl*' => Process::result(exitCode: 0, output: '25000000'),
            '*ping*' => Process::result(exitCode: 0, output: "4 packets transmitted, 4 received, 0% packet loss\nrtt min/avg/max/mdev = 9.0/9.0/9.0/0.0 ms"),
            '*' => Process::result(exitCode: 0, output: ''),
        ]);

        $user = $this->superAdmin();

        $runId = $this->actingAs($user)
            ->postJson(route('admin.speed-test.start'))
            ->assertOk()
            ->json('run_id');

        // Habiskan tahap ping, lalu ambil cuplikan tahap download.
        $this->actingAs($user)->postJson(route('admin.speed-test.step'), ['run_id' => $runId])->assertOk();

        $live = $this->actingAs($user)
            ->postJson(route('admin.speed-test.probe'), ['run_id' => $runId])
            ->assertOk()
            ->json('metrics');

        $this->assertEqualsWithDelta(50.0, $live['download_mbps'], 0.001);

        // Tutup pengujian. Yang tersimpan HARUS angka pengukuran resmi.
        $this->actingAs($user)
            ->postJson(route('admin.speed-test.step'), ['run_id' => $runId])
            ->assertOk()
            ->assertJsonPath('completed_stage', 'download');

        $this->actingAs($user)
            ->postJson(route('admin.speed-test.step'), ['run_id' => $runId])
            ->assertOk()
            ->assertJsonPath('done', true);

        $log = SpeedTestLog::first();

        $this->assertNotNull($log, 'harus ada satu baris riwayat');
        // 200 Mbps resmi, bukan 50 Mbps hasil cuplikan.
        $this->assertEqualsWithDelta(200.0, $log->download_mbps, 0.001);
    }

    public function test_probe_reports_done_when_the_session_is_already_closed(): void
    {
        $this->fakeFallbackOnly(['ping' => ['latency' => 8.0]]);

        $user = $this->superAdmin();

        $runId = $this->actingAs($user)
            ->postJson(route('admin.speed-test.start'))
            ->assertOk()
            ->json('run_id');

        foreach (SpeedTestService::STAGES as $ignored) {
            $this->actingAs($user)->postJson(route('admin.speed-test.step'), ['run_id' => $runId])->assertOk();
        }

        // Polling cuplikan yang telat harus berhenti sendiri, bukandilaporkan
        // error yang membuat halaman menampilkan kegagalan.
        $this->actingAs($user)
            ->postJson(route('admin.speed-test.probe'), ['run_id' => $runId])
            ->assertStatus(422)
            ->assertJsonPath('done', true)
            ->assertJsonPath('live', false);
    }

    public function test_probe_refuses_a_session_belonging_to_another_account(): void
    {
        $this->fakeFallbackOnly(['ping' => ['latency' => 8.0]]);

        $runId = $this->actingAs($this->superAdmin())
            ->postJson(route('admin.speed-test.start'))
            ->assertOk()
            ->json('run_id');

        // Akun lain tidak boleh bisa mengintip atau melanjutkan sesi ini.
        $this->actingAs($this->makeUser('Admin lain', 'another-admin@example.test', 'super_admin'))
            ->postJson(route('admin.speed-test.probe'), ['run_id' => $runId])
            ->assertStatus(422)
            ->assertJsonPath('done', true);
    }

    public function test_probe_refuses_staff(): void
    {
        $runId = (string) Str::uuid();

        $this->actingAs($this->staff())
            ->postJson(route('admin.speed-test.probe'), ['run_id' => $runId])
            ->assertForbidden();
    }

    public function test_probe_refuses_guest(): void
    {
        $runId = (string) Str::uuid();

        // Dipisah dari test staff supaya benar-benar tanpa sesi login — pada
        // test yang sama, actingAs() masih menempel ke request berikutnya.
        $this->postJson(route('admin.speed-test.probe'), ['run_id' => $runId])
            ->assertUnauthorized();

        Process::assertNothingRan();
    }

    public function test_probe_validates_the_run_id(): void
    {
        $this->actingAs($this->superAdmin())
            ->postJson(route('admin.speed-test.probe'), ['run_id' => 'bukan-uuid'])
            ->assertStatus(422)
            ->assertJsonValidationErrors('run_id');
    }

    /* ══ 10 · Status metrik: pending vs gagal ═══════
     *
     * Null saja tidak cukup untuk ditampilkan: tahap yang belum dijalankan
     * dan tahap yang sudah dicoba tapi angkanya tidak terbaca sama-sama
     * bernilai null. Kalau keduanya tidak dibedakan, kartu di layar
     * menulis "Gagal terbaca" untuk tahap yang belum mulai — dan setiap
     * polling menimpa cuplikan langsung yang sedang tampil dengan tanda
     * hubung, sehingga angka berkedip antara nilai dan "—".
     */

    public function test_unmeasured_metrics_are_reported_as_pending_not_failed(): void
    {
        $this->fakeFallbackOnly(['ping' => ['latency' => 9.0]]);

        $user = $this->superAdmin();

        $runId = $this->actingAs($user)
            ->postJson(route('admin.speed-test.start'))
            ->assertOk()
            ->json('run_id');

        // Baris pertama: hanya tahap 'ping' yang selesai. Unduh dan unggah
        // BELUM dijalankan sama sekali.
        $first = $this->actingAs($user)
            ->postJson(route('admin.speed-test.step'), ['run_id' => $runId])
            ->assertOk()
            ->json('metrics');

        $this->assertSame('ok', $first['status']['ping_ms']);
        $this->assertSame('pending', $first['status']['download_mbps']);
        $this->assertSame('pending', $first['status']['upload_mbps']);

        // Baris kedua: tahap unduh selesai, unggah masih pending.
        $second = $this->actingAs($user)
            ->postJson(route('admin.speed-test.step'), ['run_id' => $runId])
            ->assertOk()
            ->json('metrics');

        $this->assertSame('ok', $second['status']['download_mbps']);
        $this->assertSame('pending', $second['status']['upload_mbps']);
    }

    public function test_a_measurement_that_could_not_be_read_is_reported_as_failed(): void
    {
        // curl dan ping "ada", tapi keduanya mengembalikan keluaran kosong:
        // tahap dijalankan, angkanya tidak terbaca. Ini harus 'gagal', bukan
        // 'pending' — supaya kartu boleh menulis "Gagal terbaca".
        Process::fake([
            '*which speedtest*' => Process::result(exitCode: 1, output: ''),
            '*command -v speedtest*' => Process::result(exitCode: 1, output: ''),
            '*which curl*' => Process::result(exitCode: 0, output: "/usr/bin/curl\n"),
            '*which ping*' => Process::result(exitCode: 0, output: "/bin/ping\n"),
            '*command -v curl*' => Process::result(exitCode: 0, output: "/usr/bin/curl\n"),
            '*command -v ping*' => Process::result(exitCode: 0, output: "/bin/ping\n"),
            '*curl*' => Process::result(exitCode: 0, output: ''),
            '*ping*' => Process::result(exitCode: 0, output: ''),
            '*' => Process::result(exitCode: 0, output: ''),
        ]);

        $user = $this->superAdmin();

        $runId = $this->actingAs($user)
            ->postJson(route('admin.speed-test.start'))
            ->assertOk()
            ->json('run_id');

        $metrics = $this->actingAs($user)
            ->postJson(route('admin.speed-test.step'), ['run_id' => $runId])
            ->assertOk()
            ->json('metrics');

        $this->assertSame('gagal', $metrics['status']['ping_ms']);
        // Yang belum dijalankan tetap pending, bukan gagal.
        $this->assertSame('pending', $metrics['status']['download_mbps']);
        $this->assertSame('pending', $metrics['status']['upload_mbps']);
        $this->assertNull($metrics['ping_ms']);
    }

    public function test_metric_statuses_are_null_before_any_stage_runs(): void
    {
        $service = new SpeedTestService;

        $this->assertSame([
            'ping_ms' => SpeedTestService::METRIC_PENDING,
            'download_mbps' => SpeedTestService::METRIC_PENDING,
            'upload_mbps' => SpeedTestService::METRIC_PENDING,
        ], $service->metricStatuses($service->freshState()));
    }

    /* ══ Helper ═══════════════════════════════════════════ */

    /**
     * Palsukan pengukuran cadangan saja (tanpa speedtest-cli), supaya tiap
     * tahap harus mengukur sendiri dan angka bisa Diamati muncul bertahap.
     *
     * @param  array<string, mixed>  $cliJson
     */
    private function fakeFallbackOnly(array $cliJson): void
    {
        Process::fake([
            // Hanya speedtest-cli yang dianggap tidak ada; curl dan ping
            // tetap tersedia supaya jalur cadangan bisa diukur per tahap.
            '*which speedtest*' => Process::result(exitCode: 1, output: ''),
            '*command -v speedtest*' => Process::result(exitCode: 1, output: ''),
            '*which curl*' => Process::result(exitCode: 0, output: "/usr/bin/curl\n"),
            '*which ping*' => Process::result(exitCode: 0, output: "/bin/ping\n"),
            '*command -v curl*' => Process::result(exitCode: 0, output: "/usr/bin/curl\n"),
            '*command -v ping*' => Process::result(exitCode: 0, output: "/bin/ping\n"),
            // Cadangan: 12.500.000 byte/detik = 100 Mbps.
            '*curl*' => Process::result(exitCode: 0, output: '12500000'),
            '*ping*' => Process::result(
                exitCode: 0,
                output: "4 packets transmitted, 4 received, 0% packet loss\nrtt min/avg/max/mdev = 9.0/9.0/9.0/0.0 ms"
            ),
            '*' => Process::result(exitCode: 0, output: ''),
        ]);
    }

    /**
     * Aktifkan jalur SSH dengan private key dummy yang benar-benar terbaca,
     * supaya SshTransport lolos validasi tanpa perlu server sungguhan.
     */
    private function enableSshWithFakeKey(): void
    {
        $keyPath = tempnam(sys_get_temp_dir(), 'speedtest-key-');
        file_put_contents($keyPath, "-----BEGIN OPENSSH PRIVATE KEY-----\ntest\n-----END OPENSSH PRIVATE KEY-----\n");

        $this->beforeApplicationDestroyed(function () use ($keyPath): void {
            if (is_file($keyPath)) {
                @unlink($keyPath);
            }
        });

        config([
            'ssh.enabled' => true,
            'ssh.user' => 'noc',
            'ssh.identity_file' => $keyPath,
            // Jaringan contoh milik Radnet, untuk menguji jalur target manual.
            'ssh.allowed_cidrs' => ['202.154.0.0/16'],
        ]);
    }

    /**
     * Palsukan perintah SSH ke perangkat tujuan supaya test tidak menyentuh
     * jaringan sungguhan.
     *
     * @param  array<string, mixed>  $cliJson
     */
    private function fakeRemoteCli(array $cliJson): void
    {
        $json = json_encode($cliJson);

        Process::fake([
            // speedtest-cli di perangkat tujuan.
            '*speedtest*' => Process::result(exitCode: 0, output: $json),
            '*speedtest-cli*' => Process::result(exitCode: 0, output: $json),
            // "command -v" di sisi perangkat: CLI selalu dianggap ada.
            '*command -v*' => Process::result(exitCode: 0, output: "/usr/bin/speedtest\n"),
            '*curl*' => Process::result(exitCode: 0, output: '12500000'),
            '*ping*' => Process::result(
                exitCode: 0,
                output: "4 packets transmitted, 4 received, 0% packet loss\nrtt min/avg/max/mdev = 10.0/12.5/15.0/1.2 ms"
            ),
            '*' => Process::result(exitCode: 0, output: ''),
        ]);
    }

    /**
     * Palsukan proses speedtest-cli + fallback curl/ping supaya test tidak
     * menyentuh jaringan sungguhan.
     *
     * @param  array<string, mixed>  $cliJson
     */
    private function fakeCli(array $cliJson): void
    {
        $json = json_encode($cliJson);

        Process::fake([
            // Output stand-in untuk speedtest --format=json.
            '*speedtest*' => Process::result(exitCode: 0, output: $json),
            '*speedtest-cli*' => Process::result(exitCode: 0, output: $json),
            // "which" melaporkan CLI selalu ada supaya jalur cli teruji.
            '*which*' => Process::result(exitCode: 0, output: "/usr/bin/ookla\n"),
            // Cadangan: 12.500.000 byte/detik = 100 Mbps.
            '*curl*' => Process::result(exitCode: 0, output: '12500000'),
            // rtt min/avg/max/mdev = 10.0/12.5/15.0/1.2 ms
            '*ping*' => Process::result(
                exitCode: 0,
                output: "4 packets transmitted, 4 received, 0% packet loss\nrtt min/avg/max/mdev = 10.0/12.5/15.0/1.2 ms"
            ),
        ]);
    }

    /**
     * Akun di-cache per test: kolom email punya constraint unik, jadi satu
     * test yang memakai akun sama dua kali harus memakai objek yang sama.
     */
    private function superAdmin(): User
    {
        return $this->cachedUser('super', 'Speed Super', 'speed-super@example.test', 'super_admin');
    }

    private function staff(): User
    {
        return $this->cachedUser('staff', 'Speed Staff', 'speed-staff@example.test', 'staff');
    }

    private function cachedUser(string $key, string $name, string $email, string $role): User
    {
        $property = 'user_'.$key;

        return $this->{$property} ??= $this->makeUser($name, $email, $role);
    }

    private function makeUser(string $name, string $email, string $role): User
    {
        return User::create([
            'name' => $name,
            'email' => $email,
            'role' => $role,
            'password' => bcrypt('password'),
        ]);
    }

    private function createSchema(string $table, callable $definition): void
    {
        if (! Schema::hasTable($table)) {
            Schema::create($table, $definition);
        }
    }
}
