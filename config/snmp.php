<?php

return [
    /*
    |--------------------------------------------------------------------------
    | SNMP Feature Flag
    |--------------------------------------------------------------------------
    | SNMP hanya data tambahan; status UP/DOWN tetap ditentukan ICMP.
    | */
    'enabled' => (bool) env('SNMP_ENABLED', false),

    'host' => env('SNMP_HOST'),

    'port' => (int) env('SNMP_PORT', 161),

    /*
    | Community string default "public" hanya dipakai bila variabel env
    | dikosongkan. Nilai string yang lebih sensitif (v3 passphrase) tidak
    | pernah disimpan di repository.
    */
    'community' => env('SNMP_COMMUNITY') ?: 'public',

    'version' => env('SNMP_VERSION') ?: '2c',

    'timeout' => (int) env('SNMP_TIMEOUT', 3),

    'retries' => (int) env('SNMP_RETRIES', 1),

    /*
    |--------------------------------------------------------------------------
    | Vai v3
    |--------------------------------------------------------------------------
    | Untuk versi 3. Kosongkan community bila memakai v3.
    */
    'v3' => [
        'username' => env('SNMP_V3_USERNAME'),
        'security_level' => env('SNMP_V3_SECURITY_LEVEL') ?: 'noAuthNoPriv',
        'auth_protocol' => env('SNMP_V3_AUTH_PROTOCOL'),
        'auth_passphrase' => env('SNMP_V3_AUTH_PASSPHRASE'),
        'priv_protocol' => env('SNMP_V3_PRIV_PROTOCOL'),
        'priv_passphrase' => env('SNMP_V3_PRIV_PASSPHRASE'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Scope: perangkat mana yang diperiksa SNMP saat polling
    |--------------------------------------------------------------------------
    | disabled : SNMP tidak pernah dijalankan otomatis.
    | protocol : hanya device ber-protocol SNMP (default, nol overhead).
    | list     : hanya IP yang dicantumkan di targets.
    | all      : semua device yang ICMP-nya UP.
    */
    'mode' => env('SNMP_MODE') ?: 'protocol',

    'targets' => array_values(array_filter(array_map(
        'trim',
        explode(',', (string) env('SNMP_TARGETS', ''))
    ))),

    /*
    | Cache hasil polling SNMP (detik) supaya tidak satu request SNMP per
    | menit per device pada command yang berjalan tiap menit.
    | 0 = tanpa cache.
    */
    'cache_ttl' => (int) env('SNMP_CACHE_TTL', 300),

    /*
    | Budget waktu probing SNMP per siklus polling (detik) supaya polling
    | ICMP tidak ikut melambat saat banyak device tidak merespons SNMP.
    */
    'cycle_budget_seconds' => (int) env('SNMP_CYCLE_BUDGET', 20),

    /*
    | Lewati probing SNMP untuk device yang ICMP-nya DOWN (SNMP mustahil
    | dijawab perangkat yang tidak merespons ping).
    */
    'only_when_up' => (bool) env('SNMP_ONLY_WHEN_UP', true),
];
