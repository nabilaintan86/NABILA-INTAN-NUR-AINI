<?php

namespace App\Helpers;

class LaporanFormat
{
    /**
     * Format durasi detik menjadi teks ringkas (ID).
     * contoh: 81375 -> "22 jam 36 mnt", 752 -> "12 mnt 32 dtk".
     */
    public static function duration(int $seconds): string
    {
        if ($seconds <= 0) {
            return '<1 dtk';
        }

        $days = intdiv($seconds, 86400);
        $hours = intdiv($seconds % 86400, 3600);
        $minutes = intdiv($seconds % 3600, 60);
        $secs = $seconds % 60;

        $parts = [];
        if ($days > 0) {
            $parts[] = $days.' hari';
        }
        if ($hours > 0) {
            $parts[] = $hours.' jam';
        }
        if ($minutes > 0 && $days === 0) {
            $parts[] = $minutes.' mnt';
        }
        if ($secs > 0 && $days === 0 && $hours === 0) {
            $parts[] = $secs.' dtk';
        }

        return implode(' ', $parts);
    }
}