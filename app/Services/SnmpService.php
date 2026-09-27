<?php

namespace App\Services;

class SnmpService
{
    public const OID_SYS_DESCR = '1.3.6.1.2.1.1.1.0';

    public const OID_SYS_UPTIME = '1.3.6.1.2.1.1.3.0';

    public const OID_SYS_NAME = '1.3.6.1.2.1.1.5.0';

    public const OID_IF_DESCR = 'ifDescr';

    public const OID_IF_OPER_STATUS = 'ifOperStatus';

    /**
     * Dipakai bila net-snmp di server tidak punya MIB symbolic name.
     * ifDescr = 1.3.6.1.2.1.2.2.1.2, ifOperStatus = 1.3.6.1.2.1.2.2.1.8
     */
    public const OID_IF_DESCR_NUMERIC = '1.3.6.1.2.1.2.2.1.2';

    public const OID_IF_OPER_STATUS_NUMERIC = '1.3.6.1.2.1.2.2.1.8';

    /**
     * Nilai enum ifOperStatus (IF-MIB) memakai label, tetapi beberapa perangkat
     * hanya mengirim angka. Dipakai agar klasifikasi status tetap akurat.
     */
    protected const INTERFACE_STATUS_BY_CODE = [
        1 => 'up',
        2 => 'down',
        3 => 'testing',
        4 => 'unknown',
        5 => 'dormant',
        6 => 'not-present',
        7 => 'lower-layer-down',
    ];

    protected const INTERFACE_STATUS_BY_LABEL = [
        'up' => 'up',
        'down' => 'down',
        'testing' => 'testing',
        'unknown' => 'unknown',
        'dormant' => 'dormant',
        'notpresent' => 'not-present',
        'notconnected' => 'not-connected',
        'lowerlayerdown' => 'lower-layer-down',
    ];

    /**
     * @var array<string, bool>
     */
    protected static array $binaryCache = [];

    protected array $config;

    public function __construct(array $config = [])
    {
        $this->config = array_replace_recursive([
            'enabled' => false,
            'host' => null,
            'port' => 161,
            'community' => 'public',
            'version' => '2c',
            'timeout' => 3,
            'retries' => 1,
        ], $config);
    }

    public function isEnabled(): bool
    {
        return $this->isEnabledForHost((string) ($this->config['host'] ?? ''));
    }

    public function isEnabledForHost(string $host, array $overrides = []): bool
    {
        $config = $this->buildConfig($host, $overrides);

        return $this->isUsable($config);
    }

    /**
     * Informasi environment SNMP untuk keperluan diagnosis (tidak pernah
     * mengirim nilai community string ke output).
     *
     * @return array<string, mixed>
     */
    public function environment(?array $overrides = []): array
    {
        $config = $this->buildConfig((string) ($this->config['host'] ?? ''), $overrides);

        return [
            'enabled' => (bool) ($config['enabled'] ?? false),
            'host' => trim((string) $config['host']),
            'version' => $this->normalizeVersion($config['version'] ?? '2c'),
            'port' => (int) ($config['port'] ?? 161),
            'timeout' => (int) ($config['timeout'] ?? 3),
            'retries' => (int) ($config['retries'] ?? 1),
            'community_configured' => $this->resolveCommunity($config) !== null,
            'community_source' => $this->communitySource($overrides),
            'php_snmp_extension' => extension_loaded('snmp'),
            'binary_snmpget' => $this->commandExists('snmpget'),
            'binary_snmpwalk' => $this->commandExists('snmpwalk'),
            'backend' => $this->resolveBackend($config),
            'backend_label' => match ($this->resolveBackend($config)) {
                'php-ext' => 'PHP ext-snmp',
                'net-snmp-cli' => 'net-snmp CLI (snmpget/snmpwalk)',
                default => 'tidak ada backend SNMP',
            },
        ];
    }

