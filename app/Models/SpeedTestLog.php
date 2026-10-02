<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SpeedTestLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'device_id',
        'device_name',
        'device_ip_address',
        'target_type',
        'target_host',
        'download_mbps',
        'upload_mbps',
        'ping_ms',
        'source',
        'server',
        'ip_address',
        'duration_ms',
        'status',
        'message',
        'tested_at',
    ];

    protected $casts = [
        'download_mbps' => 'float',
        'upload_mbps'   => 'float',
        'ping_ms'       => 'float',
        'duration_ms'   => 'integer',
        'tested_at'     => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Perangkat yang diuji. Device_name / device_ip_address tetap dipakai untuk
     * tampilan supaya log lama tidak ikut berubah bila device direname.
     */
    public function device(): BelongsTo
    {
        return $this->belongsTo(Device::class);
    }

    /**
     * Ringkasan target untuk ditampilkan di tabel riwayat.
     */
    public function targetLabel(): string
    {
        // Target manual disimpan terpisah karena isinya bisa nama host, bukan
        // IP perangkat terdaftar.
        $manual = trim((string) ($this->target_host ?? ''));

        if ($manual !== '') {
            return $manual;
        }

        $name = trim((string) ($this->device_name ?? ''));
        $ip   = trim((string) ($this->device_ip_address ?? ''));

        if ($name === '' && $ip === '') {
            return 'Server NOC';
        }

        if ($name === '' || $name === $ip) {
            return $ip;
        }

        return $name.' ('.$ip.')';
    }
}
