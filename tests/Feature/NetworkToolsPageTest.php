<?php

namespace Tests\Feature;

use App\Models\Device;
use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Facades\Schema;
use Tests\Feature\Concerns\InteractsWithDeviceRegistry;
use Tests\TestCase;

/**
 *_halaman Network Tools_
 *
 * Menjugate dua hal:
 *
 *  1. Tata letak dua panel. Panel kiri untuk daftar perangkat Radnet
 *     (pencarian + filter status) dengan header hijau, panel kanan untuk
 *     target, parameter lanjutan, dan tombol aksi. Keduanya berdampingan
 *     kiri-kanan, bukan bertumpuk vertikal.
 *  2. Output pemeriksaan ditulis dalam bahasa manusia: target, status
 *     UP/DOWN/WARNING, waktu pengecekan, metrik durasi, dan penjelasan.
 *     Baris kode terminal mentah tidak boleh ikut ditampilkan.
 */
class NetworkToolsPageTest extends TestCase
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

        $this->createSchema('monitoring_logs', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('device_id')->nullable();
            $table->string('ip_address')->nullable();
            $table->string('status')->default('up');
            $table->integer('response_time')->nullable();
            $table->timestamp('checked_at')->nullable();
            $table->timestamps();
        });

        $this->createSchema('device_connections', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('from_device_id')->nullable();
            $table->unsignedBigInteger('to_device_id')->nullable();
            $table->string('status')->default('up');
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

    /* ══ 1 · TATA LETAK DUA PANEL ════════════════════════ */

    public function test_device_list_panel_stands_side_by_side_with_the_action_panel(): void
    {
        $html = $this->toolsPageHtml();

        $radnet = strpos($html, 'id="tierRadnet"');
        $action = strpos($html, 'id="tierLuar"');
        $result = strpos($html, 'id="hasil"');

        $this->assertNotFalse($radnet, 'Panel perangkat harus ada');
        $this->assertNotFalse($action, 'Panel target & tombol aksi harus ada');
        $this->assertNotFalse($result, 'Panel hasil pemeriksaan harus ada');

        // Dua panel utama sharing satu baris grid: kiri daftar, kanan aksi.
        $row = strpos($html, 'class="row g-4"');
        $this->assertNotFalse($row, 'harus ada baris grid untuk dua panel');
        $this->assertLessThan($radnet, $row, 'baris grid opened sebelum panel kiri');

        // Wrapper kolom ada di luar section, jadi dicek posisi relatifnya:
        // kolom sempit membungkus panel kiri, kolom lebar membungkus kanan.
        $leftCol  = strpos($html, 'col-12 col-lg-5');
        $rightCol = strpos($html, 'col-12 col-lg-7');

        $this->assertNotFalse($leftCol, 'panel kiri harus memakai kolom grid');
        $this->assertNotFalse($rightCol, 'panel kanan harus memakai kolom grid');
        $this->assertLessThan($radnet, $leftCol, 'kolom kiri membungkus panel perangkat');
        $this->assertLessThan($leftCol, $row, 'kedua kolom hidup di dalam baris grid');
        $this->assertLessThan($action, $rightCol, 'kolom kanan membungkus panel target');

        // Hasil pemeriksaan sekarang menempel di DALAM kolom kanan, di bawah
        // tombol aksi — jadi alur isi target → tekan tombol → baca hasil tidak
        // lagi melompat ke panel terpisah di luar grid.
        $this->assertGreaterThan(
            $action,
            $result,
            'hasil pemeriksaan harus berada setelah bagian pemeriksaan di kolom kanan'
        );
    }

    public function test_device_picker_lists_every_registered_device_with_status(): void
    {
        $html = $this->toolsPageHtml();

        $start = strpos($html, 'id="devicePicker"');
        $end   = strpos($html, 'id="toolTargetCard"');
        $picker = substr($html, $start, $end - $start);

        // Dropdown pemilih di panel kanan menawarkan seluruh perangkat resmi.
        $deviceCount = preg_match_all('/<option value="\d+">/', $picker);
        $this->assertSame(
            Device::count(),
            $deviceCount,
            'pemilih harus memuat semua perangkat, tidak ada yang ketinggalan'
        );

        // Label per opsi mencantumkan status UP / WARNING / DOWN sekaligus.
        $this->assertMatchesRegularExpression('/(UP|WARNING|DOWN)/', $picker);

        // Pemilihan lewat dropdown memakai jalur yang sama dengan daftar kiri.
        $this->assertStringContainsString('selectDevice(item)', $html);
        $this->assertStringContainsString('devicePicker.addEventListener', $html);
    }

    public function test_right_column_is_three_ordered_blocks_with_no_duplicate_surface(): void
    {
        $html = $this->toolsPageHtml();

        $rightCol = strpos($html, 'class="col-12 col-lg-7 col-xxl-8"');

        // Tiga bagian berurutan: server lokal → target aktif → pemeriksaan.
        $lokal  = strpos($html, 'id="tierLokal"');
        $target = strpos($html, 'id="tierTarget"');
        $action = strpos($html, 'id="tierLuar"');
        $hasil  = strpos($html, 'id="hasil"');

        foreach (['lokal' => $lokal, 'target' => $target, 'action' => $action, 'hasil' => $hasil] as $name => $pos) {
            $this->assertNotFalse($pos, 'bagian '.$name.' harus ada');
            $this->assertGreaterThan($rightCol, $pos, 'bagian '.$name.' harus di kolom kanan');
        }

        $this->assertLessThan($lokal, $rightCol, 'kolom kanan membuka sebelum bagian server lokal');
        $this->assertLessThan($target, $lokal, 'urutan bagian: server lokal → target aktif');
        $this->assertLessThan($action, $target, 'urutan bagian: target aktif → pemeriksaan');
        $this->assertLessThan($hasil, $action, 'hasil pemeriksaan paling bawah');

        // Surface yang dulu terduplikasi (target card, tombol server lokal,
        // tombol aksi, kotak hasil) kini masing-masing hanya satu kali.
        foreach ([
            'id="toolTargetCard"' => 'kartu target',
            'id="toolLocalBtn"' => 'tombol server lokal',
            'id="toolResult"' => 'kotak hasil',
            'id="customTarget"' => 'kolom alamat luar',
        ] as $needle => $label) {
            $this->assertSame(
                1,
                substr_count($html, $needle),
                $label.' harus muncul tepat satu kali, tidak boleh terduplikasi'
            );
        }

        // Section tidak boleh menempel satu sama lain tanpa jarak.
        $this->assertMatchesRegularExpression(
            '/\.tools-section\s*\{[^}]*margin-bottom:\s*\d/',
            $html,
            'setiap bagian harus punya jarak dari bagian sebelumnya'
        );
    }

    public function test_device_panel_start_directly_with_search_and_list_no_green_header(): void
    {
        $html = $this->toolsPageHtml();

        $start = strpos($html, 'id="tierRadnet"');
        $end   = strpos($html, 'id="tierPemeriksaan"');
        $left  = substr($html, $start, $end - $start);

        // Kartu hijau "Perangkat / Router" dihapus total: panel kiri dimulai
        // langsung dari pencarian, lalu filter status, lalu daftar perangkat.
        $this->assertStringContainsString('tools-list-panel', $left);
        $this->assertStringNotContainsString('tools-panel-head', $left);
        $this->assertStringNotContainsString('Perangkat / Router', $left);
        $this->assertStringNotContainsString(
            'Klik salah satu perangkat untuk menjadikannya target diagnosa.',
            $left
        );

        // CSS kepala panel hijau tidak ada lagi di stylesheet.
        $this->assertStringNotContainsString('.tools-list-panel .tools-panel-head', $html);
        $this->assertDoesNotMatchRegularExpression(
            '/\.tools-panel-head[^{]*\{[^}]*linear-gradient\([^)]*0f766e/',
            $html
        );

        // Isi panel utuh, dan pencarian adalah elemen pertama di dalam body.
        $this->assertStringContainsString('id="deviceSearch"', $left);
        $this->assertStringContainsString('Cari nama atau alamat IP', $left);
        $this->assertStringContainsString('id="deviceList"', $left);
        $bodyOpen = strpos($left, 'tools-panel-body');
        $searchPos = strpos($left, 'id="deviceSearch"');
        $this->assertLessThan($searchPos, $bodyOpen, 'pencarian langsung di bawah kepala panel');
    }

    public function test_left_panel_holds_the_full_device_list_with_search_and_status_filter(): void
    {
        $html = $this->toolsPageHtml();

        $start = strpos($html, 'id="tierRadnet"');
        $end   = strpos($html, 'id="tierLuar"');
        $top   = substr($html, $start, $end - $start);

        // Kotak daftar perangkat, pencarian, dan filter status semuanya di kiri.
        $this->assertStringContainsString('id="deviceList"', $top);
        $this->assertStringContainsString('id="deviceSearch"', $top);
        $this->assertStringContainsString('Cari nama atau alamat IP', $top);
        $this->assertStringContainsString('id="statusFilter"', $top);
        $this->assertStringContainsString('class="device-box"', $top);

        foreach (['all', 'up', 'warning', 'down'] as $status) {
            $this->assertStringContainsString('data-status="'.$status.'"', $top);
        }
    }

    public function test_status_filter_buttons_keep_their_meaningful_colours(): void
    {
        $html = $this->toolsPageHtml();

        // Warna filter harus melekat pada tombolnya, bukan hanya saat aktif:
        // semua biru, up hijau, wrn kuning, down merah.
        foreach (['all' => '#0369a1', 'up' => '#15803d', 'warning' => '#a16207', 'down' => '#dc2626'] as $status => $colour) {
            $this->assertMatchesRegularExpression(
                '/\.tool-filter-btn\[data-status="'.$status.'"\]\s*\{[^}]*color:\s*'.$colour.'/',
                $html,
                'filter '.$status.' harus berwarna '.$colour
            );
        }
    }

    public function test_device_rows_show_a_type_matched_icon_name_ip_and_status_badge(): void
    {
        $html = $this->toolsPageHtml();

        $start = strpos($html, 'id="deviceList"');
        $end   = strpos($html, 'id="tierLuar"');
        $list  = substr($html, $start, $end - $start);

        // Ikon di dalam kotak hijau, nama, IP, lalu badge status di kanan.
        // Ikon tidak lagi satu ikon WiFi untuk semua baris: mengikuti tipe
        // perangkat (Router / Switch / EPON / OLT), dipetakan di
        // ToolsController::DEVICE_TYPES.
        $this->assertMatchesRegularExpression(
            '/<span class="device-ic"><em class="icon ni ni-[a-z-]+"><\/em><\/span>/',
            $list,
            'setiap baris harus punya satu ikon dari font ikon tema'
        );

        $this->assertStringContainsString('data-type="ni-share-alt"', $list, 'tipe Router memakai ikon share-alt');
        $this->assertStringContainsString('data-type="ni-network"', $list, 'tipe Switch memakai ikon network');
        $this->assertStringNotContainsString(
            '<em class="icon ni ni-wifi"></em>',
            $list,
            'ikon WiFi generik tidak boleh dipakai untuk semua tipe'
        );

        // Kartu target di panel kanan juga memakai ikon asal target, bukan
        // satu ikon tetap yang tidak pernah berubah.
        $this->assertStringContainsString('id="toolTargetIcon"', $list);
        $this->assertStringContainsString('setTargetIcon(', $html, 'ikon kartu target harus ikut berubah mengikuti target aktif');

        $this->assertStringContainsString('device-name', $list);
        $this->assertStringContainsString('device-ip', $list);
        $this->assertStringContainsString('status-badge', $list);

        // Kotak ikon memakai warna hijau, bukan abu-abu/biru.
        $this->assertMatchesRegularExpression(
            '/\.device-ic\s*\{[^}]*linear-gradient\([^)]*#0f766e/',
            $html
        );
    }

    public function test_right_panel_holds_target_advanced_parameters_and_action_buttons(): void
    {
        $html  = $this->toolsPageHtml();
        $start = strpos($html, 'id="tierLuar"');
        $end   = strpos($html, 'id="hasil"');
        $bottom = substr($html, $start, $end - $start);

        $this->assertStringContainsString('id="customTarget"', $bottom, 'kolom alamat target harus di bawah');
        $this->assertStringContainsString('Pengaturan Pemeriksaan', $bottom, 'parameter lanjutan harus di bawah');

        foreach (['toolCount', 'toolHops', 'toolLookupType', 'toolLookupServer', 'toolFirstPort', 'toolLastPort', 'toolScanTimeout'] as $field) {
            $this->assertStringContainsString('id="'.$field.'"', $bottom, $field.' harus ada di bagian bawah');
        }

        foreach (['ping', 'trace', 'lookup', 'scan'] as $action) {
            $this->assertStringContainsString('data-action="'.$action.'"', $bottom);
        }
    }

    public function test_action_buttons_appear_exactly_once(): void
    {
        $html = $this->toolsPageHtml();

        // Karena target-nya satu (panel kanan), keempat tombol tidak boleh
        // diduplikasi lagi di panel kiri.
        $this->assertSame(1, preg_match_all('/data-action="ping"/', $html));
        $this->assertSame(1, preg_match_all('/data-action="trace"/', $html));
        $this->assertSame(1, preg_match_all('/data-action="lookup"/', $html));
        $this->assertSame(1, preg_match_all('/data-action="scan"/', $html));
    }

    /* ══ 2 · PANDUAN ARTI TOMBOL ═════════════════════════ */

    public function test_action_button_guide_explains_each_button_with_its_colour(): void
    {
        $html = $this->toolsPageHtml();

        foreach (['ping' => 'Hijau', 'trace' => 'Ungu', 'lookup' => 'Biru', 'scan' => 'Merah'] as $action => $colour) {
            $this->assertStringContainsString('tool-guide-color--'.$action, $html, $action.' punya warna sendiri');
            $this->assertStringContainsString($colour, $html, 'warna '.$colour.' harus dijelaskan');
        }

        // Isi panduan sesuai makna yang diminta.
        $this->assertStringContainsString('kecepatan sinyal', $html);
        $this->assertStringContainsString('jalur perjalanan data', $html);
        $this->assertStringContainsString('pencarian DNS', $html);
        $this->assertStringContainsString('port yang terbuka', $html);
    }

    /* ══ 3 · OUTPUT BERSIH, BUKAN KODE TERMINAL ══════════ */

    public function test_page_does_not_print_raw_terminal_code_on_load(): void
    {
        $html = $this->toolsPageHtml();

        // Perintah hanya boleh muncul sebagai teks JavaScript untuk fetch,
        // bukan dicetak ke halaman sebagai isi kotak terminal.
        $this->assertStringNotContainsString('PING 10.', $html);
        $this->assertStringNotContainsString('rtt min/avg/max', $html);
    }

    public function test_ping_result_is_summarised_in_plain_language(): void
    {
        Process::fake([
            '*ping*' => Process::result(exitCode: 0, output: implode("\n", [
                'PING 10.0.0.1 (10.0.0.1) 56(84) bytes of data.',
                '64 bytes from 10.0.0.1: icmp_seq=1 ttl=64 time=4.2 ms',
                '',
                '--- 10.0.0.1 ping statistics ---',
                '5 packets transmitted, 5 received, 0% packet loss, time 4005ms',
                'rtt min/avg/max/mdev = 3.9/4.2/4.5/0.2 ms',
            ])),
            '*command*' => Process::result(exitCode: 0, output: ''),
        ]);

        $response = $this->actingAs($this->user())->postJson(route('admin.tools.ping'), [
            'target' => '10.0.0.1',
            'count'  => 5,
        ]);

        $response->assertOk();

        $payload = $response->json();

        $this->assertTrue($payload['success']);
        $this->assertFalse($payload['network_fault']);
        // Target, waktu, dan durasi dikirim apa adanya supaya halaman bisa
        // merangkumnya jadi kalimat bahasa manusia.
        $this->assertSame('10.0.0.1', $payload['summary']);
        $this->assertMatchesRegularExpression('/^\d{2}:\d{2}:\d{2}$/', $payload['checked_at']);
        $this->assertMatchesRegularExpression('/^\d{2}\/\d{2}\/\d{4}$/', $payload['checked_date']);
        // Perintah dipalsukan, jadi durasi bisa saja 0 ms; yang dijamin
        // adalah tipenya supaya halaman selalu bisa menampilkannya.
        $this->assertIsInt($payload['duration_ms']);
        $this->assertGreaterThanOrEqual(0, $payload['duration_ms']);
        // Output mentah hanya jadi bahan olah di sisi browser, tidak
        // pernah ditampilkan apa adanya.
        $this->assertStringContainsString('0% packet loss', $payload['output']);
    }

    public function test_ping_on_unreachable_host_is_reported_as_network_fault(): void
    {
        Process::fake([
            '*ping*' => Process::result(exitCode: 1, output: implode("\n", [
                'PING 10.0.0.9 (10.0.0.9) 56(84) bytes of data.',
                '',
                '--- 10.0.0.9 ping statistics ---',
                '5 packets transmitted, 0 received, 100% packet loss, time 4005ms',
            ])),
            '*command*' => Process::result(exitCode: 0, output: ''),
        ]);

        $payload = $this->actingAs($this->user())
            ->postJson(route('admin.tools.ping'), ['target' => '10.0.0.9', 'count' => 5])
            ->json();

        // 100% paket hilang harus dibaca sebagai gangguan, bukan error server.
        $this->assertTrue($payload['network_fault']);
        $this->assertSame(1, $payload['exit']);
    }

    public function test_invalid_target_is_rejected_with_readable_message(): void
    {
        $analysis = $this->actingAs($this->user())
            ->postJson(route('admin.tools.ping'), ['target' => 'not a valid target; rm -rf /'])
            ->assertStatus(422)
            ->assertJsonPath('message', 'Target IP / domain tidak valid.')
            ->assertJsonPath('analysis.status', 'down');

        //Alasan harus menyebut APA yang salah, bukan hanya "gagal".
        $this->assertStringContainsString('tidak valid', strtolower($analysis->json('analysis.reason')));
    }

    /* ══ 4 · KOTAK "ANALISIS STATUS / ALASAN" ═════════════
     *
     * Kotak ini adalah wajah "ala WhatsUp Gold": satu kalimat status yang
     * selalu bisa dibaca, berapa pun coerce alat yang dipakai. Yang diuji
     * di sini adalah isi respons JSON-nya, karena itulah yang jadi sumber
     * kebenaran kotak tersebut di browser.
     */

    public function test_ping_up_is_explained_with_stable_response_and_no_loss(): void
    {
        Process::fake([
            '*ping*' => Process::result(exitCode: 0, output: implode("\n", [
                'PING 10.0.0.1 (10.0.0.1) 56(84) bytes of data.',
                '64 bytes from 10.0.0.1: icmp_seq=1 ttl=64 time=4.2 ms',
                '',
                '--- 10.0.0.1 ping statistics ---',
                '5 packets transmitted, 5 received, 0% packet loss, time 4005ms',
                'rtt min/avg/max/mdev = 3.9/4.2/4.5/0.2 ms',
            ])),
            '*command*' => Process::result(exitCode: 0, output: ''),
        ]);

        $analysis = $this->actingAs($this->user())
            ->postJson(route('admin.tools.ping'), ['target' => '10.0.0.1', 'count' => 5])
            ->assertOk()
            ->json('analysis');

        $this->assertSame('up', $analysis['status']);
        $this->assertSame('UP', $analysis['label']);
        // Tanpa packet loss dan latency rendah =answered normal, bukan "sehat
        //sempurna" seperti di WhatsUp Gold: itu tidak diukur oleh ping.
        $this->assertStringContainsString('stabil', strtolower($analysis['reason']));
        $this->assertNotEmpty($analysis['recommendation']);
    }

    public function test_ping_with_high_latency_and_small_loss_is_warning(): void
    {
        Process::fake([
            '*ping*' => Process::result(exitCode: 1, output: implode("\n", [
                'PING 10.0.0.5 (10.0.0.5) 56(84) bytes of data.',
                '64 bytes from 10.0.0.5: icmp_seq=1 ttl=64 time=840 ms',
                '',
                '--- 10.0.0.5 ping statistics ---',
                '5 packets transmitted, 4 received, 20% packet loss, time 4005ms',
                'rtt min/avg/max/mdev = 780/840/910/40.0 ms',
            ])),
            '*command*' => Process::result(exitCode: 0, output: ''),
        ]);

        $analysis = $this->actingAs($this->user())
            ->postJson(route('admin.tools.ping'), ['target' => '10.0.0.5', 'count' => 5])
            ->assertOk()
            ->json('analysis');

        $this->assertSame('warning', $analysis['status']);
        $this->assertSame('WARNING', $analysis['label']);

        $reason = strtolower($analysis['reason']);

        // Alasan harus menyebut penyebabnya, bukan cuma statusnya.
        $this->assertStringContainsString('latency', $reason);
        $this->assertStringContainsString('packet loss', $reason);
        // 840 ms adalah batas "buruk", jadi harus ikut disebut angkanya.
        $this->assertStringContainsString('840', $reason);
    }

    public function test_unreachable_host_is_explained_as_down_not_as_server_error(): void
    {
        Process::fake([
            '*ping*' => Process::result(exitCode: 1, output: implode("\n", [
                'PING 10.0.0.9 (10.0.0.9) 56(84) bytes of data.',
                '',
                '--- 10.0.0.9 ping statistics ---',
                '5 packets transmitted, 0 received, 100% packet loss, time 4005ms',
            ])),
            '*command*' => Process::result(exitCode: 0, output: ''),
        ]);

        $analysis = $this->actingAs($this->user())
            ->postJson(route('admin.tools.ping'), ['target' => '10.0.0.9', 'count' => 5])
            ->assertOk()
            ->json('analysis');

        $this->assertSame('down', $analysis['status']);
        $this->assertSame('DOWN', $analysis['label']);

        $reason = strtolower($analysis['reason']);

        // Tidak merespons harus diterjemahkan ke sebab yang bisa dikerjakan.
        $this->assertStringContainsString('tidak merespons', $reason);
        $this->assertStringContainsString('mati', $reason);
        $this->assertStringContainsString('kabel', $reason);
    }

    public function test_trace_without_replies_is_reported_as_down(): void
    {
        Process::fake($this->fakeTrace(implode("\n", [
            'traceroute to 10.0.0.9 (10.0.0.9), 30 hops max, 60 byte packets',
            ' 1  10.0.0.1  0.4 ms  0.3 ms  0.4 ms',
            ' 2  * * *',
            ' 3  * * *',
        ])));

        $analysis = $this->actingAs($this->user())
            ->postJson(route('admin.tools.trace'), ['target' => '10.0.0.9', 'max_hops' => 5])
            ->assertOk()
            ->json('analysis');

        $this->assertSame('down', $analysis['status']);
        $this->assertStringContainsString('tidak', strtolower($analysis['reason']));
    }

    public function test_successful_trace_is_reported_as_up(): void
    {
        Process::fake($this->fakeTrace(implode("\n", [
            'traceroute to 10.0.0.1 (10.0.0.1), 30 hops max, 60 byte packets',
            ' 1  10.0.0.1  0.4 ms  0.3 ms  0.4 ms',
            ' 2  10.0.0.2  1.2 ms  1.1 ms  1.3 ms',
        ])));

        $this->actingAs($this->user())
            ->postJson(route('admin.tools.trace'), ['target' => '10.0.0.1', 'max_hops' => 5])
            ->assertOk()
            ->assertJsonPath('analysis.status', 'up');
    }

    /**
     * Palsukan perintah trace memakai nama yang BENAR-BENAR dipakai server.
     *
     * ToolsController memakai `traceroute` kalau terpasang, lalu otomatis
     * turun ke `tracepath` kalau tidak. Dua-duanya dipalsukan supaya tes ini
     * tidak bergantung pada paket apa yang kebetulan ada di mesin: kalau
     * tidak, perintah aslinya ikut jalan dan hasilnya ditentukan jaringan
     * mesin, bukan oleh kode yang diuji.
     */
    private function fakeTrace(string $output): array
    {
        return [
            '*traceroute*' => Process::result(exitCode: 0, output: $output),
            '*tracepath*'  => Process::result(exitCode: 0, output: $output),
            '*command*'    => Process::result(exitCode: 0, output: ''),
        ];
    }

    public function test_every_tool_result_carries_an_analysis_and_the_raw_log(): void
    {
        Process::fake([
            '*ping*' => Process::result(exitCode: 0, output: 'ping done'),
            '*traceroute*' => Process::result(exitCode: 0, output: 'traceroute done'),
            '*tracepath*' => Process::result(exitCode: 0, output: 'tracepath done'),
            '*dig*' => Process::result(exitCode: 0, output: 'lookup done'),
            '*nmap*' => Process::result(exitCode: 0, output: 'scan done'),
            '*command*' => Process::result(exitCode: 0, output: ''),
        ]);

        // Satu user untuk seluruh iterasi: alamat email-nya harus unik.
        $user = $this->user();

        // `withLog` menandai tool yang perintahnya pasti jalan, jadi log
        // mentahnya harus ada. Scan tidak diberi tanda itu karena nmap
        // belum tentu terpasang di server; kalau paketnya hilang, memang
        // tidak ada log yang bisa ditampilkan.
        $requests = [
            ['uri' => route('admin.tools.ping'), 'body' => ['target' => '10.0.0.1'], 'withLog' => true],
            ['uri' => route('admin.tools.trace'), 'body' => ['target' => '10.0.0.1'], 'withLog' => true],
            ['uri' => route('admin.tools.lookup'), 'body' => ['target' => '10.0.0.1'], 'withLog' => true],
            ['uri' => route('admin.tools.scan'), 'body' => ['target' => '10.0.0.1', 'port_from' => 80, 'port_to' => 81], 'withLog' => false],
        ];

        foreach ($requests as $case) {
            $uri     = $case['uri'];
            $payload = $this->actingAs($user)->postJson($uri, $case['body'])->assertOk()->json();

            // Alasan wajib ada dan tidak boleh kosong: kolom "Alasan" yang
            // kosong membuat kotak ini jadi tidak berguna.
            $this->assertArrayHasKey('analysis', $payload, $uri);
            $this->assertContains($payload['analysis']['status'], ['up', 'warning', 'down', 'info'], $uri);
            $this->assertNotSame('', trim((string) $payload['analysis']['reason']), $uri);

            // Log mentah tetap dikirim sebagai bukti: dianalisis untuk
            // mengambil kesimpulan, tidak ikut dibuang begitu saja.
            $this->assertArrayHasKey('output', $payload, $uri);

            if ($case['withLog']) {
                $this->assertNotSame('', trim((string) $payload['output']), $uri);
            }
        }
    }

    public function test_page_offers_the_analysis_box_and_a_hidden_raw_log(): void
    {
        $html = $this->toolsPageHtml();

        // Kotak analisis ada, lengkap dengan slot badge status.
        $this->assertStringContainsString('Analisis Status / Alasan', $html);
        $this->assertStringContainsString('analysis-badge--up', $html);
        $this->assertStringContainsString('analysis-badge--warning', $html);
        $this->assertStringContainsString('analysis-badge--down', $html);

        // Log mentah tetap ada untuk dibaca, tapi tidak langsung dibuka:
        // analisis status yang jadi fokus utama, log Terminal jadi bukti.
        // Elemennya dibuat lewat JS, jadi yang diperiksa adalah cara
        // pembuatannya, bukan string HTML yang sudah jadi.
        $this->assertStringContainsString('Log Terminal (mentah)', $html);
        $this->assertStringContainsString("createElement('details')", $html);
        $this->assertStringContainsString('result-log', $html);
        $this->assertStringContainsString('result-log-body', $html);

        // Ambang penilaian dikirim dari server supaya browser tidak punya
        // aturan sendiri yang bisa berbeda dari backend.
        $this->assertStringContainsString('const LIMITS', $html);
        $this->assertStringContainsString('goodLatencyMs', $html);
    }

    /* ══ 5 · TARGET LOKAL DI PALING ATAS PANEL KANAN ════ */

    public function test_local_server_option_sits_above_device_and_external_targets(): void
    {
        $html = $this->toolsPageHtml();

        $local    = strpos($html, 'Server Radnet (Server Lokal)');
        $device   = strpos($html, 'Target dari daftar jaringan');
        $outside  = strpos($html, 'Target dari Luar');

        $this->assertNotFalse($local, 'opsi Server Radnet harus ada');
        $this->assertNotFalse($device, 'kartu target perangkat harus ada');
        $this->assertNotFalse($outside, 'kotak target dari luar harus ada');

        // Alur dari atas (lokal) ke bawah (luar).
        $this->assertLessThan($device, $local, 'Server Radnet harus paling atas');
        $this->assertLessThan($outside, $device, 'target perangkat harus di atas target luar');

        // Tombol lokal harus mencolok: punya kelas khusus + state aktif.
        $this->assertStringContainsString('id="toolLocalBtn"', $html);
        $this->assertStringContainsString('tool-local-option is-active', $html);
        $this->assertStringContainsString('aria-pressed="true"', $html);
    }

    public function test_external_target_form_sits_below_the_local_option(): void
    {
        $html  = $this->toolsPageHtml();
        $start = strpos($html, 'id="toolOutside"');
        $end   = strpos($html, 'data-action="ping"');

        $this->assertNotFalse($start);
        $this->assertNotFalse($end);
        $this->assertLessThan($end, $start, 'form target luar harus sebelum tombol aksi');

        $block = substr($html, $start, $end - $start);

        $this->assertStringContainsString('id="customTarget"', $block);
        $this->assertStringContainsString('IP atau nama host', $block);
        $this->assertStringContainsString('Contoh: 10.0.0.1 atau router.pop-a', $block);
    }

    public function test_device_list_with_search_and_filter_stays_on_the_page(): void
    {
        $html = $this->toolsPageHtml();

        $this->assertStringContainsString('id="deviceList"', $html);
        $this->assertStringContainsString('id="deviceSearch"', $html);
        $this->assertStringContainsString('id="statusFilter"', $html);
        $this->assertStringContainsString('POP SBY', $html);
        $this->assertStringContainsString('10.0.0.1', $html);
    }

    /* ══ 6 · BACKEND MENERIMA TARGET LOKAL ══════════════ */

    public function test_local_target_token_is_resolved_to_loopback(): void
    {
        Process::fake([
            '*ping*' => Process::result(exitCode: 0, output: implode("\n", [
                'PING 127.0.0.1 (127.0.0.1) 56(84) bytes of data.',
                '64 bytes from 127.0.0.1: icmp_seq=1 ttl=64 time=0.0 ms',
                '1 packets transmitted, 1 received, 0% packet loss, time 0ms',
                'rtt min/avg/max/mdev = 0.0/0.0/0.0/0.0 ms',
            ])),
            '*command*' => Process::result(exitCode: 0, output: ''),
        ]);

        $payload = $this->actingAs($this->user())
            ->postJson(route('admin.tools.ping'), ['target' => '@local', 'count' => 1])
            ->assertOk()
            ->json();

        // Token lokal harus diterjemahkan jadi alamat loopback yang sah,
        // bukan lolos mentah-mentah ke perintah.
        $this->assertTrue($payload['success']);
        $this->assertSame('127.0.0.1', $payload['summary']);

        Process::assertRan(function ($process): bool {
            return str_contains(implode(' ', (array) $process->command), '127.0.0.1');
        });
    }

    public function test_local_target_token_is_not_pingable_as_raw_hostname(): void
    {
        // Tanpa penanganan khusus, "@local" akan ditolak validator hostname.
        // Test ini mengunci bahwa penanganannya benar-benar ada.
        $this->assertNull((new \ReflectionClass(\App\Http\Controllers\Admin\ToolsController::class))
            ->getMethod('validateTarget')
            ->invoke(
                new \App\Http\Controllers\Admin\ToolsController,
                '@local'
            ));
    }

    /* ══ Helper ═══════════════════════════════════════════ */

    private function toolsPageHtml(): string
    {
        $this->registerOfficialDevices(['10.0.0.1', '10.0.0.2']);

        Device::create(['name' => 'POP SBY', 'ip_address' => '10.0.0.1', 'status' => 'up', 'type' => 'Router']);
        Device::create(['name' => 'POP SBY 2', 'ip_address' => '10.0.0.2', 'status' => 'down', 'type' => 'Switch']);

        $html = $this->actingAs($this->user())
            ->get(route('admin.tools'))
            ->assertOk()
            ->getContent();

        return $html;
    }

    private function user(): User
    {
        return User::create([
            'name'     => 'Tools Admin',
            'email'    => 'tools-admin@example.test',
            'role'     => 'super_admin',
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
