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
use App\Http\Controllers\Admin\SpeedTestController;
use App\Http\Controllers\Admin\TelegramUserController;
use App\Http\Controllers\Admin\ToolsController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TelegramWebhookController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| PUNTAK MASUK
|--------------------------------------------------------------------------
*/

// Tanpa route ini, mengetik domain monitoring ini hanya menghasilkan 404.
// Akar situs langsung meneruskan ke dashboard admin; middleware auth di
// dalam group admin yang memutuskan apakah pengunjung melihat login atau
// dashboard, jadi tidak ada aturan yang diduplikasi di sini.
Route::get('/', fn () => redirect()->route('admin.dashboard'));

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
|
| Seluruh isi aplikasi ada di group ini, jadi `auth` dipasang DI LEVEL
| GROUP, bukan(route per route). Sebelumnya dashboard, monitoring, log,
| notifikasi, laporan, DHCP lease, device notes, dan Network Tools
| didefinisikan tanpa middleware sama sekali — anyone yang tahu URL-nya
| bisa membaca peta jaringan, latency, dan catatan perangkat tanpa login.
| Group `role:super_admin` di dalamnya tetap seperti sebelumnya.
|
*/

Route::prefix('admin')
    ->name('admin.')
    ->middleware('auth')
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

        // Drag and drop posisi perangkat pada Map View
        Route::post('/monitoring/device/{device}/position', [MonitoringController::class, 'updatePosition'])
            ->name('monitoring.device.position');

        // Reset semua posisi perangkat ke tata letak default
        Route::post('/monitoring/reset-positions', [MonitoringController::class, 'resetPositions'])
            ->name('monitoring.reset-positions');

        // Atur arah panah relasi (->, <-, <->, --)
        Route::post('/monitoring/connection/{connection}/direction', [MonitoringController::class, 'updateDirection'])
            ->name('monitoring.connection.direction');

        // CRUD Relasi Antar-Perangkat (WhatsUp Gold)
        Route::post('/monitoring/connection', [MonitoringController::class, 'storeConnection'])
            ->name('monitoring.connection.store');
        Route::put('/monitoring/connection/{connection}', [MonitoringController::class, 'updateConnection'])
            ->name('monitoring.connection.update');
        Route::delete('/monitoring/connection/{connection}', [MonitoringController::class, 'destroyConnection'])
            ->name('monitoring.connection.destroy');

        // CRUD Card Area / POP Pembatas Router (WhatsUp Gold 2026)
        Route::post('/monitoring/area', [MonitoringController::class, 'storeArea'])
            ->name('monitoring.area.store');
        Route::put('/monitoring/area/{area}', [MonitoringController::class, 'updateArea'])
            ->name('monitoring.area.update');
        Route::delete('/monitoring/area/{area}', [MonitoringController::class, 'destroyArea'])
            ->name('monitoring.area.destroy');
        Route::post('/monitoring/area/{area}/bounds', [MonitoringController::class, 'updateAreaBounds'])
            ->name('monitoring.area.bounds');

        // Simpan & Reset Kustomisasi Full Layout Map oleh Super Admin (Pembatas Router, Posisi, Jarak Jarum)
        Route::post('/monitoring/map-layout', [MonitoringController::class, 'saveMapLayout'])
            ->name('monitoring.map-layout.save');
        Route::post('/monitoring/map-layout/reset', [MonitoringController::class, 'resetMapLayout'])
            ->name('monitoring.map-layout.reset');

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
        | Ping Monitor -> dialihkan ke Network Tools
        |--------------------------------------------------------------------------
        | Tampilan ping monitor (tab Radnet / Luar / Ringkasan, terminal log,
        | dan analisis bahasa sehari-hari) sudah dipindahkan ke dalam
        | /admin/tools, jadi halaman /admin/ping-monitor tidak lagi menjadi
        | halaman terpisah. URL lamanya tetap hidup sebagai redirect supaya
        | bookmark dan tautan yang sudah dibagikan orang tidak jadi 404.
        |
        | Redirect, bukan dihapus, dengan alasan yang sama.
        */

        Route::middleware(['auth', 'role:super_admin'])->group(function () {
            Route::permanentRedirect('/ping-monitor', '/admin/tools')
                ->name('ping-monitor');
        });

        /*
        |--------------------------------------------------------------------------
        | Speed Test (khusus Super Admin)
        |--------------------------------------------------------------------------
        | Pengujian dijalankan di sisi server lewat SpeedTestService
        | (speedtest-cli melalui SSH), jadi halaman dan seluruh endpoint
        | dibatasi Super Admin: staff Forbidden, tamu/Login.
        |
        | Batas throttle sengaja tidak seragam:
        |   run    satu pengujian penuh sekaligus -> ketat (6/menit).
        |   step   polling bertahap tiap detik, butuh beberapa panggilan
        |          per pengujian -> longgar, kalau disamakan dengan 'run'
        |          pengujian akan kena 429 di tengah jalan.
        |   probe  cuplikan live, diamankan RateLimiter internal controller
        |          (20 per 10 detik per run) -> route ini hanya pelindung kasar.
        */

        Route::middleware(['auth', 'role:super_admin'])->group(function () {
            Route::get('/speed-test', [SpeedTestController::class, 'index'])
                ->name('speed-test');

            Route::post('/speed-test/run', [SpeedTestController::class, 'run'])
                ->middleware('throttle:6,1')
                ->name('speed-test.run');

            Route::post('/speed-test/start', [SpeedTestController::class, 'start'])
                ->middleware('throttle:30,1')
                ->name('speed-test.start');

            Route::post('/speed-test/step', [SpeedTestController::class, 'step'])
                ->middleware('throttle:180,1')
                ->name('speed-test.step');

            Route::post('/speed-test/probe', [SpeedTestController::class, 'probe'])
                ->middleware('throttle:120,1')
                ->name('speed-test.probe');

            Route::get('/speed-test/history', [SpeedTestController::class, 'history'])
                ->name('speed-test.history');

            // Tong unggah milik sendiri: pengukuran laju unggah di browser
            // harus mengunggah ke origin yang sama, karena CDN luar menolak
            // POST lintas asal (CORS). Batasnya longgar karena satu pengujian
            // mengunggah beberapa kali berturut-turut.
            Route::post('/speed-test/upload-sink', [SpeedTestController::class, 'uploadSink'])
                ->middleware('throttle:120,1')
                ->name('speed-test.upload-sink');
        });

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
})
    ->middleware('auth')
    ->name('laporan.redirect');

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