    /**
     * Ambil data perangkat lewat SNMP.Selalu mengembalikan array dengan
     * pesan error yang nyata (bukan null generik) supaya troubleshooting
     * snmpget/snmpwalk jelas.
     *
     * @return array<string, mixed>
     */
    public function getDeviceInfo(string $host, array $overrides = []): array
    {
        $config = $this->buildConfig($host, $overrides);
        $config['version'] = $this->normalizeVersion($config['version'] ?? '2c');

        $device = $this->emptyResult($host, $config);

        if (trim((string) $config['host']) === '') {
            return $this->fail($device, 'SNMP target host kosong.', 'invalid-host');
        }

        $community = $this->resolveCommunity($config);

        if ($community === null) {
            return $this->fail(
                $device,
                'SNMP community string belum dikonfigurasi. Isi SNMP_COMMUNITY di .env atau kirim override community.',
                'community-missing'
            );
        }

        $config['community'] = $community;

        if (! $this->snmpSupportAvailable($config)) {
            return $this->fail(
                $device,
                'SNMP nonaktif atau tidak ada backend yang tersedia (butuh ekstensi PHP "snmp" atau binary snmpget/snmpwalk).',
                'backend-missing'
            );
        }

        $errors = [];

        try {
            [$device['sys_name'], $sysNameError] = $this->readOid($config, self::OID_SYS_NAME);
            $errors['sys_name'] = $sysNameError;

            [$device['sys_descr'], $sysDescrError] = $this->readOid($config, self::OID_SYS_DESCR);
            $errors['sys_descr'] = $sysDescrError;

            [$device['sys_uptime'], $uptimeError] = $this->readOid($config, self::OID_SYS_UPTIME);
            $errors['sys_uptime'] = $uptimeError;

            [$device['interfaces'], $interfaceError] = $this->readInterfaces($config);
            $errors['interfaces'] = $interfaceError;
        } catch (\Throwable $e) {
            $errors['exception'] = $e->getMessage();
        }

        $device['errors'] = array_filter($errors);

        $device['reachable'] = ! empty($device['sys_name'])
            || ! empty($device['sys_descr'])
            || ! empty($device['sys_uptime'])
            || ! empty($device['interfaces']);

        $device['status'] = $device['reachable'] ? 'reachable' : 'unavailable';

        $device['uptime_seconds'] = $device['reachable'] ? $this->parseTimeticks($device['sys_uptime']) : null;
        $device['uptime_human'] = $device['uptime_seconds'] !== null
            ? $this->formatUptime($device['uptime_seconds'])
            : null;
        $device['interface_summary'] = $device['reachable']
            ? $this->summarizeInterfaces($device['interfaces'])
            : $this->emptyInterfaceSummary();

        if (! $device['reachable']) {
            $firstError = $device['errors']['exception']
                ?? $device['errors']['sys_name']
                ?? $device['errors']['sys_descr']
                ?? $device['errors']['sys_uptime']
                ?? $device['errors']['interfaces']
                ?? 'SNMP tidak mengembalikan data yang didukung (periksa community, versi, dan akses SNMP di perangkat).';

            return $this->fail($device, (string) $firstError, $this->classifyError($device['errors']));
        }

        $device['error'] = null;
        $device['error_type'] = null;

        return $device;
    }

    /**
     * @return array{0: ?string, 1: ?string} Nilai OID dan pesan error asli.
     */
    protected function readOid(array $config, string $oid): array
    {
        $data = $this->runSnmpCommand($config, $this->buildCommand('snmpget', $config, $oid));

        if ($data['exit_code'] !== 0) {
            return [null, $this->formatCommandError('snmpget', $oid, $data)];
        }

        return [$this->normalizeSnmpValue($data['output']), null];
    }

    /**
     * @return array{0: array<int, array<string, mixed>>, 1: ?string}
     */
    protected function readInterfaces(array $config): array
    {
        $descriptions = $this->walkTable($config, self::OID_IF_DESCR, self::OID_IF_DESCR_NUMERIC);
        $statuses = $this->walkTable($config, self::OID_IF_OPER_STATUS, self::OID_IF_OPER_STATUS_NUMERIC);

        if ($descriptions['rows'] === [] && $statuses['rows'] === []) {
            return [[], trim($descriptions['error'].' | '.$statuses['error'], ' |')];
        }

        $interfaceMap = [];

        foreach ($descriptions['rows'] as $index => $value) {
            $interfaceMap[$index]['name'] = $value;
        }

        foreach ($statuses['rows'] as $index => $value) {
            $interfaceMap[$index]['status'] = $this->normalizeInterfaceStatus($value);
        }

        $interfaces = [];

        foreach ($interfaceMap as $index => $interface) {
            $name = trim((string) ($interface['name'] ?? ''));
            $status = $interface['status'] ?? null;

            if ($name === '' && $status === null) {
                continue;
            }

            $interfaces[] = [
                'index' => (string) $index,
                'name' => $name !== '' ? $name : null,
                'status' => $status ?? 'unknown',
            ];
        }

        $errors = array_values(array_filter([
            $descriptions['error'],
            $statuses['error'],
        ]));

        return [$interfaces, $errors === [] ? null : implode(' | ', $errors)];
    }

