<?php

namespace Tests\Feature;

use App\Models\TelegramSubscriber;
use App\Services\TelegramService;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema;
use Tests\Feature\Concerns\InteractsWithDeviceRegistry;
use Tests\TestCase;

class TelegramBroadcastTest extends TestCase
{
    use InteractsWithDeviceRegistry;

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

        // Test ini menguji broadcast, bukan sumber data: tabel device = daftar resmi.
        $this->treatDeviceTableAsOfficialRegistry();
    }

    public function test_broadcast_device_down_sends_to_all_active_subscribers_and_default_chat(): void
    {
        Http::fake([
            'api.telegram.org/*' => Http::response(['ok' => true, 'result' => []], 200),
        ]);

        TelegramSubscriber::create(['chat_id' => 111, 'username' => 'user_111', 'is_active' => true]);
        TelegramSubscriber::create(['chat_id' => 222, 'username' => 'user_222', 'is_active' => true]);
        TelegramSubscriber::create(['chat_id' => 333, 'username' => 'user_333', 'is_active' => false]);

        $service = new TelegramService();

        $sent = $service->broadcastDeviceDown([
            'device' => 'Router-01',
            'ip'     => '192.168.1.1',
            'type'   => 'Router',
            'time'   => '2026-09-18 10:00:00',
        ]);

        $this->assertTrue($sent);

        Http::assertSentCount(3);

        foreach (['111', '222', '999'] as $chatId) {
            Http::assertSent(function ($request) use ($chatId) {
                return $request['chat_id'] === $chatId
                    && str_contains($request['text'], '🚨 DEVICE DOWN')
                    && str_contains($request['text'], 'Router-01');
            });
        }

        // subscriber non-aktif tidak menerima
        Http::assertNotSent(function ($request) {
            return $request['chat_id'] === '333';
        });
    }

    public function test_broadcast_deduplicates_default_chat_when_same_as_subscriber(): void
    {
        Http::fake([
            'api.telegram.org/*' => Http::response(['ok' => true, 'result' => []], 200),
        ]);

        TelegramSubscriber::create(['chat_id' => 999, 'is_active' => true]);

        $service = new TelegramService();

        $service->broadcastDeviceRecovered([
            'device' => 'Router-01',
            'ip'     => '192.168.1.1',
            'type'   => 'Router',
            'time'   => '2026-09-18 10:05:00',
        ]);

        Http::assertSentCount(1);
        Http::assertSent(function ($request) {
            return $request['chat_id'] === '999'
                && str_contains($request['text'], '✅ DEVICE RECOVERED');
        });
    }

    public function test_broadcast_works_when_default_chat_is_empty_but_subscribers_exist(): void
    {
        config()->set('services.telegram.chat_id', '');

        Http::fake([
            'api.telegram.org/*' => Http::response(['ok' => true, 'result' => []], 200),
        ]);

        TelegramSubscriber::create(['chat_id' => 111, 'is_active' => true]);
        TelegramSubscriber::create(['chat_id' => 222, 'is_active' => true]);

        $service = new TelegramService();

        $sent = $service->broadcastDeviceDown([
            'device' => 'Router-01',
            'ip'     => '192.168.1.1',
            'type'   => 'Router',
            'time'   => '2026-09-18 10:00:00',
        ]);

        $this->assertTrue($sent);

        Http::assertSentCount(2);

        foreach (['111', '222'] as $chatId) {
            Http::assertSent(function ($request) use ($chatId) {
                return $request['chat_id'] === $chatId
                    && str_contains($request['text'], '🚨 DEVICE DOWN');
            });
        }
    }

    public function test_broadcast_returns_false_when_no_recipients(): void
    {
        config()->set('services.telegram.chat_id', '');

        Http::fake();

        $service = new TelegramService();

        $sent = $service->broadcastDeviceRecovered([
            'device' => 'Router-01',
            'ip'     => '192.168.1.1',
            'type'   => 'Router',
            'time'   => '2026-09-18 10:05:00',
        ]);

        $this->assertFalse($sent);
        Http::assertNothingSent();
    }

    public function test_broadcast_continues_when_one_recipient_fails(): void
    {
        Http::fake([
            'api.telegram.org/*' => function ($request) {
                if ($request['chat_id'] === '111') {
                    return Http::response(['ok' => false, 'description' => 'chat not found'], 400);
                }

                return Http::response(['ok' => true, 'result' => []], 200);
            },
        ]);

        TelegramSubscriber::create(['chat_id' => 111, 'is_active' => true]);
        TelegramSubscriber::create(['chat_id' => 222, 'is_active' => true]);
        config()->set('services.telegram.chat_id', '');

        $service = new TelegramService();

        $sent = $service->broadcastDeviceDown([
            'device' => 'Router-01',
            'ip'     => '192.168.1.1',
            'type'   => 'Router',
            'time'   => '2026-09-18 10:00:00',
        ]);

        $this->assertTrue($sent);

        Http::assertSentCount(2);
        Http::assertSent(function ($request) {
            return $request['chat_id'] === '222'
                && str_contains($request['text'], '🚨 DEVICE DOWN');
        });
    }
}