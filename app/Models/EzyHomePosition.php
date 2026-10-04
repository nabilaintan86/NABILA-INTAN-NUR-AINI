<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EzyHomePosition extends Model
{
    protected $table = 'ezyhome_positions';

    protected $fillable = [
        'ezyhome_device_id', 'ezyhome_area_id', 'pos_x', 'pos_y',
    ];

    protected $casts = [
        'ezyhome_device_id' => 'integer',
        'ezyhome_area_id'   => 'integer',
        'pos_x'             => 'integer',
        'pos_y'             => 'integer',
    ];

    public function device(): BelongsTo
    {
        return $this->belongsTo(EzyHomeDevice::class, 'ezyhome_device_id');
    }

    public function area(): BelongsTo
    {
        return $this->belongsTo(EzyHomeArea::class, 'ezyhome_area_id');
    }
}