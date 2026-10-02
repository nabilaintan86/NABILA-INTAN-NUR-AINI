<?php

namespace App\Console\Commands\Concerns;

use Illuminate\Contracts\Cache\Lock;
use Illuminate\Support\Facades\Cache;

/**
 * Kunci polling bersama antara `network:poll` dan `monitor:ping`.
 *
 * Kedua command itu sama-sama menjalankan satu siklus ping penuh (perangkat
 * resmi + SNMP) dan menulis status ke tabel yang sama. Kalau keduanya jalan
 * serentak, beban jaringan naik dua kali lipat dan transisi status bisa
 * dihitung dua kali. Pesan dari kunci ini: hanya SATU siklus polling yang
 * boleh berjalan pada satu waktu, siapa pun pemiliknya.
 *
 * Lock memakai cache store (database/file/redis). TTL 25 menit hanyalah
 * pengaman bila proses meninggal tanpa sempat melepas lock — siklus normal
 * melepasnya di `finally` handle().
 */
trait HasSharedPollLock
{
    private const POLL_LOCK_KEY = 'device-polling-cycle';

    private const POLL_LOCK_TTL_SECONDS = 1500;

    private ?Lock $pollLock = null;

    /**
     * Coba pegang kunci siklus polling. Kalau command lain sedang berjalan,
     * cycle ini dilewati (SUCCESS) supaya tidak ada dua siklus berat barengan.
     *
     * Dipanggil di paling awal handle(); pasangan releasePollLock() dipanggil
     * di finally. Tidak memakai register_shutdown_function karena di dalam
     * satu proses (test / schedule:work) itu baru berjalan saat proses selesai,
     * sehingga cycle berikutnya di proses yang sama akan melihat kunci masih
     * terkunci.
     */
    protected function acquirePollLock(): bool
    {
        $lock = Cache::lock(self::POLL_LOCK_KEY, self::POLL_LOCK_TTL_SECONDS);

        if (! $lock->get()) {
            $this->warn('Siklus polling lain sedang berjalan — cycle ini dilewati.');

            return false;
        }

        $this->pollLock = $lock;

        return true;
    }

    /**
     * Lepaskan kunci polling setelah siklus selesai (normal atau exception).
     */
    protected function releasePollLock(): void
    {
        $this->pollLock?->release();
        $this->pollLock = null;
    }
}