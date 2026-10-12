<?php

namespace Tests\Feature;

use App\Models\Device;
use App\Models\Note;
use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Tests\Feature\Concerns\InteractsWithDeviceRegistry;
use Tests\TestCase;

class NoteAuthorTest extends TestCase
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
            $table->string('telegram_chat_id')->nullable()->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->enum('role', ['super_admin', 'staff'])->default('staff');
            $table->string('avatar')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        $this->createSchema('devices', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('type')->default('Router');
            $table->string('protocol')->default('ICMP');
            $table->string('ip_address');
            $table->string('status')->default('up');
            $table->timestamps();
        });

        $this->createSchema('device_notes', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('device_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->datetime('tanggal')->nullable();
            $table->text('catatan');
            $table->string('priority')->default('Normal');
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

    private function createSchema(string $table, callable $definition): void
    {
        if (! Schema::hasTable($table)) {
            Schema::create($table, $definition);
        }
    }

    private function user(string $role, string $name): User
    {
        return User::create([
            'name' => $name,
            'email' => strtolower(str_replace(' ', '.', $name)).'@radnet.test',
            'password' => Hash::make('rahasia123'),
            'role' => $role,
        ]);
    }

    private function device(): Device
    {
        return Device::create([
            'name' => 'POP Gresik',
            'type' => 'Router',
            'protocol' => 'ICMP',
            'ip_address' => '10.20.30.40',
            'status' => 'up',
        ]);
    }

    private function createNotePayload(array $overrides = []): array
    {
        return array_merge([
            'tanggal' => '2026-09-27T18:30',
            'catatan' => 'Router POP Gresik sudah dilakukan pengecekan.',
            'priority' => 'Normal',
        ], $overrides);
    }

    // =========================================================
    // CATATAN OTOMATIS TERISI PEMBUAT
    // =========================================================

    public function test_created_note_stores_authenticated_user_as_author(): void
    {
        $staff = $this->user('staff', 'Staff Radius');
        $device = $this->device();

        $this->actingAs($staff)->post(route('admin.device.notes.store', $device), $this->createNotePayload())
            ->assertRedirect(route('admin.device.detail', $device));

        $note = Note::query()->sole();

        $this->assertSame($staff->id, $note->user_id, 'user_id harus ambil dari session auth');
        $this->assertSame('Staff Radius', $note->user->name);
    }

    public function test_note_created_from_report_stores_authenticated_user_as_author(): void
    {
        $superAdmin = $this->user('super_admin', 'Super One');
        $device = $this->device();

        $this->actingAs($superAdmin)->post(route('admin.device.notes.store-report'), $this->createNotePayload([
            'device_id' => $device->id,
        ]))->assertRedirect(route('admin.device.notes.report'));

        $this->assertSame($superAdmin->id, Note::query()->sole()->user_id);
    }

    public function test_author_is_never_taken_from_request_input(): void
    {
        $staff = $this->user('staff', 'Staff Radius');
        $other = $this->user('staff', 'Staff Lain');
        $device = $this->device();

        $this->actingAs($staff)->post(route('admin.device.notes.store', $device), $this->createNotePayload([
            'user_id' => $other->id,
            'created_by' => $other->id,
            'author_id' => $other->id,
        ]))->assertRedirect(route('admin.device.detail', $device));

        $this->assertSame($staff->id, Note::query()->sole()->user_id, 'Identitas pembuat harus dari auth, bukan input');
    }

    public function test_editing_note_keeps_original_author(): void
    {
        $author = $this->user('staff', 'Staff Radius');
        $editor = $this->user('super_admin', 'Super One');
        $device = $this->device();

        $note = Note::create([
            'device_id' => $device->id,
            'user_id' => $author->id,
            'tanggal' => '2026-09-27 18:30:00',
            'catatan' => 'Catatan asli.',
            'priority' => 'Normal',
        ]);

        $this->actingAs($editor)
            ->from(route('admin.device.detail', $device))
            ->put(route('admin.device.notes.update', $note), [
                'tanggal' => '2026-09-27 19:00',
                'catatan' => 'Catatan sudah diperbarui.',
                'priority' => 'Urgent',
            ])->assertRedirect(route('admin.device.detail', $device));

        $note->refresh();

        $this->assertSame($author->id, $note->user_id, '"diedit oleh" tidak boleh menimpa "dibuat oleh"');
        $this->assertSame('Catatan sudah diperbarui.', $note->catatan);
    }

    public function test_edited_at_is_stored_and_shown(): void
    {
        $author = $this->user('staff', 'Staff Radius');
        $device = $this->device();

        $note = Note::create([
            'device_id' => $device->id,
            'user_id' => $author->id,
            'tanggal' => '2026-09-27 18:30:00',
            'catatan' => 'Catatan asli.',
            'priority' => 'Normal',
        ]);

        $original = $note->updated_at;

        $this->actingAs($author)->put(route('admin.device.notes.update', $note), [
            'tanggal' => '2026-09-27 18:30',
            'catatan' => 'Catatan diperbarui oleh pemiliknya.',
            'priority' => 'Normal',
        ]);

        $this->assertTrue($note->fresh()->updated_at->greaterThanOrEqualTo($original));
    }

    // =========================================================
    // LAPORAN: PEMBUAT + ROLE
    // =========================================================

    public function test_report_shows_author_and_role_columns(): void
    {
        $author = $this->user('staff', 'Melisa Febriana');
        $viewer = $this->user('staff', 'Staff Radar');
        $device = $this->device();

        Note::create([
            'device_id' => $device->id,
            'user_id' => $author->id,
            'tanggal' => '2026-09-27 18:30:00',
            'catatan' => 'Router POP Gresik sudah dilakukan pengecekan.',
            'priority' => 'Normal',
        ]);

        $this->actingAs($viewer)
            ->get(route('admin.device.notes.report'))
            ->assertOk()
            ->assertSee('Pembuat')
            ->assertSee('Role')
            ->assertSee('Melisa Febriana')
            ->assertSee('Staff')
            ->assertSee('Router POP Gresik sudah dilakukan pengecekan.')
            ->assertSee('27 Sep 2026, 18:30');
    }

    public function test_report_keeps_showing_original_author_to_other_users(): void
    {
        $authorA = $this->user('staff', 'Melisa Febriana');
        $userB = $this->user('staff', 'Budi Santoso');
        $device = $this->device();

        Note::create([
            'device_id' => $device->id,
            'user_id' => $authorA->id,
            'tanggal' => '2026-09-27 18:30:00',
            'catatan' => 'Catatan milik User A.',
            'priority' => 'Normal',
        ]);

        $html = $this->actingAs($userB)
            ->get(route('admin.device.notes.report'))
            ->assertOk()
            ->getContent();

        // Baris catatan tetap milik User A meski yang membuka halaman adalah User B.
        $row = $this->noteRow($html, 'Catatan milik User A.');

        $this->assertStringContainsString('Melisa Febriana', $row, 'Pembuat asli harus tetap tampil');
        $this->assertStringNotContainsString('Budi Santoso', $row, 'Nama user yang membuka halaman tidak boleh menggantikan pembuat');
    }

    public function test_report_author_row_is_actually_shown_not_just_filtered_out(): void
    {
        $authorA = $this->user('staff', 'Melisa Febriana');
        $userB = $this->user('staff', 'Budi Santoso');
        $device = $this->device();

        Note::create([
            'device_id' => $device->id,
            'user_id' => $authorA->id,
            'tanggal' => '2026-09-27 18:30:00',
            'catatan' => 'Catatan milik User A.',
            'priority' => 'Normal',
        ]);

        $html = $this->actingAs($userB)->get(route('admin.device.notes.report'))->assertOk()->getContent();

        $this->assertStringContainsString('Melisa Febriana', $html);
        $this->assertMatchesRegularExpression(
            '/<th[^>]*>\s*Pembuat\s*<\/th>/',
            $html,
            'Kolom PEMBUAT harus ada di header tabel'
        );
        $this->assertMatchesRegularExpression(
            '/<th[^>]*>\s*Role\s*<\/th>/',
            $html,
            'Kolom ROLE harus ada di header tabel'
        );
    }

    public function test_report_does_not_run_n_plus_one_query_per_note(): void
    {
        $author = $this->user('staff', 'Melisa Febriana');
        $viewer = $this->user('staff', 'Budi Santoso');
        $device = $this->device();

        Note::create([
            'device_id' => $device->id,
            'user_id' => $author->id,
            'tanggal' => now(),
            'catatan' => 'Catatan tunggal.',
            'priority' => 'Normal',
        ]);

        $withOneNote = $this->countNoteQueries($viewer);

        foreach (range(1, 6) as $i) {
            Note::create([
                'device_id' => $device->id,
                'user_id' => $author->id,
                'tanggal' => now()->subMinutes($i),
                'catatan' => 'Catatan tambahan '.$i,
                'priority' => 'Normal',
            ]);
        }

        $withSevenNotes = $this->countNoteQueries($viewer);

        $this->assertSame(
            $withOneNote,
            $withSevenNotes,
            'Jumlah query tidak boleh bertambah saat jumlah catatan bertambah (indikasi N+1). '.
            '1 catatan: '.$withOneNote.' | 7 catatan: '.$withSevenNotes
        );
    }

    /**
     * Hitung query yang menyentuh device_notes/users saat membuka halaman report.
     */
    private function countNoteQueries(User $viewer): int
    {
        $queries = [];
        DB::listen(function ($query) use (&$queries): void {
            $queries[] = $query->sql;
        });

        $this->actingAs($viewer)
            ->get(route('admin.device.notes.report'))
            ->assertOk();

        return count(array_filter(
            $queries,
            fn (string $sql): bool => str_contains($sql, 'device_notes') || str_contains($sql, 'from "users"')
        ));
    }

    // =========================================================
    // CATATAN LAMA TANPA PEMBUAT
    // =========================================================

    public function test_legacy_note_without_author_shows_clear_fallback(): void
    {
        $viewer = $this->user('super_admin', 'Super One');
        $device = $this->device();

        Note::create([
            'device_id' => $device->id,
            'user_id' => null,
            'tanggal' => '2026-09-20 10:00:00',
            'catatan' => 'Catatan lama sistem.',
            'priority' => 'Normal',
        ]);

        $html = $this->actingAs($viewer)
            ->get(route('admin.device.notes.report'))
            ->assertOk()
            ->assertSee('Catatan lama sistem.')
            ->assertSee('User tidak ditemukan')
            ->assertSee('Catatan lama')
            ->getContent();

        $this->assertStringNotContainsString('System', $html, 'Jangan mengarang nama pembuat');
    }

    public function test_legacy_note_fallback_does_not_invent_any_name(): void
    {
        $viewer = $this->user('super_admin', 'Super One');
        $device = $this->device();

        Note::create([
            'device_id' => $device->id,
            'user_id' => null,
            'tanggal' => '2026-09-20 10:00:00',
            'catatan' => 'Catatan tanpa Pembuat.',
            'priority' => 'Normal',
        ]);

        $html = $this->actingAs($viewer)
            ->get(route('admin.device.notes.report'))
            ->assertOk()
            ->getContent();

        // Baris catatan lama harus memakai label fallback, bukan nama siapa pun.
        $row = $this->noteRow($html, 'Catatan tanpa Pembuat.');

        $this->assertStringContainsString('User tidak ditemukan', $row);
        $this->assertStringContainsString('Catatan lama', $row);
        $this->assertStringNotContainsString('Super One', $row, 'Nama user lain tidak boleh masuk ke baris catatan lama');
        $this->assertStringNotContainsString('Budi Santoso', $row);
    }

    /**
     * Ambil satu baris <tr> tabel catatan berdasarkan isi catatannya.
     */
    private function noteRow(string $html, string $needle): string
    {
        preg_match_all('/<tr\b.*?<\/tr>/s', $html, $matches);

        foreach ($matches[0] as $row) {
            if (str_contains($row, e($needle))) {
                return $row;
            }
        }

        $this->fail('Baris catatan "'.$needle.'" tidak ditemukan di halaman report.');
    }

    public function test_device_detail_also_shows_author_and_fallback(): void
    {
        $author = $this->user('staff', 'Melisa Febriana');
        $viewer = $this->user('staff', 'Budi Santoso');
        $device = $this->device();

        Note::create([
            'device_id' => $device->id,
            'user_id' => $author->id,
            'tanggal' => '2026-09-27 18:30:00',
            'catatan' => 'Catatan di detail perangkat.',
            'priority' => 'Normal',
        ]);

        Note::create([
            'device_id' => $device->id,
            'user_id' => null,
            'tanggal' => '2026-09-20 10:00:00',
            'catatan' => 'Catatan lama di detail.',
            'priority' => 'Normal',
        ]);

        $this->actingAs($viewer)
            ->get(route('admin.device.detail', $device))
            ->assertOk()
            ->assertSee('Melisa Febriana')
            ->assertSee('Staff')
            ->assertSee('Catatan lama di detail.')
            ->assertSee('User tidak ditemukan');
    }
}
