<?php

namespace App\Services;

use App\Models\TelegramSubscriber;

/**
 * Memproses satu update Telegram (umumnya pesan command /start atau /stop).
 *
 * Dipakai oleh command `telegram:poll` (long polling / getUpdates) dan
 * tetap tersedia sebagai fallback legasy untuk TelegramWebhookController.
 */
class TelegramUpdateHandler
{
    public function __construct(
        protected TelegramService $telegram
    ) {
    }

    public function handleUpdate(array $update): void
    {
        $message = $update['message'] ?? null;

        if ($message === null) {
            return;
        }

        $chatId = $message['chat']['id'] ?? null;

        if ($chatId === null) {
            return;
        }

        $text = trim((string) ($message['text'] ?? ''));

        $isStart = (bool) preg_match('#^/start($|@)#', $text);
        $isStop  = (bool) preg_match('#^/stop($|@)#', $text);

        if ($isStart) {
            $this->subscribe((int) $chatId, $message['from'] ?? []);
        } elseif ($isStop) {
            $this->unsubscribe((int) $chatId);
        }

        $this->telegram->sendToChat($chatId, $this->replyText($isStart, $isStop));
    }

    protected function subscribe(int $chatId, array $from): void
    {
        TelegramSubscriber::updateOrCreate(
            ['chat_id' => $chatId],
            [
                'first_name'         => $from['first_name'] ?? null,
                'username'           => $from['username'] ?? null,
                'last_name'          => $from['last_name'] ?? null,
                'is_active'          => true,
                'last_interacted_at' => now(),
            ]
        );
    }

    protected function unsubscribe(int $chatId): void
    {
        TelegramSubscriber::where('chat_id', $chatId)
            ->update([
                'is_active'          => false,
                'last_interacted_at' => now(),
            ]);
    }

    protected function replyText(bool $isStart, bool $isStop): string
    {
        if ($isStart) {
            return "✅ Berhasil berlangganan! Anda akan menerima notifikasi otomatis saat ada device yang DOWN atau RECOVERED di monitoring jaringan RADNET.";
        }

        if ($isStop) {
            return "👋 Anda berhenti menerima notifikasi.\n\n"
                ."Kirim /start kapan saja untuk berlangganan kembali.";
        }

        return "Gunakan perintah:\n"
            ."/start — berlangganan notifikasi device\n"
            ."/stop  — berhenti menerima notifikasi";
    }
}