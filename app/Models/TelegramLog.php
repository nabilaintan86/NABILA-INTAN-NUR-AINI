<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TelegramLog extends Model
{
    protected $table = 'telegram_logs';

    protected $fillable = [
        'telegram_user_id',
        'chat_id',
        'message_text',
        'response',
    ];

    public function telegramUser(): BelongsTo
    {
        return $this->belongsTo(TelegramUser::class, 'telegram_user_id');
    }
}
