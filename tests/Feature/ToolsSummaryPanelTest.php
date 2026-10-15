<?php

namespace Tests\Feature;

use App\Models\Device;
use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Tests\Feature\Concerns\InteractsWithDeviceRegistry;
use Tests\TestCase;

/**
 * Bagian atas halaman Network Tools.
 *
 * Dulu halaman ini punya strip ringkasan kecil berisi empat kartu KPI
 * (total / up / down / warning), tabel per Area / POP, dan badge jumlah
 * perangkat di header panel. Semuanya dihapus: angkanya hanya mengulang
 * apa yang sudah terlihat di daftar perangkat dan di badge status tiap
 * baris, jadi Statistics tidak menambah informasi apa pun.
 *
 * Test di sini mengunci keputusan itu, supaya strip/badanya tidak diam-diam
 * muncul lagi, sekaligus menjaga dua hal yang tidak boleh ikut hilang:
 *
 *  1. Jumlah perangkat di daftar tetap jujur dan dihitung dari data, bukan
 *     placeholder nol.
 *  2. Halaman tetap utuh saat daftar perangkat kosong.
 */
class ToolsSummaryPanelTest extends TestCase
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
            $table->string('pop_name')->nullable();
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

    /* ══ 1 · STRIP & BADGE RINGKASAN HARUS TIDAK ADA ═══ */

    public function test_summary_strip_and_kpi_cards_are_removed(): void
    {
        $this->seedDevices([
            ['A-1', '10.0.0.1', 'up', 'POP A'],
            ['A-2', '10.0.0.2', 'up', 'POP A'],
            ['A-3', '10.0.0.3', 'down', 'POP A'],
            ['B-1', '10.0.0.4', 'up', 'POP B'],
            ['B-2', '10.0.0.5', 'warning', 'POP B'],
            ['C-1', '10.0.0.6', 'up', null],
        ]);

        $html = $this->toolsPageHtml();

        // Tidak ada lagi markup maupun CSS dari panel ringkasan.
        $this->assertStringNotContainsString('id="ringkasan"', $html);
        $this->assertStringNotContainsString('sum-strip', $html);
        $this->assertStringNotContainsString('sum-kpi', $html);
        $this->assertStringNotContainsString('sum-area', $html);

        // Badge jumlah perangkat di header panel juga dihapus, dan tidak
        // ada lagi elemen yang harus disembunyikan dari JavaScript.
        $this->assertStringNotContainsString('radnetCount', $html);
    }

    /* ══ 2 · DAFTAR PERANGKAT TETAP JUJUR ════════════════ */

    public function test_device_count_follows_the_official_registry(): void
    {
        // Enam perangkat di daftar resmi, tidak semuanya sehat: daftar
        // perangkat harus tetap menampilkan enam, bukan hanya yang UP.
        $this->seedDevices([
            ['A-1', '10.0.0.1', 'up', 'POP A'],
            ['A-2', '10.0.0.2', 'up', 'POP A'],
            ['A-3', '10.0.0.3', 'down', 'POP A'],
            ['B-1', '10.0.0.4', 'up', 'POP B'],
            ['B-2', '10.0.0.5', 'warning', 'POP B'],
            ['C-1', '10.0.0.6', 'up', null],
        ]);

        $html = $this->toolsPageHtml();

        // Satu-satunya angka total yang boleh tampil adalah jumlah baris di
        // daftar — dan itu dihitung server, bukan diketik di view.
        $this->assertSame(
            1,
            preg_match('/id="deviceCountHint"[^>]*>\s*6 perangkat/s', $html),
            'jumlah perangkat harus dibaca dari daftar resmi'
        );

        // Setiap status punya baris sendiri dengan badge-nya. Dihitung dari
        // isi daftar saja: tombol filter di atas juga memakai data-status,
        // jadi seluruh halaman tidak bisa dipakai sebagai hitungan.
        $list = $this->deviceListHtml($html);

        foreach (['up' => 4, 'warning' => 1, 'down' => 1] as $status => $expected) {
            $this->assertSame(
                $expected,
                preg_match_all('/data-status="'.$status.'"/', $list),
                'harus ada '.$expected.' baris berstatus '.$status
            );
        }
    }

    public function test_empty_device_list_renders_without_error(): void
    {
        $html = $this->toolsPageHtml();

        $this->assertStringContainsString('Belum ada perangkat terdaftar', $html);
        $this->assertMatchesRegularExpression('/id="deviceCountHint"[^>]*>\s*0 perangkat/s', $html);

        // Panel sisanya tetap ada, jadi halaman tidak setengah jadi.
        $this->assertStringContainsString('id="tierRadnet"', $html);
        $this->assertStringContainsString('id="tierLuar"', $html);
        $this->assertStringContainsString('id="hasil"', $html);
    }

    public function test_latency_thresholds_are_sent_from_the_backend(): void
    {
        $html = $this->toolsPageHtml();

        // Ambang harus datang dari server supaya browser tidak punya aturan
        // sendiri yang bisa berbeda dari DiagnosisAnalyzer.
        $this->assertStringContainsString('const LIMITS', $html);
        $this->assertMatchesRegularExpression('/goodLatencyMs"?\s*:\s*100(?:\.0+)?/', $html);
        $this->assertMatchesRegularExpression('/poorLatencyMs"?\s*:\s*300(?:\.0+)?/', $html);
    }

    /* ── helper ───────────────────────────────────────────── */

    /** Isi kotak daftar perangkat saja, tanpa panel di atasnya. */
    private function deviceListHtml(string $html): string
    {
        $start = strpos($html, 'id="deviceList"');
        $end   = strpos($html, 'id="tierLuar"');

        $this->assertNotFalse($start, 'kotak daftar perangkat harus ada');
        $this->assertNotFalse($end, 'panel pemeriksaan harus ada');

        return substr($html, $start, $end - $start);
    }

    private function toolsPageHtml(): string
    {
        return $this->actingAs($this->superAdmin())
            ->get(route('admin.tools'))
            ->assertOk()
            ->getContent();
    }

    /** @param  array<int, array{0:string,1:string,2:string,3:?string}>  $rows */
    private function seedDevices(array $rows): void
    {
        foreach ($rows as [$name, $ip, $status, $pop]) {
            Device::create([
                'name' => $name,
                'ip_address' => $ip,
                'status' => $status,
                'pop_name' => $pop,
                'avg_delay' => 0,
                'missed_percentage' => 0,
            ]);
        }
    }

    private function superAdmin(): User
    {
        $user = new User;
        $user->forceFill([
            'name' => 'Super Admin Uji',
            'email' => 'super@contoh.test',
            'password' => bcrypt('password'),
            'role' => 'super_admin',
        ]);

        return $user;
    }

    private function createSchema(string $table, callable $definition): void
    {
        if (! Schema::hasTable($table)) {
            Schema::create($table, $definition);
        }
    }
}
