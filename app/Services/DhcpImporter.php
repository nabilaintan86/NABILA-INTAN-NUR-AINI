<?php

namespace App\Services;

use App\Models\DhcpLease;
use Illuminate\Support\Carbon;
use InvalidArgumentException;

/**
 * Importer data DHCP Lease dari file `devices.csv`.
 *
 * File devices.csv adalah daftar device REAL Radnext (bukan export server DHCP,
 * bukan juga file seed devices.csv lama). Kolom utama:
 *   mac_address, ip_address, hostname, vendor, router, location,
 *   lease_start, lease_end (opsional), first_seen, last_seen (opsional), comment.
 *
 * Setiap baris dicocokkan dengan tabel `device` (by ip_address):
 *   cocok    -> classification = 'known' + device_id (hanya referensi, tidak duplikasi device)
 *   punya identitas (hostname/vendor) tapi IP belum terdaftar -> 'new'
 *   tanpa kecocokan & tanpa identitas                          -> 'unknown'
 *
 * Bila kolom lease tidak tersedia, waktu turunan dihitung dari timestamp
 * yang ada (first_seen / last_seen).
 */
class DhcpImporter
{
    /**
     * Lokasi default file devices.csv (relatif terhadap root project).
     */
    public const DEFAULT_FILE = 'storage/app/dhcp/devices.csv';

    protected const HEADER_ALIASES = [
        'mac_address' => ['mac_address', 'mac', 'macaddr'],
        'ip_address' => ['ip_address', 'ip', 'ipaddr'],
        'hostname' => ['hostname', 'host', 'client_hostname', 'name'],
        'client_id' => ['client_id', 'clientid', 'client-id', 'client_identifier'],
        'vendor' => ['vendor', 'oui', 'vendor_class'],
        'router' => ['router', 'server', 'dhcp_server'],
        'location' => ['location', 'pop', 'site', 'lokasi'],
        'lease_start' => ['lease_start', 'start', 'starts', 'lease_start_dt'],
        'lease_end' => ['lease_end', 'end', 'ends', 'expiry', 'expires'],
        'first_seen' => ['first_seen', 'firstseen', 'first_seen_at'],
        'last_seen' => ['last_seen', 'lastseen', 'last_seen_at'],
        'comment' => ['comment', 'keterangan', 'note', 'deskripsi'],
    ];

    protected const REQUIRED = ['mac_address', 'ip_address'];

    /**
     * Path absolut file devices.csv default (storage/app/dhcp/devices.csv).
     */
    public static function defaultFilePath(): string
    {
        return base_path(self::DEFAULT_FILE);
    }

    /**
     * Auto-sync aktif bila file devices.csv ada di path default
     * (bisa dimatikan lewat config('dhcp_auto_sync') saat testing).
     */
    public static function autoSyncEnabled(): bool
    {
        return (bool) config('dhcp_auto_sync', true);
    }

    /**
     * Import dari path file.
     *
     * @param  ?callable  $onProgress  dipanggil tiap baris: fn(int $index, int $total)
     *
     * @throws InvalidArgumentException
     */
    public function importFile(string $path, ?string $source = null, ?callable $onProgress = null): array
    {
        if (! is_file($path)) {
            throw new InvalidArgumentException("File tidak ditemukan: {$path}");
        }

        return $this->importString(
            (string) file_get_contents($path),
            $source ?: basename($path),
            $onProgress
        );
    }

