<?php

namespace Tests\Feature;

use App\Models\ActivityLog;
use App\Models\Device;
use App\Models\DeviceNotification;
use App\Services\SnmpInspectionService;
use App\Services\SnmpService;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Facades\Schema;
use Tests\Feature\Concerns\InteractsWithDeviceRegistry;
use Tests\TestCase;

/**
 * SNMP adalah data tambahan di dalam pipeline monitoring.
 * Semua test di sini membuktikan status ICMP tetap sama persis.
 */
class SnmpMonitoringIntegrationTest extends TestCase
{
    use InteractsWithDeviceRegistry;

    protected function setUp(): void
    {
        parent::setUp();

        $this->createTables();
        $this->treatDeviceTableAsOfficialRegistry();
        $this->configureSnmp();
    }

    protected function createTables(): void
    {
        foreach (['device', 'activity_logs', 'device_notifications', 'monitoring_logs'] as $table) {
            Schema::dropIfExists($table);
        }

        Schema::create('device', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('ip_address');
            $table->string('type')->default('Router');
            $table->string('protocol')->default('ICMP');
            $table->string('status')->default('unknown');
            $table->unsignedBigInteger('polls')->default(0);
            $table->float('responded_percentage')->default(100);
            $table->float('missed_percentage')->default(0);
            $table->unsignedInteger('alerts_count')->default(0);
            $table->integer('avg_delay')->default(0);
            $table->integer('min_delay')->default(0);
            $table->integer('max_delay')->default(0);
            $table->string('down_time')->nullable();
            $table->string('period')->nullable();
            $table->timestamps();
        });

