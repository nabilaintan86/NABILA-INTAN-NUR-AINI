<?php

namespace App\Helpers;

use Illuminate\Http\Request;

class AdminPageTitle
{
    /**
     * Judul header admin berdasarkan nama route.
     */
    public const ROUTE_TITLES = [
        'admin.dashboard' => 'Dashboard',
        'admin.monitoring' => 'Monitoring Jaringan',
        'admin.log' => 'Log History',
        'admin.notifikasi' => 'Notifikasi',
        'admin.kelola-router' => 'Kelola Router',
        'admin.profile' => 'Profile',
        'admin.device.notes.report' => 'Report Catatan',
        'admin.dhcp-lease' => 'DHCP Lease',
        'admin.laporan' => 'Laporan & Grafik',
        'admin.tools' => 'Network Tools',
        'admin.troubleshooting' => 'Troubleshooting',
        'admin.kelola-user' => 'Kelola User',
        'admin.kelola-user.create' => 'Tambah User',
        'admin.kelola-user.edit' => 'Edit User',
        'admin.telegram-users' => 'Telegram Users',
        'admin.device.detail' => 'Detail Device',
    ];

    /**
     * Judul header admin berdasarkan path URL, dipakai sebagai cadangan
     * saat nama route tidak terdaftar (mis. halaman tanpa nama route).
     */
    public const PATH_TITLES = [
        'admin' => 'Dashboard',
        'admin/monitoring' => 'Monitoring Jaringan',
        'admin/log' => 'Log History',
        'admin/notifikasi' => 'Notifikasi',
        'admin/kelola-router' => 'Kelola Router',
        'admin/profile' => 'Profile',
        'admin/device-notes' => 'Report Catatan',
        'admin/dhcp-lease' => 'DHCP Lease',
        'admin/laporan' => 'Laporan & Grafik',
        'admin/tools' => 'Network Tools',
        'admin/troubleshooting' => 'Troubleshooting',
        'admin/kelola-user' => 'Kelola User',
        'admin/telegram-users' => 'Telegram Users',
    ];

    /**
     * Judul header admin untuk halaman turunan, kunci = path awalan.
     */
    public const PATH_PREFIX_TITLES = [
        'admin/monitoring' => 'Monitoring Jaringan',
        'admin/log' => 'Log History',
        'admin/device' => 'Detail Device',
        'admin/kelola-user' => 'Kelola User',
    ];

    /**
     * Judul header untuk request saat ini.
     */
    public static function resolve(?Request $request = null): string
    {
        $request ??= request();

        $routeName = $request->route()?->getName();

        if (is_string($routeName) && isset(self::ROUTE_TITLES[$routeName])) {
            return self::ROUTE_TITLES[$routeName];
        }

        $path = trim($request->path(), '/');

        if ($path === '') {
            return 'Dashboard';
        }

        if (isset(self::PATH_TITLES[$path])) {
            return self::PATH_TITLES[$path];
        }

        $segments = explode('/', $path);
        $matchedLength = 0;
        $title = null;

        foreach (self::PATH_PREFIX_TITLES as $prefix => $prefixTitle) {
            $prefixSegments = explode('/', $prefix);
            $matches = count($prefixSegments) === count(array_slice($segments, 0, count($prefixSegments)))
                && array_slice($segments, 0, count($prefixSegments)) === $prefixSegments;

            if ($matches && count($prefixSegments) > $matchedLength) {
                $matchedLength = count($prefixSegments);
                $title = $prefixTitle;
            }
        }

        if ($title !== null) {
            return $title;
        }

        return self::humanize(end($segments));
    }

    private static function humanize(string $segment): string
    {
        $text = trim(ucwords(str_replace(['-', '_'], ' ', $segment)));

        return $text !== '' ? $text : 'Dashboard';
    }
}
