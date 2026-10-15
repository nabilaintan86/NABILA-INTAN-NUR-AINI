<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Tests\Feature\Concerns\InteractsWithDeviceRegistry;
use Tests\TestCase;

/**
 * Tampilan Ping Monitor.
 *
 * Awalnya halaman ping terpisah di /admin/ping-monitor. Sekarang tampilan itu
 * sudah dipindahkan ke dalam halaman Network Tools (/admin/tools), sementara
 * URL lamanya tetap hidup sebagai redirect supaya bookmark lama tidak 404.
 *
 * Yang diuji di sini:
 *
 *  1. UI ping benar-benar menyatu di /admin/tools: tiga tab (Ping Server Radnet,
 *     Ping Server Luar, Status Ringkasan) dengan nilai IP bawaannya, dan hanya
 *     muncul satu kali -- bukan dua kali karena halaman lama masih di-render.
 *  2. URL /admin/ping-monitor mengarahkan ke /admin/tools, dan tetap aja
 *     terlarang untuk staff.
 *  3. Page-scoped Tailwind benar-benar dimuat lewat @vite sehingga kelas
 *     utility khusus UI ping ikut terbawa.
 *  4. Log terminal dan kotak analisis punya penanda (data-log dan
 *     data-analysis) per tab, supaya JavaScript tidak salah mengisi.
 *  5. Tidak ada tombol mati: tidak boleh ada tombol "Return to Tools" lagi,
 *     karena halaman ini sudah Network Tools.
 *  6. Endpoint ping di belakang halaman benar-benar menjalankan perintah
 *     ping dan menolak target berbahaya.
 */
