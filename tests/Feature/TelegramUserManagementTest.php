<?php

namespace Tests\Feature;

use App\Models\TelegramUser;
use App\Models\User;
use App\Services\TelegramService;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class TelegramUserManagementTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        config()->set('services.telegram.bot_token', '123456:FAKE_TEST_TOKEN');
        config()->set('services.telegram.chat_id', '');
        config()->set('services.telegram.webhook_secret', 'webhook-secret');
        config()->set('cache.default', 'array');
        Cache::clear();

        $this->createSchema('telegram_users', function (Blueprint $table): void {
            $table->id();
            $table->string('chat_id', 64)->unique();
            $table->string('username')->nullable();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->enum('role', ['super_admin', 'admin', 'staff', 'pending'])->default('pending');
            $table->boolean('is_approved')->default(false);
            $table->timestamp('last_active_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        $this->createSchema('telegram_logs', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('telegram_user_id')->nullable();
            $table->string('chat_id', 64);
            $table->text('message_text')->nullable();
            $table->text('response')->nullable();
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

        $this->createSchema('users', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('role')->default('staff');
            $table->string('telegram_chat_id', 64)->nullable()->unique();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    protected function createSchema(string $name, callable $definition): void
    {
        if (! Schema::hasTable($name)) {
            Schema::create($name, $definition);
        }
    }

    protected function update(string $text, string $chatId = '9001'): array
    {
        return [
            'update_id' => 1,
            'message' => [
                'message_id' => 1,
                'from' => [
                    'id' => (int) $chatId,
                    'is_bot' => false,
                    'first_name' => 'Budi',
                    'username' => 'budi',
                ],
                'chat' => [
                    'id' => $chatId,
                    'type' => 'private',
                ],
                'date' => now()->timestamp,
                'text' => $text,
            ],
        ];
    }

    public function test_start_registers_pending_user_and_logs_activity(): void
    {
        Http::fake([
            'api.telegram.org/*' => Http::response(['ok' => true, 'result' => []], 200),
        ]);

        $this->postJson('/telegram/webhook', $this->update('/start'), [
            'X-Telegram-Bot-Api-Secret-Token' => 'webhook-secret',
        ])->assertOk();

        $this->assertDatabaseHas('telegram_users', [
            'chat_id' => '9001',
            'username' => 'budi',
            'role' => 'pending',
            'is_approved' => false,
        ]);
        $this->assertDatabaseHas('telegram_logs', [
            'chat_id' => '9001',
            'message_text' => '/start',
        ]);
        Http::assertSent(fn ($request) => $request['chat_id'] === '9001'
            && str_contains($request['text'], 'Menunggu persetujuan admin'));
    }

    public function test_new_registration_notifies_linked_super_admin(): void
    {
        Http::fake([
            'api.telegram.org/*' => Http::response(['ok' => true, 'result' => []], 200),
        ]);

        TelegramUser::create([
            'chat_id' => '7000',
            'first_name' => 'Admin Bot',
            'role' => 'super_admin',
            'is_approved' => true,
        ]);

        $this->postJson('/api/telegram/webhook', $this->update('/start', '7001'), [
            'X-Telegram-Bot-Api-Secret-Token' => 'webhook-secret',
        ])->assertOk();

        Http::assertSent(fn ($request) => $request['chat_id'] === '7000'
            && str_contains($request['text'], '7001')
            && str_contains($request['text'], '/admin/telegram-users'));
    }

    public function test_pending_user_cannot_run_approved_commands(): void
    {
        Http::fake([
            'api.telegram.org/*' => Http::response(['ok' => true, 'result' => []], 200),
        ]);

        $this->postJson('/telegram/webhook', $this->update('/start'), [
            'X-Telegram-Bot-Api-Secret-Token' => 'webhook-secret',
        ])->assertOk();
        $this->postJson('/telegram/webhook', $this->update('/status'), [
            'X-Telegram-Bot-Api-Secret-Token' => 'webhook-secret',
        ])->assertOk();

        Http::assertSent(fn ($request) => $request['chat_id'] === '9001'
            && str_contains($request['text'], 'belum disetujui admin'));
    }

    public function test_missing_webhook_secret_is_rejected_in_managed_mode(): void
    {
        config()->set('services.telegram.webhook_secret', '');

        $this->postJson('/telegram/webhook', $this->update('/start'))
            ->assertStatus(503);
    }

    public function test_approved_broadcast_excludes_pending_users(): void
    {
        Http::fake([
            'api.telegram.org/*' => Http::response(['ok' => true, 'result' => []], 200),
        ]);

        TelegramUser::create([
            'chat_id' => '1001',
            'first_name' => 'Approved',
            'role' => 'staff',
            'is_approved' => true,
        ]);
        TelegramUser::create([
            'chat_id' => '1002',
            'first_name' => 'Pending',
            'role' => 'pending',
            'is_approved' => false,
        ]);

        $result = app(TelegramService::class)->broadcastApproved('Broadcast test');

        $this->assertSame(1, $result['sent']);
        $this->assertSame(1, $result['target_count']);
        Http::assertSent(fn ($request) => $request['chat_id'] === '1001');
        Http::assertNotSent(fn ($request) => $request['chat_id'] === '1002');
    }

    public function test_super_admin_can_approve_user_from_api(): void
    {
        Http::fake([
            'api.telegram.org/*' => Http::response(['ok' => true, 'result' => []], 200),
        ]);

        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => 'password',
            'role' => 'super_admin',
        ]);
        $this->actingAs($admin);

        $telegramUser = TelegramUser::create([
            'chat_id' => '2001',
            'first_name' => 'Pending User',
            'role' => 'pending',
            'is_approved' => false,
        ]);

        $this->postJson(route('admin.telegram-users.approve', $telegramUser))
            ->assertOk()
            ->assertJson(['success' => true]);

        $this->assertDatabaseHas('telegram_users', [
            'id' => $telegramUser->id,
            'role' => 'staff',
            'is_approved' => true,
        ]);

        $this->get(route('admin.telegram-users'))
            ->assertOk()
            ->assertSee('Telegram Users')
            ->assertSee('Pending User');
    }

    public function test_non_super_admin_cannot_access_dashboard(): void
    {
        $staff = User::create([
            'name' => 'Staff',
            'email' => 'staff@example.com',
            'password' => 'password',
            'role' => 'staff',
        ]);
        $this->actingAs($staff);

        $this->get(route('admin.telegram-users'))->assertForbidden();
    }

    public function test_rate_limit_rejects_excess_messages(): void
    {
        Http::fake([
            'api.telegram.org/*' => Http::response(['ok' => true, 'result' => []], 200),
        ]);

        for ($index = 0; $index < 30; $index++) {
            $this->postJson('/telegram/webhook', $this->update('/status'), [
                'X-Telegram-Bot-Api-Secret-Token' => 'webhook-secret',
            ])->assertOk();
        }

        $this->postJson('/telegram/webhook', $this->update('/status'), [
            'X-Telegram-Bot-Api-Secret-Token' => 'webhook-secret',
        ])->assertStatus(429);
    }
}
