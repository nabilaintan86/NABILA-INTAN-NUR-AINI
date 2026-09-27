<?php

namespace App\Services;

use App\Models\Device;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * Orkestrasi SNMP untuk pipeline monitoring.
 *
 * Aturan main:
 * - SNMP hanya PELENGKAP. Status up/down/warning tetap murni hasil ICMP.
 * - Tidak pernah melempar exception: kegagalan SNMP tidak boleh menggagalkan
 *   monitoring, hanya dicatat sebagai informasi tambahan.
 * - Tidak dijalankan di proses web (hanya command polling), dan hasilnya
 *   di-cache sehingga tidak ada request SNMP setiap menit untuk device yang sama.
 */
class SnmpInspectionService
{
    public const SKIP_DISABLED = 'disabled';

    public const SKIP_SCOPE = 'di luar scope SNMP';

    public const SKIP_ICMP_DOWN = 'ICMP down';

    public const SKIP_BUDGET = 'budget probing habis';

    public const SKIP_INVALID_TARGET = 'IP tidak valid';

    protected float $cycleStartedAt = 0.0;

    protected int $probed = 0;

    protected int $skipped = 0;

    public function __construct(protected SnmpService $snmp) {}

    public function isEnabled(): bool
    {
        return (bool) config('snmp.enabled', false) && $this->mode() !== 'disabled';
    }

    public function mode(): string
    {
        $mode = strtolower(trim((string) config('snmp.mode', 'protocol')));

        return in_array($mode, ['disabled', 'protocol', 'list', 'all'], true) ? $mode : 'protocol';
    }

    /**
     * Mulai siklus polling baru: reset penghitung & budget waktu probing SNMP.
     */
    public function beginCycle(): void
    {
        $this->cycleStartedAt = microtime(true);
        $this->probed = 0;
        $this->skipped = 0;
    }

    public function probedCount(): int
    {
        return $this->probed;
    }

    public function skippedCount(): int
    {
        return $this->skipped;
    }

    public function budgetRemaining(): float
    {
        $budget = (int) config('snmp.cycle_budget_seconds', 20);

        if ($budget <= 0) {
            return (float) PHP_INT_MAX;
        }

        return max(0.0, $budget - (microtime(true) - $this->cycleStartedAt));
    }

    /**
     * Titik masuk tunggal untuk command polling.
     *
     * @return array{
     *     inspected: bool,
     *     skipped_reason: ?string,
     *     summary: ?string,
     *     result: array<string, mixed>
     * }
     */
    public function inspectIfEligible(Device $device, ?string $icmpStatus = null, array $overrides = []): array
    {
        $skip = $this->skipReason($device, $icmpStatus);

        if ($skip !== null) {
            $this->skipped++;

            return [
                'inspected' => false,
                'skipped_reason' => $skip,
                'summary' => null,
                'result' => [],
            ];
        }

        return $this->inspect($device, $overrides);
    }

