<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Monitoring Jaringan & Admin Dashboard">

    <title>
        @yield('title', 'RadneXt - Admin Dashboard')
    </title>

    <link rel="shortcut icon" href="{{ asset('admin-assets/images/favicon.png') }}">

    <link rel="stylesheet" href="{{ asset('admin-assets/assets/css/style.css') }}">

    <style>
        {{-- =========================================================
            STANDARD LAYOUT ADMIN (GLOBAL)
            ---------------------------------------------------------
            .admin-content  = SATU-SATUNYA grid horizontal seluruh halaman
                             admin. Semua halaman (heading, breadcrumb,
                             card, filter, tabel, section, tombol kanan)
                             memakai garis kiri & kanan yang sama.
            .admin-page    = akar konten, jarak vertikal diseragamkan.
            Halaman DILARANG menambah container / offset sendiri.
        ========================================================== --}}
        :root {
            --admin-max: 1560px;      /* lebar container maksimum */
            --admin-gutter: 1rem;     /* padding horizontal container */
            --admin-pad: 1.25rem;     /* padding internal card/panel */
            --admin-gap: 1.5rem;      /* jarak antar section */
            --admin-gap-head: 1.75rem;/* jarak judul -> konten */
            --admin-radius: .75rem;   /* radius seragam seluruh surface */

            /* Token intro konten — dipakai seluruh halaman admin */
            --admin-head-h1: 1.375rem;  /* judul = elemen visual utama */
            --admin-head-sub: .8125rem; /* subtitle = informasi sekunder */
        }

        @media (min-width: 768px) {
            :root { --admin-gutter: 1.5rem; }
        }
        @media (min-width: 1400px) {
            :root { --admin-gutter: 2rem; }
        }

        /* ── 1. SATU CONTAINER ─────────────────────────────────── */
        .nk-content {
            padding: 0 !important;
            min-height: calc(100vh - 3.75rem);
        }

        /* ═══════════════════════════════════════════════════════
           1b. SATU REFERENSI KIRI BERSAMA — SATU-SATUNYA SUMBER
           ───────────────────────────────────────────────────────
           Ini satu-satunya tempat yang menentukan posisi horizontal
           di admin. .nk-header-wrap (baris header atas) dan
           .admin-content (kolom konten) DIBAGAI satu rule yang sama,
           sehingga secara struktural tidak mungkin berbeda:

               .nk-header-wrap, .admin-content { …bersama… }

           Huruf "M" pada judul header atas dan huruf "M" pada judul
           intro konten memakai nilai yang sama persis — tidak ada
           nilai terpisah yang bisa saling menyimpang.

           TIGA hal yang membuat X-nya benar:

           (1) ASAL X SAMA.
               .nk-header-fixed punya `left: 280px` dan .nk-wrap punya
               `padding-left: 280px`, jadi header dan konten sudah
               berawal pada titik yang sama. Yang perlu disamakan
               hanya geometri di dalam titik itu.

           (2) TIDAK ADA PADDING TAMBAH DI HEADER.
               Thema memberi padding horizontal di DUA tingkat, dan dua-duanya
               harus dinolkan supaya tidak menumpuk di kiri judul header:
                 .nk-header        -> padding:.5rem 1rem  (16px)
                 .nk-header .container-fluid -> 0.75rem    (12px)
               Total 28px. Inilah sumber selisih 16px + 12px yang membuat
               judul header berdiri di kanan judul konten. Padding vertikal
               .5rem pada .nk-header tetap dipertahankan.

           (3) RAIL DI-ANCHOR KIRI, BUKAN DI-CENTER.
               Ini akar masalah "lompatan ke kanan". Dengan
               `margin-inline: auto`, kolom di-center di dalam ruang yang
               tersedia, sehingga tepi KIRINYA ikut bergerak begitu
               viewport melewati --admin-max: di 1366px rail masih dekat
               tepi, di 1920px sudah melompat jauh ke kanan, di 2560px
               melompat lagi. Roda mouse sedikit saja sudah menggeser
               garis judul.
               Dengan `margin-inline: 0 auto` sisi kiri dikunci ke
               --admin-gutter, sementara sisa ruang mengumpul di kanan.
               Hasilnya satu garis vertikal yang DIAM di semua lebar
               layar: 992px, 1366px, 1440px, 1920px, 2560px.

           Bukan negative margin, bukan hard-coded px per halaman, dan
           bukan hanya "sama-sama center" — left reference-nya benar-benar
           satu dan tidak bergerak. */
        .nk-header-wrap,
        .admin-content {
            width: 100%;
            max-width: var(--admin-max);
            margin-inline: 0 auto;            /* anchor kiri, bukan center */
            padding-inline: var(--admin-gutter);
            box-sizing: border-box;
        }

        /* Padding horizontal header dinolkan di .nk-header DAN
           .container-fluid — lihat penjelasan (2) di rule bersama. */
        .nk-header {
            padding-inline: 0;
        }
        .nk-header .container-fluid {
            max-width: none;
            padding-inline: 0;
        }

        /* .admin-content hanya menambah jarak VERTIKAL. Padding horizontal
           sudah ditangani oleh rule bersama di atas — tidak ditulis ulang
           di sini supaya tidak ada dua sumber nilai. */
        .admin-content {
            padding-block: var(--admin-gap) calc(var(--admin-gap) * 2);
        }

        /* ── 1c. RAIL YANG TETAP DI < 992px ─────────────────────
           Di bawah 992px sidebar menjadi off-canvas, jadi tombol
           .nk-sidebar-toggle dan .nk-navbar-toggle muncul dan
           menempati ruang di depan judul — itu yang mendorong
           judul ke kanan dan membuatnya tidak rata dengan konten.

           Lebar tombol itu variabel (btn-sm di xs, btn-md di sm+),
           jadi tidak bisa dicocokkan dengan px statis. Yang pasti
           benar: judul harus didahulukan (order) supaya menjadi
           item pertama, persis di rail yang sama dengan konten.
           Tidak ada px yang di-hard-code, dan di >= 992px aturan ini
           tidak berefek karena kedua tombol sudah display:none. */
        @media (max-width: 991.98px) {
            .nk-header-title { order: -1; }
        }

        /* ── 2. LARANG WRAPPER / OFFSET PER HALAMAN ─────────────── */
        /* Wrapper bawaan template (nk-content > container >
           nk-content-inner) diratakan supaya tidak menambah padding
           ganda / max-width sendiri. */
        .admin-content :is(
            .nk-content,
            .nk-content-inner,
            .container,
            .container-fluid,
            .container-sm,
            .container-md,
            .container-lg,
            .container-xl,
            .container-xxl
        ) {
            width: 100% !important;
            max-width: none !important;
            margin-inline: 0 !important;
            padding: 0 !important;
        }

        /* .nk-content-body bawaan template mengunci tinggi & memotong
           isi halaman yang panjang. */
        .nk-content-body {
            height: auto !important;
            min-height: 0 !important;
            overflow: visible !important;
        }

        /* ── 3. SATU POLA JARAK VERTIKAL ───────────────────────── */
        .admin-page :where(.nk-block, .nk-block-head, .nk-page-head) { margin-block: 0; }
        .admin-page > * + * { margin-top: var(--admin-gap); }
        .admin-page > * { min-width: 0; }

        /* Overlay & panel fixed (modal, backdrop, toast, loading) berada di
           luar flow. Jangan beri jarak vertikal, kalau tidak backdrop tidak
           menutup layar penuh. */
        .admin-page > :is(
            .modal,
            .offcanvas,
            .toast,
            .toast-stack,
            .modal-backdrop,
            .modal-backdrop-dhcp,
            .laporan-loading
        ) {
            margin-top: 0;
        }

        /* ── 4. SATU POLA PERMUKAAN (CARD / PANEL / TABEL) ─────── */
        .admin-page :is(
            .card,
            .nk-block,
            .panel,
            .modal-content,
            .offcanvas,
            .stat-chip,
            .kpi-card,
            .kpi-card-new,
            .kpi-table-wrap,
            .summary-card-new,
            .note-kpi,
            .note-report-filter,
            .noc-card,
            .noc-header,
            .stat-card,
            .lp-card,
            .lp-header,
            .telegram-card,
            .telegram-stat,
            .dhcp-panel,
            .dhcp-table-wrap,
            .tools-panel,
            .console-box
        ) {
            border-radius: var(--admin-radius);
        }

        /* Padding internal card/panel seragam supaya teks di dalam
           semua card mulai pada garis yang sama. */
        .admin-page :is(.card-body, .noc-card-body, .noc-card-header) {
            padding-inline: var(--admin-pad);
        }

        /* Tabel & grid tidak boleh keluar dari container. */
        .admin-page .table-responsive { margin-bottom: 0; }
        .admin-page .row { margin-bottom: 0; }

        /* ── 5. INTRO / PAGE HEAD KONTEN (SATU POLA SELURUH HALAMAN) ──
            Header atas sudah menampilkan nama halaman aktif, jadi di sini
            TIDAK ada breadcrumb dan TIDAK ada icon. Sisa kontennya:

                [ judul + subtitle ]                    [ action.. ]

            - Satu baris, rata kiri; action rata kanan & sejajar vertikal.
            - TANPA padding/kolom ikon: teks mulai tepat di garis kiri area
              konten, jadi sejajar dengan header atas dan dengan seluruh
              blok lain (KPI, card, tabel, filter) di halaman ini.
        ------------------------------------------------------- */
        .admin-page-head {
            display: grid;
            grid-template-columns: minmax(0, 1fr) auto;
            align-items: center;
            column-gap: var(--admin-gap);
            width: 100%;
        }

        .admin-page-head-title { min-width: 0; }

        /* 5.2 Typography: judul = visual utama, subtitle = secondary. */
        .admin-page-head-text { min-width: 0; }
        .admin-page-head-text h1 {
            margin: 0;
            font-size: var(--admin-head-h1);
            font-weight: 800;
            line-height: 1.25;
            letter-spacing: -.01em;
            color: #16223c;
        }
        .admin-page-head-text p {
            margin: .1875rem 0 0;
            font-size: var(--admin-head-sub);
            line-height: 1.45;
            color: #6b7a99;
        }

        /* 5.3 Action sejajar dengan area judul, rata kanan di desktop. */
        .admin-page-head-actions {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: .5rem;
            flex-wrap: wrap;
            min-width: 0;
        }
        .admin-page-head-actions:empty { display: none; }

        /* 5.4 Responsive: turun rapi, tanpa overflow. */
        @media (max-width: 767.98px) {
            .admin-page-head {
                grid-template-columns: minmax(0, 1fr);
                row-gap: .75rem;
            }
            .admin-page-head-actions {
                justify-content: flex-start;
                width: 100%;
            }
        }

        @media (max-width: 575.98px) {
            :root {
                --admin-head-h1: 1.125rem;
                --admin-head-sub: .75rem;
            }
        }

        /* Varian boxed: judul halaman berada di dalam surface penuh
           (dashboard), dengan padding internal yang sama seperti card. */
        .admin-page-head-boxed {
            padding: calc(var(--admin-pad) + .25rem) var(--admin-pad);
        }
        .admin-page-head-boxed .admin-page-head-text h1 { color: inherit; }
        .admin-page-head-boxed .admin-page-head-text p {
            color: inherit;
            opacity: .8;
        }

        /* ── 6. LOGO RADNEXT DI SIDEBAR ──────────────────────────
            - Biarkan logo tampil utuh (tidak dipotong wrapper 34px)
            - Sembunyikan logo saat sidebar dikecilkan (compact) */
        .nk-sidebar-brand .logo-wrap { height: auto; overflow: visible; }
        .nk-sidebar-brand .logo-wrap img { display: block; }
        .is-compact:not(:hover) .nk-sidebar-brand .logo-wrap img { display: none; }
    </style>

    @stack('styles')
</head>

<body
    class="nk-body"
    data-sidebar-collapse="lg"
    data-navbar-collapse="lg"
>

    <div class="nk-app-root">

        <div class="nk-main">

            {{-- SIDEBAR --}}
            @include('layouts.partials.sidebar')

            <div class="nk-wrap">

                {{-- HEADER --}}
                @include('layouts.partials.header')

                {{-- CONTENT --}}
                <div class="nk-content">
                    <div class="admin-content">
                        <div class="nk-content-body admin-page">
                            @yield('content')
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>

    {{-- OFFCANVAS --}}
    @include('layouts.partials.offcanvas')


    {{-- =========================================================
        JAVASCRIPT
    ========================================================== --}}

    <script src="{{ asset('admin-assets/assets/js/bundle.js') }}"></script>

    <script src="{{ asset('admin-assets/assets/js/scripts.js') }}"></script>

    @stack('scripts')

</body>
</html>