    /**
     * snmpwalk untuk tabel interface: coba nama MIB dulu, lalu OID numerik
     * (banyak server tidak membawa paket MIB net-snmp).
     *
     * Data parsial tetap dipakai: perangkat sering membalas sebagian baris
     * lalu timeout, dan baris yang sudah diterima tetap valid.
     *
     * @return array{exit_code: int, output: string, error: string, rows: array<int|string, string>}
     */
    protected function walkTable(array $config, string $symbolicOid, string $numericOid): array
    {
        $fallback = null;

        foreach ([$symbolicOid, $numericOid] as $oid) {
            $data = $this->runSnmpCommand($config, $this->buildCommand('snmpwalk', $config, $oid));
            $rows = $this->parseWalkLines($data['output']);
            $error = $data['exit_code'] === 0 ? '' : $this->formatCommandError('snmpwalk', $oid, $data);

            if ($rows !== []) {
                return [
                    'exit_code' => $data['exit_code'],
                    'output' => $data['output'],
                    'error' => $error === '' ? '' : 'partial ('.$error.')',
                    'rows' => $rows,
                ];
            }

            $fallback ??= [
                'exit_code' => $data['exit_code'],
                'output' => $data['output'],
                'error' => $error,
                'rows' => [],
            ];
        }

        return $fallback;
    }

    /**
     * @return array<int, string>
     */
    protected function buildCommand(string $binary, array $config, string $oid): array
    {
        $version = $this->normalizeVersion($config['version'] ?? '2c');
        $prefix = $this->snmpCommandPrefix($config);

        $command = [$binary, ...$prefix];

        // SNMPv3 tidak memakai community string.
        if ($version !== '3') {
            $command[] = '-c';
            $command[] = (string) $config['community'];
        }

        return [
            ...$command,
            '-t',
            (string) (int) ($config['timeout'] ?? 3),
            '-r',
            (string) (int) ($config['retries'] ?? 1),
            $this->targetAddress($config),
            $oid,
        ];
    }

    protected function formatCommandError(string $binary, string $oid, array $data): string
    {
        $output = trim((string) ($data['output'] ?? ''));
        $output = $output !== '' ? $output : 'tanpa pesan dari '.$binary;

        return sprintf(
            '%s %s exit %d: %s',
            $binary,
            $oid,
            (int) ($data['exit_code'] ?? 1),
            preg_replace('/\s+/', ' ', $output) ?? $output
        );
    }

    protected function classifyError(array $errors): string
    {
        $haystack = strtolower(implode(' | ', array_map('strval', $errors)));

        return match (true) {
            str_contains($haystack, 'authorizationerror'), str_contains($haystack, 'authentication') => 'authentication',
            str_contains($haystack, 'unknownuser'), str_contains($haystack, 'unknownengineid') => 'v3-handshake',
            str_contains($haystack, 'timeout'), str_contains($haystack, 'no response') => 'timeout',
            str_contains($haystack, 'not resolved'), str_contains($haystack, 'name or service') => 'dns',
            str_contains($haystack, 'no such object'), str_contains($haystack, 'nosuchobject') => 'oid-unsupported',
            str_contains($haystack, 'community') => 'community-rejected',
            default => 'unavailable',
        };
    }

    /**
     * @return array<string, mixed>
     */
    protected function emptyResult(string $host, array $config): array
    {
        return [
            'host' => trim((string) ($config['host'] ?? $host)),
            'version' => $this->normalizeVersion($config['version'] ?? '2c'),
            'backend' => $this->resolveBackend($config),
            'reachable' => false,
            'status' => 'unavailable',
            'sys_name' => null,
            'sys_descr' => null,
            'sys_uptime' => null,
            'uptime_seconds' => null,
            'uptime_human' => null,
            'interfaces' => [],
            'interface_summary' => $this->emptyInterfaceSummary(),
            'error' => null,
            'error_type' => null,
            'errors' => [],
            'polled_at' => now()->format('Y-m-d H:i:s'),
        ];
    }

