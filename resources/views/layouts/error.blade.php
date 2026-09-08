<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->
    getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>
        @yield('title', 'Error - NioBoard')
        </title>
        <link rel="shortcut icon" href="{{ asset('admin/images/favicon.png') }}">
        <link rel="stylesheet" href="{{ asset('admin/assets/css/style.css') }}">
        @stack('styles')
    </head>
    <body class="nk-body" data-sidebar-collapse="lg" data-navbar-collapse="lg">
        <div class="nk-app-root">
            <div class="nk-main">
                @yield('content')
            </div>
        </div>
        <script src="{{ asset('admin/assets/js/bundle.js') }}"></script>
        <script src="{{ asset('admin/assets/js/scripts.js') }}"></script>
        @stack('scripts')
    </body>
</html>
