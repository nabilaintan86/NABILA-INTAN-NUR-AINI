<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DeviceDetailController;
use App\Http\Controllers\Admin\DhcpLeaseController;
use App\Http\Controllers\Admin\DeviceNoteController;
use App\Http\Controllers\Admin\EzyHomeController;
use App\Http\Controllers\Admin\KelolaRouterController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Admin\LogHistoryController;
use App\Http\Controllers\Admin\MonitoringController;
use App\Http\Controllers\Admin\NotifikasiController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\TelegramUserController;
use App\Http\Controllers\Admin\ToolsController;
use App\Http\Controllers\Admin\TopologyMapController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TelegramWebhookController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| AUTHENTICATION
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| ADMIN AREA
|--------------------------------------------------------------------------
*/

Route::prefix('admin')
    ->name('admin.')
    ->group(function () {

        /*
        |----------------------------------------------------------------------
        | Dashboard
        |----------------------------------------------------------------------
        */

        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/dashboard/data', [DashboardController::class, 'data'])->name('dashboard.data');

        /*
        |----------------------------------------------------------------------
        | Monitoring
        |----------------------------------------------------------------------
        */

        Route::get('/monitoring', [MonitoringController::class, 'index'])->name('monitoring');
        Route::get('/monitoring/data', [MonitoringController::class, 'data'])->name('monitoring.data');
        Route::get('/monitoring/devices', [MonitoringController::class, 'devices'])->name('monitoring.devices');
        Route::get('/monitoring/device/{device}', [MonitoringController::class, 'device'])->name('monitoring.device');

        Route::post('/monitoring/device/{device}/position', [MonitoringController::class, 'updatePosition'])->name('monitoring.device.position');
        Route::post('/monitoring/reset-positions', [MonitoringController::class, 'resetPositions'])->name('monitoring.reset-positions');

        Route::post('/monitoring/connection/{connection}/direction', [MonitoringController::class, 'updateDirection'])->name('monitoring.connection.direction');
        Route::post('/monitoring/connection', [MonitoringController::class, 'storeConnection'])->name('monitoring.connection.store');
        Route::put('/monitoring/connection/{connection}', [MonitoringController::class, 'updateConnection'])->name('monitoring.connection.update');
        Route::delete('/monitoring/connection/{connection}', [MonitoringController::class, 'destroyConnection'])->name('monitoring.connection.destroy');

        Route::post('/monitoring/area', [MonitoringController::class, 'storeArea'])->name('monitoring.area.store');
        Route::put('/monitoring/area/{area}', [MonitoringController::class, 'updateArea'])->name('monitoring.area.update');
        Route::delete('/monitoring/area/{area}', [MonitoringController::class, 'destroyArea'])->name('monitoring.area.destroy');
        Route::post('/monitoring/area/{area}/bounds', [MonitoringController::class, 'updateAreaBounds'])->name('monitoring.area.bounds');

        Route::post('/monitoring/map-layout', [MonitoringController::class, 'saveMapLayout'])->name('monitoring.map-layout.save');
        Route::post('/monitoring/map-layout/reset', [MonitoringController::class, 'resetMapLayout'])->name('monitoring.map-layout.reset');

        // CRUD Device Monitoring (Super Admin)
        Route::post('/monitoring/device', [MonitoringController::class, 'storeDevice'])->name('monitoring.device.store');
        Route::put('/monitoring/device/{device}', [MonitoringController::class, 'updateDevice'])->name('monitoring.device.update');
        Route::delete('/monitoring/device/{device}', [MonitoringController::class, 'destroyDevice'])->name('monitoring.device.destroy');
        Route::delete('/monitoring/position/{position}', [MonitoringController::class, 'destroyPosition'])->name('monitoring.position.destroy');

        /*
        |----------------------------------------------------------------------
        | Topology Maps (CRUD)
        |----------------------------------------------------------------------
        | ROUTE STRUCTURE:
        |   - READ (index, show, data)        → semua role bisa akses
        |   - WRITE (create, store, edit, update, destroy) → cuma Super Admin
        |----------------------------------------------------------------------
        */
        Route::prefix('topology-maps')->name('topology-maps.')->group(function () {

            /*
            |--------------------------------------------------------------
            | READ-ONLY (semua role)
            |--------------------------------------------------------------
            */
            Route::get('/',  [TopologyMapController::class, 'index'])->name('index');

/*
            |--------------------------------------------------------------
            | WRITE (Super Admin only) — ditaruh di atas /{topologyMap}
            | biar "/create" tidak dianggap sebagai {topologyMap}
            |--------------------------------------------------------------
            */
            Route::middleware('role:super_admin')->group(function () {
                Route::get('/create',     [TopologyMapController::class, 'create'])->name('create');
                Route::post('/',          [TopologyMapController::class, 'store'])->name('store');
                Route::get('/{topologyMap}/edit', [TopologyMapController::class, 'edit'])->name('edit');
                Route::put('/{topologyMap}',      [TopologyMapController::class, 'update'])->name('update');
                Route::delete('/{topologyMap}',   [TopologyMapController::class, 'destroy'])->name('destroy');
            });

            /*
            |--------------------------------------------------------------
            | SHOW (semua role) — harus di bawah /create
            |--------------------------------------------------------------
            */
            Route::get('/{topologyMap}',      [TopologyMapController::class, 'show'])->name('show');
            Route::get('/{topologyMap}/data', [TopologyMapController::class, 'mapData'])->name('data');
            Route::get('/{topologyMap}/available-devices', [TopologyMapController::class, 'availableDevices'])->name('available-devices');

            /*
            |--------------------------------------------------------------
            | SAVE LAYOUT — bisa diakses siapa saja (buat auto-save posisi)
            |--------------------------------------------------------------
            */
            Route::post('/{topologyMap}/save-layout', [TopologyMapController::class, 'saveLayout'])->name('save-layout');

            /*
            |--------------------------------------------------------------
            | DEVICE DI DALAM MAP
            |--------------------------------------------------------------
            */
            Route::get('/{topologyMap}/devices/{mapDevice}',  [TopologyMapController::class, 'showDevice'])->name('devices.show');
            Route::put('/{topologyMap}/devices/{mapDevice}',  [TopologyMapController::class, 'updateDevice'])->name('devices.update');
            Route::post('/{topologyMap}/devices',             [TopologyMapController::class, 'storeDevice'])->name('devices.store');
            Route::post('/{topologyMap}/devices/new',         [TopologyMapController::class, 'storeNewDevice'])->name('devices.new');
            Route::patch('/{topologyMap}/devices/{mapDevice}/position', [TopologyMapController::class, 'updateDevicePosition'])->name('devices.position');
            Route::delete('/{topologyMap}/devices/{mapDevice}',         [TopologyMapController::class, 'destroyDevice'])->name('devices.destroy');

            /*
            |--------------------------------------------------------------
            | AREA / POP DI DALAM MAP
            |--------------------------------------------------------------
            */
            Route::post('/{topologyMap}/areas',              [TopologyMapController::class, 'storeArea'])->name('areas.store');
            Route::put('/{topologyMap}/areas/{mapArea}',     [TopologyMapController::class, 'updateArea'])->name('areas.update');
            Route::delete('/{topologyMap}/areas/{mapArea}',  [TopologyMapController::class, 'destroyArea'])->name('areas.destroy');

            /*
            |--------------------------------------------------------------
            | CONNECTION / RELASI
            |--------------------------------------------------------------
            */
            Route::post('/{topologyMap}/connections',                                 [TopologyMapController::class, 'storeConnection'])->name('connections.store');
            Route::delete('/{topologyMap}/connections/{mapConnection}',               [TopologyMapController::class, 'destroyConnection'])->name('connections.destroy');
            Route::post('/{topologyMap}/connections/{mapConnection}/direction',       [TopologyMapController::class, 'updateConnectionDirection'])->name('connections.direction');
            /*
            |--------------------------------------------------------------
            | WRITE (Super Admin only) — ditaruh di atas /{topologyMap}
            | biar "/create" tidak dianggap sebagai {topologyMap}
            |--------------------------------------------------------------
            */
            Route::middleware('role:super_admin')->group(function () {
                Route::get('/create',     [TopologyMapController::class, 'create'])->name('create');
                Route::post('/',          [TopologyMapController::class, 'store'])->name('store');
                Route::get('/{topologyMap}/edit', [TopologyMapController::class, 'edit'])->name('edit');
                Route::put('/{topologyMap}',      [TopologyMapController::class, 'update'])->name('update');
                Route::delete('/{topologyMap}',   [TopologyMapController::class, 'destroy'])->name('destroy');
            });

            /*
            |--------------------------------------------------------------
            | SHOW (semua role) — harus di bawah /create
            |--------------------------------------------------------------
            */
            Route::get('/{topologyMap}',      [TopologyMapController::class, 'show'])->name('show');
            Route::get('/{topologyMap}/data', [TopologyMapController::class, 'mapData'])->name('data');
            Route::get('/{topologyMap}/available-devices', [TopologyMapController::class, 'availableDevices'])->name('available-devices');

            /*
            |--------------------------------------------------------------
            | SAVE LAYOUT — bisa diakses siapa saja (buat auto-save posisi)
            |--------------------------------------------------------------
            */
            Route::post('/{topologyMap}/save-layout', [TopologyMapController::class, 'saveLayout'])->name('save-layout');

            /*
            |--------------------------------------------------------------
            | DEVICE DI DALAM MAP
            |--------------------------------------------------------------
            */
            Route::get('/{topologyMap}/devices/{mapDevice}',  [TopologyMapController::class, 'showDevice'])->name('devices.show');
            Route::put('/{topologyMap}/devices/{mapDevice}',  [TopologyMapController::class, 'updateDevice'])->name('devices.update');
            Route::post('/{topologyMap}/devices',             [TopologyMapController::class, 'storeDevice'])->name('devices.store');
            Route::post('/{topologyMap}/devices/new',         [TopologyMapController::class, 'storeNewDevice'])->name('devices.new');
            Route::patch('/{topologyMap}/devices/{mapDevice}/position', [TopologyMapController::class, 'updateDevicePosition'])->name('devices.position');
            Route::delete('/{topologyMap}/devices/{mapDevice}',         [TopologyMapController::class, 'destroyDevice'])->name('devices.destroy');

            /*
            |--------------------------------------------------------------
            | AREA / POP DI DALAM MAP
            |--------------------------------------------------------------
            */
            Route::post('/{topologyMap}/areas',              [TopologyMapController::class, 'storeArea'])->name('areas.store');
            Route::put('/{topologyMap}/areas/{mapArea}',     [TopologyMapController::class, 'updateArea'])->name('areas.update');
            Route::delete('/{topologyMap}/areas/{mapArea}',  [TopologyMapController::class, 'destroyArea'])->name('areas.destroy');

            /*
            |--------------------------------------------------------------
            | CONNECTION / RELASI
            |--------------------------------------------------------------
            */
            Route::post('/{topologyMap}/connections',                                 [TopologyMapController::class, 'storeConnection'])->name('connections.store');
            Route::delete('/{topologyMap}/connections/{mapConnection}',               [TopologyMapController::class, 'destroyConnection'])->name('connections.destroy');
            Route::post('/{topologyMap}/connections/{mapConnection}/direction',       [TopologyMapController::class, 'updateConnectionDirection'])->name('connections.direction');
        });

        /*
        |----------------------------------------------------------------------
        | EzyHome (Legacy)
        |----------------------------------------------------------------------
        */

        Route::get('/ezyhome', [EzyHomeController::class, 'index'])->name('ezyhome');
        Route::get('/ezyhome/data', [EzyHomeController::class, 'data'])->name('ezyhome.data');
        Route::get('/ezyhome/devices', [EzyHomeController::class, 'devices'])->name('ezyhome.devices');
        Route::get('/ezyhome/device/{device}', [EzyHomeController::class, 'device'])->name('ezyhome.device');

        Route::post('/ezyhome/device', [EzyHomeController::class, 'storeDevice'])->name('ezyhome.device.store');
        Route::put('/ezyhome/device/{device}', [EzyHomeController::class, 'updateDevice'])->name('ezyhome.device.update');
        Route::delete('/ezyhome/device/{device}', [EzyHomeController::class, 'destroyDevice'])->name('ezyhome.device.destroy');

        Route::post('/ezyhome/device/{device}/position', [EzyHomeController::class, 'updatePosition'])->name('ezyhome.device.position');
        Route::delete('/ezyhome/position/{position}', [EzyHomeController::class, 'destroyPosition'])->name('ezyhome.position.destroy');

        Route::post('/ezyhome/connection', [EzyHomeController::class, 'storeConnection'])->name('ezyhome.connection.store');
        Route::delete('/ezyhome/connection/{connection}', [EzyHomeController::class, 'destroyConnection'])->name('ezyhome.connection.destroy');
        Route::post('/ezyhome/connection/{connection}/direction', [EzyHomeController::class, 'updateDirection'])->name('ezyhome.connection.direction');

        Route::post('/ezyhome/area', [EzyHomeController::class, 'storeArea'])->name('ezyhome.area.store');
        Route::put('/ezyhome/area/{area}', [EzyHomeController::class, 'updateArea'])->name('ezyhome.area.update');
        Route::delete('/ezyhome/area/{area}', [EzyHomeController::class, 'destroyArea'])->name('ezyhome.area.destroy');
        Route::post('/ezyhome/area/{area}/bounds', [EzyHomeController::class, 'updateAreaBounds'])->name('ezyhome.area.bounds');

        Route::post('/ezyhome/map-layout', [EzyHomeController::class, 'saveMapLayout'])->name('ezyhome.map-layout.save');
        Route::post('/ezyhome/map-layout/reset', [EzyHomeController::class, 'resetMapLayout'])->name('ezyhome.map-layout.reset');

        /*
        |----------------------------------------------------------------------
        | Log History
        |----------------------------------------------------------------------
        */

        Route::get('/log', [LogHistoryController::class, 'index'])->name('log');
        Route::get('/log/load-more', [LogHistoryController::class, 'loadMore'])->name('log.load-more');
        Route::get('/log/archive', [LogHistoryController::class, 'archive'])->name('log.archive');
        Route::get('/log/detail/{id}', [LogHistoryController::class, 'detail'])->whereNumber('id')->name('log.detail');

        /*
        |----------------------------------------------------------------------
        | Notifikasi
        |----------------------------------------------------------------------
        */

        Route::get('/notifikasi', [NotifikasiController::class, 'index'])->name('notifikasi');
        Route::get('/notifikasi/unread-count', [NotifikasiController::class, 'unreadCount'])->name('notifikasi.unread-count');
        Route::patch('/notifikasi/read-displayed', [NotifikasiController::class, 'markDisplayedAsRead'])->name('notifikasi.read-displayed');
        Route::patch('/notifikasi/read-all', [NotifikasiController::class, 'markAllAsRead'])->name('notifikasi.read-all');
        Route::patch('/notifikasi/{notification}/read', [NotifikasiController::class, 'markAsRead'])->whereNumber('notification')->name('notifikasi.read');

        /*
        |----------------------------------------------------------------------
        | Manajemen
        |----------------------------------------------------------------------
        */

        Route::middleware('auth')->group(function () {

            Route::get('/kelola-router', [KelolaRouterController::class, 'index'])->name('kelola-router');
            Route::get('/device/{device}/detail', [DeviceDetailController::class, 'show'])->name('device.detail');

            Route::middleware('role:super_admin')->group(function () {
                Route::post('/kelola-router', [KelolaRouterController::class, 'store'])->name('kelola-router.store');
                Route::put('/kelola-router/{device}', [KelolaRouterController::class, 'update'])->name('kelola-router.update');
                Route::delete('/kelola-router/{device}', [KelolaRouterController::class, 'destroy'])->name('kelola-router.destroy');
            });
        });

        // Profile & Device Notes
        Route::middleware('auth')->group(function () {
            Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
            Route::post('/profile/photo', [ProfileController::class, 'updatePhoto'])->name('profile.photo.update');
            Route::patch('/profile', [ProfileController::class, 'updateAccount'])->name('profile.update');
            Route::patch('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');

            Route::get('/device-notes', [DeviceNoteController::class, 'report'])->name('device.notes.report');
            Route::post('/device-notes', [DeviceNoteController::class, 'storeFromReport'])->name('device.notes.store-report');
            Route::post('/device/{device}/notes', [DeviceNoteController::class, 'store'])->name('device.notes.store');
            Route::put('/device-notes/{note}', [DeviceNoteController::class, 'update'])->name('device.notes.update');
            Route::delete('/device-notes/{note}', [DeviceNoteController::class, 'destroy'])->name('device.notes.destroy');
        });

        /*
        |----------------------------------------------------------------------
        | DHCP Lease
        |----------------------------------------------------------------------
        */

        Route::middleware('auth')->group(function () {
            Route::get('/dhcp-lease', [DhcpLeaseController::class, 'index'])
                ->name('dhcp-lease');

            Route::post('/dhcp-lease/upload', [DhcpLeaseController::class, 'upload'])
                ->name('dhcp-lease.upload');

            Route::post('/dhcp-lease/import', [DhcpLeaseController::class, 'sync'])
                ->name('dhcp-lease.import');
        });

        /*
        |----------------------------------------------------------------------
        | Laporan
        |----------------------------------------------------------------------
        */

        Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan');
        Route::get('/laporan/pdf', [LaporanController::class, 'pdf'])->name('laporan.pdf');
        Route::get('/laporan/perangkat/{device}/pdf', [LaporanController::class, 'perangkatPdf'])->whereNumber('device')->name('laporan.perangkat.pdf');

        /*
        |----------------------------------------------------------------------
        | Tools
        |----------------------------------------------------------------------
        */

/*
         * Network Tools dapat dipakai penuh oleh staff maupun Super Admin:
         * halaman, ping, trace, lookup, scan, dan tanya-jawab asisten semua
         * berada di grup yang sama. Hanya pembersihan riwayat (aksi
         * destruktif) yang tetap dibatasi Super Admin di grup terpisah.
         */
        Route::middleware(['auth', 'role:super_admin,staff'])->group(function () {
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

            Route::post('/tools/ask', [ToolsController::class, 'ask'])
                ->name('tools.ask');
        });

        Route::middleware(['auth', 'role:super_admin'])->group(function () {
            Route::post('/tools/purge-all', [ToolsController::class, 'purgeAll'])
                ->name('tools.purge-all');
        });

        /*
        |----------------------------------------------------------------------
        | Kelola User (Super Admin)
        |----------------------------------------------------------------------
        */

        Route::group(['middleware' => 'role:super_admin'], function () {
            Route::get('/kelola-user', [UserController::class, 'index'])->name('kelola-user');
            Route::get('/kelola-user/create', [UserController::class, 'create'])->name('kelola-user.create');
            Route::post('/kelola-user', [UserController::class, 'store'])->name('kelola-user.store');
            Route::get('/kelola-user/{user}/edit', [UserController::class, 'edit'])->name('kelola-user.edit');
            Route::put('/kelola-user/{user}', [UserController::class, 'update'])->name('kelola-user.update');
            Route::delete('/kelola-user/{user}', [UserController::class, 'destroy'])->name('kelola-user.destroy');
        });

        /*
        |----------------------------------------------------------------------
        | Telegram Users (Super Admin)
        |----------------------------------------------------------------------
        */

        Route::middleware(['auth', 'role:super_admin'])->group(function () {
            Route::get('/telegram-users', [TelegramUserController::class, 'index'])->name('telegram-users');
            Route::post('/telegram-users/settings', [TelegramUserController::class, 'updateSettings'])->name('telegram-users.settings');
            Route::post('/telegram-users/test-connection', [TelegramUserController::class, 'testConnection'])->name('telegram-users.test-connection');
            Route::post('/telegram-users/test-send', [TelegramUserController::class, 'testSend'])->name('telegram-users.test-send');
            Route::post('/telegram-users/set-webhook', [TelegramUserController::class, 'setWebhook'])->name('telegram-users.set-webhook');
            Route::post('/telegram-users/delete-webhook', [TelegramUserController::class, 'deleteWebhook'])->name('telegram-users.delete-webhook');
            Route::post('/telegram-users/{telegramUser}/approve', [TelegramUserController::class, 'approve'])->name('telegram-users.approve');
            Route::post('/telegram-users/{telegramUser}/reject', [TelegramUserController::class, 'reject'])->name('telegram-users.reject');
            Route::delete('/telegram-users/{telegramUser}', [TelegramUserController::class, 'destroy'])->name('telegram-users.destroy');
            Route::post('/telegram-users/{telegramUser}/set-role', [TelegramUserController::class, 'setRole'])->name('telegram-users.set-role');
            Route::post('/telegram-users/broadcast', [TelegramUserController::class, 'broadcast'])->name('telegram-users.broadcast');
        });
    });

/*
|--------------------------------------------------------------------------
| Compatibility routes (legacy URL redirects)
|--------------------------------------------------------------------------
*/

Route::get('/laporan', function () {
    return redirect()->route('admin.laporan');
})->name('laporan.redirect');

Route::get('/kelola-router', function () {
    return redirect()->route('admin.kelola-router');
})->middleware('auth')->name('kelola-router.redirect');

/*
|--------------------------------------------------------------------------
| TELEGRAM BOT WEBHOOK
|--------------------------------------------------------------------------
*/

Route::post('/telegram/webhook', [TelegramWebhookController::class, 'handle'])->name('telegram.webhook');
Route::post('/api/telegram/webhook', [TelegramWebhookController::class, 'handle'])->name('telegram.webhook.api');
