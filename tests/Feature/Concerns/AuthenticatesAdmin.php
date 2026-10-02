<?php

namespace Tests\Feature\Concerns;

use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Seluruh route admin kini berada di balik middleware `auth` (lihat
 * routes/web.php). Trait ini menyediakan user super_admin untuk test yang
 * menguji halaman admin tetapi tidak peduli dengan detail autentikasi.
 *
 * Tabel `users` hanya dibuat bila BELUM ada — sebagian test membangun
 * schema-nya sendiri (mis. TelegramUserManagementTest) dan tidak boleh
 * ditimpa. Role `super_admin` dipakai supaya test bisa menyentuh semua
 * halaman, termasuk yang ber-middleware role:super_admin.
 */
trait AuthenticatesAdmin
{
    protected User $adminUser;

    protected function authenticateAsAdmin(): User
    {
        if (! Schema::hasTable('users')) {
            Schema::create('users', function (Blueprint $table): void {
                $table->id();
                $table->string('name');
                $table->string('email')->unique();
                $table->timestamp('email_verified_at')->nullable();
                $table->string('password');
                $table->string('role')->default('staff');
                $table->string('telegram_chat_id', 64)->nullable();
                $table->rememberToken();
                $table->timestamps();
            });
        }

        $this->adminUser = User::create([
            'name'     => 'Test Admin',
            'email'    => 'admin-'.uniqid().'@example.test',
            'password' => 'password',
            'role'     => 'super_admin',
        ]);

        $this->actingAs($this->adminUser);

        return $this->adminUser;
    }
}