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
            GLOBAL OVERRIDES (RadneXt)
            Hapus spasi kosong besar di atas judul halaman
        ========================================================== --}}
        .nk-content { padding-top: 0 !important; }
        .nk-content-body { padding-top: 0 !important; margin-top: 0 !important; }
        .nk-content-body > .nk-block:first-child { margin-top: 0 !important; padding-top: 0 !important; }
        .nk-block-head { margin-top: 0 !important; }
        .nk-block-head-content { padding-top: 0 !important; }
        .nk-page-head { margin-top: 0 !important; }
        .nk-content .nk-content-inner { padding-top: 0 !important; }

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
                    <div class="container-fluid">
                        <div class="nk-content-inner">
                            <div class="nk-content-body">
                                @yield('content')
                            </div>
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