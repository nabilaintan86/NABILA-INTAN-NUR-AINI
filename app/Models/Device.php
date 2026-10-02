<?php

namespace App\Models;

use App\Helpers\DeviceType;
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
}