    /**
     * @param  array<string, mixed>  $device
     * @return array<string, mixed>
     */
    protected function fail(array $device, string $message, string $errorType): array
    {
        $device['reachable'] = false;
        $device['status'] = 'unavailable';
        $device['error'] = $message;
        $device['error_type'] = $errorType;

        return $device;
    }

    /**
     * @return array{total: int, up: int, down: int, other: int}
     */
    protected function emptyInterfaceSummary(): array
    {
        return ['total' => 0, 'up' => 0, 'down' => 0, 'other' => 0];
    }

    /**
     * @param  array<int, array<string, mixed>>  $interfaces
     * @return array{total: int, up: int, down: int, other: int}
     */
    protected function summarizeInterfaces(array $interfaces): array
    {
        $summary = $this->emptyInterfaceSummary();
        $summary['total'] = count($interfaces);

        foreach ($interfaces as $interface) {
            $status = (string) ($interface['status'] ?? 'unknown');

            if ($status === 'up') {
                $summary['up']++;
            } elseif ($status === 'down') {
                $summary['down']++;
            } else {
                $summary['other']++;
            }
        }

        return $summary;
    }

    protected function parseTimeticks(?string $raw): ?int
    {
        if ($raw === null) {
            return null;
        }

        if (! preg_match('/(\d+)/', $raw, $matches)) {
            return null;
        }

        // Timeticks = hundredths of a second.
        return (int) round(((int) $matches[1]) / 100);
    }

    protected function formatUptime(int $seconds): string
    {
        $days = intdiv($seconds, 86400);
        $hours = intdiv($seconds % 86400, 3600);
        $minutes = intdiv($seconds % 3600, 60);
        $remaining = $seconds % 60;

        $parts = [];

        if ($days > 0) {
            $parts[] = $days.'h';
        }

        if ($days > 0 || $hours > 0) {
            $parts[] = $hours.'j';
        }

        $parts[] = $minutes.'m';
        $parts[] = $remaining.'d';

        return implode(' ', $parts);
    }

    /**
     * @return array<string, mixed>
     */
    protected function buildConfig(string $host, array $overrides = []): array
    {
        $base = $this->config;
        $base['host'] = $host;

        foreach ($overrides as $key => $value) {
            if ($value !== null) {
                $base[$key] = $value;
            }
        }

        return $base;
    }

    protected function isUsable(array $config): bool
    {
        if (! (bool) ($config['enabled'] ?? false)) {
            return false;
        }

        if (trim((string) ($config['host'] ?? '')) === '') {
            return false;
        }

        return $this->resolveCommunity($config) !== null && $this->snmpSupportAvailable($config);
    }

    protected function snmpSupportAvailable(array $config = []): bool
    {
        $config = $config ?: $this->config;

        if (! (bool) ($config['enabled'] ?? false)) {
            return false;
        }

        return $this->resolveBackend($config) !== null;
    }

    /**
     * Backend SNMP yang benar-benar dipakai: ekstensi PHP lebih dulu,
     * lalu binary net-snmp. Mengembalikan null bila tidak ada.
     */
    protected function resolveBackend(array $config): ?string
    {
        if ($this->shouldUsePhpSnmpExtension($config)) {
            return 'php-ext';
        }

        if ($this->commandExists('snmpget') || $this->commandExists('snmpwalk')) {
            return 'net-snmp-cli';
        }

        return null;
    }

    /**
     * Community string: override > config > null (tanpa nilai default yang
     * disembunyikan di kode). Tidak pernah mengirim string kosong ke snmpget.
     *
     * @return array{0: ?string, 1: string} Nilai community dan sumbernya.
     */
    protected function communityWithSource(array $config, array $overrides = []): array
    {
        $override = trim((string) ($overrides['community'] ?? ''));

        if ($override !== '') {
            return [$override, 'override'];
        }

        $configured = trim((string) ($config['community'] ?? ''));

        if ($configured !== '') {
            return [$configured, 'config'];
        }

        return [null, 'missing'];
    }

