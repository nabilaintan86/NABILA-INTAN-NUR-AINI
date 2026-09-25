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
            STANDARD LAYOUT ADMIN
            .admin-content = SATU-SATUNYA grid horizontal untuk seluruh
            halaman admin. Halaman tidak boleh menambah .nk-content /
            .container sendiri di dalam @yield('content').
        ========================================================== --}}
        .admin-content {
            --admin-gutter: 1.5rem;
            --admin-gap: 1.5rem;
            --admin-gap-head: 2rem;
            --admin-radius: .5rem;
            width: 100%;
            max-width: 1440px;
            margin-left: auto;
            margin-right: auto;
            padding: 1.25rem var(--admin-gutter) var(--admin-gutter);
        }

        {{-- Padding horizontal & max-width hanya boleh dari .admin-content --}}
        .nk-content { padding: 0 !important; }

        {{-- Lipat wrapper lama (nk-content > container > nk-content-inner >
             nk-content-body) supaya halaman lama ikut satu container yang
             sama, tanpa padding ganda. --}}
        .nk-content-body .nk-content,
        .nk-content-body .container,
        .nk-content-body .container-fluid,
        .nk-content-body .nk-content-inner {
            max-width: none;
            margin: 0;
            padding: 0;
        }
        .nk-content-body .nk-content-body {
            height: auto;
            min-height: 0;
            overflow: visible;
        }

        {{-- Satu pola jarak vertikal: semua section memakai --admin-gap,
             block judul halaman memakai --admin-gap-head. --}}
        .nk-content-body > * + *,
        .nk-content-body > .nk-block + .nk-block { margin-top: var(--admin-gap); }
        .nk-content-body > *:first-child { margin-top: 0; }
        .nk-content-body > .nk-block { margin-top: var(--admin-gap); }
        .nk-content-body > .nk-block:first-child { margin-top: 0; }
        .nk-content-body > .nk-block-head { margin-top: var(--admin-gap); margin-bottom: 0; }
        .nk-content-body > .nk-block-head:first-child { margin-top: 0; }
        .nk-content-body > .nk-block-head + * { margin-top: var(--admin-gap-head); }

        {{-- Radius seragam untuk card / panel / pembungkus tabel --}}
        .admin-content :is(
            .card,
            .nk-block,
            .panel,
            .stat-chip,
            .modal-content,
            .kpi-card,
            .kpi-table-wrap,
            .noc-card,
            .stat-card,
            .lp-card,
            .telegram-card,
            .telegram-stat,
            .dhcp-panel,
            .dhcp-table-wrap,
            .tools-panel
        ) {
            border-radius: var(--admin-radius);
        }

        {{-- =========================================================
            LOGO RADNEXT DI SIDEBAR
            - Biarkan logo tampil utuh (tidak dipotong wrapper 34px)
            - Sembunyikan logo saat sidebar dikecilkan (compact)
        ========================================================== --}}
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
                        <div class="nk-content-body">
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