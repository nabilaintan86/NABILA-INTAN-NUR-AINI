<?php

namespace App\Services;

use App\Models\Device;
use App\Models\DhcpLease;
use App\Models\MonitoringLog;
use App\Models\TelegramSubscriber;
use App\Models\TelegramUser;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Schema;

class TelegramUpdateHandler
{
    public function __construct(
        protected TelegramService $telegram,
        protected TelegramUserService $users,
    ) {}

    public function handleUpdate(array $update): ?bool
    {
        $message = $update['message'] ?? null;

        if (! is_array($message)) {
            return null;
        }

        $rawChatId = $message['chat']['id'] ?? null;

        if (! is_int($rawChatId) && ! is_string($rawChatId)) {
            return null;
        }

        $chatId = (string) $rawChatId;
        $text = trim((string) ($message['text'] ?? ''));
        $from = is_array($message['from'] ?? null) ? $message['from'] : [];
        $rateKey = 'telegram:message:'.$chatId;

        if (RateLimiter::tooManyAttempts($rateKey, 30)) {
            $user = Schema::hasTable('telegram_users')
                ? $this->users->find($chatId)
                : null;
            $response = '⏳ Terlalu banyak pesan. Silakan coba lagi dalam satu menit.';
            $this->telegram->sendToChat($chatId, $response);
            $this->users->log($user, $chatId, $text, $response);

            return false;
        }

        RateLimiter::hit($rateKey, 60);

        if (! Schema::hasTable('telegram_users')) {
            return $this->handleLegacyUpdate($chatId, $text, $from);
        }

        return $this->handleManagedUpdate($chatId, $text, $from);
    }

    protected function handleLegacyUpdate(string $chatId, string $text, array $from): bool
    {
        $isStart = (bool) preg_match('#^/start($|@)#', $text);
        $isStop = (bool) preg_match('#^/stop($|@)#', $text);

        if ($isStart) {
            $this->subscribeLegacy($chatId, $from);
        } elseif ($isStop) {
            $this->unsubscribeLegacy($chatId);
        }

        $this->telegram->sendToChat($chatId, $this->legacyReplyText($isStart, $isStop));

        return true;
    }

    protected function handleManagedUpdate(string $chatId, string $text, array $from): bool
    {
        $command = $this->parseCommand($text);
        $user = $this->users->find($chatId);

        if ($command === null || $command['name'] === 'start') {
            if ($command !== null && $command['name'] === 'start') {
                $user = $this->users->register($chatId, $from);
                $isNew = $user->wasRecentlyCreated;
                $response = $user->is_approved
                    ? "✅ Anda sudah terdaftar dan disetujui.\n\nGunakan /help untuk melihat perintah."
                    : '✅ Registrasi berhasil. Menunggu persetujuan admin.';

                $this->telegram->sendToChat($chatId, $response);
                $this->users->log($user, $chatId, $text, $response);

                if ($isNew) {
                    $this->users->notifySuperAdmins($user);
                }

                return true;
            }

            if ($user !== null) {
                $this->users->touch($user, $from);
            }

            $response = $user === null
                ? 'Kirim /start untuk registrasi Telegram.'
                : ($user->is_approved
                    ? 'Perintah tidak dikenal. Kirim /help untuk melihat perintah.'
                    : '⏳ Akun Anda belum disetujui admin. Gunakan /start untuk registrasi.');

            $this->telegram->sendToChat($chatId, $response);
            $this->users->log($user, $chatId, $text, $response);

            return true;
        }

        if ($user === null) {
            $response = 'Kirim /start untuk registrasi Telegram.';
            $this->telegram->sendToChat($chatId, $response);
            $this->users->log(null, $chatId, $text, $response);

            return true;
        }

        $this->users->touch($user, $from);

        if (! $user->is_approved || $user->role === 'pending') {
            $response = '⏳ Akun Anda belum disetujui admin. Gunakan /start untuk registrasi.';
            $this->telegram->sendToChat($chatId, $response);
            $this->users->log($user, $chatId, $text, $response);

            return true;
        }

        if (! $this->isAllowed($user, $command['name'])) {
            $response = '⛔ Anda tidak memiliki akses untuk command ini.';
            $this->telegram->sendToChat($chatId, $response);
            $this->users->log($user, $chatId, $text, $response);

            return true;
        }

        $response = $this->executeCommand($user, $command['name'], $command['argument']);
        $this->telegram->sendToChat($chatId, $response);
        $this->users->log($user, $chatId, $text, $response);

        return true;
    }

    protected function parseCommand(string $text): ?array
    {
        if ($text === '' || ! preg_match('#^/([a-z0-9_]+)(?:@[a-z0-9_]+)?(?:\s+(.*))?$#isu', $text, $matches)) {
            return null;
        }

        return [
            'name' => strtolower($matches[1]),
            'argument' => mb_substr(trim((string) ($matches[2] ?? '')), 0, 5000),
        ];
    }

