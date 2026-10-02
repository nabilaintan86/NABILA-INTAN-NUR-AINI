<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Tests\Feature\Concerns\CreatesDeviceTables;
use Tests\Feature\Concerns\CreatesNetworkAreaTables;
use Tests\TestCase;

/**
 * Titik masuk utama aplikasi.
 *
 * Aplikasi ini tidak punya halaman publik — semua layar berada di /admin.
 * Test ini menjaga agar mengetik domain monitoring ini tidak berakhir di 404
 * dan tidak membocorkan halaman admin ke pengunjung yang belum login.
 */
class ExampleTest extends TestCase
{
    /* Root meneruskan ke dashboard, yang membaca tabel perangkat — jadi
       skema minimalnya ikut disiapkan di sini. */
    use CreatesDeviceTables;
    use CreatesNetworkAreaTables;

    protected function setUp(): void
    {
        parent::setUp();

        $this->createDeviceTables();
        $this->createNetworkAreaTables();

        if (! Schema::hasTable('users')) {
            Schema::create('users', function (Blueprint $table): void {
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
        }
    }

    public function test_root_redirects_to_the_dashboard(): void
    {
        $this->get('/')->assertRedirect(route('admin.dashboard'));
    }

    public function test_root_does_not_expose_admin_pages_to_guests(): void
    {
        // Pengunjung yang belum login harus sampai di halaman login, bukan
        // di dashboard — maupun di 404.
        $this->get('/')->assertRedirect(route('admin.dashboard'));
        $this->get(route('admin.dashboard'))->assertRedirect(route('login'));
    }

    public function test_signed_in_user_reaches_the_dashboard_from_the_root(): void
    {
        $user = User::create([
            'name'     => 'Root Admin',
            'email'    => 'root@example.test',
            'role'     => 'super_admin',
            'password' => bcrypt('password'),
        ]);

        $this->actingAs($user)
            ->get('/')
            ->assertRedirect(route('admin.dashboard'));
    }
}