    protected function resolveCommunity(array $config): ?string
    {
        return $this->communityWithSource($config)[0];
    }

    protected function communitySource(array $overrides = []): string
    {
        $config = $this->config;

        return $this->communityWithSource($config, $overrides)[1];
    }

    /**
     * Terima "1", "v1", "2c", "v2c", "2", "3", "v3" -> "1" | "2c" | "3".
     */
    protected function normalizeVersion(mixed $version): string
    {
        $value = strtolower(trim((string) $version));

        return match ($value) {
            '1', 'v1' => '1',
            '3', 'v3' => '3',
            default => '2c',
        };
    }

    /**
     * Flag versi + flag khusus v3. Tidak ada kredensial yang ditulis di kode:
     * username/passphrase hanya dibaca dari config (env).
     *
     * @return array<int, string>
     */
    protected function snmpCommandPrefix(array $config): array
    {
        $version = $this->normalizeVersion($config['version'] ?? '2c');
        $prefix = ['-v'.$version];

        if ($version !== '3') {
            return $prefix;
        }

        $v3 = (array) config('snmp.v3', []);

        $prefix[] = '-l';
        $prefix[] = (string) ($v3['security_level'] ?: 'noAuthNoPriv');

        $prefix[] = '-u';
        $prefix[] = (string) ($v3['username'] ?: 'snmpuser');

        if (! empty($v3['auth_protocol'])) {
            $prefix[] = '-a';
            $prefix[] = (string) $v3['auth_protocol'];
        }

        if (! empty($v3['auth_passphrase'])) {
            $prefix[] = '-A';
            $prefix[] = (string) $v3['auth_passphrase'];
        }

        if (! empty($v3['priv_protocol'])) {
            $prefix[] = '-x';
            $prefix[] = (string) $v3['priv_protocol'];
        }

        if (! empty($v3['priv_passphrase'])) {
            $prefix[] = '-X';
            $prefix[] = (string) $v3['priv_passphrase'];
        }

        return $prefix;
    }

    protected function normalizeSnmpValue(string $raw): ?string
    {
        $trimmed = trim($raw);

        if ($trimmed === '' || $this->isErrorOutput($trimmed)) {
            return null;
        }

        if (preg_match('/=\s*(?:STRING|OCTET STRING|INTEGER|Gauge32|Counter32|Counter64|Timeticks|OID|Hex-STRING|UInteger32)\s*:\s*(.*)$/i', $trimmed, $matches)) {
            $value = trim($matches[1]);

            // Timeticks: ambil angka di dalam kurung pertama (ratusan detik).
            if (preg_match('/^\((\d+)\)/', $value, $ticks)) {
                return $ticks[1];
            }

            if (preg_match('/^"(.*)"$/s', $value, $quoted)) {
                return $quoted[1];
            }

            if (preg_match('/^\((.*)\)\s*$/s', $value, $captured)) {
                return $captured[1];
            }

            return $value;
        }

        if (preg_match('/=\s*(.*)$/s', $trimmed, $matches)) {
            $value = trim($matches[1]);

            if (str_starts_with($value, '"') && str_ends_with($value, '"')) {
                return substr($value, 1, -1);
            }

            return $value;
        }

        return null;
    }

