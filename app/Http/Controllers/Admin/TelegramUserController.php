<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TelegramLog;
use App\Models\TelegramSubscriber;
use App\Models\TelegramUser;
use App\Services\TelegramService;
use App\Services\TelegramUserService;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Schema;

class TelegramUserController extends Controller
{
    public function index(Request $request)
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

        if (! Schema::hasTable('telegram_users')) {
            return view('admin.telegram-users', [
                'telegramUsers' => new LengthAwarePaginator([], 0, 15, 1, [
                    'path' => $request->url(),
                    'query' => $request->query(),
                ]),
                'logs' => collect(),
                'kpi' => [
                    'total' => 0,
                    'pending' => 0,
                    'approved' => 0,
                    'active_today' => 0,
                ],
                'q' => $query,
                'status' => $status,
                'role' => $role,
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
        ]);
    }

    public function approve(Request $request, TelegramUser $telegramUser, TelegramService $telegram, TelegramUserService $users)
    {
        $telegramUser->update([
            'is_approved' => true,
            'role' => $telegramUser->role === 'pending' ? 'staff' : $telegramUser->role,
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
            'role' => ['required', 'string', 'in:super_admin,admin,pending'],
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
}
