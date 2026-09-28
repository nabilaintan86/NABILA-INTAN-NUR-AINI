<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeviceConnection extends Model
{
    use HasFactory;

    protected $table = 'device_connections';

    protected $fillable = [
        'from_device_id',
        'to_device_id',
        'connection_type',
        'direction', // 'forward' (->), 'reverse' (<-), 'bidirectional' (<->), 'none' (--)
    ];

    public function fromDevice()
    {
        return $this->belongsTo(Device::class, 'from_device_id');
    }

    public function toDevice()
    {
        return $this->belongsTo(Device::class, 'to_device_id');
    }

    /**
     * Simbol panah untuk tampilan UI.
     */
    public function getArrowSymbolAttribute(): string
    {
        return match ($this->direction) {
            'bidirectional' => '↔',
            'reverse'       => '←',
            'none'          => '—',
            default         => '→',
        };
    }

    /**
     * Label fungsi arah sesuai standar WhatsUp Gold.
     */
    public function getDirectionLabelAttribute(): string
    {
        return match ($this->direction) {
            'bidirectional' => 'Dua Arah (Full Duplex / Peering)',
            'reverse'       => 'Satu Arah Balik (←)',
            'none'          => 'Tanpa Panah (Link Fisik / Pasif)',
            default         => 'Satu Arah (Uplink / Parent-Child)',
        };
    }
}
