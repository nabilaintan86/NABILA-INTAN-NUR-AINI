<?php

namespace Tests\Feature;

use App\Models\TelegramSubscriber;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class TelegramPollCommandTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        config()->set('services.telegram.bot_token', '123456:FAKE_TEST_TOKEN');
        config()->set('services.telegram.chat_id', '987654321');
        config()->set('services.telegram.poll_retry_delay', 0);
        config()->set('cache.default', 'array');

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

    protected function fakeApi(array $updates): void
    {
        Http::fake([
            '*/deleteWebhook'  => Http::response(['ok' => true, 'result' => true], 200),
            '*/getUpdates'     => Http::response(['ok' => true, 'result' => $updates], 200),
            '*/sendMessage'    => Http::response(['ok' => true, 'result' => []], 200),
        ]);
    }

    public function test_poll_once_deletes_webhook_processes_start_and_persists_offset(): void
    {
        $this->fakeApi([
            [
                'update_id' => 100,
                'message'   => [
                    'message_id' => 1,
                    'from'       => ['id' => 555, 'is_bot' => false, 'first_name' => 'Siti', 'username' => 'siti'],
                    'chat'       => ['id' => 555, 'type' => 'private'],
                    'date'       => now()->timestamp,
                    'text'       => '/start',
                ],
            ],
        ]);

        $this->artisan('telegram:poll', ['--once' => true])
            ->expectsOutputToContain('Webhook Telegram dihapus')
            ->assertExitCode(0);

        $this->assertDatabaseHas('telegram_subscribers', [
            'chat_id'   => 555,
            'username'  => 'siti',
            'is_active' => true,
        ]);

        Http::assertSent(function ($request) {
            return str_contains($request->url(), '/getUpdates')
                && (int) $request['offset'] === 0;
        });

        Http::assertSent(function ($request) {
            return str_contains($request->url(), '/sendMessage')
                && $request['chat_id'] === '555'
                && str_contains($request['text'], 'berlangganan');
        });

        $this->assertSame(101, Cache::get('telegram.poll.offset'));
    }

    public function test_poll_resumes_from_persisted_offset_after_restart(): void
    {
        Cache::forever('telegram.poll.offset', 200);

        $this->fakeApi([
            [
                'update_id' => 200,
                'message'   => [
                    'message_id' => 2,
                    'from'       => ['id' => 777, 'is_bot' => false, 'first_name' => 'Andi'],
                    'chat'       => ['id' => 777, 'type' => 'private'],
                    'date'       => now()->timestamp,
                    'text'       => '/start',
                ],
            ],
        ]);

        $this->artisan('telegram:poll', ['--once' => true])->assertExitCode(0);

        Http::assertSent(function ($request) {
            return str_contains($request->url(), '/getUpdates')
                && (int) $request['offset'] === 200;
        });

        $this->assertDatabaseHas('telegram_subscribers', ['chat_id' => 777]);

        $this->assertSame(201, Cache::get('telegram.poll.offset'));
    }

    public function test_poll_returns_failure_when_getupdates_fails(): void
    {
        Http::fake([
            '*/deleteWebhook' => Http::response(['ok' => true, 'result' => true], 200),
            '*/getUpdates'    => Http::response(
                ['ok' => false, 'error_code' => 409, 'description' => 'Conflict: terminated by other getUpdates request'],
                409
            ),
        ]);

        $this->withoutMockingConsoleOutput();

        $exit = Artisan::call('telegram:poll', ['--once' => true]);

        $output = Artisan::output();

        $this->assertSame(1, $exit);
        $this->assertStringContainsString('HTTP 409', $output);
        $this->assertStringContainsString('Telegram API: Conflict: terminated by other getUpdates request', $output);
        $this->assertStringContainsString('Petunjuk', $output);
        $this->assertStringContainsString('Coba lagi dalam 0 detik', $output);
    }

    public function test_poll_shows_connection_error_on_network_failure(): void
    {
        Http::fake([
            '*/deleteWebhook' => Http::response(['ok' => true, 'result' => true], 200),
            '*/getUpdates'    => fn () => throw new \Illuminate\Http\Client\ConnectionException('cURL error 28: Operation timed out'),
        ]);

        $this->artisan('telegram:poll', ['--once' => true])
            ->expectsOutputToContain('Connection error: cURL error 28: Operation timed out')
            ->assertExitCode(1);
    }

    public function test_poll_does_not_create_duplicate_subscriber_on_restart(): void
    {
        TelegramSubscriber::create(['chat_id' => 888, 'username' => 'budi', 'is_active' => true]);

        $this->fakeApi([
            [
                'update_id' => 300,
                'message'   => [
                    'message_id' => 3,
                    'from'       => ['id' => 888, 'is_bot' => false, 'first_name' => 'Budi'],
                    'chat'       => ['id' => 888, 'type' => 'private'],
                    'date'       => now()->timestamp,
                    'text'       => '/start',
                ],
            ],
        ]);

        $this->artisan('telegram:poll', ['--once' => true])->assertExitCode(0);

        $this->assertDatabaseCount('telegram_subscribers', 1);
        $this->assertDatabaseHas('telegram_subscribers', [
            'chat_id'   => 888,
            'is_active' => true,
        ]);
    }
}