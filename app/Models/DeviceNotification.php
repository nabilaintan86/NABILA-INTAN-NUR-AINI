<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DeviceNotification extends Model
{
    protected $table = 'device_notifications';

    protected $guarded = [];

    protected $casts = [
        'device_id'  => 'integer',
        'latency'    => 'integer',
        'is_read'    => 'boolean',
        'checked_at' => 'datetime',
    ];

    public function device(): BelongsTo
    {
        return $this->belongsTo(Device::class, 'device_id');
    }
}