    /**
     * Import dari isi string CSV.
     */
    public function importString(string $content, ?string $source = 'devices.csv', ?callable $onProgress = null): array
    {
        $content = $this->normalizeEncoding($content);

        $rows = $this->parseCsv($content);
        if (count($rows) < 2) {
            throw new InvalidArgumentException('CSV kosong atau tidak memiliki baris data.');
        }

        $headers = array_map(
            fn ($h) => strtolower(trim((string) $h)),
            array_shift($rows)
        );

        $col = $this->resolveColumns($headers);

        $missing = array_values(array_filter(self::REQUIRED, fn ($f) => ! array_key_exists($f, $col)));
        if ($missing !== []) {
            throw new InvalidArgumentException(
                'Kolom '.implode(', ', $missing).' tidak ditemukan. '.
                'Pastikan file devices.csv memiliki kolom: '.
                'mac_address, ip_address, hostname, vendor, router, location, '.
                'lease_start, lease_end, first_seen, last_seen.'
            );
        }

        $recognition = new DhcpRecognition;
        $now = now();

        $result = [
            'total' => 0,
            'imported' => 0,
            'skipped' => 0,
            'known' => 0,
            'new' => 0,
            'unknown' => 0,
        ];

        $totalRows = count($rows);
        $index = 0;

        foreach ($rows as $line => $row) {
            if (empty(array_filter($row))) {
                continue;
            }

            $index++;

            try {
                $this->importRow($recognition, $row, $col, $source, $result, $now);
            } catch (\Throwable $e) {
                // Baris bermasalah dilewati, tidak menggagalkan seluruh import.
                $result['skipped']++;
            }

            if ($onProgress !== null) {
                $onProgress($index, $totalRows);
            }
        }

        return $result;
    }

    /**
     * Proses satu baris CSV menjadi record dhcp_leases.
     */
    protected function importRow(DhcpRecognition $recognition, array $row, array $col, ?string $source, array &$result, Carbon $now): void
    {
        $get = fn (string $field) => $this->cell($row, $col, $field);

        $ip = strtolower(trim((string) $get('ip_address')));
        $mac = $this->normalizeMac((string) $get('mac_address'));

        // dhcp_leases.ip_address wajib terisi (NOT NULL).
        if ($ip === '') {
            $result['skipped']++;

            return;
        }

        $hostname = $get('hostname');
        $clientId = $get('client_id');
        $vendor = $get('vendor');
        $router = $get('router');
        $location = $get('location');
        $comment = $get('comment');

        $firstSeen = $this->parseDate($get('first_seen'));
        $lastSeen = $this->parseDate($get('last_seen'));
        [$leaseStart, $leaseEnd] = $this->deriveTimes($get, $firstSeen, $lastSeen);

        $classification = $recognition->classify([
            'ip' => $ip,
            'hostname' => $hostname,
            'vendor' => $vendor,
            'last_seen' => $lastSeen ?? $firstSeen,
            'first_seen' => $firstSeen,
            'lease_start' => $leaseStart,
        ], $now);

        // Referensi ke device resmi (tanpa membuat baris device baru).
        $deviceId = null;
        if ($classification === 'known') {
            $deviceId = $recognition->knownByIp()[$ip]['device_id'] ?? null;
        }

        $resolvedLocation = $recognition->resolveLocation($ip, $router, $location)
            ?: ($router ?: $location);

        DhcpLease::updateOrCreate(
            ['mac_address' => $mac ?: null, 'ip_address' => $ip],
            [
                'device_id' => $deviceId,
                'hostname' => $hostname,
                'client_id' => $clientId,
                'vendor' => $vendor,
                'router' => $router,
                'location' => $resolvedLocation,
                'lease_start' => $leaseStart,
                'lease_end' => $leaseEnd,
                'classification' => $classification,
                'first_seen' => $firstSeen,
                'last_seen' => $lastSeen,
                'comment' => $comment,
                'source' => $source,
            ]
        );

        $result['total']++;
        $result['imported']++;
        $result[$classification]++;
    }

