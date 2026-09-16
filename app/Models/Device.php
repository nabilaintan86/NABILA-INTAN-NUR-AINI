<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    use HasFactory;

    // Tambahan ini biar Laravel baca tabel 'device1', bukan 'devices'
    protected $table = 'device';

    protected $fillable = [
        'name',
        'type',
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
        'pop_name',
        'pos_x',
        'pos_y',
    ];

    protected $casts = [
        'polls'                 => 'integer',
        'responded_percentage'  => 'float',
        'missed_percentage'     => 'float',
        'alerts_count'          => 'integer',
        'avg_delay'             => 'integer',
        'min_delay'             => 'integer',
        'max_delay'             => 'integer',
        'pos_x'                 => 'integer',
        'pos_y'                 => 'integer',
    ];

    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class);
    }

    public function troubleshootingLogs()
    {
        return $this->hasMany(TroubleshootingLog::class);
    }

    public function outboundConnections()
    {
        return $this->hasMany(DeviceConnection::class, 'from_device_id');
    }

    public function inboundConnections()
    {
        return $this->hasMany(DeviceConnection::class, 'to_device_id');
    }
}