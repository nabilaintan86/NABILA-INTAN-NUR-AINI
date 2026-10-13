<?php

namespace App\Services;

use App\Models\MapSetting;
use App\Models\TelegramSubscriber;
use App\Models\TelegramUser;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class TelegramService
{
    protected ?string $botToken;

    protected ?string $chatId;

    protected ?string $webhookSecret;

    public function __construct()
    {
        $this->reloadConfig();
    }

    public function reloadConfig(): void
    {
        $dbToken = null;
        $dbChatId = null;
        $dbSecret = null;

        try {
            if (Schema::hasTable('map_settings')) {
                $dbToken = MapSetting::get('telegram_bot_token');
                $dbChatId = MapSetting::get('telegram_chat_id');
                $dbSecret = MapSetting::get('telegram_webhook_secret');
            }
        } catch (\Throwable $e) {
            // Ignore when database is not ready
        }

        $this->botToken = ! empty($dbToken) ? (string) $dbToken : (string) config('services.telegram.bot_token');
        $this->chatId = ! empty($dbChatId) ? (string) $dbChatId : (string) config('services.telegram.chat_id');
        $this->webhookSecret = ! empty($dbSecret) ? (string) $dbSecret : (string) config('services.telegram.webhook_secret');
    }

    public function getBotToken(): ?string
    {
        return $this->botToken;
    }

    public function getChatId(): ?string
    {
        return $this->chatId;
    }

    public function getWebhookSecret(): ?string
    {
        return $this->webhookSecret;
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

    /**
     * Panggil getMe untuk memeriksa token bot dan mengambil data profil bot.
     *
     * @return array{ok: bool, bot: ?array, description: ?string, status: ?int}
     */
    public function getMe(?string $customToken = null): array
    {
        $token = $customToken ?: $this->botToken;

        if (empty($token)) {
            return [
                'ok' => false,
                'bot' => null,
                'description' => 'Bot token belum dikonfigurasi.',
                'status' => null,
            ];
        }

        try {
            $response = Http::timeout(10)
                ->get("https://api.telegram.org/bot{$token}/getMe");

            $json = $response->json();
            $ok = $response->successful() && ! empty($json['ok']);

            return [
                'ok' => $ok,
                'bot' => $ok ? ($json['result'] ?? null) : null,
                'description' => $json['description'] ?? ($ok ? 'Bot terhubung.' : 'Gagal menghubungi Telegram API.'),
                'status' => $response->status(),
            ];
        } catch (\Throwable $e) {
            return [
                'ok' => false,
                'bot' => null,
                'description' => $this->sanitize($e->getMessage()),
                'status' => null,
            ];
        }
    }

    /**
     * Dapatkan informasi webhook yang sedang aktif dari Telegram API.
     */
    public function getWebhookInfo(?string $customToken = null): array
    {
        $token = $customToken ?: $this->botToken;

        if (empty($token)) {
            return [
                'ok' => false,
                'info' => null,
                'description' => 'Bot token belum dikonfigurasi.',
            ];
        }

        try {
            $response = Http::timeout(10)
                ->get("https://api.telegram.org/bot{$token}/getWebhookInfo");

            $json = $response->json();
            $ok = $response->successful() && ! empty($json['ok']);

            return [
                'ok' => $ok,
                'info' => $ok ? ($json['result'] ?? null) : null,
                'description' => $json['description'] ?? null,
            ];
        } catch (\Throwable $e) {
            return [
                'ok' => false,
                'info' => null,
                'description' => $this->sanitize($e->getMessage()),
            ];
        }
    }

    /**
     * Daftarkan webhook Telegram.
     */
    public function setWebhook(string $url, ?string $secretToken = null, bool $dropPendingUpdates = true): array
    {
        if (! $this->isConfigured()) {
            return [
                'ok' => false,
                'description' => 'Bot token belum dikonfigurasi.',
            ];
        }

        $payload = [
            'url' => $url,
            'drop_pending_updates' => $dropPendingUpdates,
        ];

        $secret = $secretToken ?: $this->webhookSecret;
        if (! empty($secret)) {
            $payload['secret_token'] = $secret;
        }

        try {
            $response = Http::timeout(15)
                ->post("https://api.telegram.org/bot{$this->botToken}/setWebhook", $payload);

            $json = $response->json();
            $ok = $response->successful() && ! empty($json['ok']);

            return [
                'ok' => $ok,
                'description' => $json['description'] ?? ($ok ? 'Webhook berhasil disetel.' : 'Gagal menyetel webhook.'),
                'status' => $response->status(),
            ];
        } catch (\Throwable $e) {
            return [
                'ok' => false,
                'description' => $this->sanitize($e->getMessage()),
                'status' => null,
            ];
        }
    }

    /**
     * Hapus webhook Telegram dengan detail respons.
     */
    public function deleteWebhookWithDetails(bool $dropPendingUpdates = true): array
    {
        if (! $this->isConfigured()) {
            return [
                'ok' => false,
                'description' => 'Bot token belum dikonfigurasi.',
            ];
        }

        try {
            $response = Http::timeout(15)
                ->post("https://api.telegram.org/bot{$this->botToken}/deleteWebhook", [
                    'drop_pending_updates' => $dropPendingUpdates,
                ]);

            $json = $response->json();
            $ok = $response->successful() && ! empty($json['ok']);

            return [
                'ok' => $ok,
                'description' => $json['description'] ?? ($ok ? 'Webhook berhasil dihapus.' : 'Gagal menghapus webhook.'),
            ];
        } catch (\Throwable $e) {
            return [
                'ok' => false,
                'description' => $this->sanitize($e->getMessage()),
            ];
        }
    }

    /**
     * Kirim pesan uji coba ke target chat tertentu atau default chatId.
     */
    public function sendTestMessage(?string $targetChatId = null): array
    {
        $target = $targetChatId ?: $this->chatId;

        if (empty($target)) {
            return [
                'ok' => false,
                'description' => 'Chat ID tujuan belum ditentukan.',
            ];
        }

        if (! $this->isConfigured()) {
            return [
                'ok' => false,
                'description' => 'Bot token belum dikonfigurasi.',
            ];
        }

        $now = now()->setTimezone('Asia/Jakarta')->format('d M Y H:i:s');
        $text = "🔔 <b>UJI COBA KONEKSI BOT TELEGRAM RADNET NOC</b>\n\n"
            ."✅ Bot Telegram berhasil terhubung dengan sistem monitoring jaringan RadneXt.\n\n"
            ."📡 <b>Status:</b> Siap menerima notifikasi dan perintah.\n"
            ."🕐 <b>Waktu:</b> {$now} WIB\n"
            ."💬 <b>Target Chat ID:</b> <code>{$target}</code>\n"
            ."════════════════════════════";

        try {
            $response = Http::timeout(10)
                ->post("https://api.telegram.org/bot{$this->botToken}/sendMessage", [
                    'chat_id' => (string) $target,
                    'text' => $text,
                    'parse_mode' => 'HTML',
                ]);

            $json = $response->json();
            $ok = $response->successful() && ! empty($json['ok']);

            return [
                'ok' => $ok,
                'description' => $ok ? 'Pesan uji coba berhasil dikirim!' : ($json['description'] ?? 'Gagal mengirim pesan.'),
                'status' => $response->status(),
            ];
        } catch (\Throwable $e) {
            return [
                'ok' => false,
                'description' => $this->sanitize($e->getMessage()),
            ];
        }
    }

    /**
     * Simpan pengaturan bot ke database dan sinkronisasi ke file .env.
     */
    public function saveSettings(array $data, ?int $userId = null): void
    {
        if (Schema::hasTable('map_settings')) {
            if (array_key_exists('bot_token', $data)) {
                MapSetting::set('telegram_bot_token', $data['bot_token'], $userId);
            }
            if (array_key_exists('chat_id', $data)) {
                MapSetting::set('telegram_chat_id', $data['chat_id'], $userId);
            }
            if (array_key_exists('webhook_secret', $data)) {
                MapSetting::set('telegram_webhook_secret', $data['webhook_secret'], $userId);
            }
            if (array_key_exists('bot_info', $data)) {
                MapSetting::set('telegram_bot_info', $data['bot_info'], $userId);
            }
        }

        $this->syncToEnv([
            'TELEGRAM_BOT_TOKEN' => $data['bot_token'] ?? null,
            'TELEGRAM_CHAT_ID' => $data['chat_id'] ?? null,
            'TELEGRAM_WEBHOOK_SECRET' => $data['webhook_secret'] ?? null,
        ]);

        $this->reloadConfig();
    }

    /**
     * Sinkronisasi nilai kredensial ke file .env bila writable.
     */
    protected function syncToEnv(array $pairs): void
    {
        $envPath = base_path('.env');
        if (! file_exists($envPath) || ! is_writable($envPath)) {
            return;
        }

        try {
            $content = file_get_contents($envPath);
            foreach ($pairs as $key => $val) {
                if ($val === null) {
                    continue;
                }
                $valStr = (string) $val;
                $escaped = (str_contains($valStr, ' ') || str_contains($valStr, '#'))
                    ? '"'.addcslashes($valStr, '"\\').'"'
                    : $valStr;

                $pattern = "/^{$key}=.*/m";
                if (preg_match($pattern, $content)) {
                    $content = preg_replace($pattern, "{$key}={$escaped}", $content);
                } else {
                    $content = rtrim($content)."\n{$key}={$escaped}\n";
                }
            }
            file_put_contents($envPath, $content);
        } catch (\Throwable $e) {
            Log::warning('Gagal sinkronisasi .env untuk Telegram: '.$e->getMessage());
        }
    }
}
