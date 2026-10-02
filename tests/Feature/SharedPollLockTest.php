<?php

namespace Tests\Feature;

use App\Models\Device;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Contracts\Cache\Lock;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Tests\Feature\Concerns\CreatesDeviceTables;
use Tests\Feature\Concerns\InteractsWithDeviceRegistry;
use Tests\TestCase;

/**
 * `network:poll` dan `monitor:ping` sama-sama menjalankan satu siklus ping
 * penuh ke device resmi. Agar tidak ada dua siklus berat yang berjalan
 * serentak (dua kali beban jaringan + transisi status yang bisa dihitung ulang),
 * keduanya memakai SATU kunci bersama `device-polling-cycle`.
 */
class SharedPollLockTest extends TestCase
{
    use CreatesDeviceTables;
    use InteractsWithDeviceRegistry;

    protected function setUp(): void
    {
        parent::setUp();
        $this->createDeviceTables();

        // Kolom yang ditulis network:poll (polls, min/max_delay, missed_percentage)
        // tidak ada di skema minimal CreatesDeviceTables — lengkapi di sini.
        $deviceColumns = Schema::getColumnListing('device');
        Schema::table('device', function (Blueprint $table) use ($deviceColumns): void {
            if (! in_array('polls', $deviceColumns, true)) {
                $table->unsignedInteger('polls')->default(0);
            }
            if (! in_array('min_delay', $deviceColumns, true)) {
                $table->integer('min_delay')->default(0);
            }
            if (! in_array('max_delay', $deviceColumns, true)) {
                $table->integer('max_delay')->default(0);
            }
            if (! in_array('missed_percentage', $deviceColumns, true)) {
                $table->decimal('missed_percentage', 6, 2)->default(0);
            }
        });

        if (! Schema::hasTable('activity_logs')) {
            Schema::create('activity_logs', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('device_id')->nullable();
                $table->string('device_name')->nullable();
                $table->string('ip_address')->nullable();
                $table->string('status')->nullable();
                $table->text('reason')->nullable();
                $table->timestamps();
            });
        }

        $this->registerOfficialDevices('10.0.0.10');
    }

    private function holdPollLock(): Lock
    {
        $lock = Cache::lock('device-polling-cycle', 1500);
        $this->assertTrue($lock->get(), 'Kunci siklus polling harus bisa dipegang');

        return $lock;
    }

    private function reachablePing(): void
    {
        Process::fake([
            '*' => Process::result(
                output: "1 packets transmitted, 1 received, 0% packet loss\nrtt min/avg/max/mdev = 12.000/12.000/12.000/0.000 ms",
                exitCode: 0
            ),
        ]);
    }

    public function test_network_poll_skips_when_monitor_cycle_is_running(): void
    {
        $lock = $this->holdPollLock();

        // Kalau network:poll ikut jalan, ping pasti terekam oleh Process::fake.
        Process::fake();
        $this->artisan('network:poll', ['--no-log' => true, '--no-snmp' => true])->assertExitCode(0);

        Process::assertNothingRan();
        $lock->release();
    }

    public function test_monitor_ping_skips_when_network_cycle_is_running(): void
    {
        $lock = $this->holdPollLock();

        $this->artisan('monitor:ping', ['--no-snmp' => true])->assertExitCode(0);

        $this->assertDatabaseCount('monitoring_logs', 0);
        $lock->release();
    }

    public function test_cycle_runs_when_lock_is_free(): void
    {
        $device = Device::create([
            'name'       => 'Official 0',
            'ip_address' => '10.0.0.10',
            'status'     => 'down', // harus berubah menjadi up setelah poll
            'type'       => 'Router',
            'protocol'   => 'ICMP',
        ]);

        $this->reachablePing();
        $this->artisan('network:poll', ['--no-log' => true, '--no-snmp' => true])->assertExitCode(0);

        Process::assertRan(fn ($pending) => str_contains($pending->command, '10.0.0.10'));
        $this->assertSame('up', $device->fresh()->status, 'Siklus harus berjalan setelah lock dilepaskan');
    }

    public function test_both_commands_are_registered_in_the_scheduler(): void
    {
        $events = collect(app(Schedule::class)->events());

        $network = $events->first(
            fn ($event) => str_contains((string) $event->command, 'network:poll')
        );
        $monitor = $events->first(
            fn ($event) => str_contains((string) $event->command, 'monitor:ping')
        );

        $this->assertNotNull($network);
        $this->assertSame('* * * * *', $network->getExpression());
        $this->assertTrue($network->withoutOverlapping, 'network:poll harus anti-overlap');

        $this->assertNotNull($monitor);
        // Menit offset 2,7,12,... supaya tidak berangkat pada menit :00 (start network:poll).
        $this->assertSame('2-59/5 * * * *', $monitor->getExpression());
        $this->assertTrue($monitor->withoutOverlapping, 'monitor:ping harus anti-overlap');
    }
}