    /**
     * @return array{
     *     inspected: bool,
     *     skipped_reason: ?string,
     *     summary: ?string,
     *     result: array<string, mixed>
     * }
     */
    public function inspect(Device $device, array $overrides = [], bool $useCache = true): array
    {
        $ip = trim((string) $device->ip_address);
        $ttl = max(0, (int) config('snmp.cache_ttl', 300));
        $useCache = $useCache && $ttl > 0 && $overrides === [];
        $cacheKey = 'snmp:device:'.$device->getKey().':'.sha1($ip);

        try {
            if ($useCache) {
                $result = Cache::remember($cacheKey, $ttl, fn () => $this->probe($device, $ip));
            } else {
                $result = $this->probe($device, $ip);
            }
        } catch (\Throwable $e) {
            // Jaring pengaman: SNMP tidak boleh menjatuhkan siklus monitoring.
            Log::warning('SNMP inspection gagal diproses', [
                'device' => $device->name,
                'ip' => $ip,
                'error' => $e->getMessage(),
            ]);

            $result = [
                'reachable' => false,
                'status' => 'unavailable',
                'host' => $ip,
                'version' => (string) config('snmp.version', '2c'),
                'sys_name' => null,
                'sys_descr' => null,
                'sys_uptime' => null,
                'uptime_human' => null,
                'interfaces' => [],
                'interface_summary' => ['total' => 0, 'up' => 0, 'down' => 0, 'other' => 0],
                'error' => 'SNMP inspection error: '.$e->getMessage(),
                'error_type' => 'exception',
                'errors' => ['exception' => $e->getMessage()],
            ];
        }

        $this->probed++;

        if (! ($result['reachable'] ?? false)) {
            Log::info('SNMP tidak reachable', [
                'device' => $device->name,
                'ip' => $ip,
                'error_type' => $result['error_type'] ?? null,
                'error' => $result['error'] ?? null,
            ]);
        }

        return [
            'inspected' => true,
            'skipped_reason' => null,
            'summary' => $this->summary($result),
            'result' => $result,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    protected function probe(Device $device, string $ip): array
    {
        return $this->snmp->getDeviceInfo($ip, [
            'enabled' => true,
            'community' => $this->communityOverride(),
            'version' => config('snmp.version') ?: '2c',
            'port' => (int) config('snmp.port', 161),
            'timeout' => (int) config('snmp.timeout', 3),
            'retries' => (int) config('snmp.retries', 1),
        ]);
    }

    /**
     * Community per device tidak ada di skema, jadi yang dipakai community dari
     * config (atau override eksplisit pemanggil). Tidak ada credential di kode.
     */
    protected function communityOverride(): ?string
    {
        $community = trim((string) config('snmp.community', ''));

        return $community !== '' ? $community : null;
    }

    /**
     * Alasan device tidak diperiksa SNMP pada siklus ini.
     */
    public function skipReason(Device $device, ?string $icmpStatus = null): ?string
    {
        if (! $this->isEnabled()) {
            return self::SKIP_DISABLED;
        }

        $ip = trim((string) $device->ip_address);

        if ($ip === '' || ! filter_var($ip, FILTER_VALIDATE_IP)) {
            return self::SKIP_INVALID_TARGET;
        }

        if (config('snmp.only_when_up', true) && $icmpStatus === 'down') {
            return self::SKIP_ICMP_DOWN;
        }

        if (! $this->inScope($device, $ip)) {
            return self::SKIP_SCOPE;
        }

        if ($this->budgetRemaining() <= 0.0) {
            return self::SKIP_BUDGET;
        }

        return null;
    }

    protected function inScope(Device $device, string $ip): bool
    {
        return match ($this->mode()) {
            'all' => true,
            'list' => in_array($ip, array_map('trim', (array) config('snmp.targets', [])), true),
            'protocol' => strtoupper((string) $device->display_protocol) === 'SNMP',
            default => false,
        };
    }

    /**
     * Ringkasan singkat siap disimpan di log (reason) / dicetak ke console.
     *
     * @param  array<string, mixed>  $result
     */
    public function summary(array $result): string
    {
        if (! ($result['reachable'] ?? false)) {
            $type = $result['error_type'] ?? 'unavailable';
            $error = (string) ($result['error'] ?? 'tidak diketahui');

            return Str::limit('SNMP gagal ('.$type.'): '.$error, 200);
        }

        $parts = ['SNMP OK'];

        if (! empty($result['sys_name'])) {
            $parts[] = (string) $result['sys_name'];
        }

        if (! empty($result['uptime_human'])) {
            $parts[] = 'uptime '.$result['uptime_human'];
        }

        $summary = (array) ($result['interface_summary'] ?? []);

        if ((int) ($summary['total'] ?? 0) > 0) {
            $parts[] = sprintf(
                'iface %d (%d up/%d down/%d lainnya)',
                (int) $summary['total'],
                (int) ($summary['up'] ?? 0),
                (int) ($summary['down'] ?? 0),
                (int) ($summary['other'] ?? 0)
            );
        }

        return Str::limit(implode(' ', $parts), 200);
    }

    /**
     * Ringkasan satu-baris untuk ditempel ke kolom reason log aktivitas.
     */
    public function decorateReason(?string $reason, array $inspection): string
    {
        $summary = $inspection['summary'] ?? null;

        if (! empty($summary)) {
            return trim(($reason !== null && $reason !== '' ? $reason : 'Normal').' | '.$summary);
        }

        return (string) ($reason ?? '');
    }

    public function forget(Device $device): void
    {
        $key = 'snmp:device:'.$device->getKey().':'.sha1(trim((string) $device->ip_address));

        try {
            Cache::forget($key);
        } catch (\Throwable $e) {
            Log::warning('Gagal menghapus cache SNMP', [
                'device' => $device->name,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
