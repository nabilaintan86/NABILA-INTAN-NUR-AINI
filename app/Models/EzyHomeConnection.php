<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EzyHomeConnection extends Model
{
    protected $table = 'ezyhome_connections';

    protected $fillable = [
        'from_device_id', 'from_position_id',
        'to_device_id', 'to_position_id',
        'connection_type', 'direction',
    ];

    public function fromDevice(): BelongsTo
    {
        return $this->belongsTo(EzyHomeDevice::class, 'from_device_id');
    }

    public function toDevice(): BelongsTo
    {
        return $this->belongsTo(EzyHomeDevice::class, 'to_device_id');
    }

    public function getArrowSymbolAttribute(): string
    {
        return match ($this->direction) {
            'bidirectional' => '↔',
            'reverse'       => '←',
            'none'          => '—',
            default         => '→',
        };
    }
}