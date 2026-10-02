<?php

namespace Tests\Feature;

use App\Models\LogArchive;
use App\Models\MonitoringLog;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Tests\Feature\Concerns\AuthenticatesAdmin;
use Tests\TestCase;

class LogHistoryTest extends TestCase
{
    use AuthenticatesAdmin;
    protected function setUp(): void
    {
        parent::setUp();

        foreach (['monitoring_logs', 'log_archives'] as $table) {
            if (!Schema::hasTable($table)) {
                Schema::create($table, function (Blueprint $t) {
                    $t->id();
                    $t->unsignedBigInteger('device_id')->nullable();
                    $t->string('device_name');
                    $t->string('ip_address');
                    $t->string('status');
                    $t->integer('response_time')->nullable();
                    $t->timestamp('checked_at');
                    $t->timestamps();
                });
            }
        }

        // Data dalam rentang (09-24 s/d 09-25, ketiga format berikut termasuk)
        MonitoringLog::create([
            'device_name' => 'POP SBY',
            'ip_address'  => '192.168.1.1',
            'status'      => 'up',
            'response_time' => 12,
            'checked_at'    => '2026-09-24 09:30:00',
        ]);
        MonitoringLog::create([
            'device_name'    => 'POP GRESIK',
            'ip_address'     => '202.154.4.254',
            'status'         => 'down',
            'response_time'  => null,
            'checked_at'     => '2026-09-25 23:59:00', // hari terakhir inklusif
        ]);
        MonitoringLog::create([
            'device_name'   => 'LUAR RANGE',
            'ip_address'    => '10.0.0.9',
            'status'        => 'up',
            'response_time' => 5,
            'checked_at'    => '2026-09-20 12:00:00',
        ]);

        LogArchive::create([
            'device_name'   => 'ARCHIVE SBY',
            'ip_address'    => '192.168.1.55',
            'status'        => 'warning',
            'response_time' => 250,
            'checked_at'    => '2026-09-24 15:00:00',
        ]);
        LogArchive::create([
            'device_name'   => 'ARCHIVE TUA',
            'ip_address'    => '10.0.0.55',
            'status'        => 'down',
            'response_time' => null,
            'checked_at'    => '2026-09-10 08:00:00',
        ]);

        $this->authenticateAsAdmin();
    }

    public function test_archive_tab_merges_monitoring_logs_and_log_archives(): void
    {
        $response = $this->get(route('admin.log.archive', [
            'from' => '2026-09-24',
            'to'   => '2026-09-25',
        ]));

        $response->assertOk();

        // current (2 dalam rentang) + archive (1 dalam rentang) = 3
        $this->assertSame(3, $response->json('total'));

        $html = $response->json('html');
        $this->assertStringContainsString('POP SBY', $html);
        $this->assertStringContainsString('POP GRESIK', $html);
        $this->assertStringContainsString('ARCHIVE SBY', $html);

        // Data di luar rentang tidak ikut serta.
        $this->assertStringNotContainsString('LUAR RANGE', $html);
        $this->assertStringNotContainsString('ARCHIVE TUA', $html);
        $this->assertStringNotContainsString('Tidak ada data pada rentang tanggal ini.', $html);
    }

    public function test_archive_understands_mm_dd_yyyy_format(): void
    {
        $response = $this->get(route('admin.log.archive', [
            'from' => '09/24/2026',
            'to'   => '09/25/2026',
        ]));

        $response->assertOk();
        $this->assertSame(3, $response->json('total'));
        $this->assertStringContainsString('POP SBY', $response->json('html'));
    }

    public function test_archive_reports_empty_state_when_range_has_no_rows(): void
    {
        $response = $this->get(route('admin.log.archive', [
            'from' => '2026-01-01',
            'to'   => '2026-01-02',
        ]));

        $response->assertOk();
        $this->assertSame(0, $response->json('total'));
        $this->assertStringContainsString('Tidak ada data pada rentang tanggal ini.', $response->json('html'));
    }
}