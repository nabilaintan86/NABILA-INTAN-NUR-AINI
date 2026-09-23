<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TelegramSubscriber extends Model
{
    protected $table = 'telegram_subscribers';

    protected $guarded = [];

    protected $casts = [
        'chat_id'             => 'integer',
        'is_active'           => 'boolean',
        'last_interacted_at'  => 'datetime',
    ];
}