<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

class Note extends Model
{
    use HasFactory;

    protected $table = 'device_notes';

    protected $fillable = [
        'device_id',
        'user_id',
        'tanggal',
        'catatan',
        'priority',
        'status',
    ];

    protected $casts = [
        'device_id' => 'integer',
        'user_id' => 'integer',
        'tanggal' => 'datetime',
    ];

    public function setTanggalAttribute(mixed $value): void
    {
        if ($value === null || $value === '') {
            $this->attributes['tanggal'] = null;

            return;
        }

        $this->attributes['tanggal'] = $value instanceof \DateTimeInterface
            ? $value->format('Y-m-d H:i:s')
            : Carbon::parse((string) $value)->format('Y-m-d H:i:s');
    }

    protected static function booted(): void
    {
        static::saving(function (Note $note): void {
            $priority = strtolower(trim((string) ($note->priority ?: $note->status)));
            $note->priority = match ($priority) {
                'urgent', 'tinggi', 'high', 'penting' => 'Urgent',
                default => 'Normal',
            };
        });
    }

    public function device(): BelongsTo
    {
        return $this->belongsTo(Device::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getStatusAttribute(): string
    {
        return (string) $this->priority;
    }

    public function setStatusAttribute(?string $value): void
    {
        $this->attributes['priority'] = ucfirst(strtolower(trim((string) $value)));
    }

    public function getPriorityLabelAttribute(): string
    {
        return $this->priority === 'Urgent' ? 'Urgent' : 'Normal';
    }

    public function getPriorityClassAttribute(): string
    {
        return $this->priority === 'Urgent' ? 'priority-urgent' : 'priority-normal';
    }
}
