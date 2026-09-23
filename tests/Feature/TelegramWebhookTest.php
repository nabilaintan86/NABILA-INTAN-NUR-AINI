<?php

namespace Tests\Feature;

use App\Models\TelegramSubscriber;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class TelegramWebhookTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        config()->set('services.telegram.bot_token', '123456:FAKE_TEST_TOKEN');
        config()->set('services.telegram.chat_id', '999');

        if (!Schema::hasTable('telegram_subscribers')) {
            Schema::create('telegram_subscribers', function (Blueprint $table) {
                $table->id();
                $table->bigInteger('chat_id')->unique();
                $table->string('first_name')->nullable();
                $table->string('username')->nullable();
                $table->string('last_name')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamp('last_interacted_at')->nullable();
                $table->timestamps();
            });
        }
    }

    protected function fakeUpdate(string $text, int $chatId = 123): array
    {
        return [
            'update_id' => 1001,
            'message'   => [
                'message_id' => 1,
                'from'       => [
                    'id'         => $chatId,
                    'is_bot'     => false,
                    'first_name' => 'Budi',
                    'username'   => 'budi',
                ],
                'chat' => [
                    'id'         => $chatId,
                    'first_name' => 'Budi',
                    'type'       => 'private',
                ],
                'date' => now()->timestamp,
                'text' => $text,
            ],
        ];
    }

    public function test_start_command_creates_active_subscriber_and_sends_welcome(): void
    {
        Http::fake([
            'api.telegram.org/*' => Http::response(['ok' => true, 'result' => []], 200),
        ]);

        $this->postJson('/telegram/webhook', $this->fakeUpdate('/start'))
            ->assertOk()
            ->assertJson(['ok' => true]);

        $this->assertDatabaseHas('telegram_subscribers', [
            'chat_id'   => 123,
            'username'  => 'budi',
            'is_active' => true,
        ]);

        Http::assertSent(function ($request) {
            return $request['chat_id'] === '123'
                && str_contains($request['text'], 'berlangganan');
        });
    }

    public function test_stop_command_deactivates_subscriber(): void
    {
        Http::fake([
            'api.telegram.org/*' => Http::response(['ok' => true, 'result' => []], 200),
        ]);

        TelegramSubscriber::create(['chat_id' => 123, 'is_active' => true]);

        $this->postJson('/telegram/webhook', $this->fakeUpdate('/stop'))
            ->assertOk();

        $this->assertDatabaseHas('telegram_subscribers', [
            'chat_id'   => 123,
            'is_active' => false,
        ]);
    }

    public function test_unknown_command_does_not_change_subscription(): void
    {
        Http::fake([
            'api.telegram.org/*' => Http::response(['ok' => true, 'result' => []], 200),
        ]);

        $this->postJson('/telegram/webhook', $this->fakeUpdate('halo'))
            ->assertOk();

        $this->assertDatabaseMissing('telegram_subscribers', ['chat_id' => 123]);
    }

    public function test_webhook_is_csrf_exempt(): void
    {
        Http::fake([
            'api.telegram.org/*' => Http::response(['ok' => true, 'result' => []], 200),
        ]);

        $this->post('/telegram/webhook', $this->fakeUpdate('/start'))
            ->assertOk();
    }

    public function test_webhook_rejects_invalid_secret_token(): void
    {
        config()->set('services.telegram.webhook_secret', 'rahasia123');

        Http::fake();

        $this->postJson('/telegram/webhook', $this->fakeUpdate('/start'))
            ->assertStatus(403);

        Http::assertNothingSent();
    }

    public function test_webhook_accepts_correct_secret_token(): void
    {
        config()->set('services.telegram.webhook_secret', 'rahasia123');

        Http::fake([
            'api.telegram.org/*' => Http::response(['ok' => true, 'result' => []], 200),
        ]);

        $this->postJson('/telegram/webhook', $this->fakeUpdate('/start'), [
            'X-Telegram-Bot-Api-Secret-Token' => 'rahasia123',
        ])->assertOk();

        $this->assertDatabaseHas('telegram_subscribers', ['chat_id' => 123]);
    }
}