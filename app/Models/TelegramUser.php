<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TelegramUser extends Model
{
    use HasFactory;

    protected $table = 'telegram_users';

    protected $fillable = [
        'chat_id',
        'username',
        'first_name',
        'last_name',
        'role',
        'is_approved',
        'last_active_at',
        'notes',
    ];

    protected $casts = [
        'is_approved' => 'boolean',
        'last_active_at' => 'datetime',
    ];

    public function logs(): HasMany
    {
        return $this->hasMany(TelegramLog::class);
    }

    public function linkedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'chat_id', 'telegram_chat_id');
    }

    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }

    public function scopePending($query)
    {
        return $query->where('is_approved', false);
    }
}
