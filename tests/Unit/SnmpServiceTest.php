<?php

namespace Tests\Unit;

use App\Services\SnmpService;
use PHPUnit\Framework\Attributes\CoversClass;
use Tests\TestCase;

#[CoversClass(SnmpService::class)]
class SnmpServiceTest extends TestCase
{
    public function test_disabled_snmp_returns_unavailable(): void
    {
        $service = new SnmpService([
            'enabled' => false,
            'host' => '192.168.1.10',
        ]);

        $result = $service->getDeviceInfo('192.168.1.10');

        $this->assertFalse($result['reachable']);
        $this->assertSame('unavailable', $result['status']);
        $this->assertNull($result['sys_name']);
    }

    public function test_invalid_host_is_handled_gracefully(): void
    {
        $service = new SnmpService([
            'enabled' => true,
            'host' => 'invalid-host',
        ]);

        $result = $service->getDeviceInfo('invalid-host');

        $this->assertFalse($result['reachable']);
        $this->assertSame('unavailable', $result['status']);
        $this->assertNotNull($result['error']);
    }

    public function test_successful_snmp_response_maps_data(): void
    {
        $service = new class(['enabled' => true, 'host' => '10.0.0.2', 'community' => 'public', 'version' => '2c', 'timeout' => 3, 'retries' => 1]) extends SnmpService
        {
            protected function snmpSupportAvailable(array $config = []): bool
            {
                return true;
            }

            protected function runSnmpCommand(array $config, array $command): array
            {
                $oid = $command[array_key_last($command)];

                if ($oid === '1.3.6.1.2.1.1.5.0') {
                    return ['exit_code' => 0, 'output' => 'SNMPv2-MIB::sysName.0 = STRING: "Router-01"'];
                }

                if ($oid === '1.3.6.1.2.1.1.1.0') {
                    return ['exit_code' => 0, 'output' => 'SNMPv2-MIB::sysDescr.0 = STRING: "Cisco IOS Router"'];
                }

                if ($oid === '1.3.6.1.2.1.1.3.0') {
                    return ['exit_code' => 0, 'output' => 'DISMAN-EVENT-MIB::sysUpTimeInstance = Timeticks: (12345) 0:00:12.35'];
                }

                if ($oid === 'ifDescr') {
                    return ['exit_code' => 0, 'output' => "IF-MIB::ifDescr.1 = STRING: \"Ethernet0/0\"\nIF-MIB::ifDescr.2 = STRING: \"Ethernet0/1\""];
                }

                if ($oid === 'ifOperStatus') {
                    return ['exit_code' => 0, 'output' => "IF-MIB::ifOperStatus.1 = INTEGER: up(1)\nIF-MIB::ifOperStatus.2 = INTEGER: down(2)"];
                }

                return ['exit_code' => 1, 'output' => ''];
            }
        };

        $result = $service->getDeviceInfo('10.0.0.2');

        $this->assertTrue($result['reachable']);
        $this->assertSame('reachable', $result['status']);
        $this->assertSame('Router-01', $result['sys_name']);
        $this->assertSame('Cisco IOS Router', $result['sys_descr']);
        $this->assertNotNull($result['sys_uptime']);
        $this->assertCount(2, $result['interfaces']);
        $this->assertSame('Ethernet0/0', $result['interfaces'][0]['name']);
        $this->assertSame('up', $result['interfaces'][0]['status']);
    }

    public function test_failed_command_keeps_the_real_error_message(): void
    {
        $service = $this->fakeSnmpService(['host' => '10.0.0.2'], [
            SnmpService::OID_SYS_NAME => [
                'exit_code' => 1,
                'output' => 'Timeout: No Response from udp:10.0.0.2:161.',
            ],
        ]);

        $result = $service->getDeviceInfo('10.0.0.2');

        $this->assertFalse($result['reachable']);
        $this->assertSame('unavailable', $result['status']);
        $this->assertSame('timeout', $result['error_type']);
        $this->assertStringContainsString('snmpget', $result['error']);
        $this->assertStringContainsString('exit 1', $result['error']);
        $this->assertStringContainsString('Timeout: No Response from udp:10.0.0.2:161.', $result['error']);
        $this->assertArrayHasKey('sys_name', $result['errors']);
    }

    public function test_empty_community_is_never_sent_to_snmp_command(): void
    {
        $service = $this->fakeSnmpService(['host' => '10.0.0.2', 'community' => '']);

        $result = $service->getDeviceInfo('10.0.0.2');

        $this->assertFalse($result['reachable']);
        $this->assertSame('community-missing', $result['error_type']);
        $this->assertStringContainsString('community', $result['error']);
        $this->assertSame([], $service->commands, 'Perintah SNMP tidak boleh dijalankan tanpa community.');
    }

