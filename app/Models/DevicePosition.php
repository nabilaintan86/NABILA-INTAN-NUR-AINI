<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DevicePosition extends Model
{
    protected $table = 'device_positions';

    protected $fillable = [
        'device_id',
        'network_area_id',
        'pos_x',
        'pos_y',
    ];

    protected $casts = [
        'device_id'       => 'integer',
        'network_area_id' => 'integer',
        'pos_x'           => 'integer',
        'pos_y'           => 'integer',
    ];

    public function device(): BelongsTo
    {
        return $this->belongsTo(Device::class);
    }

    public function networkArea(): BelongsTo
    {
        return $this->belongsTo(NetworkArea::class);
    }
}