<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MapSetting;
use App\Models\TelegramLog;
use App\Models\TelegramSubscriber;
use App\Models\TelegramUser;
use App\Services\TelegramService;
use App\Services\TelegramUserService;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class TelegramUserController extends Controller
{
    public function index(Request $request, TelegramService $telegram)
    {
        $query = trim((string) $request->query('q', ''));
        $status = (string) $request->query('status', 'all');
        $role = (string) $request->query('role', 'all');

        if (! in_array($status, ['all', 'pending', 'approved'], true)) {
            $status = 'all';
        }

        if (! in_array($role, ['all', 'super_admin', 'admin', 'staff', 'pending'], true)) {
            $role = 'all';
        }

        $botToken = $telegram->getBotToken();
        $chatId = $telegram->getChatId();
        $webhookSecret = $telegram->getWebhookSecret();

        $botProfile = [
            'ok' => false,
            'bot' => null,
            'description' => 'Bot token belum dikonfigurasi.',
        ];
        $webhookInfo = [
            'ok' => false,
            'info' => null,
        ];

        if ($telegram->isConfigured()) {
            $botProfile = Cache::remember('telegram_bot_profile', 30, fn () => $telegram->getMe());
            $webhookInfo = Cache::remember('telegram_webhook_info', 30, fn () => $telegram->getWebhookInfo());
        }

        $kpi = [
            'total' => 0,
            'pending' => 0,
            'approved' => 0,
            'active_today' => 0,
            'super_admin' => 0,
            'admin' => 0,
            'staff' => 0,
            'subscribers' => 0,
        ];

        if (! Schema::hasTable('telegram_users')) {
            return view('admin.telegram-users', [
                'telegramUsers' => new LengthAwarePaginator([], 0, 15, 1, [
                    'path' => $request->url(),
                    'query' => $request->query(),
                ]),
                'logs' => collect(),
                'kpi' => $kpi,
                'q' => $query,
                'status' => $status,
                'role' => $role,
                'botToken' => $botToken,
                'chatId' => $chatId,
                'webhookSecret' => $webhookSecret,
                'botProfile' => $botProfile,
                'webhookInfo' => $webhookInfo,
            ]);
        }

        $users = TelegramUser::query()
            ->when($query !== '', function ($builder) use ($query) {
                $like = '%'.addcslashes($query, '%_').'%';
                $builder->where(function ($nested) use ($like) {
                    $nested->where('first_name', 'like', $like)
                        ->orWhere('last_name', 'like', $like)
                        ->orWhere('username', 'like', $like)
                        ->orWhere('chat_id', 'like', $like);
                });
            })
            ->when($status === 'pending', fn ($builder) => $builder->where('is_approved', false))
            ->when($status === 'approved', fn ($builder) => $builder->where('is_approved', true))
            ->when($role !== 'all', fn ($builder) => $builder->where('role', $role))
            ->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString();

        $kpi = [
            'total' => TelegramUser::query()->count(),
            'pending' => TelegramUser::query()->where('is_approved', false)->count(),
            'approved' => TelegramUser::query()->where('is_approved', true)->count(),
            'active_today' => TelegramUser::query()
                ->whereNotNull('last_active_at')
                ->where('last_active_at', '>=', now()->startOfDay())
                ->count(),
            'super_admin' => TelegramUser::query()->where('role', 'super_admin')->where('is_approved', true)->count(),
            'admin' => TelegramUser::query()->where('role', 'admin')->where('is_approved', true)->count(),
            'staff' => TelegramUser::query()->where('role', 'staff')->where('is_approved', true)->count(),
            'subscribers' => Schema::hasTable('telegram_subscribers')
                ? TelegramSubscriber::query()->where('is_active', true)->count()
                : 0,
        ];

        $logs = Schema::hasTable('telegram_logs')
            ? TelegramLog::query()->with('telegramUser')->latest()->limit(20)->get()
            : collect();

        return view('admin.telegram-users', [
            'telegramUsers' => $users,
            'logs' => $logs,
            'kpi' => $kpi,
            'q' => $query,
            'status' => $status,
            'role' => $role,
            'botToken' => $botToken,
            'chatId' => $chatId,
            'webhookSecret' => $webhookSecret,
            'botProfile' => $botProfile,
            'webhookInfo' => $webhookInfo,
        ]);
    }

    public function approve(Request $request, TelegramUser $telegramUser, TelegramService $telegram, TelegramUserService $users)
    {
        $role = $request->input('role', $telegramUser->role === 'pending' ? 'staff' : $telegramUser->role);

        $telegramUser->update([
            'is_approved' => true,
            'role' => $role,
        ]);

        $message = "🎉 Akses Telegram RADNET Anda telah disetujui!\n\n".
            "Anda sekarang dapat menggunakan bot monitoring.\n\n".
            "Gunakan /help untuk melihat daftar perintah yang tersedia.";

        $chatId = (string) $telegramUser->chat_id;
        $sent = false;

        try {
            $sent = $telegram->sendToChat($chatId, $message);

            if ($sent) {
                $users->log($telegramUser, $chatId, 'Approval notification sent', $message);
            } else {
                $users->log($telegramUser, $chatId, 'Approval notification failed', null);
                Log::warning('Gagal mengirim notifikasi Telegram saat approval', ['chat_id' => $chatId]);
            }
        } catch (\Throwable $e) {
            $users->log($telegramUser, $chatId, 'Approval notification error', $e->getMessage());
            Log::warning('Exception mengirim notifikasi Telegram saat approval: '.$e->getMessage(), ['chat_id' => $chatId]);
            $sent = false;
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'User Telegram berhasil disetujui.',
                'notification_sent' => (bool) $sent,
            ]);
        }

        $redirect = redirect()->route('admin.telegram-users')
            ->with('success', 'User Telegram berhasil disetujui.');

        if (! $sent) {
            $redirect = $redirect->with('error', 'Approval berhasil, tetapi notifikasi Telegram gagal dikirim.');
        }

        return $redirect;
    }

    public function reject(Request $request, TelegramUser $telegramUser)
    {
        $chatId = (string) $telegramUser->chat_id;
        $telegramUser->delete();

        if (Schema::hasTable('telegram_subscribers')) {
            TelegramSubscriber::query()
                ->where('chat_id', $chatId)
                ->update(['is_active' => false]);
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'User Telegram berhasil dihapus.',
            ]);
        }

        return redirect()->route('admin.telegram-users')
            ->with('success', 'User Telegram berhasil dihapus.');
    }

    public function destroy(Request $request, TelegramUser $telegramUser)
    {
        return $this->reject($request, $telegramUser);
    }

    public function setRole(Request $request, TelegramUser $telegramUser)
    {
        $validated = $request->validate([
        'role' => ['required', 'string', 'in:super_admin,admin,staff,pending'],
        ]);

        $role = $validated['role'];
        $telegramUser->update([
            'role' => $role,
            'is_approved' => $role !== 'pending',
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Role user Telegram berhasil diperbarui.',
                'role' => $role,
            ]);
        }

        return redirect()->route('admin.telegram-users')
            ->with('success', 'Role user Telegram berhasil diperbarui.');
    }

    public function broadcast(Request $request, TelegramService $telegram)
    {
        $validated = $request->validate([
            'message' => ['required', 'string', 'max:5000'],
        ]);

        $result = $telegram->broadcastApproved($validated['message'], [
            'source' => 'admin_dashboard',
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => $result['sent'] > 0,
                'message' => $result['sent'] > 0
                    ? 'Broadcast berhasil diproses.'
                    : 'Broadcast tidak memiliki penerima aktif.',
                'result' => $result,
            ]);
        }

        return redirect()->route('admin.telegram-users')
            ->with($result['sent'] > 0 ? 'success' : 'error', $result['sent'] > 0
                ? "Broadcast dikirim ke {$result['sent']} user."
                : 'Broadcast tidak memiliki penerima aktif.');
    }

    public function updateSettings(Request $request, TelegramService $telegram)
    {
        $validated = $request->validate([
            'bot_token' => ['nullable', 'string', 'max:255'],
            'chat_id' => ['nullable', 'string', 'max:100'],
            'webhook_secret' => ['nullable', 'string', 'max:255'],
        ]);

        $botToken = trim((string) ($validated['bot_token'] ?? ''));
        $chatId = trim((string) ($validated['chat_id'] ?? ''));
        $webhookSecret = trim((string) ($validated['webhook_secret'] ?? ''));

        Cache::forget('telegram_bot_profile');
        Cache::forget('telegram_webhook_info');

        $botInfo = null;
        $connectionNotice = '';

        if ($botToken !== '') {
            $test = $telegram->getMe($botToken);
            if ($test['ok']) {
                $botInfo = $test['bot'];
                $username = $test['bot']['username'] ?? 'bot';
                $firstName = $test['bot']['first_name'] ?? 'Bot';
                $connectionNotice = " Terhubung ke bot @{$username} ({$firstName}).";
            } else {
                $connectionNotice = ' Peringatan: Token tersimpan, tetapi verifikasi Telegram gagal: '.($test['description'] ?? 'Token tidak valid.');
            }
        }

        $telegram->saveSettings([
            'bot_token' => $botToken,
            'chat_id' => $chatId,
            'webhook_secret' => $webhookSecret,
            'bot_info' => $botInfo,
        ], auth()->id());

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Pengaturan Telegram berhasil disimpan.'.$connectionNotice,
                'bot' => $botInfo,
            ]);
        }

        return redirect()->route('admin.telegram-users')
            ->with('success', 'Pengaturan bot Telegram berhasil disimpan.'.$connectionNotice);
    }

    public function testConnection(Request $request, TelegramService $telegram)
    {
        $token = $request->input('bot_token');
        $startTime = microtime(true);
        $result = $telegram->getMe($token ?: null);
        $latencyMs = round((microtime(true) - $startTime) * 1000);

        Cache::forget('telegram_bot_profile');
        if ($result['ok'] && ! empty($result['bot'])) {
            Cache::put('telegram_bot_profile', $result, 60);
        }

        $bot = $result['bot'] ?? null;
        $message = $result['ok']
            ? "Terkoneksi ke bot: @".($bot['username'] ?? 'bot')." (".($bot['first_name'] ?? 'Bot').") — Latensi {$latencyMs} ms"
            : 'Gagal terhubung: '.($result['description'] ?? 'Token tidak valid.');

        $data = [
            'success' => $result['ok'],
            'message' => $message,
            'bot' => $bot,
            'latency_ms' => $latencyMs,
            'status' => $result['status'] ?? null,
        ];

        if ($request->expectsJson()) {
            return response()->json($data, $result['ok'] ? 200 : 422);
        }

        return redirect()->route('admin.telegram-users')
            ->with($result['ok'] ? 'success' : 'error', $message);
    }

    public function testSend(Request $request, TelegramService $telegram)
    {
        $targetChatId = $request->input('chat_id');
        $result = $telegram->sendTestMessage($targetChatId ?: null);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => $result['ok'],
                'message' => $result['description'],
            ], $result['ok'] ? 200 : 422);
        }

        return redirect()->route('admin.telegram-users')
            ->with($result['ok'] ? 'success' : 'error', $result['description']);
    }

    public function setWebhook(Request $request, TelegramService $telegram)
    {
        $validated = $request->validate([
            'webhook_url' => ['required', 'url'],
            'secret_token' => ['nullable', 'string', 'max:255'],
        ]);

        $result = $telegram->setWebhook($validated['webhook_url'], $validated['secret_token'] ?? null);
        Cache::forget('telegram_webhook_info');

        if ($request->expectsJson()) {
            return response()->json([
                'success' => $result['ok'],
                'message' => $result['description'],
            ], $result['ok'] ? 200 : 422);
        }

        return redirect()->route('admin.telegram-users')
            ->with($result['ok'] ? 'success' : 'error', $result['description']);
    }

    public function deleteWebhook(Request $request, TelegramService $telegram)
    {
        $result = $telegram->deleteWebhookWithDetails(true);
        Cache::forget('telegram_webhook_info');

        $message = $result['ok']
            ? 'Webhook berhasil dihapus. Bot siap menggunakan mode long polling.'
            : ($result['description'] ?? 'Gagal menghapus webhook.');

        if ($request->expectsJson()) {
            return response()->json([
                'success' => $result['ok'],
                'message' => $message,
            ], $result['ok'] ? 200 : 422);
        }

        return redirect()->route('admin.telegram-users')
            ->with($result['ok'] ? 'success' : 'error', $message);
    }
}
