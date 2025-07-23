<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - @yield('title', 'Auth')</title>

    @vite(['resources/css/app.scss', 'resources/js/app.js'])
</head>

<body class="theme-dark d-flex flex-column">
    <div class="page page-center">
        <div class="container container-tight py-4">
            <div class="text-center mb-4">
                <a href="{{ url('/') }}" class="navbar-brand navbar-brand-autodark">
                    <img src="https://tabler.io/demo/static/logo.svg" height="36" alt="{{ config('app.name') }}">
                </a>
            </div>
            @yield('content')
        </div>
    </div>
</body>
</html>
