{{--
    INTRO / PAGE HEAD KONTEN ADMIN
    ---------------------------------------------------------
    SATU komponen untuk SELURUH halaman admin, dengan aturan:

      1. Tanpa breadcrumb. Nama halaman aktif sudah ditampilkan satu
         kali di header atas oleh layouts/partials/header.blade.php
         (App\Helpers\AdminPageTitle::resolve() via View::composer).
      2. Tanpa icon. Kolom ikon sudah dihapus supaya teks intro mulai
         tepat di garis kiri area konten — sejajar dengan header atas
         dan dengan seluruh blok lain (card, tabel, filter) di halaman.
      3. Yang tersisa hanya teks intro + action: judul deskriptif,
         subtitle, dan tombol aksi.

    Pemakaian:
        <x-admin.page-head
            title="Network Tools"
            subtitle="Diagnosa cepat perangkat, router, dan perintah jaringan.">
            <x-slot:actions>
                <button class="btn btn-primary">Tambah</button>
            </x-slot:actions>
        </x-admin.page-head>

    Props:
        title      string  judul intro halaman (wajib)
        subtitle   string  deskripsi singkat (opsional)
        boxed      bool    intro berada di dalam surface penuh
--}}
@props([
    'title',
    'subtitle' => null,
    'boxed' => false,
    'icon-url' => null,
    'crumbs' => null,
])

<div {{ $attributes->class(['admin-page-head', 'admin-page-head-boxed' => $boxed]) }}>

    <div class="admin-page-head-title">
        <div class="admin-page-head-text">
            <h1>{{ $title }}</h1>
            @if(!empty($subtitle))
                <p>{{ $subtitle }}</p>
            @endif
        </div>
    </div>

    @if(isset($actions) && trim((string) $actions) !== '')
        <div class="admin-page-head-actions">
            {{ $actions }}
        </div>
    @endif

</div>