    public function test_community_override_wins_over_config(): void
    {
        $service = $this->fakeSnmpService(['host' => '10.0.0.2', 'community' => 'dari-config']);

        $service->getDeviceInfo('10.0.0.2', ['community' => 'dari-override']);

        $sysNameCommand = $service->commandFor(SnmpService::OID_SYS_NAME);

        $this->assertContains('dari-override', $sysNameCommand);
        $this->assertNotContains('dari-config', $sysNameCommand);
    }

    public function test_interface_walk_uses_snmpwalk_and_single_oid_uses_snmpget(): void
    {
        $service = $this->fakeSnmpService(['host' => '10.0.0.2']);

        $service->getDeviceInfo('10.0.0.2');

        $this->assertSame('snmpget', $service->commandFor(SnmpService::OID_SYS_NAME)[0]);
        $this->assertSame('snmpwalk', $service->commandFor(SnmpService::OID_IF_DESCR)[0]);
        $this->assertSame('snmpwalk', $service->commandFor(SnmpService::OID_IF_OPER_STATUS)[0]);
        $this->assertNotSame([], $service->commandFor(SnmpService::OID_IF_DESCR_NUMERIC));
    }

    public function test_lower_layer_down_is_not_classified_as_plain_down(): void
    {
        $service = $this->fakeSnmpService(['host' => '10.0.0.2'], [
            SnmpService::OID_SYS_NAME => ['exit_code' => 0, 'output' => 'SNMPv2-MIB::sysName.0 = STRING: "OLT-01"'],
            SnmpService::OID_IF_DESCR => ['exit_code' => 0, 'output' => 'IF-MIB::ifDescr.1 = STRING: "GigabitEthernet0/1"'],
            SnmpService::OID_IF_OPER_STATUS => ['exit_code' => 0, 'output' => 'IF-MIB::ifOperStatus.1 = INTEGER: lowerLayerDown(7)'],
        ]);

        $result = $service->getDeviceInfo('10.0.0.2');

        $this->assertSame('lower-layer-down', $result['interfaces'][0]['status']);
        $this->assertSame(1, $result['interface_summary']['other']);
        $this->assertSame(0, $result['interface_summary']['down']);
    }

    public function test_numeric_interface_status_codes_are_classified(): void
    {
        $service = $this->fakeSnmpService(['host' => '10.0.0.2'], [
            SnmpService::OID_SYS_NAME => ['exit_code' => 0, 'output' => 'SNMPv2-MIB::sysName.0 = STRING: "SW-01"'],
            SnmpService::OID_IF_DESCR => ['exit_code' => 0, 'output' => '1.3.6.1.2.1.2.2.1.2.1 = STRING: "Gi0/1"'],
            SnmpService::OID_IF_OPER_STATUS => ['exit_code' => 0, 'output' => "1.3.6.1.2.1.2.2.1.8.1 = INTEGER: 2\n1.3.6.1.2.1.2.2.1.8.2 = INTEGER: 7"],
        ]);

        $result = $service->getDeviceInfo('10.0.0.2');

        $this->assertSame('down', $result['interfaces'][0]['status']);
        $this->assertSame('lower-layer-down', $result['interfaces'][1]['status']);
        $this->assertSame(1, $result['interface_summary']['down']);
    }

    public function test_version_from_config_is_used_instead_of_hardcoded_2c(): void
    {
        $service = $this->fakeSnmpService(['host' => '10.0.0.2', 'version' => '1']);
        $service->getDeviceInfo('10.0.0.2');
        $this->assertContains('-v1', $service->commandFor(SnmpService::OID_SYS_NAME));

        $service = $this->fakeSnmpService(['host' => '10.0.0.2', 'version' => 'v2c']);
        $service->getDeviceInfo('10.0.0.2');
        $this->assertContains('-v2c', $service->commandFor(SnmpService::OID_SYS_NAME));

        $service = $this->fakeSnmpService(['host' => '10.0.0.2', 'version' => '3']);
        $service->getDeviceInfo('10.0.0.2');
        $command = $service->commandFor(SnmpService::OID_SYS_NAME);
        $this->assertContains('-v3', $command);
        $this->assertNotContains('-c', $command, 'SNMPv3 tidak memakai community string.');
    }

