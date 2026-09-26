<?php

namespace App\Services;

use App\Models\Device;
use Illuminate\Support\Carbon;

/**
 * Mesin pengenal perangkat (Device Recognition) untuk halaman DHCP Lease.
 *
 * Basis data yang dipakai adalah daftar perangkat resmi devices.csv yang
 * diteruskan lewat DeviceCsvService::officialDevices() (state up/down tetap
 * dari tabel `device`), sehingga "Known Device" benar-benar berasal dari data
 * devices.csv — bukan daftar hardcoded dan bukan sekadar semua isi tabel.
 *
 * Karena 5 IP punya dua baris di devices.csv, "Known Device" dihitung dari
 * IP unik resmi (110), bukan jumlah baris (115).
 *
 * Klasifikasi tiap lease:
 *   known   - IP lease cocok dengan IP perangkat resmi di devices.csv.
 *   new     - IP tidak dikenal tetapi punya identitas (hostname/vendor)
 *             atau baru terlihat dalam 72 jam terakhir.
 *   unknown - tidak dikenal dan tanpa identitas sama sekali.
 */
class DhcpRecognition
{
    protected array $knownByIp = [];

    protected array $locations = [];

    public function __construct(?DeviceCsvService $devices = null)
    {
        $this->loadKnownDevices($devices ?? app(DeviceCsvService::class));
    }

    /**
     * Muat registry perangkat dikenal dari daftar resmi devices.csv.
     */
    public function loadKnownDevices(DeviceCsvService $source): void
    {
        $this->knownByIp = [];
        $this->locations = [];

        $source->officialDevices()->each(function (Device $device): void {
            $ip = strtolower(trim((string) $device->ip_address));
            if ($ip === '' || isset($this->knownByIp[$ip])) {
                return;
            }

            $this->knownByIp[$ip] = [
                'device_id' => (int) $device->id,
                'name'      => $device->name,
                'location'  => $device->pop_name,
            ];

            if ($device->pop_name) {
                $this->locations[$device->pop_name] = true;
            }
        });
    }

    public function knownByIp(): array
    {
        return $this->knownByIp;
    }

    public function knownCount(): int
    {
        return count($this->knownByIp);
    }

    /**
     * Daftar Router/Lokasi dari known device (pop_name).
     *
     * @return string[]
     */
    public function locations(): array
    {
        return array_keys($this->locations);
    }

    /**
     * Cari nama resmi perangkat yang diketahui dari sebuah IP.
     */
    public function resolveName(?string $ip): ?string
    {
        $ip = strtolower(trim((string) $ip));
        if ($ip === '') {
            return null;
        }

        return $this->knownByIp[$ip]['name'] ?? null;
    }

    /**
     * Tentukan Router/Lokasi untuk sebuah lease.
     * Prioritas: lokasi tercatat -> lokasi known device -> router pemberi lease.
     */
    public function resolveLocation(?string $ip, ?string $router = null, ?string $current = null): ?string
    {
        if ($current) {
            return $current;
        }

        $ip = strtolower(trim((string) $ip));
        if ($ip !== '' && isset($this->knownByIp[$ip])) {
            return $this->knownByIp[$ip]['location'];
        }

        return $router ?: null;
    }

    /**
     * Klasifikasi sebuah lease menjadi known / new / unknown.
     *
     * @param array $lease berisi ip, hostname, vendor, last_seen,
     *                     first_seen, lease_start
     */
    public function classify(array $lease, Carbon|string|null $now = null): string
    {
        $ip = strtolower(trim((string) ($lease['ip'] ?? '')));
        if ($ip !== '' && isset($this->knownByIp[$ip])) {
            return 'known';
        }

        $hostname = trim((string) ($lease['hostname'] ?? ''));
        $vendor   = trim((string) ($lease['vendor'] ?? ''));

        if ($hostname !== '' || $vendor !== '') {
            return 'new';
        }

        $seen = $this->lastSeen($lease, $now);
        if ($seen !== null && $seen->gte($this->freshSince($now))) {
            return 'new';
        }

        return 'unknown';
    }

    protected function lastSeen(array $lease, Carbon|string|null $now): ?Carbon
    {
        foreach (['last_seen', 'first_seen', 'lease_start'] as $key) {
            $value = $lease[$key] ?? null;
            if (!$value) {
                continue;
            }

            try {
                return $value instanceof Carbon
                    ? $value
                    : Carbon::parse($value);
            } catch (\Throwable) {
                // lanjut ke field berikutnya
            }
        }

        return null;
    }

    protected function freshSince(Carbon|string|null $now): Carbon
    {
        $now = $now instanceof Carbon ? $now : Carbon::parse($now ?? 'now');

        return $now->copy()->subHours(72);
    }
}