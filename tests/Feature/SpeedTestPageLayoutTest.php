<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Tests\Feature\Concerns\InteractsWithDeviceRegistry;
use Tests\TestCase;

/**
 * Susunan halaman Speed Test ala Speedtest by Ookla.
 *
 * Test ini menjaga bentuk halamannya, bukan cara programnya:
 *
 *  1. Gauge di tengah, dan tombol MULAI benar-benar berada DI DALAM dial —
 *     bukan tombol melayang di bawah angka.
 *  2. Panel hasil disembunyikan sampai pengujian selesai, lalu memuat dua
 *     kotak simetris (Unduhan / Unggahan), baris Ping-Jitter-Loss, dan
 *     kotak informasi koneksi.
 *  3. Tidak ada kontrol lain yang menggantung di halaman. Ini yang menjaga
 *     halaman ini bebas tombol mati: satu-satunya tombol adalah MULAI,
 *     dan semuanya benar-benar terpasang ke pengukuran nyata.
 */
class SpeedTestPageLayoutTest extends TestCase
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

        $this->createSchema('device', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('ip_address');
            $table->string('type')->default('Router');
            $table->string('status')->default('up');
            $table->integer('avg_delay')->default(0);
            $table->decimal('missed_percentage', 6, 2)->default(0);
            $table->timestamps();
        });

        $this->createSchema('telegram_users', function (Blueprint $table): void {
            $table->id();
            $table->bigInteger('user_id')->nullable();
            $table->string('name')->nullable();
            $table->string('username')->nullable();
            $table->enum('role', ['super_admin', 'staff'])->default('staff');
            $table->boolean('is_approved')->default(false);
            $table->timestamps();
        });

        $this->createSchema('device_notifications', function (Blueprint $table): void {
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

    public function test_gauge_carries_the_start_button_inside_the_dial(): void
    {
        $html = $this->pageHtml();

        // Dua lapis dalam dial: satu untuk tombol, satu untuk angka live.
        $this->assertStringContainsString('stg-center-go', $html);
        $this->assertStringContainsString('stg-center-read', $html);

        // Tombol berada DI DALAM stg-gauge-wrap, bukan sesudahnya.
        $gaugeStart = strpos($html, 'id="stgGaugeWrap"');
        $buttonAt = strpos($html, 'id="stgRun"');
        $gaugeEnd = strpos($html, 'id="stgResults"');

        $this->assertNotFalse($gaugeStart, 'gauge tidak ditemukan');
        $this->assertNotFalse($buttonAt, 'tombol MULAI tidak ditemukan');
        $this->assertNotFalse($gaugeEnd);
        $this->assertGreaterThan($gaugeStart, $buttonAt, 'tombol harus di dalam dial');
        $this->assertLessThan($gaugeEnd, $buttonAt, 'tombol harus di dalam dial');

        // Label yang terlihat benar-benar "MULAI".
        $this->assertStringContainsString('>MULAI<', $html);

        // Nama yang lebih panjang tetap ada di aria-label untuk pembaca layar.
        $this->assertStringContainsString('aria-label="Mulai Speed Test"', $html);
    }

    public function test_results_panel_stays_hidden_until_a_test_finishes(): void
    {
        $html = $this->pageHtml();

        // Atribut `hidden` membuat panel benar-benar tak terlihat, bukan
        // sekadar opacity 0 — jadi tidak bisa dibaca sebelum ada angkanya.
        $this->assertMatchesRegularExpression('/id="stgResults"[^>]*\shidden/', $html);

        // JavaScript yang memunculkannya harus benar-benar ada.
        $this->assertStringContainsString('resultsEl.hidden = false', $html);
        $this->assertStringContainsString("resultsEl.classList.add('is-shown')", $html);
    }

    public function test_two_symmetric_result_boxes_sit_above_the_metric_row(): void
    {
        $markup = $this->pageMarkup();
        $html = $this->pageHtml();

        $this->assertSame(1, substr_count($markup, 'data-hero="download"'));
        $this->assertSame(1, substr_count($markup, 'data-hero="upload"'));

        // Dua kotak benar-benar berjajar, bukan menumpuk: grid dua kolom
        // diaktifkan mulai lebar layar yang cukup.
        $this->assertStringContainsString('.stg-heroes', $this->compiledCss());
        $this->assertMatchesRegularExpression(
            '/\.stg-heroes\s*\{[^}]*grid-template-columns:\s*1fr\s+1fr/',
            $this->compiledCss()
        );

        // Kotak hasil muncul sebelum baris metrik di sumber halaman.
        $this->assertLessThan(
            strpos($markup, 'data-metric="ping"'),
            strpos($markup, 'data-hero="download"'),
            'dua kotak besar harus di atas baris metrik'
        );

        // Kedua kotak diambil oleh script, jadi angka di dalamnya tidak
        // sekadar tampilan mati.
        $this->assertStringContainsString('document.querySelector(\'[data-hero="download"]\')', $html);
        $this->assertStringContainsString('document.querySelector(\'[data-hero="upload"]\')', $html);
    }

    public function test_metric_row_covers_ping_jitter_and_loss(): void
    {
        $markup = $this->pageMarkup();
        $html = $this->pageHtml();

        foreach (['ping', 'jitter', 'loss'] as $metric) {
            $this->assertSame(1, substr_count($markup, "data-metric=\"{$metric}\""), "metrik {$metric} kurang");

            // Script benar-benar mengambil elemen itu, jadi tidak ada kotak
            // metrik yang cuma jadi hiasan.
            $this->assertStringContainsString("[data-metric=\"{$metric}\"]", $html);
        }

        $this->assertStringContainsString('Ping', $html);
        $this->assertStringContainsString('Jitter', $html);
        $this->assertStringContainsString('Loss', $html);

        // Loss memakai satuan persen, dua metrik lain memakai milidetik.
        $this->assertSame(2, substr_count($markup, '>ms</span>'));
        $this->assertSame(1, substr_count($markup, '>%</span>'));
    }

    public function test_connection_panel_lists_isp_ip_and_server_location(): void
    {
        $markup = $this->pageMarkup();
        $html = $this->pageHtml();

        $this->assertStringContainsString('Koneksi (ISP)', $markup);
        $this->assertStringContainsString('IP Address', $markup);
        $this->assertStringContainsString('Server Lokasi', $markup);

        foreach (['isp', 'ip', 'server'] as $key) {
            $this->assertSame(1, substr_count($markup, "data-info=\"{$key}\""));
            $this->assertStringContainsString("[data-info=\"{$key}\"]", $html);
        }

        // Ketiganya diisi dari sumber nyata, bukan teks hard-code.
        $this->assertStringContainsString('cloudflare.com/cdn-cgi/trace', $html);
        $this->assertStringContainsString('trace.asOrganization', $html);
        $this->assertStringContainsString('trace.colo', $html);

        // IP dari server ikut dikirim sebagai cadangan, supaya barisnya tidak
        // kosong walau CDN tidak dapat dijangkau.
        $this->assertMatchesRegularExpression('/var SERVER_IP = "[^"]*"/', $html);
        $this->assertStringContainsString('var SERVER_IP = "127.0.0.1"', $html);
    }

    public function test_page_has_no_dead_controls(): void
    {
        $page = $this->pageMarkup();

        // Satu-satunya tombol yang bisa diklik adalah MULAI.
        $this->assertSame(1, substr_count($page, '<button'), 'halaman harus punya tepat satu tombol');
        $this->assertSame(1, substr_count($page, 'id="stgRun"'));

        // Tidak ada form atau input yang menggantung di area halaman.
        $this->assertSame(0, substr_count($page, '<form'));
        $this->assertSame(0, substr_count($page, '<input'));

        // Tombol itu benar-benar terhubung ke pengukuran: event klik-nya
        // dipasang ke runTest().
        $html = $this->pageHtml();

        $this->assertStringContainsString("runBtn.addEventListener('click', runTest)", $html);
        $this->assertStringContainsString('function runTest()', $html);

        // Ketiga pengukuran itu benar-benar dipanggil, bukan cuma ditulis.
        $this->assertStringContainsString('measureDownload()', $html);
        $this->assertStringContainsString('measureUpload()', $html);
        $this->assertStringContainsString('measurePing()', $html);
    }

    /* ── helper ───────────────────────────────────────────── */

    private function pageHtml(): string
    {
        $user = new User;
        $user->forceFill([
            'name' => 'Super Admin Uji',
            'email' => 'super@contoh.test',
            'password' => bcrypt('password'),
            'role' => 'super_admin',
        ]);

        return $this->actingAs($user)
            ->get(route('admin.speed-test'))
            ->assertOk()
            ->getContent();
    }

    /**
     * Markup milik halaman ini saja, dipotong dari wrapper halaman sampai
     * catatan penutup. Pemotongan ini perlu karena layout admin ikut
     * membawa tombol miliknya sendiri (notifikasi, tombol tutup offcanvas).
     */
    private function pageMarkup(): string
    {
        $html = $this->pageHtml();

        $start = strpos($html, '<div class="stg-page">');
        $end = strpos($html, 'Pengujian memakai bandwidth penuh', $start);

        $this->assertNotFalse($start, 'wrapper .stg-page tidak ditemukan');
        $this->assertNotFalse($end, 'catatan penutup tidak ditemukan');

        return substr($html, $start, ($end - $start) + 300);
    }

    /**
     * Isi CSS hasil build untuk halaman ini.
     *
     * Assertion soal layout dua kolom tidak bisa dibaca dari markup, karena
     * kolomnya dibentuk CSS.
     */
    private function compiledCss(): string
    {
        $file = glob(public_path('build/assets/speedtest-*.css'))[0] ?? null;

        $this->assertNotNull($file, 'CSS hasil build untuk speedtest tidak ditemukan');

        return (string) file_get_contents($file);
    }

    private function createSchema(string $table, callable $definition): void
    {
        if (! Schema::hasTable($table)) {
            Schema::create($table, $definition);
        }
    }
}