    public function test_php_snmp_extension_version_follows_config(): void
    {
        if (! extension_loaded('snmp')) {
            $this->markTestSkipped('Ekstensi PHP "snmp" tidak terpasang di environment test.');
        }

        $service = new SnmpService(['enabled' => true]);

        $method = new \ReflectionMethod($service, 'phpSnmpVersion');
        $method->setAccessible(true);

        $this->assertSame(\SNMP::VERSION_1, $method->invoke($service, ['version' => '1']));
        $this->assertSame(\SNMP::VERSION_2C, $method->invoke($service, ['version' => '2c']));
        $this->assertSame(\SNMP::VERSION_3, $method->invoke($service, ['version' => '3']));
    }

    public function test_uptime_timeticks_is_converted_to_readable_value(): void
    {
        $service = $this->fakeSnmpService(['host' => '10.0.0.2'], [
            SnmpService::OID_SYS_NAME => ['exit_code' => 0, 'output' => 'SNMPv2-MIB::sysName.0 = STRING: "Router-01"'],
            SnmpService::OID_SYS_UPTIME => ['exit_code' => 0, 'output' => 'DISMAN-EVENT-MIB::sysUpTimeInstance = Timeticks: (12345) 0:00:12.35'],
        ]);

        $result = $service->getDeviceInfo('10.0.0.2');

        $this->assertSame('12345', $result['sys_uptime']);
        $this->assertSame(123, $result['uptime_seconds']);
        $this->assertSame('2m 3d', $result['uptime_human']);
    }

    public function test_partial_walk_output_is_still_used(): void
    {
        // Perangkat nyata (MikroTik) sering membalas sebagian baris lalu timeout
        // dengan exit code 1. Baris yang sudah diterima tetap dipakai.
        $service = $this->fakeSnmpService(['host' => '10.0.0.2'], [
            SnmpService::OID_SYS_NAME => ['exit_code' => 0, 'output' => 'iso.3.6.1.2.1.1.5.0 = STRING: "RAD-ICONMALL"'],
            SnmpService::OID_IF_DESCR_NUMERIC => [
                'exit_code' => 1,
                'output' => "Timeout: No Response from udp:10.0.0.2:161.\niso.3.6.1.2.1.2.2.1.2.1 = STRING: \"ether1\"\niso.3.6.1.2.1.2.2.1.2.2 = STRING: \"ether2\"",
            ],
            SnmpService::OID_IF_OPER_STATUS_NUMERIC => [
                'exit_code' => 1,
                'output' => "Timeout: No Response from udp:10.0.0.2:161.\niso.3.6.1.2.1.2.2.1.8.1 = INTEGER: 1\niso.3.6.1.2.1.2.2.1.8.2 = INTEGER: 2",
            ],
        ]);

        $result = $service->getDeviceInfo('10.0.0.2');

        $this->assertSame('RAD-ICONMALL', $result['sys_name']);
        $this->assertCount(2, $result['interfaces']);
        $this->assertSame('ether1', $result['interfaces'][0]['name']);
        $this->assertSame('up', $result['interfaces'][0]['status']);
        $this->assertSame('down', $result['interfaces'][1]['status']);
        $this->assertStringContainsString('partial', (string) $result['errors']['interfaces']);
    }

    public function test_environment_diagnostic_never_exposes_community_value(): void
    {
        $service = new SnmpService([
            'enabled' => true,
            'host' => '10.0.0.2',
            'community' => 'rahasia-super',
        ]);

        $environment = $service->environment();

        $this->assertTrue($environment['community_configured']);
        $this->assertSame('config', $environment['community_source']);
        $this->assertStringNotContainsString('rahasia-super', json_encode($environment));
    }

    /**
     * Stub SnmpService: mencatat perintah yang dibentuk dan mengembalikan
     * respons OID yang dikirim test (tanpa memanggil snmpget/snmpwalk sungguhan).
     */
    protected function fakeSnmpService(array $config, array $responses = []): SnmpService
    {
        return new class(array_merge(['enabled' => true, 'community' => 'public'], $config), $responses) extends SnmpService
        {
            /** @var array<int, array<int, string>> */
            public array $commands = [];

            public function __construct(array $config, private array $responses)
            {
                parent::__construct($config);
            }

            protected function snmpSupportAvailable(array $config = []): bool
            {
                return true;
            }

            protected function runSnmpCommand(array $config, array $command): array
            {
                $this->commands[] = $command;

                $oid = (string) $command[array_key_last($command)];

                return $this->responses[$oid] ?? [
                    'exit_code' => 1,
                    'output' => 'Unsupported OID: '.$oid,
                ];
            }

            public function commandFor(string $oid): array
            {
                foreach ($this->commands as $command) {
                    if ((string) $command[array_key_last($command)] === $oid) {
                        return $command;
                    }
                }

                return [];
            }
        };
    }
}
