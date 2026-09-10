<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MonitoringController;
use App\Http\Controllers\Admin\DetailRouterController;
use App\Http\Controllers\Admin\LogHistoryController;
use App\Http\Controllers\Admin\NotifikasiController;
use App\Http\Controllers\Admin\TroubleshootingController;
use App\Http\Controllers\Admin\KelolaRouterController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Admin\ToolsController;


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


        /*
        |--------------------------------------------------------------------------
        | Monitoring
        |--------------------------------------------------------------------------
        */

        Route::get('/monitoring', [MonitoringController::class, 'index'])
            ->name('monitoring');

        Route::get('/detail-router', [DetailRouterController::class, 'index'])
            ->name('detail-router');

        Route::get('/log', [LogHistoryController::class, 'index'])
            ->name('log');

        Route::get('/notifikasi', [NotifikasiController::class, 'index'])
            ->name('notifikasi');


        /*
        |--------------------------------------------------------------------------
        | Manajemen
        |--------------------------------------------------------------------------
        */

        Route::get('/troubleshooting', [TroubleshootingController::class, 'index'])
            ->name('troubleshooting');

        Route::get('/kelola-router', [KelolaRouterController::class, 'index'])
            ->name('kelola-router');


        Route::get('/laporan', [LaporanController::class, 'index'])
            ->name('laporan');

        Route::get('/tools', [ToolsController::class, 'index'])
            ->name('tools');
    });