<?php

namespace App\Console\Commands;

use App\Services\TelegramService;
use App\Services\TelegramUpdateHandler;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

/**
 * Long polling Telegram Bot API (getUpdates).
 *
 * Command ini berjalan terus-menerus sampai dihentikan (SIGTERM/SIGINT).
 * Offset disimpan di cache agar restart tidak memproses update yang sama
 * dua kali, dan tidak ada tunnel/webhook/URL publik yang dibutuhkan.
 */
class TelegramPoll extends Command
{
    protected $signature = 'telegram:poll
        {--once : Proses satu batch update lalu keluar (untuk testing)}
        {--no-delete-webhook : Jangan panggil deleteWebhook saat start}';

    protected $description = 'Long polling Telegram (getUpdates) untuk menerima dan memproses perintah';

    private bool $running = true;

    public function handle(TelegramService $telegram, TelegramUpdateHandler $handler): int
    {
        if (! $telegram->isConfigured()) {
            $this->error('TELEGRAM_BOT_TOKEN belum dikonfigurasi di .env');

            return self::FAILURE;
        }

        $this->registerStopSignals();

        /*
        |--------------------------------------------------------------------------
        | Webhook harus dihapus dari sisi Telegram agar getUpdates bisa dipakai.
        |--------------------------------------------------------------------------
        */

        if (! $this->option('no-delete-webhook')) {
            if ($telegram->deleteWebhook()) {
                $this->info('Webhook Telegram dihapus, getUpdates aktif.');
            } else {
                $this->warn('Gagal menghapus webhook. Lanjut polling; pastikan tidak ada webhook aktif.');
            }
        }

        $offset = max(0, (int) Cache::get('telegram.poll.offset', 0));
        $retryDelay = max(0, (int) config('services.telegram.poll_retry_delay', 5));

        $this->info("Telegram long polling dimulai (offset: {$offset}).");

        while ($this->running) {
            $result = $telegram->getUpdatesWithDetails($offset);

            if (! $result['ok']) {
                $this->showError($result);

                $this->warn("Coba lagi dalam {$retryDelay} detik...");

                if ($this->option('once')) {
                    return self::FAILURE;
                }

                sleep($retryDelay);

                continue;
            }

            $updates = $result['updates'];

            if (empty($updates)) {
                if ($this->option('once')) {
                    break;
                }

                usleep(200000);

                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | Proses setiap update, kemudian geser offset ke update_id + 1.
            | Offset disimpan setelah tiap update agar restart tidak memproses
            | ulang update yang sama.
            |--------------------------------------------------------------------------
            */

            foreach ($updates as $update) {
                $updateId = (int) ($update['update_id'] ?? 0);

                if ($updateId < 1) {
                    continue;
                }

                try {
                    $handler->handleUpdate($update);
                } catch (\Throwable $e) {
                    Log::error('Gagal memproses update Telegram: '.$e->getMessage());
                    $this->error('Gagal memproses satu update, lihat log.');
                }

                $offset = $updateId + 1;
                Cache::forever('telegram.poll.offset', $offset);
            }

            if ($this->option('once')) {
                break;
            }
        }

        $this->info("Telegram polling dihentikan (offset: {$offset}).");

        return self::SUCCESS;
    }

    protected function registerStopSignals(): void
    {
        if (! function_exists('pcntl_signal') || ! function_exists('pcntl_async_signals')) {
            return;
        }

        pcntl_async_signals(true);

        foreach ([SIGTERM, SIGINT, SIGHUP] as $signal) {
            pcntl_signal($signal, function () {
                $this->running = false;
                $this->line('Menerima sinyal berhenti...');
            });
        }
    }

    /**
     * Tampilkan detail error getUpdates ke console.
     *
     * Yang ditampilkan hanya: HTTP status code, deskripsi Telegram API,
     * dan/atau pesan exception jaringan yang sudah disanitasi.
     * Bot token dan URL lengkap yang mengandung token TIDAK pernah dicetak.
     */
    protected function showError(array $result): void
    {
        $this->error('Gagal mengambil update dari Telegram:');

        if ($result['status'] !== null) {
            $this->error("HTTP {$result['status']}");
        }

        if (! empty($result['description'])) {
            $this->error('Telegram API: '.$this->sanitizeOutput($result['description']));
        }

        if (! empty($result['error'])) {
            $this->error('Connection error: '.$this->sanitizeOutput($result['error']));
        }

        if ((int) ($result['status'] ?? 0) === 409) {
            $this->warn(
                'Petunjuk: HTTP 409 Conflict biasanya muncul saat ada lebih dari satu proses '
                .'polling yang aktif (mis. service systemd telegram-poll). Jalankan hanya satu '
                .'poller. Untuk pengujian manual, hentikan service dulu: '
                .'`systemctl --user stop telegram-poll`'
            );
        }
    }

    protected function sanitizeOutput(string $text): string
    {
        $token = (string) config('services.telegram.bot_token');

        if ($token !== '') {
            $text = str_replace($token, '***', $text);
        }

        return $text;
    }
}