    protected function isAllowed(TelegramUser $user, string $command): bool
    {
        if (in_array($command, ['help', 'status', 'device'], true)) {
            return true;
        }

        if ($command === 'incident') {
            return in_array($user->role, ['staff', 'admin', 'super_admin'], true);
        }

        if ($command === 'lease') {
            return in_array($user->role, ['admin', 'super_admin'], true);
        }

        if (in_array($command, ['broadcast', 'users', 'approve', 'reject', 'setrole'], true)) {
            return $user->role === 'super_admin';
        }

        return false;
    }

    protected function executeCommand(TelegramUser $user, string $command, string $argument): string
    {
        return match ($command) {
            'help' => $this->helpText($user),
            'status' => $this->statusText(),
            'device' => $this->deviceText($argument),
            'incident' => $this->incidentText(),
            'lease' => $this->leaseText(),
            'broadcast' => $this->broadcastText($argument),
            'users' => $this->usersText(),
            'approve' => $this->approveText($argument),
            'reject' => $this->rejectText($argument),
            'setrole' => $this->setRoleText($argument),
            default => 'Perintah tidak dikenal. Kirim /help untuk melihat perintah.',
        };
    }

    protected function helpText(TelegramUser $user): string
    {
        $commands = [
            '/status — ringkasan monitoring',
            '/device — daftar perangkat',
            '/device {nama} — cari perangkat',
            '/incident — insiden terbaru',
        ];

        if (in_array($user->role, ['admin', 'super_admin'], true)) {
            $commands[] = '/lease — lease DHCP aktif';
        }

        if ($user->role === 'super_admin') {
            $commands[] = '/broadcast {pesan} — kirim broadcast';
            $commands[] = '/users — daftar user Telegram';
            $commands[] = '/approve {chat_id} — setujui user';
            $commands[] = '/reject {chat_id} — hapus user';
            $commands[] = '/setrole {chat_id} {role} — ubah role';
        }

        return "🤖 RADNET Telegram Bot\n\n"
            ."Perintah yang tersedia:\n"
            .implode("\n", $commands);
    }

    protected function statusText(): string
    {
        if (! Schema::hasTable('device')) {
            return "📊 Status RADNET\n\nData perangkat belum tersedia.";
        }

        $total = Device::query()->count();
        $up = Device::query()->where('status', 'up')->count();
        $down = Device::query()->where('status', 'down')->count();
        $warning = Device::query()->where('status', 'warning')->count();

        return "📊 Status RADNET\n\n"
            ."Total: {$total}\n"
            ."🟢 UP: {$up}\n"
            ."🔴 DOWN: {$down}\n"
            ."🟡 WARNING: {$warning}";
    }

    protected function deviceText(string $argument): string
    {
        if (! Schema::hasTable('device')) {
            return 'Data perangkat belum tersedia.';
        }

        $query = Device::query()->orderBy('name');

        if ($argument !== '') {
            $query->where('name', 'like', '%'.addcslashes($argument, '%_').'%');
        }

        $devices = $query->limit(10)->get();

        if ($devices->isEmpty()) {
            return 'Perangkat tidak ditemukan.';
        }

        $lines = ['📡 Perangkat RADNET', ''];

        foreach ($devices as $device) {
            $lines[] = '• '.($device->name ?: '-').' | '.($device->ip_address ?: '-').' | '.strtoupper((string) ($device->status ?: 'unknown'));
        }

        return implode("\n", $lines);
    }

    protected function incidentText(): string
    {
        if (! Schema::hasTable('monitoring_logs')) {
            return 'Data incident belum tersedia.';
        }

        $incidents = MonitoringLog::query()
            ->whereIn('status', ['down', 'warning'])
            ->orderByDesc('checked_at')
            ->limit(10)
            ->get();

        if ($incidents->isEmpty()) {
            return '✅ Tidak ada incident aktif.';
        }

        $lines = ['🚨 Incident terbaru', ''];

        foreach ($incidents as $incident) {
            $time = $incident->checked_at?->format('d/m/Y H:i') ?: '-';
            $lines[] = '• '.strtoupper((string) $incident->status).' | '.($incident->device_name ?: '-').' | '.($incident->ip_address ?: '-').' | '.$time;
        }

        return implode("\n", $lines);
    }

    protected function leaseText(): string
    {
        if (! Schema::hasTable('dhcp_leases')) {
            return 'Data lease DHCP belum tersedia.';
        }

        $leases = DhcpLease::query()
            ->where('classification', '!=', 'unknown')
            ->where(function ($query) {
                $query->whereNull('lease_end')->orWhere('lease_end', '>=', now());
            })
            ->orderBy('ip_address')
            ->limit(10)
            ->get();

        if ($leases->isEmpty()) {
            return 'Tidak ada lease DHCP aktif.';
        }

        $lines = ['🌐 Lease DHCP aktif', ''];

        foreach ($leases as $lease) {
            $lines[] = '• '.($lease->hostname ?: $lease->ip_address ?: '-').' | '.($lease->ip_address ?: '-').' | '.($lease->mac_address ?: '-');
        }

        return implode("\n", $lines);
    }

