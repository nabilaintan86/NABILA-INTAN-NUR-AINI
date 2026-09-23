<?php

namespace App\Console\Commands;

use App\Services\TelegramService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class SetTelegramWebhook extends Command
{
    protected $signature = 'telegram:set-webhook {url? : URL webhook (default: APP_URL + /telegram/webhook)}';

    protected $description = 'Daftarkan webhook Telegram (setWebhook) ke Bot API';

    public function handle(TelegramService $telegram): int
    {
        $this->warn('DEPRECATED: sistem aktif sekarang memakai long polling (telegram:poll).');
        $this->warn('Command ini memerlukan tunnel/webhook dan tidak dipakai di workflow normal.');

        if (!$telegram->isConfigured()) {
            $this->error('TELEGRAM_BOT_TOKEN belum dikonfigurasi.');

            return self::FAILURE;
        }

        $url = $this->argument('url')
            ?: rtrim((string) config('app.url'), '/').'/telegram/webhook';

        $payload = ['url' => $url];

        if (!empty(config('services.telegram.webhook_secret'))) {
            $payload['secret_token'] = config('services.telegram.webhook_secret');
        }

        try {
            $response = Http::timeout(15)
                ->post(
                    'https://api.telegram.org/bot'.config('services.telegram.bot_token').'/setWebhook',
                    $payload
                );

            if ($response->successful() && ($response->json('ok') ?? false)) {
                $this->info("Webhook disetel ke: {$url}");
                $this->line('Description: '.($response->json('result.description') ?? '-'));

                return self::SUCCESS;
            }

            $this->error('setWebhook gagal: '.$response->body());

            return self::FAILURE;
        } catch (\Throwable $e) {
            $this->error('setWebhook error: '.$e->getMessage());

            return self::FAILURE;
        }
    }
}