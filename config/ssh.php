<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Koneksi SSH ke Perangkat Jaringan
    |--------------------------------------------------------------------------
     | Satu konfigurasi kredensial dipakai seluruh fitur yang perlu melihat
     | "dalam" perangkat — saat ini Speed Test, yang mengukur bandwidth dari
     | sisi router, bukan dari sisi server NOC.
     |
     | Jadi admin cukup menyiapkan SATU key untuk semua perangkat.
    |
    | Autentikasi WAJIB key-based. Aplikasi tidak pernah membaca, menyimpan,
    | maupun mengirim password router, dan private key hanya boleh dibaca
    | oleh proses PHP di server ini — tidak pernah lewat HTTP.
    |
    | BatchMode=yes memastikan ssh langsung gagal (bukan menggantung atau
    | meminta input) saat kunci ditolak atau perangkat butuh interaksi.
    |
    | Matikan enabled=false untuk menonaktifkan semua fitur yang butuh SSH.
    */
    'enabled' => (bool) env('SSH_ENABLED', false),

    'user' => env('SSH_USER'),

    'port' => (int) env('SSH_PORT', 22),

    /*
    | Path private key di server ini (bukan isi kuncinya), mis.
    | /home/noc/.ssh/id_ed25519. Harus absolut.
    */
    'identity_file' => env('SSH_IDENTITY_FILE'),

    /*
    | accept-new : host key baru diterima lalu disimpan ke known_hosts, tapi
    |              host key yang BERUBAH tetap ditolak (proteksi MITM).
    | yes        : seperti accept-new, selalu ditulis ulang.
    | no         : tidak mengecek sama sekali. Hanya untuk lab.
    */
    'host_key_checking' => env('SSH_HOST_KEY_CHECKING') ?: 'accept-new',

    'known_hosts_file' => env('SSH_KNOWN_HOSTS'),

    'connect_timeout' => (int) env('SSH_CONNECT_TIMEOUT', 10),

    'timeout' => (int) env('SSH_TIMEOUT', 120),

    /*
    |--------------------------------------------------------------------------
    | Target Bebas (diisi manual oleh Super Admin)
    |--------------------------------------------------------------------------
    | Halaman Speed Test mengizinkan Super Admin mengetik alamat sendiri,
    | untuk perangkat yang belum terdaftar di tabel `device`. Agar daftar
    | perangkat tidak menjadi satu-satunya penjaga, alamat manual wajib
    | lolos dua saringan:
    |
    |  1. Bentuknya harus benar-benar IP atau nama host yang wajar.
    |  2. Kalau berupa nama host, server me-resolve-nya sendiri lalu
    |     memeriksa IP hasilnya against daftar CIDR di bawah.
    |
    | Daftar CIDR sendiri: pakai koma, boleh CIDR atau satu IP.
    | Contoh: 10.0.0.0/8,192.168.0.0/16,203.0.113.9
    |
    | Kosong = SEMUA alamat ditolak. Itu pilihan yang aman, jadi fitur
    | target manual tidak aktif sampai Super Admin sengaja mengizinkan
    | jaringan mana saja yang boleh diuji.
    |
    | Alasan wajib ada daftar ini: tanpa itu, satu akun Super Admin bisa
    | memakai server ini untuk mengetuk alamat dalam jaringan internal
    | yang tidak boleh disentuh, hanya dengan mengetik IP di kolom itu.
    */
    'allowed_cidrs' => array_values(array_filter(array_map(
        'trim',
        explode(',', (string) env('SSH_ALLOWED_CIDRS', '')),
    ))),
];
