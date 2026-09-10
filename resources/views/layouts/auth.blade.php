<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Login - Network Monitoring')</title>

    <link rel="stylesheet" href="{{ asset('admin-assets/assets/css/style.css') }}">

    @stack('styles')
</head>

<body class="nk-body bg-light">

    <div class="nk-app-root">
        <div class="nk-main">

            @yield('content')

        </div>
    </div>

    <script src="{{ asset('admin-assets/assets/js/bundle.js') }}"></script>
    <script src="{{ asset('admin-assets/assets/js/scripts.js') }}"></script>

    @stack('scripts')

</body>
</html>