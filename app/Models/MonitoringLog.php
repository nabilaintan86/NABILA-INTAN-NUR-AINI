<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MonitoringLog extends Model
{
    use HasFactory;

    protected $table = 'monitoring_logs';

    protected $guarded = [];

    protected $casts = [
        'response_time' => 'integer',
        'checked_at'    => 'datetime',
        'device_id'     => 'integer',
    ];

    public function device(): BelongsTo
    {
        return $this->belongsTo(Device::class, 'device_id');
    }
}