    protected function broadcastText(string $argument): string
    {
        if ($argument === '') {
            return 'Gunakan format: /broadcast {pesan}';
        }

        $result = $this->telegram->broadcastApproved($argument, [
            'source' => 'telegram_command',
        ]);

        return "📣 Broadcast diproses.\n"
            ."Penerima: {$result['target_count']}\n"
            ."Berhasil: {$result['sent']}\n"
            .'Gagal: '.count($result['failed']);
    }

    protected function usersText(): string
    {
        $users = TelegramUser::query()
            ->orderByDesc('created_at')
            ->limit(20)
            .get();

        if ($users->isEmpty()) {
            return 'Belum ada user Telegram.';
        }

        $lines = ['👥 User Telegram', ''];

        foreach ($users as $telegramUser) {
            $name = trim((string) ($telegramUser->first_name.' '.$telegramUser->last_name));
            $name = $name !== '' ? $name : ($telegramUser->username ?: '-');
            $status = $telegramUser->is_approved ? 'approved' : 'pending';
            $lines[] = '• '.$name.' | '.$telegramUser->chat_id.' | '.$telegramUser->role.' | '.$status;
        }

        return implode("\n", $lines);
    }

    protected function approveText(string $argument): string
    {
        if ($argument === '') {
            return 'Gunakan format: /approve {chat_id}';
        }

        $telegramUser = TelegramUser::query()->where('chat_id', $argument)->first();

        if ($telegramUser === null) {
            return 'User Telegram tidak ditemukan.';
        }

        $telegramUser->update([
            'is_approved' => true,
            'role' => $telegramUser->role === 'pending' ? 'staff' : $telegramUser->role,
        ]);

        return "✅ User {$telegramUser->chat_id} berhasil disetujui.";
    }

    protected function rejectText(string $argument): string
    {
        if ($argument === '') {
            return 'Gunakan format: /reject {chat_id}';
        }

        $telegramUser = TelegramUser::query()->where('chat_id', $argument)->first();

        if ($telegramUser === null) {
            return 'User Telegram tidak ditemukan.';
        }

        $chatId = (string) $telegramUser->chat_id;
        $telegramUser->delete();

        if (Schema::hasTable('telegram_subscribers')) {
            TelegramSubscriber::query()->where('chat_id', $chatId)->update(['is_active' => false]);
        }

        return "🗑️ User {$chatId} berhasil dihapus.";
    }

    protected function setRoleText(string $argument): string
    {
        $parts = preg_split('/\s+/', trim($argument), 2) ?: [];
        $chatId = $parts[0] ?? '';
        $role = strtolower($parts[1] ?? '');

        if ($chatId === '' || ! in_array($role, ['super_admin', 'admin', 'staff', 'pending'], true)) {
            return 'Gunakan format: /setrole {chat_id} {super_admin|admin|staff|pending}';
        }

        $telegramUser = TelegramUser::query()->where('chat_id', $chatId)->first();

        if ($telegramUser === null) {
            return 'User Telegram tidak ditemukan.';
        }

        $telegramUser->update([
            'role' => $role,
            'is_approved' => $role !== 'pending',
        ]);

        return "✅ Role user {$chatId} diubah menjadi {$role}.";
    }

    protected function subscribeLegacy(string $chatId, array $from): void
    {
        TelegramSubscriber::updateOrCreate(
            ['chat_id' => (int) $chatId],
            [
                'first_name' => $from['first_name'] ?? null,
                'username' => $from['username'] ?? null,
                'last_name' => $from['last_name'] ?? null,
                'is_active' => true,
                'last_interacted_at' => now(),
            ]
        );
    }

    protected function unsubscribeLegacy(string $chatId): void
    {
        TelegramSubscriber::query()
            ->where('chat_id', (int) $chatId)
            ->update([
                'is_active' => false,
                'last_interacted_at' => now(),
            ]);
    }

    protected function legacyReplyText(bool $isStart, bool $isStop): string
    {
        if ($isStart) {
            return '✅ Berhasil berlangganan! Anda akan menerima notifikasi otomatis saat ada device yang DOWN atau RECOVERED di monitoring jaringan RADNET.';
        }

        if ($isStop) {
            return "👋 Anda berhenti menerima notifikasi.\n\n"
                .'Kirim /start kapan saja untuk berlangganan kembali.';
        }

        return "Gunakan perintah:\n"
            ."/start — berlangganan notifikasi device\n"
            .'/stop  — berhenti menerima notifikasi';
    }
}
