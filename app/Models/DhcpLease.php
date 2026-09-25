<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class DhcpLease extends Model
{
    protected $table = 'dhcp_leases';

    protected $fillable = [
        'device_id',
        'mac_address',
        'ip_address',
        'hostname',
        'client_id',
        'vendor',
        'router',
        'location',
        'lease_start',
        'lease_end',
        'classification',
        'first_seen',
        'last_seen',
        'source',
        'comment',
    ];

    protected $casts = [
        'lease_start' => 'datetime',
        'lease_end' => 'datetime',
        'first_seen' => 'datetime',
        'last_seen' => 'datetime',
        'device_id' => 'integer',
    ];

    /**
     * Device resmi (tabel device/devices.csv) yang dicocokkan lewat IP address.
     * Hanya referensi — data device tidak diduplikasi di sini.
     */
    public function device()
    {
        return $this->belongsTo(Device::class, 'device_id');
    }

    /**
     * Status halaman DHCP Lease: aktif / expired / unknown.
     * - unknown: perangkat tidak dikenali (prioritas utama).
     * - aktif  : lease masih berlaku (lease_end null atau >= sekarang).
     * - expired: lease sudah melewati masa berlaku.
     */
    public function statusLabel(): string
    {
        if ($this->classification === 'unknown') {
            return 'unknown';
        }

        $end = $this->lease_end;

        return ($end === null || $end->gte(now())) ? 'aktif' : 'expired';
    }

    /**
     * Durasi lease dalam format "1d 2h 30m" (Lebih - sampai).
     */
    public function leaseDurationLabel(): string
    {
        if (! $this->lease_start || ! $this->lease_end) {
            return '';
        }

        return self::formatInterval($this->lease_start, $this->lease_end);
    }

    /**
     * Label relatif berakhirnya lease: "in 6h 12m" untuk masih berlaku,
     * atau "2h 10m lalu" untuk yang sudah berakhir. Empty jika tanpa lease_end.
     */
    public function expiresLabel(): string
    {
        $end = $this->lease_end;
        if (! $end) {
            return '';
        }

        $now = now();
        if ($end->gte($now)) {
            return 'in '.self::formatInterval($now, $end);
        }

        return self::formatInterval($end, $now).' lalu';
    }

    /**
     * Lease milik device yang dikenali (classification known) dan punya referensi device_id.
     */
    public function isKnownDevice(): bool
    {
        return $this->classification === 'known' && $this->device_id !== null;
    }

    protected static function formatInterval(Carbon $from, Carbon $to): string
    {
        $secs = max(0, abs($from->getTimestamp() - $to->getTimestamp()));
        $days = intdiv($secs, 86400);
        $hours = intdiv($secs % 86400, 3600);
        $mins = intdiv($secs % 3600, 60);

        if ($days > 0) {
            return "{$days}d {$hours}h {$mins}m";
        }

        if ($hours > 0) {
            return "{$hours}h {$mins}m";
        }

        return max(1, $mins).'m';
    }
}
