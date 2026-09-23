<?php

namespace Tests\Feature;

use App\Models\Device;
use App\Models\DeviceNotification;
use App\Models\MonitoringLog;
use App\Models\TelegramSubscriber;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class MonitorDevicesTelegramTest extends TestCase
{
    private string $fakeBinDir;

    protected function setUp(): void
    {
        parent::setUp();

        config()->set('services.telegram.bot_token', '123456:FAKE_TEST_TOKEN');
        config()->set('services.telegram.chat_id', '');

        $this->ensureTables();
        $this->installFakePing();
    }

    protected function tearDown(): void
    {
        $this->removeFakePingFromPath();

        parent::tearDown();
    }

    private function ensureTables(): void
    {
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

        if (!Schema::hasTable('device')) {
            Schema::create('device', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('type')->default('ICMP');
                $table->string('ip_address');
                $table->bigInteger('polls')->default(0);
                $table->decimal('responded_percentage', 6, 2)->default(100);
                $table->decimal('missed_percentage', 6, 2)->default(0);
                $table->string('down_time')->nullable();
                $table->string('period')->nullable();
                $table->integer('alerts_count')->default(0);
                $table->integer('avg_delay')->default(0);
                $table->integer('min_delay')->default(0);
                $table->integer('max_delay')->default(0);
                $table->string('status')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('monitoring_logs')) {
            Schema::create('monitoring_logs', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('device_id')->nullable()->index();
                $table->string('device_name');
                $table->string('ip_address');
                $table->string('status');
                $table->integer('response_time')->nullable();
                $table->timestamp('checked_at')->index();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('device_notifications')) {
            Schema::create('device_notifications', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('device_id')->nullable()->index();
                $table->string('device_name');
                $table->string('ip_address');
                $table->string('event_type');
                $table->string('event');
                $table->string('status');
                $table->text('message')->nullable();
                $table->integer('latency')->nullable();
                $table->boolean('is_read')->default(false);
                $table->timestamp('checked_at')->index();
                $table->timestamps();
            });
        }
    }

    private function installFakePing(): void
    {
        $this->fakeBinDir = sys_get_temp_dir().'/radnext-test-bin';

        if (!is_dir($this->fakeBinDir)) {
            mkdir($this->fakeBinDir, 0777, true);
        }

        $script = <<<'SH'
#!/bin/sh
case "${5}" in
10.0.0.1)
  echo "PING 10.0.0.1 (10.0.0.1) 56(84) bytes of data."
  echo "rtt min/avg/max/mdev = 0.045/0.052/0.065/0.008 ms"
  exit 0
  ;;
*)
  echo "ping: Destination Host Unreachable"
  exit 1
  ;;
esac
SH;

        file_put_contents($this->fakeBinDir.'/ping', $script);
        chmod($this->fakeBinDir.'/ping', 0755);

        putenv('PATH='.$this->fakeBinDir.':'.getenv('PATH'));
    }

    private function removeFakePingFromPath(): void
    {
        $path = getenv('PATH');
        $parts = explode(':', (string) $path);
        $parts = array_values(array_filter($parts, fn ($p) => $p !== $this->fakeBinDir));

        putenv('PATH='.implode(':', $parts));
    }

    private function addSubscriber(int $chatId, bool $active = true): void
    {
        TelegramSubscriber::create([
            'chat_id'   => $chatId,
            'username'  => 'user_'.$chatId,
            'is_active' => $active,
        ]);
    }

    private function fakeApiSuccess(): void
    {
        Http::fake([
            'api.telegram.org/*' => Http::response(['ok' => true, 'result' => []], 200),
        ]);
    }

    public function test_up_to_down_sends_one_device_down_per_active_subscriber(): void
    {
        $this->fakeApiSuccess();
        $this->addSubscriber(111);
        $this->addSubscriber(222);
        $this->addSubscriber(333, false);

        Device::create([
            'name'       => 'Router-Down',
            'type'       => 'Router',
            'ip_address' => '10.0.0.99',
            'status'     => 'up',
        ]);

        $this->artisan('monitor:ping')->assertExitCode(0);

        Http::assertSentCount(2);

        foreach (['111', '222'] as $chatId) {
            Http::assertSent(function ($request) use ($chatId) {
                return $request['chat_id'] === $chatId
                    && str_contains($request['text'], '🚨 DEVICE DOWN')
                    && str_contains($request['text'], 'Router-Down')
                    && str_contains($request['text'], 'RADNEXT NOC ALERT');
            });
        }

        Http::assertNotSent(function ($request) {
            return $request['chat_id'] === '333';
        });

        $this->assertDatabaseHas('device_notifications', [
            'device_name' => 'Router-Down',
            'event'       => 'DOWN',
        ]);

        $this->assertSame('down', Device::first()->fresh()->status);
    }

    public function test_down_to_up_sends_one_device_recovered_per_active_subscriber(): void
    {
        $this->fakeApiSuccess();
        $this->addSubscriber(111);

        Device::create([
            'name'       => 'Router-Recovery',
            'type'       => 'Router',
            'ip_address' => '10.0.0.1',
            'status'     => 'down',
        ]);

        $this->artisan('monitor:ping')->assertExitCode(0);

        Http::assertSentCount(1);
        Http::assertSent(function ($request) {
            return $request['chat_id'] === '111'
                && str_contains($request['text'], '✅ DEVICE RECOVERED')
                && str_contains($request['text'], 'RADNEXT NOC RECOVERY');
        });

        $this->assertDatabaseHas('device_notifications', [
            'device_name' => 'Router-Recovery',
            'event'       => 'BACK ONLINE',
        ]);

        $this->assertSame('up', Device::first()->fresh()->status);
    }

    public function test_up_to_up_does_not_send_telegram(): void
    {
        $this->fakeApiSuccess();
        $this->addSubscriber(111);

        Device::create([
            'name'       => 'Router-Stable',
            'type'       => 'Router',
            'ip_address' => '10.0.0.1',
            'status'     => 'up',
        ]);

        $this->artisan('monitor:ping')->assertExitCode(0);

        Http::assertNothingSent();
        $this->assertDatabaseCount('device_notifications', 0);
        $this->assertDatabaseHas('monitoring_logs', ['status' => 'up']);
    }

    public function test_down_to_down_does_not_send_telegram(): void
    {
        $this->fakeApiSuccess();
        $this->addSubscriber(111);

        Device::create([
            'name'       => 'Router-Off',
            'type'       => 'Router',
            'ip_address' => '10.0.0.99',
            'status'     => 'down',
        ]);

        $this->artisan('monitor:ping')->assertExitCode(0);

        Http::assertNothingSent();
        $this->assertDatabaseCount('device_notifications', 0);
        $this->assertDatabaseHas('monitoring_logs', ['status' => 'down']);
    }

    public function test_initial_null_status_does_not_send_telegram(): void
    {
        $this->fakeApiSuccess();
        $this->addSubscriber(111);

        Device::create([
            'name'       => 'Router-Fresh',
            'type'       => 'Router',
            'ip_address' => '10.0.0.99',
            'status'     => null,
        ]);

        $this->artisan('monitor:ping')->assertExitCode(0);

        Http::assertNothingSent();
        $this->assertDatabaseCount('device_notifications', 0);
    }

    public function test_telegram_failure_does_not_stop_monitoring(): void
    {
        Http::fake([
            'api.telegram.org/*' => Http::response(['ok' => false, 'description' => 'Unavailable'], 500),
        ]);

        $this->addSubscriber(111);

        Device::create([
            'name'       => 'Router-A',
            'type'       => 'Router',
            'ip_address' => '10.0.0.99',
            'status'     => 'up',
        ]);

        Device::create([
            'name'       => 'Router-B',
            'type'       => 'Router',
            'ip_address' => '10.0.0.1',
            'status'     => 'up',
        ]);

        $this->artisan('monitor:ping')->assertExitCode(0);

        $downCount = MonitoringLog::where('status', 'down')->count();
        $upCount = MonitoringLog::where('status', 'up')->count();

        $this->assertSame(1, $downCount);
        $this->assertSame(1, $upCount);
        $this->assertDatabaseHas('device_notifications', [
            'device_name' => 'Router-A',
            'event'       => 'DOWN',
        ]);
    }
}