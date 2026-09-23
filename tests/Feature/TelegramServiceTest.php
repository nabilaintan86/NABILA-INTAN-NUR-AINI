<?php

namespace Tests\Feature;

use App\Services\TelegramService;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class TelegramServiceTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        config()->set('services.telegram.bot_token', '123456:FAKE_TEST_TOKEN');
        config()->set('services.telegram.chat_id', '987654321');
    }

    public function test_send_device_down_sends_telegram_message(): void
    {
        Http::fake([
            'api.telegram.org/*' => Http::response(['ok' => true, 'result' => []], 200),
        ]);

        $service = new TelegramService();

        $sent = $service->sendDeviceDown([
            'device' => 'Router-01',
            'ip'     => '192.168.1.1',
            'type'   => 'Router',
            'time'   => '2026-09-18 10:00:00',
        ]);

        $this->assertTrue($sent);

        Http::assertSent(function ($request) {
            $containsText = str_contains($request['text'], '🚨 DEVICE DOWN')
                && str_contains($request['text'], 'RADNEXT NOC ALERT')
                && str_contains($request['text'], 'Router-01')
                && str_contains($request['text'], '192.168.1.1')
                && str_contains($request['text'], 'Router');

            return $request->url() === 'https://api.telegram.org/bot123456:FAKE_TEST_TOKEN/sendMessage'
                && $request['chat_id'] === '987654321'
                && $containsText;
        });
    }

    public function test_send_device_recovered_sends_telegram_message(): void
    {
        Http::fake([
            'api.telegram.org/*' => Http::response(['ok' => true, 'result' => []], 200),
        ]);

        $service = new TelegramService();

        $sent = $service->sendDeviceRecovered([
            'device' => 'Router-01',
            'ip'     => '192.168.1.1',
            'type'   => 'Router',
            'time'   => '2026-09-18 10:05:00',
        ]);

        $this->assertTrue($sent);

        Http::assertSent(function ($request) {
            return str_contains($request['text'], '✅ DEVICE RECOVERED')
                && str_contains($request['text'], 'RADNEXT NOC RECOVERY')
                && str_contains($request['text'], "🟢 Status\nUP");
        });
    }

    public function test_unconfigured_service_does_not_call_api_and_returns_false(): void
    {
        config()->set('services.telegram.bot_token', '');
        config()->set('services.telegram.chat_id', '');

        $service = new TelegramService();

        $sent = $service->sendDeviceDown([
            'device' => 'Router-01',
            'ip'     => '192.168.1.1',
            'type'   => 'Router',
            'time'   => '2026-09-18 10:00:00',
        ]);

        $this->assertFalse($sent);
        $this->assertFalse($service->isConfigured());
        Http::assertNothingSent();
    }

    public function test_missing_device_data_returns_false_without_calling_api(): void
    {
        Http::fake();

        $service = new TelegramService();

        $this->assertFalse($service->sendDeviceDown([]));
        $this->assertFalse($service->sendDeviceRecovered(['device' => 'only-device']));
        Http::assertNothingSent();
    }
}