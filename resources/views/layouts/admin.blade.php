<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->
    getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="description" content="Monitoring Jaringan & Admin Dashboard">
        <title>
        @yield('title', 'NioBoard - Admin Dashboard')
        </title>
        <link rel="shortcut icon" href="{{ asset('admin/images/favicon.png') }}">
        <link rel="stylesheet" href="{{ asset('admin/assets/css/style.css') }}">
        @stack('styles')
    </head>
    <body class="nk-body" data-sidebar-collapse="lg" data-navbar-collapse="lg">
        <div class="nk-app-root">
            <div class="nk-main">
                @include('layouts.partials.sidebar')
                <div class="nk-wrap">
                    @include('layouts.partials.header')
                    @yield('content')
                    @include('layouts.partials.footer')
                </div>
            </div>
        </div>
        @include('layouts.partials.offcanvas')
        <script src="{{ asset('admin/assets/js/bundle.js') }}"></script>
        <script src="{{ asset('admin/assets/js/scripts.js') }}"></script>
        @stack('scripts')
    </body>
</html>
