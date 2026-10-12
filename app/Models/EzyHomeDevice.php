<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EzyHomeDevice extends Model
{
    protected $table = 'ezyhome_devices';

    protected $fillable = [
        'name', 'ip_address', 'hostname', 'type', 'protocol',
        'deskripsi', 'penyebab', 'gambar', 'status', 'source',
        'icon_type', 'router_version', 'pop_name',
        'polls', 'responded_percentage', 'missed_percentage',
        'down_time', 'period', 'alerts_count',
        'avg_delay', 'min_delay', 'max_delay', 'uptime',
    ];

    protected $casts = [
        'polls'                => 'integer',
        'responded_percentage' => 'float',
        'missed_percentage'    => 'float',
        'alerts_count'         => 'integer',
        'avg_delay'            => 'integer',
        'min_delay'            => 'integer',
        'max_delay'            => 'integer',
    ];

    public function positions(): HasMany
    {
        return $this->hasMany(EzyHomePosition::class, 'ezyhome_device_id');
    }

    public function getIconKeyAttribute(): string
    {
        $map = [
            'Router'       => 'router',
            'Switch'       => 'switch',
            'OLT'          => 'switch',
            'EPON'         => 'router',
            'Server'       => 'server',
            'Access Point' => 'wifi',
            'Other'        => 'pc',
        ];
        return $map[$this->type] ?? 'server';
    }
}