<?php

namespace App\Services;

use App\Models\TelegramLog;
use App\Models\TelegramUser;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class TelegramUserService
{
    public function __construct(
        protected TelegramService $telegram,
    ) {}

    public function register(string $chatId, array $from = []): TelegramUser
    {
        $user = TelegramUser::firstOrNew(['chat_id' => $chatId]);

        $user->fill([
            'username' => $from['username'] ?? $user->username,
            'first_name' => $from['first_name'] ?? $user->first_name,
            'last_name' => $from['last_name'] ?? $user->last_name,
        ]);

        if (! $user->exists) {
            $user->role = 'pending';
            $user->is_approved = false;
        }

        $user->last_active_at = now();
        $user->save();

        return $user;
    }

    public function find(string $chatId): ?TelegramUser
    {
        return TelegramUser::where('chat_id', $chatId)->first();
    }

    public function touch(TelegramUser $user, array $from = []): TelegramUser
    {
        if ($from !== []) {
            $user->fill([
                'username' => $from['username'] ?? $user->username,
                'first_name' => $from['first_name'] ?? $user->first_name,
                'last_name' => $from['last_name'] ?? $user->last_name,
            ]);
        }

        $user->last_active_at = now();
        $user->save();

        return $user;
    }

    public function approvedChatIds(): array
    {
        if (! Schema::hasTable('telegram_users')) {
            return [];
        }

        return TelegramUser::query()
            ->approved()
            ->where('role', '!=', 'pending')
            ->pluck('chat_id')
            ->map(fn ($chatId): string => (string) $chatId)
            ->all();
    }

    public function log(?TelegramUser $user, string $chatId, string $message, ?string $response = null): void
    {
        if (! Schema::hasTable('telegram_logs')) {
            return;
        }

        try {
            TelegramLog::create([
                'telegram_user_id' => $user?->getKey(),
                'chat_id' => $chatId,
                'message_text' => $message,
                'response' => $response,
            ]);
        } catch (\Throwable $e) {
            Log::warning('Gagal menyimpan log Telegram: '.$e->getMessage());
        }
    }

    public function notifySuperAdmins(TelegramUser $user): int
    {
        $targets = $this->approvedChatIdsForSuperAdmins();
        $sent = 0;

        foreach ($targets as $chatId) {
            $name = trim((string) ($user->first_name.' '.$user->last_name));
            $name = $name !== '' ? $name : ($user->username ?: $user->chat_id);
            $link = route('admin.telegram-users', ['chat_id' => $user->chat_id]);

            $message = "🔔 Registrasi Telegram baru\n\n"
                ."Nama: {$name}\n"
                .'Username: '.($user->username ?: '-')."\n"
                ."Chat ID: {$user->chat_id}\n\n"
                ."Buka dashboard untuk menyetujui: {$link}";

            if ($this->telegram->sendToChat($chatId, $message)) {
                $sent++;
            }
        }

        return $sent;
    }

    protected function approvedChatIdsForSuperAdmins(): array
    {
        $chatIds = [];

        if (Schema::hasTable('telegram_users')) {
            foreach (TelegramUser::query()
                ->approved()
                ->where('role', 'super_admin')
                ->pluck('chat_id') as $chatId) {
                $chatIds[(string) $chatId] = true;
            }
        }

        if (Schema::hasTable('users') && Schema::hasColumn('users', 'telegram_chat_id')) {
            foreach (User::query()
                ->where('role', 'super_admin')
                ->whereNotNull('telegram_chat_id')
                ->pluck('telegram_chat_id') as $chatId) {
                $chatIds[(string) $chatId] = true;
            }
        }

        return array_keys($chatIds);
    }
}
