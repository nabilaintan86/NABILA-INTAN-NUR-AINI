<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LogArchive extends Model
{
    use HasFactory;

    protected $table = 'log_archives';

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