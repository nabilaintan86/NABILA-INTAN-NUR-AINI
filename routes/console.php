<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('monitor:ping')
    ->everyMinute()
    ->withoutOverlapping(5)
    ->timezone(config('app.timezone'))
    ->onSuccess(function () {
        \Illuminate\Support\Facades\Log::info('monitor:ping selesai tanpa error.');
    });

/*
|--------------------------------------------------------------------------
| ARCHIVE LOG OTOMATIS
|--------------------------------------------------------------------------
| `logs:archive` : pindahkan monitoring_logs berumur > 7 hari
| ke tabel `log_archives`, dijalankan setiap hari pukul 02:00.
|--------------------------------------------------------------------------
*/
Schedule::command('logs:archive')
    ->dailyAt('02:00');