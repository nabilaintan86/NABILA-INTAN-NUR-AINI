<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

/*
|--------------------------------------------------------------------------
| AUTOMATED NETWORK POLLING
|--------------------------------------------------------------------------
| - `network:poll` : ping detail + tulis activity_logs, tiap 1 menit.
| - `monitor:ping` : ping 115 device + tulis monitoring_logs
|   (status, response_time, checked_at) setiap 1 menit.
|   Ini sumber utama dashboard & log history.
|
| Cara menjalankan scheduler:
|   DEV  : php artisan schedule:work   (jalankan terus-menerus di terminal)
|   PROD : tambahkan baris ini ke cron sistem (crontab -e):
|     * * * * * cd /path/ke/proyek && php artisan schedule:run >> /dev/null 2>&1
|--------------------------------------------------------------------------
*/
Schedule::command('network:poll')
    ->everyMinute()
    ->withoutOverlapping(5)
    ->timezone(config('app.timezone'))
    ->onSuccess(function () {
        \Illuminate\Support\Facades\Log::info('network:poll selesai tanpa error.');
    });

Schedule::command('monitor:ping')
    ->everyMinute()
    ->withoutOverlapping(10)
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