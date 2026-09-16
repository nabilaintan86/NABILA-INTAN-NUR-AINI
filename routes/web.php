<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MonitoringController;
use App\Http\Controllers\Admin\MapController;
use App\Http\Controllers\Admin\LogHistoryController;
use App\Http\Controllers\Admin\NotifikasiController;
use App\Http\Controllers\Admin\TroubleshootingController;
use App\Http\Controllers\Admin\KelolaRouterController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Admin\ToolsController;
use App\Http\Controllers\Admin\UserController;


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

        // Network Map - topologi jaringan
        Route::get('/map', [MapController::class, 'index'])
            ->name('map');

        // Endpoint JSON data map (dipakai auto-refresh status tiap 30 dtk)
        Route::get('/map/data', [MapController::class, 'data'])
            ->name('map.data');

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


        /*
        |--------------------------------------------------------------------------
        | Manajemen
        |--------------------------------------------------------------------------
        */

        Route::get('/troubleshooting', [TroubleshootingController::class, 'index'])
            ->name('troubleshooting');

        Route::post('/troubleshooting/diagnose', [TroubleshootingController::class, 'diagnose'])
            ->name('troubleshooting.diagnose');

        Route::post('/troubleshooting/resolve/{id}', [TroubleshootingController::class, 'resolve'])
            ->name('troubleshooting.resolve');

        Route::get('/kelola-router', [KelolaRouterController::class, 'index'])
            ->name('kelola-router');

        Route::post('/kelola-router', [KelolaRouterController::class, 'store'])
            ->name('kelola-router.store');

        Route::put('/kelola-router/{device}', [KelolaRouterController::class, 'update'])
            ->name('kelola-router.update');

        Route::delete('/kelola-router/{device}', [KelolaRouterController::class, 'destroy'])
            ->name('kelola-router.destroy');


        Route::get('/laporan', [LaporanController::class, 'index'])
            ->name('laporan');

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
    });