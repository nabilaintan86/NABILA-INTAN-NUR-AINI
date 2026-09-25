<?php

namespace App\Helpers;

class DeviceType
{
    public const TYPES = [
        'Router',
        'Switch',
        'OLT',
        'EPON',
        'Server',
        'Access Point',
        'Other',
    ];

    public const PROTOCOLS = [
        'ICMP',
        'HTTP',
        'SNMP',
        'TCP',
        'SSH',
    ];

    public static function types(): array
    {
        return self::TYPES;
    }

    public static function protocols(): array
    {
        return self::PROTOCOLS;
    }

    public static function normalizeType(?string $value, ?string $iconType = null): string
    {
        $value = strtolower(str_replace(['_', '-'], ' ', trim((string) $value)));

        $mapped = match ($value) {
            'router', 'rtr', 'core router', 'edge router' => 'Router',
            'switch', 'sw', 'switching' => 'Switch',
            'olt' => 'OLT',
            'epon' => 'EPON',
            'server', 'server room', 'peti' => 'Server',
            'access point', 'accesspoint', 'ap', 'wifi', 'wi fi' => 'Access Point',
            'pc', 'komputer', 'client' => 'Other',
            default => null,
        };

        if ($mapped !== null) {
            return $mapped;
        }

        if (in_array(strtoupper(str_replace(' ', '', (string) $value)), self::protocols(), true)) {
            return self::normalizeType(null, $iconType);
        }

        $icon = strtolower((string) $iconType);

        return match ($icon) {
            'router', 'rtr' => 'Router',
            'switch', 'sw' => 'Switch',
            'wifi', 'access_point', 'accesspoint', 'ap' => 'Access Point',
            'server' => 'Server',
            'pc', 'computer' => 'Other',
            default => 'Server',
        };
    }

    public static function inferType(?string $name, ?string $iconType = null): string
    {
        $name = strtolower(trim((string) $name));
        $icon = strtolower(trim((string) $iconType));
        $haystack = $name.' '.$icon;

        return match (true) {
            str_contains($haystack, 'olt') => 'OLT',
            str_contains($haystack, 'epon') => 'EPON',
            str_contains($haystack, 'access point')
                || str_contains($haystack, 'accesspoint')
                || str_contains($haystack, 'wifi')
                || in_array($icon, ['ap', 'access_point', 'accesspoint', 'wifi'], true) => 'Access Point',
            str_contains($haystack, 'switch')
                || preg_match('/\bsw\b/i', $name) === 1
                || in_array($icon, ['switch', 'sw'], true) => 'Switch',
            str_contains($haystack, 'router')
                || in_array($icon, ['router', 'rtr'], true)
                || str_contains($name, 'pop')
                || str_contains($name, 'core')
                || str_contains($name, 'edge') => 'Router',
            str_contains($haystack, 'server')
                || in_array($icon, ['server'], true) => 'Server',
            default => 'Other',
        };
    }

    public static function normalizeProtocol(?string $value): string
    {
        $value = strtoupper(trim((string) $value));

        return in_array($value, self::protocols(), true) ? $value : 'ICMP';
    }

    public static function iconKey(?string $type, ?string $iconType = null): string
    {
        return match (self::normalizeType($type, $iconType)) {
            'Router' => 'router',
            'Switch' => 'switch',
            'OLT' => 'switch',
            'EPON' => 'router',
            'Access Point' => 'wifi',
            'Other' => 'pc',
            default => 'server',
        };
    }

    public static function iconFile(?string $type, ?string $iconType = null): string
    {
        return self::iconKey($type, $iconType).'.png';
    }

    public static function iconUrl(?string $type, ?string $iconType = null): string
    {
        return asset('images/devices/'.self::iconFile($type, $iconType));
    }
}
