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
| Jalankan pengecekan status seluruh perangkat jaringan setiap 1 menit.
| Jalankan scheduler worker dengan: `php artisan schedule:work`
| atau pasang cron sistem: `* * * * * cd /path/proyek && php artisan schedule:run >> /dev/null 2>&1`
|--------------------------------------------------------------------------
*/
Schedule::command('network:poll')
    ->everyMinute()
    ->withoutOverlapping(5)
    ->timezone(config('app.timezone'))
    ->onSuccess(function () {
        \Illuminate\Support\Facades\Log::info('network:poll selesai tanpa error.');
    });