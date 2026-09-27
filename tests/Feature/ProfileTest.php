<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Tests\Feature\Concerns\InteractsWithDeviceRegistry;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use InteractsWithDeviceRegistry;

    protected function setUp(): void
    {
        parent::setUp();
        $this->treatDeviceTableAsOfficialRegistry();
        Storage::fake('public');

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

        $this->createSchema('device', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('type')->default('Router');
            $table->string('protocol')->default('ICMP');
            $table->string('ip_address');
            $table->string('status')->default('up');
            $table->timestamps();
        });

        // Header memakai view composer notifikasi (badge lonceng).
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

        $this->createSchema('device_notes', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('device_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->datetime('tanggal')->nullable();
            $table->text('catatan');
            $table->string('priority')->default('Normal');
            $table->timestamps();
        });
    }

    private function createSchema(string $table, callable $definition): void
    {
        if (! Schema::hasTable($table)) {
            Schema::create($table, $definition);
        }
    }

    /**
     * Gambar asli berukuran 1x1 piksel (bukan file kosong) supaya validasi
     * "image" + "mimes" benar-benar menguji isi file, bukan nama file.
     * Dibuat tanpa GD karena ekstensi GD tidak terpasang di lingkungan ini.
     */
    private function fakeImage(string $filename = 'foto.png'): UploadedFile
    {
        $images = [
            'png' => 'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mP8z8BQDwAEhQGAhKmMIQAAAABJRU5ErkJggg==',
            'jpg' => '/9j/4AAQSkZJRgABAQEAYABgAAD/2wBDAAgGBgcGBQgHBwcJCQgKDBQNDAsLDBkSEw8UHRofHh0aHBwgJC4nICIsIxwcKDcpLDAxNDQ0Hyc5PTgyPC4zNDL/wAALCAABAAEBAREA/8QAFAABAAAAAAAAAAAAAAAAAAAACf/EABQQAQAAAAAAAAAAAAAAAAAAAAD/2gAIAQEAAD8AKp//2Q==',
            'webp' => 'UklGRiIAAABXRUJQVlA4IBgAAAAwAQCdASoBAAEAAUAmJaQAA3AA/v89WAAAAA==',
        ];

        $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        $extension = $extension === 'jpeg' ? 'jpg' : $extension;
        $content = $images[$extension] ?? $images['png'];

        return UploadedFile::fake()->createWithContent($filename, base64_decode($content, true));
    }

    private function user(string $role = 'staff', string $name = 'Staff Radius'): User
    {
        return User::create([
            'name' => $name,
            'email' => strtolower(str_replace(' ', '.', $name)).'@radnet.test',
            'password' => Hash::make('rahasia123'),
            'role' => $role,
        ]);
    }

    // =========================================================
    // HALAMAN PROFILE
    // =========================================================

    public function test_guest_is_redirected_to_login(): void
    {
        $this->get(route('admin.profile'))
            ->assertRedirect(route('login'));
    }

    public function test_profile_page_renders_authenticated_user_data(): void
    {
        $staff = $this->user('staff', 'Staff Radius');

        $this->actingAs($staff)
            ->get(route('admin.profile'))
            ->assertOk()
            ->assertSee('Staff Radius')
            ->assertSee($staff->email)
            ->assertSee('Staff')
            ->assertSee('Informasi Akun')
            ->assertSee('id="profilePhotoForm"', false)
            ->assertSee('Ganti Foto')
            ->assertSee('Simpan');
    }

    public function test_profile_page_shows_account_and_password_forms(): void
    {
        $staff = $this->user();

        $html = $this->actingAs($staff)
            ->get(route('admin.profile'))
            ->assertOk()
            ->assertSee('Keamanan Akun')
            ->assertSee('Simpan Perubahan')
            ->assertSee('Ubah Password')
            ->getContent();

        $this->assertStringContainsString('id="profileAccountForm"', $html);
        $this->assertStringContainsString('name="name"', $html);
        $this->assertStringContainsString('name="email"', $html);
        $this->assertStringContainsString('name="current_password"', $html);
        $this->assertStringContainsString('name="password"', $html);
        $this->assertStringContainsString('name="password_confirmation"', $html);

        // Password tidak boleh pernah tampil di halaman.
        $this->assertStringNotContainsString($staff->password, $html);
    }

    public function test_profile_form_never_exposes_role_input(): void
    {
        $staff = $this->user();

        $html = $this->actingAs($staff)
            ->get(route('admin.profile'))
            ->assertOk()
            ->getContent();

        $this->assertStringNotContainsString('name="role"', $html);
        $this->assertStringNotContainsString('name="telegram_chat_id"', $html);
    }

    public function test_profile_page_shows_role_label_per_role(): void
    {
        $superAdmin = $this->user('super_admin', 'Super One');

        $this->actingAs($superAdmin)
            ->get(route('admin.profile'))
            ->assertOk()
            ->assertSee('Super Admin');
    }

    public function test_profile_page_uses_default_avatar_when_no_photo(): void
    {
        $staff = $this->user();

        $this->actingAs($staff)
            ->get(route('admin.profile'))
            ->assertOk()
            ->assertSee('Menggunakan avatar bawaan', false)
            ->assertSee('avatar-placeholder.jpg', false);
    }

    public function test_header_links_to_profile_page(): void
    {
        $staff = $this->user();

        $this->actingAs($staff)
            ->get(route('admin.profile'))
            ->assertOk()
            ->assertSee(route('admin.profile'), false);
    }

    // =========================================================
    // GANTI FOTO
    // =========================================================

    public function test_user_can_upload_profile_photo(): void
    {
        $staff = $this->user();

        $this->actingAs($staff)
            ->post(route('admin.profile.photo.update'), [
                'photo' => $this->fakeImage('foto.jpg'),
            ])
            ->assertRedirect(route('admin.profile'))
            ->assertSessionHas('success');

        $staff->refresh();

        $this->assertNotNull($staff->avatar, 'Kolom avatar harus terisi setelah upload');
        $this->assertStringStartsWith('avatars/'.$staff->id.'/', $staff->avatar);
        Storage::disk('public')->assertExists($staff->avatar);
    }

    public function test_uploaded_photo_is_used_by_header_avatar(): void
    {
        $staff = $this->user();

        $this->actingAs($staff)->post(route('admin.profile.photo.update'), [
            'photo' => $this->fakeImage('baru.png'),
        ]);

        $path = $staff->fresh()->avatar;

        $this->actingAs($staff)
            ->get(route('admin.profile'))
            ->assertOk()
            ->assertSee($path, false);
    }

    public function test_replacing_photo_deletes_only_previous_own_file(): void
    {
        $staff = $this->user();
        $other = $this->user('staff', 'Staff Lain');

        // Foto milik user lain: tidak boleh terhapus.
        $otherPath = 'avatars/'.$other->id.'/avatar-other.jpg';
        Storage::disk('public')->put($otherPath, 'lain');
        $other->forceFill(['avatar' => $otherPath])->save();

        $this->actingAs($staff)->post(route('admin.profile.photo.update'), [
            'photo' => $this->fakeImage('satu.jpg'),
        ]);
        $firstPath = $staff->fresh()->avatar;

        $this->actingAs($staff)->post(route('admin.profile.photo.update'), [
            'photo' => $this->fakeImage('dua.jpg'),
        ]);
        $secondPath = $staff->fresh()->avatar;

        $this->assertNotSame($firstPath, $secondPath);
        Storage::disk('public')->assertMissing($firstPath);
        Storage::disk('public')->assertExists($secondPath);
        Storage::disk('public')->assertExists($otherPath);
    }

    public function test_upload_rejects_non_image_file(): void
    {
        $staff = $this->user();

        $this->actingAs($staff)
            ->post(route('admin.profile.photo.update'), [
                'photo' => UploadedFile::fake()->create('virus.exe', 40, 'application/x-msdownload'),
            ])
            ->assertSessionHasErrors('photo');

        $this->assertNull($staff->fresh()->avatar);
    }

    public function test_upload_rejects_oversized_file(): void
    {
        $staff = $this->user();

        $this->actingAs($staff)
            ->post(route('admin.profile.photo.update'), [
                'photo' => UploadedFile::fake()->create('besar.jpg', 4096, 'image/jpeg'),
            ])
            ->assertSessionHasErrors('photo');

        $this->assertNull($staff->fresh()->avatar);
    }

    public function test_upload_requires_photo(): void
    {
        $staff = $this->user();

        $this->actingAs($staff)
            ->post(route('admin.profile.photo.update'), [])
            ->assertSessionHasErrors('photo');
    }

    public function test_upload_does_not_touch_other_user_avatar(): void
    {
        $staff = $this->user();
        $other = $this->user('staff', 'Staff Lain');

        $otherPath = 'avatars/'.$other->id.'/tetap.jpg';
        Storage::disk('public')->put($otherPath, 'tetap');
        $other->forceFill(['avatar' => $otherPath])->save();

        $this->actingAs($staff)->post(route('admin.profile.photo.update'), [
            'photo' => $this->fakeImage('saya.jpg'),
        ])->assertRedirect(route('admin.profile'));

        $this->assertSame($otherPath, $other->fresh()->avatar);
    }

    public function test_staff_and_super_admin_both_can_upload_own_photo(): void
    {
        foreach (['staff', 'super_admin'] as $index => $role) {
            $user = $this->user($role, 'User'.$index);

            $this->actingAs($user)->post(route('admin.profile.photo.update'), [
                'photo' => $this->fakeImage('foto'.$index.'.jpg'),
            ])->assertRedirect(route('admin.profile'));

            $this->assertNotNull($user->fresh()->avatar);
        }
    }

    // =========================================================
    // EDIT INFORMASI AKUN (NAMA + EMAIL)
    // =========================================================

    public function test_guest_cannot_update_account(): void
    {
        $this->patch(route('admin.profile.update'), [
            'name' => 'Intruder',
            'email' => 'intruder@radnet.test',
        ])->assertRedirect(route('login'));
    }

    public function test_user_can_update_own_name_and_email(): void
    {
        $staff = $this->user('staff', 'Nama Lama');

        $this->actingAs($staff)
            ->patch(route('admin.profile.update'), [
                'name' => 'Nama Baru',
                'email' => 'nama.baru@radnet.test',
            ])
            ->assertRedirect(route('admin.profile'))
            ->assertSessionHas('success');

        $staff->refresh();

        $this->assertSame('Nama Baru', $staff->name);
        $this->assertSame('nama.baru@radnet.test', $staff->email);
    }

    public function test_updated_account_data_is_visible_after_save(): void
    {
        $staff = $this->user('staff', 'Nama Lama');

        $this->actingAs($staff)->patch(route('admin.profile.update'), [
            'name' => 'Nama Terbaru',
            'email' => 'terbaru@radnet.test',
        ])->assertRedirect(route('admin.profile'));

        $this->actingAs($staff)
            ->get(route('admin.profile'))
            ->assertOk()
            ->assertSee('Nama Terbaru')
            ->assertSee('terbaru@radnet.test');
    }

    public function test_duplicate_email_is_rejected(): void
    {
        $staff = $this->user('staff', 'Staff Radius');
        $other = $this->user('staff', 'Staff Lain');

        $this->actingAs($staff)
            ->patch(route('admin.profile.update'), [
                'name' => 'Nama Baru',
                'email' => $other->email,
            ])
            ->assertSessionHasErrors('email');

        $this->assertSame($staff->email, $staff->fresh()->email, 'Email tidak boleh berubah jika validasi gagal');
    }

    public function test_same_email_is_allowed_for_own_account(): void
    {
        $staff = $this->user('staff', 'Staff Radius');

        $this->actingAs($staff)
            ->patch(route('admin.profile.update'), [
                'name' => 'Nama Baru',
                'email' => $staff->email,
            ])
            ->assertRedirect(route('admin.profile')
            )->assertSessionHasNoErrors();

        $this->assertSame('Nama Baru', $staff->fresh()->name);
    }

    public function test_invalid_name_and_email_are_rejected(): void
    {
        $staff = $this->user();
        $original = $staff->only(['name', 'email']);

        $this->actingAs($staff)
            ->patch(route('admin.profile.update'), ['name' => 'ab', 'email' => 'bukan-email'])
            ->assertSessionHasErrors(['name', 'email']);

        $this->assertSame($original, $staff->fresh()->only(['name', 'email']));
    }

    public function test_role_and_other_protected_fields_cannot_be_changed(): void
    {
        $staff = $this->user('staff', 'Staff Radius');

        $this->actingAs($staff)
            ->patch(route('admin.profile.update'), [
                'name' => 'Nama Baru',
                'email' => 'baru@radnet.test',
                'role' => 'super_admin',
                'telegram_chat_id' => '999999',
                'password' => 'dipaksa-ini',
            ])
            ->assertRedirect(route('admin.profile'));

        $staff->refresh();

        $this->assertSame('staff', $staff->role, 'Role tidak boleh diubah dari profile');
        $this->assertNull($staff->telegram_chat_id, 'Status Telegram tidak boleh diubah dari profile');
        $this->assertTrue(Hash::check('rahasia123', $staff->password), 'Password tidak boleh diubah lewat form akun');
    }

    public function test_updating_account_does_not_touch_other_users(): void
    {
        $staff = $this->user('staff', 'Staff Radius');
        $other = $this->user('super_admin', 'Super One');
        $otherBefore = $other->only(['name', 'email', 'role', 'telegram_chat_id']);

        $this->actingAs($staff)->patch(route('admin.profile.update'), [
            'name' => 'Staff Baru',
            'email' => 'staff.baru@radnet.test',
            'user_id' => $other->id,
        ])->assertRedirect(route('admin.profile'));

        $this->assertSame($otherBefore, $other->fresh()->only(['name', 'email', 'role', 'telegram_chat_id']));
    }

    // =========================================================
    // KEAMANAN AKUN: GANTI PASSWORD
    // =========================================================

    public function test_guest_cannot_change_password(): void
    {
        $this->patch(route('admin.profile.password.update'), [
            'current_password' => 'rahasia123',
            'password' => 'barubaru123',
            'password_confirmation' => 'barubaru123',
        ])->assertRedirect(route('login'));
    }

    public function test_user_can_change_own_password(): void
    {
        $staff = $this->user();
        $oldHash = $staff->password;

        $this->actingAs($staff)
            ->patch(route('admin.profile.password.update'), [
                'current_password' => 'rahasia123',
                'password' => 'passwordbaru123',
                'password_confirmation' => 'passwordbaru123',
            ])
            ->assertRedirect(route('admin.profile'))
            ->assertSessionHas('success');

        $staff->refresh();

        $this->assertNotSame($oldHash, $staff->password);
        $this->assertTrue(Hash::check('passwordbaru123', $staff->password), 'Password baru harus di-hash');
        $this->assertNotSame('passwordbaru123', $staff->password, 'Password tidak boleh disimpan polos');
    }

    public function test_wrong_current_password_is_rejected(): void
    {
        $staff = $this->user();
        $oldHash = $staff->password;

        $this->actingAs($staff)
            ->patch(route('admin.profile.password.update'), [
                'current_password' => 'salah-sekali',
                'password' => 'passwordbaru123',
                'password_confirmation' => 'passwordbaru123',
            ])
            ->assertSessionHasErrors('current_password');

        $this->assertSame($oldHash, $staff->fresh()->password);
    }

    public function test_password_confirmation_must_match(): void
    {
        $staff = $this->user();
        $oldHash = $staff->password;

        $this->actingAs($staff)
            ->patch(route('admin.profile.password.update'), [
                'current_password' => 'rahasia123',
                'password' => 'passwordbaru123',
                'password_confirmation' => 'passwordlain123',
            ])
            ->assertSessionHasErrors('password');

        $this->assertSame($oldHash, $staff->fresh()->password);
    }

    public function test_short_new_password_is_rejected(): void
    {
        $staff = $this->user();
        $oldHash = $staff->password;

        $this->actingAs($staff)
            ->patch(route('admin.profile.password.update'), [
                'current_password' => 'rahasia123',
                'password' => 'pendek',
                'password_confirmation' => 'pendek',
            ])
            ->assertSessionHasErrors('password');

        $this->assertSame($oldHash, $staff->fresh()->password);
    }

    public function test_changing_password_does_not_affect_other_user(): void
    {
        $staff = $this->user();
        $other = $this->user('staff', 'Staff Lain');
        $otherHash = $other->password;

        $this->actingAs($staff)->patch(route('admin.profile.password.update'), [
            'current_password' => 'rahasia123',
            'password' => 'passwordbaru123',
            'password_confirmation' => 'passwordbaru123',
        ])->assertRedirect(route('admin.profile'));

        $this->assertSame($otherHash, $other->fresh()->password);
    }
}
