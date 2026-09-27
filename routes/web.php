<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DeviceDetailController;
use App\Http\Controllers\Admin\DeviceNoteController;
use App\Http\Controllers\Admin\DhcpLeaseController;
use App\Http\Controllers\Admin\KelolaRouterController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Admin\LogHistoryController;
use App\Http\Controllers\Admin\MonitoringController;
use App\Http\Controllers\Admin\NotifikasiController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\TelegramUserController;
use App\Http\Controllers\Admin\ToolsController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TelegramWebhookController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| AUTHENTICATION
|--------------------------------------------------------------------------
*/

// Login
Route::get('/login', [AuthController::class, 'showLoginForm'])
    ->name('login');

Route::post('/login', [AuthController::class, 'login']);

// Register
Route::get('/register', [AuthController::class, 'showRegisterForm'])
    ->name('register');

Route::post('/register', [AuthController::class, 'register']);

// Logout
Route::post('/logout', [AuthController::class, 'logout'])
    ->name('logout');

// Forgot Password
Route::get('/forgot-password', [AuthController::class, 'showForgotForm'])
    ->name('password.request');

Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])
    ->name('password.email');

/*
|--------------------------------------------------------------------------
| ADMIN
|--------------------------------------------------------------------------
*/

Route::prefix('admin')
    ->name('admin.')
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Dashboard
        |--------------------------------------------------------------------------
        */

        Route::get('/', [DashboardController::class, 'index'])
            ->name('dashboard');

        // Endpoint JSON data dashboard (dipakai auto-refresh grafik tiap 30 dtk)
        Route::get('/dashboard/data', [DashboardController::class, 'data'])
            ->name('dashboard.data');

        /*
        |--------------------------------------------------------------------------
        | Monitoring
        |--------------------------------------------------------------------------
        */

        Route::get('/monitoring', [MonitoringController::class, 'index'])
            ->name('monitoring');

        // Endpoint JSON data topology (dipakai auto-refresh status tiap 30 dtk)
        // Dipakai oleh komponen network-topology pada /admin/monitoring.
        Route::get('/monitoring/data', [MonitoringController::class, 'data'])
            ->name('monitoring.data');

        // Detail satu device (popup MAP RADNET) - read-only, tanpa ping.
        Route::get('/monitoring/device/{device}', [MonitoringController::class, 'device'])
            ->name('monitoring.device');

        Route::get('/log', [LogHistoryController::class, 'index'])
            ->name('log');

        // Infinite scroll tab real-time (load 50 per batch)
        Route::get('/log/load-more', [LogHistoryController::class, 'loadMore'])
            ->name('log.load-more');

        // Tab arsip (date range + tabel + pagination)
        Route::get('/log/archive', [LogHistoryController::class, 'archive'])
            ->name('log.archive');

        // Detail device (modal popup) - bisa id monitoring_logs atau log_archives
        Route::get('/log/detail/{id}', [LogHistoryController::class, 'detail'])
            ->whereNumber('id')
            ->name('log.detail');

        Route::get('/notifikasi', [NotifikasiController::class, 'index'])
            ->name('notifikasi');

        // Jumlah notifikasi yang belum dibaca untuk badge / offcanvas
        Route::get('/notifikasi/unread-count', [NotifikasiController::class, 'unreadCount'])
            ->name('notifikasi.unread-count');

        // Tandai notifikasi yang baru ditampilkan di offcanvas sebagai sudah dibaca
        Route::patch('/notifikasi/read-displayed', [NotifikasiController::class, 'markDisplayedAsRead'])
            ->name('notifikasi.read-displayed');

        // Tandai semua notifikasi yang belum dibaca sebagai sudah dibaca
        Route::patch('/notifikasi/read-all', [NotifikasiController::class, 'markAllAsRead'])
            ->name('notifikasi.read-all');

        // Tandai satu notifikasi sebagai sudah dibaca
        Route::patch('/notifikasi/{notification}/read', [NotifikasiController::class, 'markAsRead'])
            ->whereNumber('notification')
            ->name('notifikasi.read');

        /*
        |--------------------------------------------------------------------------
        | Manajemen
        |--------------------------------------------------------------------------
        */

        // Kelola Router
        Route::middleware('auth')->group(function () {
            // Read-only access (admin and super_admin)
            Route::get('/kelola-router', [KelolaRouterController::class, 'index'])
                ->name('kelola-router');

            // Detail perangkat readable by authenticated users
            Route::get('/device/{device}/detail', [DeviceDetailController::class, 'show'])
                ->name('device.detail');

            // Mutating actions: only super_admin
            Route::middleware('role:super_admin')->group(function () {
                Route::post('/kelola-router', [KelolaRouterController::class, 'store'])
                    ->name('kelola-router.store');

                Route::put('/kelola-router/{device}', [KelolaRouterController::class, 'update'])
                    ->name('kelola-router.update');

                Route::delete('/kelola-router/{device}', [KelolaRouterController::class, 'destroy'])
                    ->name('kelola-router.destroy');
            });
        });

        // Detail perangkat (read-only), dipakai sebagai target link nama device
        // di tabel laporan & modal. Tidak mengubah status / menjalankan ping.
        // Hanya didefinisikan sekali (di dalam middleware auth di atas) supaya
        // tidak ada versi tanpa proteksi auth yang menimpa.

        Route::middleware('auth')->group(function () {
            // Profile user yang sedang login (tidak ada user_id di URL,
            // sehingga user hanya bisa mengedit profile miliknya sendiri).
            Route::get('/profile', [ProfileController::class, 'index'])
                ->name('profile');

            Route::post('/profile/photo', [ProfileController::class, 'updatePhoto'])
                ->name('profile.photo.update');

            // Edit informasi akun (nama + email) milik user yang sedang login.
            Route::patch('/profile', [ProfileController::class, 'updateAccount'])
                ->name('profile.update');

            // Ganti password milik user yang sedang login.
            Route::patch('/profile/password', [ProfileController::class, 'updatePassword'])
                ->name('profile.password.update');

            Route::get('/device-notes', [DeviceNoteController::class, 'report'])
                ->name('device.notes.report');

            Route::post('/device-notes', [DeviceNoteController::class, 'storeFromReport'])
                ->name('device.notes.store-report');

            Route::post('/device/{device}/notes', [DeviceNoteController::class, 'store'])
                ->name('device.notes.store');

            Route::put('/device-notes/{note}', [DeviceNoteController::class, 'update'])
                ->name('device.notes.update');

            Route::delete('/device-notes/{note}', [DeviceNoteController::class, 'destroy'])
                ->name('device.notes.destroy');
        });

        // DHCP Lease - daftar perangkat yang mendapat IP dari DHCP
        Route::get('/dhcp-lease', [DhcpLeaseController::class, 'index'])
            ->name('dhcp-lease');

        // Upload devices.csv lewat halaman (drag & drop / pilih file)
        Route::post('/dhcp-lease/upload', [DhcpLeaseController::class, 'upload'])
            ->name('dhcp-lease.upload');

        // Import devices.csv dari path default (tombol "Import Sekarang" di empty state)
        Route::post('/dhcp-lease/import', [DhcpLeaseController::class, 'sync'])
            ->name('dhcp-lease.import');

        Route::get('/laporan', [LaporanController::class, 'index'])
            ->name('laporan');

        // Export PDF laporan monitoring mengikuti periode aktif
        Route::get('/laporan/pdf', [LaporanController::class, 'pdf'])
            ->name('laporan.pdf');

        // Export PDF laporan per perangkat (menambahi, tidak mengganti,
        // route laporan.pdf di atas).
        Route::get('/laporan/perangkat/{device}/pdf', [LaporanController::class, 'perangkatPdf'])
            ->whereNumber('device')
            ->name('laporan.perangkat.pdf');

        Route::get('/tools', [ToolsController::class, 'index'])
            ->name('tools');

        Route::post('/tools/ping', [ToolsController::class, 'ping'])
            ->name('tools.ping');

        Route::post('/tools/trace', [ToolsController::class, 'trace'])
            ->name('tools.trace');

        Route::post('/tools/lookup', [ToolsController::class, 'lookup'])
            ->name('tools.lookup');

        Route::post('/tools/scan', [ToolsController::class, 'scan'])
            ->name('tools.scan');

        /*
        |--------------------------------------------------------------------------
        | Kelola User (khusus Super Admin, middleware role)
        |--------------------------------------------------------------------------
        */

        Route::group(['middleware' => 'role:super_admin'], function () {
            Route::get('/kelola-user', [UserController::class, 'index'])
                ->name('kelola-user');

            Route::get('/kelola-user/create', [UserController::class, 'create'])
                ->name('kelola-user.create');

            Route::post('/kelola-user', [UserController::class, 'store'])
                ->name('kelola-user.store');

            Route::get('/kelola-user/{user}/edit', [UserController::class, 'edit'])
                ->name('kelola-user.edit');

            Route::put('/kelola-user/{user}', [UserController::class, 'update'])
                ->name('kelola-user.update');

            Route::delete('/kelola-user/{user}', [UserController::class, 'destroy'])
                ->name('kelola-user.destroy');
        });

        Route::middleware(['auth', 'role:super_admin'])->group(function () {
            Route::get('/telegram-users', [TelegramUserController::class, 'index'])
                ->name('telegram-users');

            Route::post('/telegram-users/{telegramUser}/approve', [TelegramUserController::class, 'approve'])
                ->name('telegram-users.approve');

            Route::post('/telegram-users/{telegramUser}/reject', [TelegramUserController::class, 'reject'])
                ->name('telegram-users.reject');

            Route::delete('/telegram-users/{telegramUser}', [TelegramUserController::class, 'destroy'])
                ->name('telegram-users.destroy');

            Route::post('/telegram-users/{telegramUser}/set-role', [TelegramUserController::class, 'setRole'])
                ->name('telegram-users.set-role');

            Route::post('/telegram-users/broadcast', [TelegramUserController::class, 'broadcast'])
                ->name('telegram-users.broadcast');
        });
    });

// Compatibility routes for legacy/short URLs. These redirect to the admin
// prefixed routes so existing bookmarks like /laporan and /kelola-router work
// without changing the admin middleware or controllers.
Route::get('/laporan', function () {
    return redirect()->route('admin.laporan');
})->name('laporan.redirect');

Route::get('/kelola-router', function () {
    return redirect()->route('admin.kelola-router');
})
    ->middleware('auth')
    ->name('kelola-router.redirect');

/*
|--------------------------------------------------------------------------
| TELEGRAM BOT WEBHOOK
|--------------------------------------------------------------------------
| Endpoint callback yang dipanggil Telegram Bot API saat ada update
| (mis. user menekan /start). Nilai chat_id disimpan sebagai subscriber
| agar notifikasi device DOWN/RECOVERED otomatis terkirim ke semua subscriber.
|--------------------------------------------------------------------------
*/

Route::post('/telegram/webhook', [TelegramWebhookController::class, 'handle'])
    ->name('telegram.webhook');

Route::post('/api/telegram/webhook', [TelegramWebhookController::class, 'handle'])
    ->name('telegram.webhook.api');