    protected function isErrorOutput(string $output): bool
    {
        $lower = strtolower($output);

        foreach (['no such object', 'no such instance', 'no more variables', 'timeout', 'unknown community', 'authorizationerror'] as $needle) {
            if (str_contains($lower, $needle)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return array<int|string, string>
     */
    protected function parseWalkLines(string $output): array
    {
        $lines = preg_split('/\r\n|\r|\n/', $output) ?: [];
        $result = [];

        foreach ($lines as $line) {
            $line = trim($line);

            if ($line === '') {
                continue;
            }

            // Buang prefiks MIB bertingkat: SNMPv2-MIB::sysDescr.0
            $normalized = preg_replace('/^(?:[A-Za-z0-9_-]+::)+/', '', $line);

            if (! is_string($normalized) || $normalized === '') {
                continue;
            }

            /*
             * OID bisa muncul sebagai nama simbolik (ifDescr.1), angka
             * (1.3.6.1.2.1.2.2.1.2.1), atau dengan prefiks iso (iso.3.6.1...).
             * Baris tanpa "=" (mis. "Timeout: No Response ...") dilewati.
             */
            if (! preg_match('/^([A-Za-z0-9_.]*\.[0-9]+)\s*=\s*(.*)$/s', $normalized, $matches)) {
                continue;
            }

            $index = $this->extractIndex($matches[1]);
            $value = trim($matches[2]);
            $value = preg_replace('/^(?:STRING|OCTET STRING|INTEGER|Gauge32|Counter32|Counter64|Timeticks|OID|Hex-STRING|UInteger32)\s*:\s*/i', '', (string) $value);
            $value = trim((string) $value);

            if (preg_match('/^"(.*)"$/s', $value, $quoted)) {
                $value = $quoted[1];
            } elseif (preg_match('/^([A-Za-z0-9_-]+)\(([^)]*)\)$/', $value, $typed)) {
                $value = $typed[1];
            } elseif (preg_match('/^\(([^)]*)\)\s*(.*)$/', $value, $wrapped)) {
                $value = trim($wrapped[2]) !== '' ? trim($wrapped[2]) : trim($wrapped[1]);
            }

            $result[$index] = $value;
        }

        return $result;
    }

    protected function extractIndex(string $oid): string
    {
        if (preg_match('/\.([0-9]+)$/', $oid, $matches)) {
            return $matches[1];
        }

        return $oid;
    }

    /**
     * Klasifikasi status interface akurat: pencocokan dilakukan per token
     * (label atau kode enum), bukan substring, supaya "lowerLayerDown" tidak
     * salah dianggap "down".
     */
    protected function normalizeInterfaceStatus(string $value): string
    {
        $normalized = strtolower(trim($value));

        if ($normalized === '') {
            return 'unknown';
        }

        // up(1) / INTEGER: up(1) -> up
        if (preg_match('/^([a-z_ -]+?)\s*\(\s*\d+\s*\)$/', $normalized, $enum)) {
            $normalized = trim($enum[1]);
        }

        $collapsed = str_replace([' ', '-', '_'], '', $normalized);

        if (isset(self::INTERFACE_STATUS_BY_LABEL[$normalized])) {
            return self::INTERFACE_STATUS_BY_LABEL[$normalized];
        }

        if (isset(self::INTERFACE_STATUS_BY_LABEL[$collapsed])) {
            return self::INTERFACE_STATUS_BY_LABEL[$collapsed];
        }

        if (preg_match('/(\d+)/', $normalized, $code) && isset(self::INTERFACE_STATUS_BY_CODE[(int) $code[1]])) {
            return self::INTERFACE_STATUS_BY_CODE[(int) $code[1]];
        }

        return 'unknown';
    }

    protected function targetAddress(array $config): string
    {
        return sprintf('udp:%s:%d', trim((string) $config['host']), (int) $config['port']);
    }

    /**
     * @return array{exit_code: int, output: string}
     */
    protected function runSnmpCommand(array $config, array $command): array
    {
        if ($this->shouldUsePhpSnmpExtension($config)) {
            $phpResult = $this->runPhpSnmpCommand($config, $command);

            if ($phpResult !== null) {
                return $phpResult;
            }
        }

        if ($command === [] || ! $this->commandExists((string) $command[0])) {
            $binary = (string) ($command[0] ?? 'snmpget');

            return [
                'exit_code' => 127,
                'output' => $binary.': command not found',
            ];
        }

        $commandString = implode(' ', array_map(static fn ($part) => escapeshellarg((string) $part), $command));
        $output = [];
        $exitCode = 0;

        exec($commandString.' 2>&1', $output, $exitCode);

        return [
            'exit_code' => $exitCode,
            'output' => implode("\n", $output),
        ];
    }

    protected function shouldUsePhpSnmpExtension(array $config): bool
    {
        return extension_loaded('snmp') && ! empty($config['host']);
    }

    /**
     * Jalur ekstensi PHP. Versi mengikuti konfigurasi (tidak hardcode 2c) dan
     * operasi yang dipakai menyesuaikan perintah: snmpget -> get(),
     * snmpwalk -> walk() untuk tabel interface.
     *
     * @return array{exit_code: int, output: string}|null
     */
    protected function runPhpSnmpCommand(array $config, array $command): ?array
    {
        if (! extension_loaded('snmp') || $command === []) {
            return null;
        }

        $binary = strtolower(basename((string) $command[0]));
        $oid = (string) $command[array_key_last($command)];

        try {
            $session = new \SNMP(
                $this->phpSnmpVersion($config),
                (string) $config['host'],
                $this->resolveCommunity($config) ?? '',
                (int) ((int) ($config['timeout'] ?? 3) * 1000000)
            );

            $this->applyPhpSnmpV3Security($session, $config);

            if ($binary === 'snmpwalk') {
                $session->setMaxRepetitions(64);

                $values = $session->walk($oid, 1000);

                if (! is_array($values) || $values === []) {
                    return [
                        'exit_code' => 1,
                        'output' => 'ext-snmp: '.$oid.' tidak mengembalikan data',
                    ];
                }

                $lines = [];

                foreach ($values as $valueOid => $value) {
                    $lines[] = $valueOid.' = '.$this->stringifyPhpSnmpValue($value);
                }

                return [
                    'exit_code' => 0,
                    'output' => implode("\n", $lines),
                ];
            }

            $value = $session->get($oid);

            if ($value === false || is_null($value)) {
                return [
                    'exit_code' => 1,
                    'output' => 'ext-snmp: '.$oid.' tidak mengembalikan data',
                ];
            }

            return [
                'exit_code' => 0,
                'output' => $oid.' = '.$this->stringifyPhpSnmpValue($value),
            ];
        } catch (\Throwable $e) {
            return [
                'exit_code' => 1,
                'output' => 'ext-snmp error: '.$e->getMessage(),
            ];
        }
    }

    protected function phpSnmpVersion(array $config): int
    {
        return match ($this->normalizeVersion($config['version'] ?? '2c')) {
            '1' => \SNMP::VERSION_1,
            '3' => \SNMP::VERSION_3,
            default => \SNMP::VERSION_2C,
        };
    }

    /**
     * SNMPv3 lewat ext-snmp. Semua kredensial dibaca dari config (env),
     * tidak ada secret yang ditulis di kode.
     */
    protected function applyPhpSnmpV3Security(\SNMP $session, array $config): void
    {
        if ($this->normalizeVersion($config['version'] ?? '2c') !== '3') {
            return;
        }

        $v3 = (array) config('snmp.v3', []);

        if (! empty($v3['username'])) {
            $session->setSecurityName((string) $v3['username']);
        }

        $level = (string) ($v3['security_level'] ?: 'noAuthNoPriv');

        if ($level !== 'noAuthNoPriv') {
            $session->setSNMPv3SecurityLevel($level);
        }

        if (! empty($v3['auth_protocol'])) {
            $session->setAuthProtocol((string) $v3['auth_protocol']);
        }

        if (! empty($v3['auth_passphrase'])) {
            $session->setAuthPassphrase((string) $v3['auth_passphrase']);
        }

        if (! empty($v3['priv_protocol'])) {
            $session->setPrivProtocol((string) $v3['priv_protocol']);
        }

        if (! empty($v3['priv_passphrase'])) {
            $session->setPrivPassphrase((string) $v3['priv_passphrase']);
        }
    }

    protected function stringifyPhpSnmpValue(mixed $value): string
    {
        if (is_bool($value)) {
            return $value ? 'up' : 'down';
        }

        if (is_array($value)) {
            return 'STRING: "'.implode(',', array_map('strval', $value)).'"';
        }

        $string = (string) $value;

        if (! str_contains($string, '"') && preg_match('/^[0-9A-Za-z ._\/-]+$/', $string)) {
            return 'STRING: "'.$string.'"';
        }

        return 'STRING: "'.$string.'"';
    }

    protected function commandExists(string $command): bool
    {
        $command = strtolower(trim($command));

        if ($command === '') {
            return false;
        }

        if (array_key_exists($command, self::$binaryCache)) {
            return self::$binaryCache[$command];
        }

        $which = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN' ? 'where' : 'which';

        $output = [];
        $exitCode = 0;
        exec($which.' '.escapeshellarg($command).' 2>/dev/null', $output, $exitCode);

        return self::$binaryCache[$command] = $exitCode === 0;
    }
}
