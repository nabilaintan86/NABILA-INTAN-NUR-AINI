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
|   (status, response_time, checked_at), tiap 5 menit.
|
| Kedua command memakai SATU kunci bersama (`device-polling-cycle`, lihat
| trait HasSharedPollLock): hanya satu siklus polling berat yang boleh jalan
| dalam satu waktu. `monitor:ping` juga dijadwalkan pada menit 2, 7, 12, ...
| (bukan menit bundar) supaya bukan cuma anti-overlap soal kunci, tetapi juga
| tidak berangkat saat `network:poll` baru mulai (menit :00).
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
    // Menit 2, 7, 12, ..., 57 — selalu selang 5 menit, tidak pernah menit :00
    // yang menjadi titik start network:poll.
    ->cron('2-59/5 * * * *')
    ->withoutOverlapping(25)
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