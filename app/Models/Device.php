<?php

namespace App\Models;

use App\Helpers\DeviceType;
use App\Services\NetworkTools\DiagnosisAnalyzer;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Schema;

class Device extends Model
{
    use HasFactory;

    protected $table = 'devices';

    protected $fillable = [
        'name',
        'type',
        'protocol',
        'ip_address',
        'polls',
        'responded_percentage',
        'missed_percentage',
        'down_time',
        'period',
        'alerts_count',
        'avg_delay',
        'min_delay',
        'max_delay',
        'status',
        'icon_type',
        'router_version',
        'pop_name',
        'network_area_id',
        'pos_x',
        'pos_y',
        'map_positioned',
        'deskripsi',
        'gambar',
        'penyebab',
    ];

    protected $casts = [
        'polls' => 'integer',
        'responded_percentage' => 'float',
        'missed_percentage' => 'float',
        'alerts_count' => 'integer',
        'avg_delay' => 'integer',
        'min_delay' => 'integer',
        'max_delay' => 'integer',
    ];

    protected static function booted(): void
    {
        static::creating(function (Device $device): void {
            if (Schema::hasColumn($device->getTable(), 'type')) {
                $device->type = DeviceType::normalizeType($device->type, $device->icon_type);
            }

            if (Schema::hasColumn($device->getTable(), 'protocol')) {
                $device->protocol = DeviceType::normalizeProtocol($device->protocol);
            }

            if (Schema::hasColumn($device->getTable(), 'icon_type') && blank($device->icon_type)) {
                $device->icon_type = DeviceType::iconKey($device->type);
            }
        });

        static::updating(function (Device $device): void {
            if ($device->isDirty('type') && Schema::hasColumn($device->getTable(), 'type')) {
                $device->type = DeviceType::normalizeType($device->type, $device->icon_type);

                if (Schema::hasColumn($device->getTable(), 'icon_type')) {
                    $device->icon_type = DeviceType::iconKey($device->type);
                }
            }

            if ($device->isDirty('protocol') && Schema::hasColumn($device->getTable(), 'protocol')) {
                $device->protocol = DeviceType::normalizeProtocol($device->protocol);
            }
        });
    }

    public function notes(): HasMany
    {
        return $this->hasMany(Note::class);
    }

    /**
     * Penetap status perangkat dari metrik hasil probe (packet loss & latency).
     *
     * Delegasi ke aturan tunggal DiagnosisAnalyzer::statusFromProbe() — fungsi
     * yang sama dipakai kartu hasil ping Network Tools, polling database, dan
     * badge daftar perangkat. Jadi tidak mungkin ada perbedaan logika antara
     * badan perangkat dan kotak hasil diagnostik.
     *
     *   - packet loss 100%       → 'down'
     *   - packet loss 1-99%      → 'warning'
     *   - latency rata-rata >100 → 'warning'
     *   - selain itu             → 'up'
     */
    public static function statusFromProbe(?float $lossPct, ?float $avgMs): string
    {
        return DiagnosisAnalyzer::statusFromProbe($lossPct, $avgMs);
    }

    /**
     * Label badge yang tampil di layar untuk satu status internal.
     */
    public static function statusLabel(string $status): string
    {
        return match ($status) {
            'warning' => 'WARNING',
            'down' => 'DOWN',
            'up', 'default' => 'UP',
        };
    }

    public function activityLogs(): HasMany
    {
        return $this->hasMany(ActivityLog::class);
    }

    public function outboundConnections(): HasMany
    {
        return $this->hasMany(DeviceConnection::class, 'from_device_id');
    }

    public function inboundConnections(): HasMany
    {
        return $this->hasMany(DeviceConnection::class, 'to_device_id');
    }

    public function getDisplayTypeAttribute(): string
    {
        return DeviceType::normalizeType($this->type, $this->icon_type);
    }

    public function getTypeLabelAttribute(): string
    {
        return $this->display_type;
    }

    public function getDisplayProtocolAttribute(): string
    {
        $protocol = $this->getAttribute('protocol');

        if (blank($protocol) && in_array(strtoupper((string) $this->type), DeviceType::protocols(), true)) {
            $protocol = $this->type;
        }

        return DeviceType::normalizeProtocol($protocol);
    }

    public function getProtocolLabelAttribute(): string
    {
        return $this->display_protocol;
    }

    public function getIconKeyAttribute(): string
    {
        return DeviceType::iconKey($this->type, $this->icon_type);
    }

    public function getIconFileAttribute(): string
    {
        return DeviceType::iconFile($this->type, $this->icon_type);
    }

    public function getIconUrlAttribute(): string
    {
        return DeviceType::iconUrl($this->type, $this->icon_type);
    }

    public function networkArea()
    {
        return $this->belongsTo(NetworkArea::class, 'network_area_id');
    }

    /**
     * Daftar POP unik dari tabel devices (SELECT DISTINCT pop_name).
     *
     * Satu sumber data untuk filter Network Tools dan input Kelola Router,
     * sehingga keduanya selalu sinkron dengan database: POP baru yang
     * disimpan pada sebuah perangkat langsung tersedia di kedua halaman.
     * Aman terhadap skema yang belum memiliki kolom `pop_name`.
     *
     * @return array<int, string>
     */
    public static function popNameOptions(): array
    {
        if (! Schema::hasColumn((new static)->getTable(), 'pop_name')) {
            return [];
        }

        return static::query()
            ->whereNotNull('pop_name')
            ->where('pop_name', '!=', '')
            ->distinct()
            ->orderBy('pop_name')
            ->pluck('pop_name')
            ->map(fn ($pop) => (string) $pop)
            ->values()
            ->all();
    }

    public function topologyMapDevice()
    {
        return $this->hasOne(TopologyMapDevice::class, 'device_id');
    }
}
