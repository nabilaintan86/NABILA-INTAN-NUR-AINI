<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PingHistory extends Model
{
    use HasFactory;

    protected $table = 'ping_history';

    protected $guarded = [];

    protected $casts = [
        'latency'   => 'integer',
        'polled_at' => 'datetime',
        'device_id' => 'integer',
    ];

    public function device(): BelongsTo
    {
        return $this->belongsTo(Device::class, 'device_id');
    }
}