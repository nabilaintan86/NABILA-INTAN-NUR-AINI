<?php

namespace App\Console\Commands;

use App\Services\SnmpService;
use Illuminate\Console\Command;

class TestSnmpCommand extends Command
{
    protected $signature = 'snmp:test
        {host? : IP/host target yang didiagnosis (wajib diisi, tidak memakai SNMP_HOST)}
        {--community= : Override community string (kalau kosong pakai config SNMP_COMMUNITY)}
        {--snmp-version= : Override versi SNMP: 1, 2c, atau 3 (default: config SNMP_VERSION)}
        {--port= : Override port SNMP (default: config SNMP_PORT)}
        {--timeout= : Override timeout SNMP dalam detik (default: config SNMP_TIMEOUT)}
        {--retries= : Override jumlah retry SNMP (default: config SNMP_RETRIES)}';

    protected $description = 'Diagnosis SNMP ke target yang diberikan tanpa bergantung pada SNMP_HOST dan tanpa memengaruhi monitoring ICMP';

    public function handle(SnmpService $service): int
    {
        $host = trim((string) $this->argument('host'));

        if ($host === '') {
            $this->error('Target SNMP wajib diisi, contoh: php artisan snmp:test 192.168.1.1');
            $this->line('Command ini tidak memakai SNMP_HOST supaya diagnosis tidak ikut terpengaruh setting global.');

            return self::FAILURE;
        }

        $overrides = array_filter([
            /*
             * Feature flag SNMP tetap berlaku untuk aplikasi, tapi command
             * diagnostik ini memang bertujuan menguji target secara eksplisit.
             */
            'enabled' => true,
            'community' => $this->option('community') ?: null,
            'version' => $this->option('snmp-version') ?: null,
            'port' => $this->option('port') !== null ? (int) $this->option('port') : null,
            'timeout' => $this->option('timeout') !== null ? (int) $this->option('timeout') : null,
            'retries' => $this->option('retries') !== null ? (int) $this->option('retries') : null,
        ], static fn ($value) => $value !== null);

        $environment = $service->environment($overrides);
        $result = $service->getDeviceInfo($host, $overrides);

        $this->renderEnvironment($environment);
        $this->newLine();

        if (! ($result['reachable'] ?? false)) {
            $this->error('SNMP tidak tersedia untuk '.$host.'.');
            $this->line('Status   : '.($result['status'] ?? 'unavailable'));
            $this->line('Tipe err: '.($result['error_type'] ?? 'unknown'));
            $this->line('Pesan    : '.($result['error'] ?? 'tanpa pesan'));

            foreach ((array) ($result['errors'] ?? []) as $source => $message) {
                $this->line('  - '.$source.': '.$message);
            }

            return self::FAILURE;
        }

        $this->info('SNMP reachable: '.$host);
        $this->table(['Field', 'Value'], [
            ['backend', $environment['backend_label']],
            ['version', $result['version'] ?? '2c'],
            ['community', $environment['community_configured'] ? 'terkonfigurasi ('.$environment['community_source'].')' : 'tidak ada'],
            ['sys_name', $result['sys_name'] ?? 'null'],
            ['sys_descr', $result['sys_descr'] ?? 'null'],
            ['sys_uptime', ($result['uptime_human'] ?? 'null').($result['uptime_seconds'] !== null ? ' ('.$result['uptime_seconds'].' dtk)' : '')],
            ['interface_count', count($result['interfaces'] ?? [])],
        ]);

        if (! empty($result['interfaces'])) {
            $rows = array_map(fn (array $interface) => [
                $interface['index'] ?? '-',
                $interface['name'] ?? 'unknown',
                $interface['status'] ?? 'unknown',
            ], $result['interfaces']);

            $this->table(['Index', 'Interface', 'Status'], $rows);
        }

        return self::SUCCESS;
    }

    /**
     * @param  array<string, mixed>  $environment
     */
    private function renderEnvironment(array $environment): void
    {
        $this->table(['Pengaturan', 'Nilai'], [
            ['SNMP_ENABLED (fitur)', $environment['enabled'] ? 'true' : 'false'],
            ['ext-snmp PHP', $environment['php_snmp_extension'] ? 'tersedia' : 'tidak ada'],
            ['binary snmpget', $environment['binary_snmpget'] ? 'ditemukan' : 'tidak ditemukan'],
            ['binary snmpwalk', $environment['binary_snmpwalk'] ? 'ditemukan' : 'tidak ditemukan'],
            ['backend aktif', $environment['backend_label']],
        ]);
    }
}
