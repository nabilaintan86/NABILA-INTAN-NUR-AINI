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
        .admin-content {
            width: 100%;
            max-width: var(--admin-max);
            margin-inline: auto;
            padding: var(--admin-gap) var(--admin-gutter) calc(var(--admin-gap) * 2);
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

        /* ── 5. KOMPONEN PAGE HEAD (dipakai semua halaman) ──────── */
        .admin-page-head {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: .75rem 1.5rem;
            flex-wrap: wrap;
            width: 100%;
        }
        .admin-page-head-main { min-width: 0; }
        .admin-page-head-breadcrumb { margin-bottom: .5rem; }
        .admin-page-head-breadcrumb .breadcrumb { margin-bottom: 0; }
        .admin-page-head-title {
            display: flex;
            align-items: center;
            gap: .875rem;
            min-width: 0;
        }
        .admin-page-head-icon {
            width: 46px;
            height: 46px;
            flex: 0 0 auto;
            border-radius: 14px;
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            color: #fff;
            font-size: 20px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 8px 20px rgba(79, 70, 229, .28);
        }
        .admin-page-head-icon.is-telegram {
            background: linear-gradient(135deg, #229ed9, #38bdf8);
            box-shadow: 0 8px 20px rgba(34, 158, 217, .28);
        }
        .admin-page-head-icon img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            padding: 5px;
            background: #fff;
        }
        .admin-page-head-text { min-width: 0; }
        .admin-page-head-text h1 {
            margin: 0;
            font-size: 1.25rem;
            font-weight: 800;
            line-height: 1.3;
            color: #1c2b4a;
        }
        .admin-page-head-text p {
            margin: .25rem 0 0;
            font-size: .8rem;
            line-height: 1.5;
            color: #6b7a99;
        }
        .admin-page-head-actions {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: .5rem;
            flex-wrap: wrap;
            margin-left: auto;
        }
        .admin-page-head-actions:empty { display: none; }

        @media (max-width: 575.98px) {
            .admin-page-head-icon {
                width: 40px;
                height: 40px;
                border-radius: 12px;
                font-size: 17px;
            }
            .admin-page-head-text h1 { font-size: 1.1rem; }
            .admin-page-head-actions { margin-left: 0; width: 100%; justify-content: flex-start; }
        }

        /* Varian boxed: judul halaman berada di dalam surface penuh
           (dashboard), dengan padding internal yang sama seperti card. */
        .admin-page-head-boxed {
            padding: calc(var(--admin-pad) + .25rem) var(--admin-pad);
        }
        .admin-page-head-boxed .admin-page-head-text h1 { color: inherit; }
        .admin-page-head-boxed .admin-page-head-text p { color: inherit; opacity: .75; }

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