class PingMonitorPageTest extends TestCase
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

        // Sidebar admin menghitung notifikasi belum dibaca lewat model ini,
        // jadi tabelnya wajib ada supaya view bisa dirender.
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

        // Panel ringkasan di Network Tools menghitung tautan antar perangkat
        // lewat tabel ini, jadi wajib ada supaya halaman yang diuji bisa
        // dirender utuh.
        $this->createSchema('device_connections', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('from_device_id')->nullable();
            $table->unsignedBigInteger('to_device_id')->nullable();
            $table->string('status')->default('up');
            $table->timestamps();
        });

        // Dashboard menghitung statistik respons dari tabel ini.
        $this->createSchema('monitoring_logs', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('device_id')->nullable();
            $table->string('ip_address')->nullable();
            $table->string('status')->default('up');
            $table->integer('response_time')->nullable();
            $table->timestamp('checked_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Halaman yang diuji: Network Tools, tempat UI ping sekarang tinggal.
     * Semua test di bawah memakainya supaya tidak ada yang diam-diam masih
     * menguji URL lama.
     */
    private function toolsPageHtml(): string
    {
        return $this->actingAs($this->superAdmin())
            ->get(route('admin.tools'))
            ->assertOk()
            ->getContent();
    }

    public function test_page_renders_all_three_tabs_and_controls(): void
    {
        $html = $this->toolsPageHtml();

        foreach ([
            'tab server radnet' => 'Ping Server Radnet',
            'tab server luar' => 'Ping Server Luar',
            'tab ringkasan' => 'Status Ringkasan',
            'tombol mulai ping' => 'Mulai Ping',
            'header biru' => 'bg-blue-800',
            'badge up' => '🟢',
            'badge warning' => '🟡',
            'badge down' => '🔴',
            'garis waktu putar' => 'round trip time =',
        ] as $label => $needle) {
            $this->assertStringContainsString($needle, $html, "Halaman harus memuat: {$label}");
        }
    }

    /**
     * UI ping dipindah, bukan disalin. Kalau partial-nya ikut ter-render dua
     * kali, maka setiap penanda data-log akan berduplikat dan JavaScript akan
     * menulis hasil ke tempat yang sama dua kali.
     */
    public function test_ping_ui_is_merged_into_network_tools_exactly_once(): void
    {
        $html = $this->toolsPageHtml();

        foreach (['radnet', 'luar'] as $key) {
            $this->assertSame(
                1,
                substr_count($html, "data-form=\"{$key}\""),
                "Harus ada tepat satu form ping untuk tab {$key} (kalau lebih, UI-nya ter-render dua kali)"
            );
        }

        $this->assertSame(
            1,
            substr_count($html, 'id="panel-radnet"'),
            'Panel ping harus ada tepat satu kali di halaman Network Tools'
        );
    }

    public function test_each_tab_carries_its_own_default_ip(): void
    {
        $html = $this->toolsPageHtml();

        $this->assertStringContainsString('value="192.168.93.59"', $html, 'Tab Radnet harus memakai IP Radnet');
        $this->assertStringContainsString('value="8.8.8.8"', $html, 'Tab luar harus memakai IP publik');
    }

    public function test_each_tab_owns_a_separate_log_and_analysis_box(): void
    {
        $html = $this->toolsPageHtml();

        // JavaScript menulis ke penanda yang berbeda tiap tab, jadi masing-masing
        // harus muncul tepat satu kali agar tidak ada hasil tertimpa.
        foreach (['radnet', 'luar'] as $key) {
            $this->assertSame(
                1,
                substr_count($html, "data-log=\"{$key}\""),
                "Harus ada tepat satu penanda data-log untuk tab {$key}"
            );
            $this->assertSame(
                1,
                substr_count($html, "data-analysis=\"{$key}\""),
                "Harus ada tepat satu penanda data-analysis untuk tab {$key}"
            );
        }

        $this->assertSame(1, substr_count($html, 'data-summary="radnet"'));
        $this->assertSame(1, substr_count($html, 'data-summary="luar"'));
    }

    public function test_page_loads_its_page_scoped_tailwind_build(): void
    {
        $html = $this->toolsPageHtml();

        // Entry ping-monitor.css harus ikut dirujuk oleh @vite, kalau tidak
        // semua kelas utility UI ping hilang diam-diam.
        $this->assertMatchesRegularExpression(
            '#/build/assets/ping-monitor-[A-Za-z0-9_-]+\.css#',
            $html,
            'Halaman harus memuat hasil build ping-monitor.css lewat @vite'
        );
    }

    public function test_page_uses_the_blue_component_classes_and_no_stale_amber_chrome(): void
    {
        $html = $this->toolsPageHtml();

        // Warna kerangka halaman (tab, tombol, kolom isian, tombol kembali)
        // diambil dari kelas komponen, bukan dari utility warna yang ditulis
        // tangan di tiap kolom.
        foreach ([
            'wadah tab' => 'pm-tabs',
            'tab' => 'pm-tab',
            'tombol mulai ping' => 'pm-run',
            'kolom isian' => 'pm-field',
            'kartu ringkasan' => 'pm-summary',
        ] as $label => $class) {
            $this->assertStringContainsString($class, $html, "Halaman harus memakai kelas {$label}");
        }

        // Halaman ini memakai identitas biru. Sisa amber hanya boleh muncul
        // sebagai warna STATUS (WARNING), bukan sebagai dekorasi kerangka.
        // Daftar di bawah adalah kelas amber yang pernah dipakai untuk
        // kerangka dan karena itu harus tidak ada lagi.
        foreach ([
            'focus:border-amber-400',
            'focus:ring-amber-400',
            'hover:border-amber-400',
            'hover:text-amber-300',
            'focus-visible:ring-amber-300',
        ] as $stale) {
            $this->assertStringNotContainsString($stale, $html, "Warna kerangka lama masih ada: {$stale}");
        }

        // Warna status tetap ada, karena itu yang membedakan UP / WARNING / DOWN.
        foreach (['text-amber-400', 'text-emerald-400', 'text-rose-400'] as $statusTone) {
            $this->assertStringContainsString($statusTone, $html, "Warna status harus tetap ada: {$statusTone}");
        }
    }

    /**
     * Kelas utility yang salah ketik tidak menghasilkan apa pun, dan gagalnya
     * diam-diam — tidak ada error, tidak ada peringatan, hanya garis fokus
     * atau tombol yang lenyap tanpa jejak. Contoh nyata: `focus:ring-400/30`
     * (nama warnanya hilang) pernah terpakai di halaman ini, dan tidak ada
     * yang capturingnya.
     *
     * Test ini mencari dua pola yang jelas salah: modifier warna tanpa nama
     * warna, dan modifier warna yang terpotong.
     */
    public function test_page_has_no_malformed_tailwind_utility_classes(): void
    {
        $html = $this->toolsPageHtml();

        $forbidden = [
            // Modifier warna tanpa nama warna: ring-400, border-300, bg-500...
            '~(?<![\w-])((?:hover:|focus:|focus-visible:|active:|disabled:)*'
                .'(?:ring|border|bg|text|outline)-\d{2,3}(?:/\d+)?)(?![\w/-])~',
            // Modifier warna terpotong: ring-amber-, bg-blue-
            '~(?<![\w-])((?:hover:|focus:|focus-visible:)*'
                .'(?:ring|border|bg|text|outline)-(?:amber|blue|emerald|rose|slate|sky)-)(?![\w-])~',
        ];

        foreach ($forbidden as $index => $pattern) {
            preg_match_all($pattern, $html, $matches);

            $this->assertSame(
                [],
                array_values(array_unique($matches[1] ?? [])),
                "Ditemukan kelas utility Tailwind yang tidak valid (pola #{$index}): "
                    .implode(', ', array_unique($matches[1] ?? []))
            );
        }
    }

    /**
     * UI ping sudah tinggal di Network Tools, jadi tombol "Return to Tools"
     * harus hilang. Kalau masih ada, tombol itu akan mengarahkan user ke
     * halaman yang sedang dibuka -- persis jenis tombol mati yang harus
     * dibuang saat fitur dipindahkan.
     */
    public function test_no_dead_return_button_remains_after_the_move(): void
    {
        $html = $this->toolsPageHtml();

        $this->assertStringNotContainsString(
            'Return to Tools',
            $html,
            'Tombol kembali tidak lagi berguna karena UI sudah berada di Network Tools'
        );
    }

    public function test_old_ping_monitor_url_redirects_to_network_tools(): void
    {
        // Redirect, bukan 404, supaya bookmark dan tautan yang sudah dibagikan
        // orang tetap hidup.
        $this->actingAs($this->superAdmin())
            ->get(route('admin.ping-monitor'))
            ->assertRedirect(route('admin.tools'));
    }

    public function test_only_super_admin_may_open_the_page(): void
    {
        $staff = $this->makeUser(['email' => 'staff@contoh.test', 'role' => 'staff']);

        $this->actingAs($staff)
            ->get(route('admin.ping-monitor'))
            ->assertForbidden();

        // Lepas sesi staff supaya permintaan berikutnya benar-benar tanpa login.
        $this->app['auth']->forgetGuards();

        $this->get(route('admin.ping-monitor'))
            ->assertRedirect(route('login'));
    }

    public function test_ping_endpoint_returns_real_command_output(): void
    {
        $response = $this->actingAs($this->superAdmin())
            ->postJson(route('admin.tools.ping'), [
                'target' => '127.0.0.1',
                'count' => 3,
            ])
            ->assertOk()
            ->assertJsonStructure(['success', 'output', 'duration']);

        $output = $response->json('output');

        // Bentuk output harus sama dengan yang diuraikan parser di halaman:
        // satu baris balasan per paket, lalu baris statistik Linux.
        $this->assertStringContainsString(
            'icmp_seq=',
            $output,
            'Output harus memuat baris balas per paket'
        );
        $this->assertMatchesRegularExpression(
            '/\d+ packets transmitted, \d+ received/',
            $output,
            'Output harus memuat baris statistik ping Linux'
        );
    }

    public function test_ping_endpoint_rejects_shell_metacharacters_in_target(): void
    {
        $this->actingAs($this->superAdmin())
            ->postJson(route('admin.tools.ping'), [
                'target' => '8.8.8.8; whoami',
                'count' => 2,
            ])
            ->assertStatus(422)
            ->assertJsonPath('success', false);
    }

    /**
     * Menu "Ping Monitor" dihapus dari sidebar supaya tidak ada dua pintu ke
     * layar yang sama. Pintu tunggalnya adalah "Network Tools".
     */
    public function test_sidebar_has_one_door_to_the_ping_ui(): void
    {
        $html = $this->actingAs($this->superAdmin())
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->getContent();

        $this->assertStringNotContainsString(
            'Ping Monitor',
            $html,
            'Menu Ping Monitor sudah dihapus; UI-nya menyatu di Network Tools'
        );

        $this->assertSame(
            1,
            substr_count($html, 'href="'.route('admin.tools').'"'),
            'Harus ada tepat satu tautan Network Tools di sidebar'
        );
    }

    private function superAdmin(): User
    {
        return $this->makeUser(['email' => 'super@contoh.test', 'role' => 'super_admin']);
    }

    private function makeUser(array $attributes): User
    {
        return tap(new User, function (User $user) use ($attributes): void {
            $user->forceFill(array_merge([
                'name' => 'Pengguna Uji',
                'password' => bcrypt('password'),
                'role' => 'staff',
            ], $attributes));
        });
    }

    private function createSchema(string $table, callable $definition): void
    {
        if (! Schema::hasTable($table)) {
            Schema::create($table, $definition);
        }
    }
}
