<?php

namespace App\Services;

use App\Models\TelegramSubscriber;
use App\Models\TelegramUser;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class TelegramService
{
    protected ?string $botToken;

    protected ?string $chatId;

    public function __construct()
    {
        $this->botToken = config('services.telegram.bot_token');
        $this->chatId = config('services.telegram.chat_id');
    }

    public function isConfigured(): bool
    {
        return ! empty($this->botToken);
    }

    /**
     * Kirim pesan ke satu chat tertentu.
     * Selalu return false (tanpa throw) kalaupun gagal.
     */
    public function sendToChat(string|int $chatId, string $text): bool
    {
        if (! $this->isConfigured()) {
            Log::warning('Telegram tidak dikonfigurasi, pesan dilewati.');

            return false;
        }

        try {
            $response = Http::timeout(10)
                ->post("https://api.telegram.org/bot{$this->botToken}/sendMessage", [
                    'chat_id' => (string) $chatId,
                    'text' => $text,
                ]);

            if ($response->successful() && ($response->json('ok') ?? false)) {
                return true;
            }

            Log::warning('Telegram gagal mengirim pesan', [
                'chat_id' => (string) $chatId,
                'status' => $response->status(),
                'body' => $this->sanitize((string) $response->body()),
            ]);

            return false;
        } catch (\Throwable $e) {
            Log::warning('Telegram service error: '.$e->getMessage());

            return false;
        }
    }

    /**
     * Kirim pesan ke chat default TELEGRAM_CHAT_ID (.env).
     * Dipakai untuk pengujian manual / pesan admin.
     */
    public function sendMessage(string $text): bool
    {
        if (empty($this->chatId)) {
            Log::warning('TELEGRAM_CHAT_ID tidak dikonfigurasi, pesan ke chat default dilewati.');

            return false;
        }

        return $this->sendToChat($this->chatId, $text);
    }

    public function broadcastApproved(string $text, array $context = []): array
    {
        $chatIds = [];

        if (Schema::hasTable('telegram_users')) {
            foreach (TelegramUser::query()
                ->approved()
                ->where('role', '!=', 'pending')
                ->pluck('chat_id') as $chatId) {
                $chatIds[(string) $chatId] = true;
            }
        }

        return $this->sendToTargets(array_keys($chatIds), $text, $context);
    }

    /**
     * Hapus webhook Telegram agar getUpdates (long polling) bisa dipakai.
     * drop_pending_updates=true membuang update lama yang menumpuk
     * selagi webhook masih aktif, supaya tidak diproses ulang.
     */
    public function deleteWebhook(bool $dropPendingUpdates = true): bool
    {
        if (! $this->isConfigured()) {
            return false;
        }

        try {
            $response = Http::timeout(15)
                ->post("https://api.telegram.org/bot{$this->botToken}/deleteWebhook", [
                    'drop_pending_updates' => $dropPendingUpdates,
                ]);

            if ($response->successful() && ($response->json('ok') ?? false)) {
                return true;
            }

            Log::warning('Telegram deleteWebhook gagal', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
        } catch (\Throwable $e) {
            Log::warning('Telegram deleteWebhook error: '.$e->getMessage());
        }

        return false;
    }

    /**
     * Ambil update dari Telegram Bot API via long polling (getUpdates).
     *
     * Mengembalikan array update, atau false bila ada kegagalan
     * (network/API) sehingga pemanggil bisa menunda & mencoba lagi.
     */
    public function getUpdates(int $offset, int $timeout = 30): array|false
    {
        if (! $this->isConfigured()) {
            return false;
        }

        try {
            $response = Http::timeout($timeout + 10)
                ->post("https://api.telegram.org/bot{$this->botToken}/getUpdates", [
                    'offset' => $offset,
                    'timeout' => $timeout,
                ]);

            if ($response->successful() && ($response->json('ok') ?? false)) {
                return (array) ($response->json('result') ?? []);
            }

            Log::warning('Telegram getUpdates gagal', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
        } catch (\Throwable $e) {
            Log::warning('Telegram getUpdates error: '.$e->getMessage());
        }

        return false;
    }

    /**
     * Ambil update via getUpdates beserta detail error untuk console.
     *
     * Method ini tidak pernah mengekspos bot token maupun URL penuh.
     * Catatan: method `getUpdates()` lama tidak diubah; method ini hanya
     * tambahan agar command telegram:poll bisa menampilkan error asli.
     *
     * Return array:
     *  'ok'          => bool    (true bila Telegram merespons ok=true)
     *  'updates'     => array   (hasil update saat sukses)
     *  'status'      => ?int    (HTTP status code)
     *  'description' => ?string (pesan error dari Telegram API)
     *  'error'       => ?string (error network/exception, sudah disanitasi)
     */
    public function getUpdatesWithDetails(int $offset, int $timeout = 30): array
    {
        $failed = [
            'ok' => false,
            'updates' => [],
            'status' => null,
            'description' => null,
            'error' => null,
        ];

        if (! $this->isConfigured()) {
            $failed['error'] = 'Bot token belum dikonfigurasi.';

            return $failed;
        }

        try {
            $response = Http::timeout($timeout + 10)
                ->post("https://api.telegram.org/bot{$this->botToken}/getUpdates", [
                    'offset' => $offset,
                    'timeout' => $timeout,
                ]);

            $json = $response->json();

            return [
                'ok' => $response->successful() && ! empty($json['ok']),
                'updates' => (array) ($json['result'] ?? []),
                'status' => $response->status(),
                'description' => isset($json['description']) ? trim((string) $json['description']) : null,
                'error' => null,
            ];
        } catch (\Throwable $e) {
            $failed['error'] = $this->sanitize($e->getMessage());

            return $failed;
        }
    }

    protected function sanitize(string $message): string
    {
        $token = (string) $this->botToken;

        if ($token !== '') {
            $message = str_replace($token, '***', $message);
        }

        return $message;
    }

    public function sendDeviceDown(array $data): bool
    {
        if (! $this->hasDeviceData($data)) {
            return false;
        }

        return $this->sendMessage($this->deviceDownText($data));
    }

    public function sendDeviceRecovered(array $data): bool
    {
        if (! $this->hasDeviceData($data)) {
            return false;
        }

        return $this->sendMessage($this->deviceRecoveredText($data));
    }

    /**
     * Kirim notifikasi DOWN ke semua subscriber aktif + chat default.
     */
    public function broadcastDeviceDown(array $data): bool
    {
        if (! $this->hasDeviceData($data)) {
            return false;
        }

        return $this->broadcast($this->deviceDownText($data), [
            'notification' => 'down',
            'device' => $data['device'],
            'ip' => $data['ip'],
            'time' => $data['time'],
        ]);
    }

    /**
     * Kirim notifikasi RECOVERED ke semua subscriber aktif + chat default.
     */
    public function broadcastDeviceRecovered(array $data): bool
    {
        if (! $this->hasDeviceData($data)) {
            return false;
        }

        return $this->broadcast($this->deviceRecoveredText($data), [
            'notification' => 'recovered',
            'device' => $data['device'],
            'ip' => $data['ip'],
            'time' => $data['time'],
        ]);
    }

    protected function hasDeviceData(array $data): bool
    {
        return isset($data['device'], $data['ip'], $data['type'], $data['time']);
    }

    protected function deviceDownText(array $data): string
    {
        return "🔴 ═══════════════════\n"
            ."   RADNEXT NOC ALERT\n"
            ."═══════════════════\n\n"
            ."🚨 DEVICE DOWN\n\n"
            ."📡 Device\n{$data['device']}\n\n"
            ."🌐 IP Address\n{$data['ip']}\n\n"
            ."📋 Type\n{$data['type']}\n\n"
            ."🔴 Status\nDOWN\n\n"
            ."🕐 Time\n{$data['time']} WIB\n\n"
            ."⚠️ Device tidak merespons ping.\n"
            .'═══════════════════';
    }

    protected function deviceRecoveredText(array $data): string
    {
        return "🟢 ═══════════════════\n"
            ."  RADNEXT NOC RECOVERY\n"
            ."═══════════════════\n\n"
            ."✅ DEVICE RECOVERED\n\n"
            ."📡 Device\n{$data['device']}\n\n"
            ."🌐 IP Address\n{$data['ip']}\n\n"
            ."📋 Type\n{$data['type']}\n\n"
            ."🟢 Status\nUP\n\n"
            ."🕐 Time\n{$data['time']} WIB\n\n"
            ."✅ Device kembali merespons ping.\n"
            .'═══════════════════';
    }

    /**
     * Fan-out ke semua chat_id subscriber aktif + chat default (.env).
     * Setiap penerima dikirim satu per satu; kegagalan satu tidak
     * menghentikan pengiriman ke yang lain.
     *
     * Logging diagnostik TIDAK pernah menulis bot token. Chat id yang
     * dicatat hanyalah identitas penerima (bukan kredensial).
     */
    protected function broadcast(string $text, array $context = []): bool
    {
        $managedMode = Schema::hasTable('telegram_users');
        $subscriberChatIds = $managedMode
            ? []
            : TelegramSubscriber::where('is_active', true)
                ->pluck('chat_id')
                ->map(fn ($chatId): string => (string) $chatId)
                ->all();

        $chatIds = [];

        foreach ($subscriberChatIds as $chatId) {
            $chatIds[$chatId] = true;
        }

        if ($managedMode) {
            foreach (TelegramUser::query()
                ->approved()
                ->where('role', '!=', 'pending')
                ->pluck('chat_id') as $chatId) {
                $chatIds[(string) $chatId] = true;
            }
        }

        $defaultChatIncluded = false;

        if (! $managedMode && ! empty($this->chatId)) {
            $chatIds[(string) $this->chatId] = true;
            $defaultChatIncluded = true;
        }

        $result = $this->sendToTargets(array_keys($chatIds), $text, array_merge($context, [
            'active_subscribers' => count($subscriberChatIds),
            'default_chat_included' => $defaultChatIncluded,
        ]));

        return $result['sent'] > 0;
    }

    protected function sendToTargets(array $targets, string $text, array $context = []): array
    {
        $targets = array_values(array_unique(array_map('strval', $targets)));

        Log::info('Telegram broadcast diproses', array_merge($context, [
            'target_count' => count($targets),
        ]));

        if (empty($targets)) {
            Log::warning('Telegram broadcast dibatalkan: tidak ada penerima aktif', $context);

            return [
                'sent' => 0,
                'failed' => [],
                'target_count' => 0,
            ];
        }

        $sent = 0;
        $failed = [];

        foreach ($targets as $chatId) {
            if ($this->sendToChat($chatId, $text)) {
                $sent++;
            } else {
                $failed[] = $chatId;
            }
        }

        Log::info('Telegram broadcast selesai', array_merge($context, [
            'target_count' => count($targets),
            'success_count' => $sent,
            'failed_count' => count($failed),
            'all_sent' => count($targets) === $sent,
        ]));

        if (! empty($failed)) {
            Log::warning('Telegram broadcast: sebagian penerima gagal', array_merge($context, [
                'failed_chat_ids' => $failed,
            ]));
        }

        return [
            'sent' => $sent,
            'failed' => $failed,
            'target_count' => count($targets),
        ];
    }
}