        Schema::create('monitoring_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('device_id')->nullable();
            $table->string('device_name');
            $table->string('ip_address');
            $table->string('status');
            $table->integer('response_time')->nullable();
            $table->timestamp('checked_at')->nullable();
            $table->timestamps();
        });

        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('device_id')->nullable();
            $table->string('device_name');
            $table->string('ip_address');
            $table->string('status');
            $table->string('reason')->nullable();
            $table->integer('latency')->nullable();
            $table->timestamp('log_time')->nullable();
            $table->timestamps();
        });

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
            $table->timestamp('checked_at')->nullable();
            $table->timestamps();
        });
    }

    protected function configureSnmp(array $overrides = []): void
    {
        config(array_merge([
            'snmp.enabled' => true,
            'snmp.mode' => 'protocol',
            'snmp.targets' => [],
            'snmp.cache_ttl' => 0,
            'snmp.cycle_budget_seconds' => 30,
            'snmp.only_when_up' => true,
            'snmp.community' => 'public',
            'snmp.version' => '2c',
            'snmp.port' => 161,
            'snmp.timeout' => 1,
            'snmp.retries' => 0,
        ], $overrides));
    }

    protected function makeDevice(string $ip, string $protocol = 'SNMP', string $status = 'up'): Device
    {
        return Device::create([
            'name' => 'Router '.$ip,
            'ip_address' => $ip,
            'type' => 'Router',
            'protocol' => $protocol,
            'status' => $status,
        ]);
    }

    /**
     * Ganti SnmpService dengan stub supaya test tidak memanggil snmpget sungguhan.
     */
    protected function fakeSnmp(array $responses = [], array $config = []): SnmpService
    {
        $sleep = (int) ($config['sleep_us'] ?? 0);
        unset($config['sleep_us']);

        $snmp = new class(array_merge(['enabled' => true, 'host' => '10.0.0.1', 'community' => 'public'], $config), $responses, $sleep) extends SnmpService
        {
            /** @var array<int, array<int, string>> */
            public array $commands = [];

            public function __construct(array $config, private array $responses, private int $sleepUs = 0)
            {
                parent::__construct($config);
            }

            protected function snmpSupportAvailable(array $config = []): bool
            {
                return true;
            }

            protected function runSnmpCommand(array $config, array $command): array
            {
                if ($this->sleepUs > 0) {
                    usleep($this->sleepUs);
                }

                $this->commands[] = $command;

                $oid = (string) $command[array_key_last($command)];

                return $this->responses[$oid] ?? [
                    'exit_code' => 1,
                    'output' => 'Timeout: No Response from udp:'.$config['host'].':161.',
                ];
            }
        };

        $this->app->instance(SnmpService::class, $snmp);
        $this->app->forgetInstance(SnmpInspectionService::class);

        return $snmp;
    }

    protected function fakePingSuccess(): void
    {
        Process::fake([
            '*' => Process::result(
                output: "1 packets transmitted, 1 received, 0% packet loss\nrtt min/avg/max/mdev = 1.2/2.4/3.0/0.1 ms",
                errorOutput: '',
                exitCode: 0
            ),
        ]);
    }

    protected function fakePingFailure(): void
    {
        Process::fake([
            '*' => Process::result(
                output: '1 packets transmitted, 0 received, 100% packet loss',
                errorOutput: '',
                exitCode: 1
            ),
        ]);
    }

    public function test_snmp_enriches_activity_log_without_changing_icmp_status(): void
    {
        $this->makeDevice('10.0.0.1');
        $this->fakePingSuccess();
        $this->fakeSnmp([
            SnmpService::OID_SYS_NAME => ['exit_code' => 0, 'output' => 'SNMPv2-MIB::sysName.0 = STRING: "Router-01"'],
            SnmpService::OID_SYS_DESCR => ['exit_code' => 0, 'output' => 'SNMPv2-MIB::sysDescr.0 = STRING: "Cisco IOS"'],
            SnmpService::OID_SYS_UPTIME => ['exit_code' => 0, 'output' => 'DISMAN-EVENT-MIB::sysUpTimeInstance = Timeticks: (12345) 0:00:12.35'],
            SnmpService::OID_IF_DESCR => ['exit_code' => 0, 'output' => 'IF-MIB::ifDescr.1 = STRING: "Gi0/1"'],
            SnmpService::OID_IF_OPER_STATUS => ['exit_code' => 0, 'output' => 'IF-MIB::ifOperStatus.1 = INTEGER: lowerLayerDown(7)'],
        ]);

        $this->artisan('network:poll')->assertExitCode(0);

        $device = Device::where('ip_address', '10.0.0.1')->sole();
        $log = ActivityLog::where('device_id', $device->id)->sole();

        // Status tetap hasil ICMP.
        $this->assertSame('up', $device->status);

        // Ringkasan SNMP menempel pada log aktivitas.
        $this->assertStringContainsString('SNMP OK', $log->reason);
        $this->assertStringContainsString('Router-01', $log->reason);
        $this->assertStringContainsString('iface 1', $log->reason);
        $this->assertLessThanOrEqual(255, strlen($log->reason));
    }

    public function test_snmp_failure_does_not_break_monitoring(): void
    {
        $this->makeDevice('10.0.0.2');
        $this->fakePingSuccess();
        $this->fakeSnmp();

        $this->artisan('network:poll')->assertExitCode(0);

        $device = Device::where('ip_address', '10.0.0.2')->sole();
        $log = ActivityLog::where('device_id', $device->id)->sole();

        $this->assertSame('up', $device->status, 'Kegagalan SNMP tidak boleh mengubah status ICMP.');
        $this->assertStringContainsString('SNMP gagal', $log->reason);
        $this->assertStringContainsString('Timeout', $log->reason);
    }

    public function test_snmp_is_not_probed_when_icmp_is_down(): void
    {
        $this->makeDevice('10.0.0.3');
        $this->fakePingFailure();
        $snmp = $this->fakeSnmp();

        $this->artisan('network:poll')->assertExitCode(0);

        // Status ping tanpa balasan tetap 'warning' (sesuai perilaku ICMP lama).
        $this->assertSame('warning', Device::where('ip_address', '10.0.0.3')->sole()->status);
        $this->assertSame([], $snmp->commands, 'SNMP tidak boleh ditanya untuk device yang ICMP-nya down.');
    }

    public function test_icmp_device_is_not_probed_in_protocol_mode(): void
    {
        $this->makeDevice('10.0.0.4', 'ICMP');
        $this->fakePingSuccess();
        $snmp = $this->fakeSnmp();

        $this->artisan('network:poll')->assertExitCode(0);

        $this->assertSame('up', Device::where('ip_address', '10.0.0.4')->sole()->status);
        $this->assertSame([], $snmp->commands, 'Mode protocol hanya memeriksa device ber-protocol SNMP.');
    }

    public function test_snmp_can_be_skipped_per_run(): void
    {
        $this->makeDevice('10.0.0.5');
        $this->fakePingSuccess();
        $snmp = $this->fakeSnmp();

        $this->artisan('network:poll', ['--no-snmp' => true])->assertExitCode(0);

        $this->assertSame([], $snmp->commands);
        $this->assertSame('Normal', ActivityLog::where('device_name', 'Router 10.0.0.5')->sole()->reason);
    }

    public function test_cycle_budget_stops_snmp_probing_without_touching_icmp(): void
    {
        $this->makeDevice('10.0.0.6');
        $this->makeDevice('10.0.0.61');
        $this->fakePingSuccess();
        $this->configureSnmp(['snmp.cycle_budget_seconds' => 1]);

        /*
         * Stub memakai delay supaya budget 1 detik habis oleh device pertama;
         * device berikutnya harus dilewati tanpa request SNMP.
         */
        $snmp = $this->fakeSnmp([], ['sleep_us' => 1_100_000]);

        $this->artisan('network:poll')->assertExitCode(0);

        $this->assertSame('up', Device::where('ip_address', '10.0.0.6')->sole()->status);
        $this->assertSame('up', Device::where('ip_address', '10.0.0.61')->sole()->status);
        $this->assertStringNotContainsString('SNMP', ActivityLog::where('device_name', 'Router 10.0.0.61')->sole()->reason);

        $probedHosts = array_map(
            fn (array $command) => $command[count($command) - 2] ?? null,
            $snmp->commands
        );
        $this->assertSame(['udp:10.0.0.6:161'], array_values(array_unique($probedHosts)));
    }

    public function test_list_mode_probes_only_configured_targets(): void
    {
        $this->makeDevice('10.0.0.7', 'ICMP');
        $this->makeDevice('10.0.0.8', 'ICMP');
        $this->fakePingSuccess();
        $this->configureSnmp(['snmp.mode' => 'list', 'snmp.targets' => ['10.0.0.8']]);
        $snmp = $this->fakeSnmp([
            SnmpService::OID_SYS_NAME => ['exit_code' => 0, 'output' => 'SNMPv2-MIB::sysName.0 = STRING: "Target-8"'],
        ]);

        $this->artisan('network:poll')->assertExitCode(0);

        $probedHosts = array_map(
            fn (array $command) => $command[count($command) - 2] ?? null,
            $snmp->commands
        );

        $this->assertNotEmpty($probedHosts);
        $this->assertSame(['udp:10.0.0.8:161'], array_values(array_unique($probedHosts)));
    }

    public function test_monitor_ping_keeps_icmp_status_and_logs_snmp_insight(): void
    {
        // 127.0.0.1 dipakai agar ping sungguhan (exec, bukan Process) succeed.
        $this->makeDevice('127.0.0.1');
        $this->fakeSnmp([
            SnmpService::OID_SYS_NAME => ['exit_code' => 0, 'output' => 'SNMPv2-MIB::sysName.0 = STRING: "Router-09"'],
        ]);

        Log::spy();

        $this->artisan('monitor:ping')->assertExitCode(0);

        $device = Device::where('ip_address', '127.0.0.1')->sole();

        $this->assertSame('up', $device->status);
        $this->assertDatabaseHas('monitoring_logs', [
            'device_id' => $device->id,
            'status' => 'up',
        ]);

        Log::shouldHaveReceived('info')
            ->with('SNMP monitoring insight', \Mockery::type('array'))
            ->once();
    }

    public function test_disabled_snmp_flag_keeps_monitoring_untouched(): void
    {
        $this->makeDevice('10.0.0.10');
        $this->fakePingSuccess();
        $this->configureSnmp(['snmp.enabled' => false]);
        $snmp = $this->fakeSnmp();

        $this->artisan('network:poll')->assertExitCode(0);

        $this->assertSame([], $snmp->commands);
        $this->assertSame('Normal', ActivityLog::where('device_name', 'Router 10.0.0.10')->sole()->reason);
    }

    public function test_notifications_are_not_polluted_by_snmp_details(): void
    {
        $this->makeDevice('10.0.0.11', 'SNMP', 'down');
        $this->fakePingFailure();
        $this->fakeSnmp();

        $this->artisan('network:poll')->assertExitCode(0);

        $notification = DeviceNotification::where('device_name', 'Router 10.0.0.11')->sole();

        $this->assertStringNotContainsString('SNMP', $notification->message);
    }
}