    /**
     * Bersihkan BOM & normalisasi encoding ke UTF-8.
     *
     * Mendukung BOM UTF-8, UTF-16 LE/BE, dan fallback windows-1252/latin1
     * bila isi file bukan UTF-8 yang valid.
     *
     * @throws InvalidArgumentException
     */
    protected function normalizeEncoding(string $content): string
    {
        if (str_starts_with($content, "\xEF\xBB\xBF")) {
            $content = substr($content, 3);
        } elseif (str_starts_with($content, "\xFF\xFE")) {
            $content = mb_convert_encoding(substr($content, 2), 'UTF-8', 'UTF-16LE');
        } elseif (str_starts_with($content, "\xFE\xFF")) {
            $content = mb_convert_encoding(substr($content, 2), 'UTF-8', 'UTF-16BE');
        }

        if (! mb_check_encoding($content, 'UTF-8')) {
            $converted = @mb_convert_encoding($content, 'UTF-8', 'auto');
            if (! $converted || ! mb_check_encoding($converted, 'UTF-8')) {
                throw new InvalidArgumentException(
                    'File devices.csv tidak dalam encoding yang valid (UTF-8). '.
                    'Simpan ulang file menggunakan encoding UTF-8 lalu coba lagi.'
                );
            }

            return $converted;
        }

        return $content;
    }

    /**
     * Turunkan lease_start / lease_end dari timestamps yang tersedia.
     * Lease end default 24 jam setelah last/first seen (durasi khas DHCP).
     *
     * @return array{0: ?Carbon, 1: ?Carbon}
     */
    protected function deriveTimes(callable $get, ?Carbon $firstSeen, ?Carbon $lastSeen): array
    {
        $leaseStart = $this->parseDate($get('lease_start'));
        $leaseEnd = $this->parseDate($get('lease_end'));

        if ($leaseStart === null) {
            $leaseStart = $firstSeen ?? ($lastSeen?->copy()->subDay());
        }

        if ($leaseEnd === null) {
            $leaseEnd = $lastSeen
                ? $lastSeen->copy()->addHours(24)
                : ($firstSeen ? $firstSeen->copy()->addHours(24) : null);
        }

        return [$leaseStart, $leaseEnd];
    }

    protected function cell(array $row, array $col, string $field): ?string
    {
        $idx = $col[$field] ?? null;
        if ($idx === null || ! array_key_exists($idx, $row)) {
            return null;
        }

        $value = trim((string) $row[$idx]);

        return $value === '' ? null : $value;
    }

    protected function resolveColumns(array $headers): array
    {
        $normalize = fn (string $value) => preg_replace('/[^a-z0-9_]/i', '', strtolower($value));
        $map = [];

        foreach (self::HEADER_ALIASES as $field => $aliases) {
            $wanted = array_map($normalize, $aliases);
            foreach ($headers as $index => $header) {
                if ($header === '') {
                    continue;
                }

                if (in_array($normalize($header), $wanted, true)) {
                    $map[$field] = $index;
                    break;
                }
            }
        }

        return $map;
    }

    protected function normalizeMac(string $mac): ?string
    {
        $mac = strtoupper(trim($mac));
        if ($mac === '') {
            return null;
        }

        $mac = preg_replace('/[^0-9A-Fa-f:\.\-]/', '', $mac);
        $mac = str_replace(['.', '-'], ':', $mac);

        if (! str_contains($mac, ':') && strlen($mac) === 12) {
            return implode(':', str_split($mac, 2));
        }

        $parts = array_values(array_filter(explode(':', $mac)));
        if ($parts === []) {
            return null;
        }

        return implode(':', $parts);
    }

    protected function parseDate(?string $value): ?Carbon
    {
        if ($value === null) {
            return null;
        }

        $value = trim($value);
        if ($value === '') {
            return null;
        }

        try {
            // Tangani serial angka Excel (mis. "45231.5").
            if (preg_match('/^\d+(\.\d+)?$/', $value) && (float) $value > 20000) {
                return Carbon::createFromFormat('Y-m-d H:i:s', '1899-12-30 00:00:00')
                    ->addDays((float) $value);
            }

            return Carbon::parse($value);
        } catch (\Throwable) {
            return null;
        }
    }

    protected function parseCsv(string $content): array
    {
        $stream = fopen('php://temp', 'r+');
        fwrite($stream, $content);
        rewind($stream);

        $rows = [];
        while (($row = fgetcsv($stream)) !== false) {
            $rows[] = $row;
        }

        fclose($stream);

        return $rows;
    }
}
