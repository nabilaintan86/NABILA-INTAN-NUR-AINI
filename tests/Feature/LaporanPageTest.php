<?php

namespace Tests\Feature;

use App\Models\MonitoringLog;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class LaporanPageTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        if (!Schema::hasTable('monitoring_logs')) {
            Schema::create('monitoring_logs', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('device_id')->nullable();
                $table->string('device_name');
                $table->string('ip_address');
                $table->string('status');
                $table->integer('response_time')->nullable();
                $table->timestamp('checked_at');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('device_notifications')) {
            Schema::create('device_notifications', function (Blueprint $table) {
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
    }

    public function test_laporan_page_renders_with_real_monitoring_data(): void
    {
        $checkedAt = now()->startOfHour();

        MonitoringLog::create([
            'device_name'   => 'Router-01',
            'ip_address'    => '192.168.1.1',
            'status'        => 'up',
            'response_time' => 10,
            'checked_at'    => $checkedAt,
        ]);

        MonitoringLog::create([
            'device_name'   => 'Router-02',
            'ip_address'    => '192.168.1.2',
            'status'        => 'down',
            'response_time' => null,
            'checked_at'    => $checkedAt,
        ]);

        $response = $this->get(route('admin.laporan'));

        $response->assertStatus(200);

        $html = $response->getContent();

        $this->assertStringContainsString('availability', $html);
        $this->assertStringContainsString('response', $html);
        $this->assertStringContainsString('Router', $html);
    }
}