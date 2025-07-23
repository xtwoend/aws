<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - @yield('title', 'Dashboard')</title>
    
    <!-- Set default dark theme in head -->
    <script>
        // Force dark theme as default before CSS loads to prevent flash
        document.documentElement.setAttribute('data-bs-theme', 'dark');
        // Check for stored theme preference, defaulting to dark
        const storedTheme = localStorage.getItem('theme') || 'dark';
        if (!localStorage.getItem('theme')) {
            localStorage.setItem('theme', 'dark');
        }
        document.documentElement.setAttribute('data-bs-theme', storedTheme);
    </script>
    
    <!-- Custom CSS -->
    @stack('styles')
    @vite(['resources/css/app.scss', 'resources/js/app.js'])
</head>

<body class="theme-dark">
    <div class="page">
        <!-- Navbar -->
        <header class="navbar navbar-expand-md d-print-none">
            <div class="container-xl">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-menu" aria-controls="navbar-menu" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <h1 class="navbar-brand navbar-brand-autodark d-none-navbar-horizontal pe-0 pe-md-3">
                    <a href="{{ route('dashboard') }}" class="d-flex align-items-center text-decoration-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-lg me-2" width="32" height="32" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                            <path d="M7 18a4.6 4.4 0 0 1 0 -9a5 4.5 0 0 1 11 2h1a3.5 3.5 0 0 1 0 7h-12"/>
                            <path d="M11 13v2m0 3v2m0 3v1m4 -5v2m0 3v1"/>
                        </svg>
                        <span class="fw-bold">Automatic Weather Station</span>
                    </a>
                </h1>
                <div class="navbar-nav flex-row order-md-last">
                    <div class="nav-item d-none d-md-flex me-3">
                        <div class="btn-list">
                            <!-- Theme toggle button -->
                            <button id="theme-toggle" class="btn btn-outline-secondary" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Toggle theme">
                                <svg id="theme-icon-dark" xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M12 3c.132 0 .263 0 .393 0a7.5 7.5 0 0 0 7.92 12.446a9 9 0 1 1 -8.313 -12.454z"></path></svg>
                                <svg id="theme-icon-light" xmlns="http://www.w3.org/2000/svg" class="icon d-none" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><circle cx="12" cy="12" r="4"></circle><path d="M3 12h1m8 -9v1m8 8h1m-9 8v1m-6.4 -15.4l.7 .7m12.1 -.7l-.7 .7m0 11.4l.7 .7m-12.1 -.7l-.7 .7"></path></svg>
                            </button>
                        </div>
                    </div>
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link d-flex lh-1 text-reset p-0 dropdown-toggle" data-bs-toggle="dropdown" aria-label="Open user menu" aria-expanded="false">
                            <span class="avatar avatar-sm" style="background-image: url('https://tabler.io/demo/static/avatars/000m.jpg')"></span>
                            <div class="d-none d-xl-block ps-2">
                                <div>{{ Auth::user()->name }}</div>
                                <div class="mt-1 small text-muted">{{ Auth::user()->email }}</div>
                            </div>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                            <a href="{{ route('profile.edit') }}" class="dropdown-item">
                                <svg class="icon dropdown-item-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10.325 4.317c.426 -1.756 2.924 -1.756 3.35 0a1.724 1.724 0 0 0 2.573 1.066c1.543 -.94 3.31 .826 2.37 2.37a1.724 1.724 0 0 0 1.065 2.572c1.756 .426 1.756 2.924 0 3.35a1.724 1.724 0 0 0 -1.066 2.573c.94 1.543 -.826 3.31 -2.37 2.37a1.724 1.724 0 0 0 -2.572 1.065c-.426 1.756 -2.924 1.756 -3.35 0a1.724 1.724 0 0 0 -2.573 -1.066c-1.543 .94 -3.31 -.826 -2.37 -2.37a1.724 1.724 0 0 0 -1.065 -2.572c-1.756 -.426 -1.756 -2.924 0 -3.35a1.724 1.724 0 0 0 1.066 -2.573c-.94 -1.543 .826 -3.31 2.37 -2.37c1 .608 2.296 .07 2.572 -1.065z" /><path d="M9 12a3 3 0 1 0 6 0a3 3 0 0 0 -6 0" /></svg>
                                Settings
                            </a>
                            <div class="dropdown-divider"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item">
                                    <svg class="icon dropdown-item-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 8v-2a2 2 0 0 0 -2 -2h-7a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h7a2 2 0 0 0 2 -2v-2" /><path d="M9 12h12l-3 -3" /><path d="M18 15l3 -3" /></svg>
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        
        <!-- Sidebar -->
        <header class="navbar-expand-md">
            <div class="collapse navbar-collapse" id="navbar-menu">
                <div class="navbar">
                    <div class="container-xl">
                        <ul class="navbar-nav">
                            <li class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('dashboard') }}">
                                    <span class="nav-link-icon d-md-none d-lg-inline-block">
                                        <svg class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l-2 0l9 -9l9 9l-2 0" /><path d="M5 12v7a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-7" /><path d="M9 21v-6a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v6" /></svg>
                                    </span>
                                    <span class="nav-link-title">
                                        Dashboard
                                    </span>
                                </a>
                            </li>
                            <li class="nav-item {{ request()->routeIs('aws-logger.*') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('aws-logger.index') }}">
                                    <span class="nav-link-icon d-md-none d-lg-inline-block">
                                        <svg class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 4h7a1 1 0 0 1 1 1v7a1 1 0 0 1 -1 1h-7a1 1 0 0 1 -1 -1v-7a1 1 0 0 1 1 -1z" /><path d="M13 4h7a1 1 0 0 1 1 1v7a1 1 0 0 1 -1 1h-7a1 1 0 0 1 -1 -1v-7a1 1 0 0 1 1 -1z" /><path d="M3 14h7a1 1 0 0 1 1 1v4a1 1 0 0 1 -1 1h-7a1 1 0 0 1 -1 -1v-4a1 1 0 0 1 1 -1z" /><path d="M13 14h7a1 1 0 0 1 1 1v4a1 1 0 0 1 -1 1h-7a1 1 0 0 1 -1 -1v-4a1 1 0 0 1 1 -1z" /></svg>
                                    </span>
                                    <span class="nav-link-title">
                                        AWS Logger
                                    </span>
                                </a>
                            </li>
                            <li class="nav-item {{ request()->routeIs('devices.*') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('devices.index') }}">
                                    <span class="nav-link-icon d-md-none d-lg-inline-block">
                                        <svg class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 7a3 3 0 0 1 3 -3h12a3 3 0 0 1 3 3v10a3 3 0 0 1 -3 3h-12a3 3 0 0 1 -3 -3v-10z" /><path d="M7 10h10" /><path d="M7 14h10" /></svg>
                                    </span>
                                    <span class="nav-link-title">
                                        Device Management
                                    </span>
                                </a>
                            </li>
                            <li class="nav-item {{ request()->routeIs('users.*') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('users.index') }}">
                                    <span class="nav-link-icon d-md-none d-lg-inline-block">
                                        <svg class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" /><path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" /></svg>
                                    </span>
                                    <span class="nav-link-title">
                                        User Management
                                    </span>
                                </a>
                            </li>
                            <li class="nav-item {{ request()->routeIs('api-test') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('api-test') }}">
                                    <span class="nav-link-icon d-md-none d-lg-inline-block">
                                        <svg class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 8l-4 4l4 4" /><path d="M17 8l4 4l-4 4" /><path d="M14 4l-4 16" /></svg>
                                    </span>
                                    <span class="nav-link-title">
                                        API Test
                                    </span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </header>

        <div class="page-wrapper">
            <!-- Page header -->
            @if(isset($header))
            <div class="page-header d-print-none">
                <div class="container-xl">
                    <div class="row g-2 align-items-center">
                        <div class="col">
                            {{ $header }}
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Page body -->
            <div class="page-body">
                <div class="container-xl">
                    @if (session('status'))
                        <div class="alert alert-success alert-dismissible" role="alert">
                            <div class="d-flex">
                                <div>
                                    <svg class="icon alert-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l5 5l10 -10" /></svg>
                                </div>
                                <div>
                                    {{ session('status') }}
                                </div>
                            </div>
                            <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
                        </div>
                    @endif

                    @yield('content')
                </div>
            </div>

            <!-- Footer -->
            <footer class="footer footer-transparent d-print-none">
                <div class="container-xl">
                    <div class="row text-center align-items-center flex-row-reverse">
                        <div class="col-lg-auto ms-lg-auto">
                            <ul class="list-inline list-inline-dots mb-0">
                                <li class="list-inline-item">
                                    <a href="https://tabler.io/docs" target="_blank" class="link-secondary" rel="noopener">Documentation</a>
                                </li>
                                <li class="list-inline-item">
                                    <a href="#" class="link-secondary">License</a>
                                </li>
                                <li class="list-inline-item">
                                    <a href="https://github.com/tabler/tabler" target="_blank" class="link-secondary" rel="noopener">Source code</a>
                                </li>
                            </ul>
                        </div>
                        <div class="col-12 col-lg-auto mt-3 mt-lg-0">
                            <ul class="list-inline list-inline-dots mb-0">
                                <li class="list-inline-item">
                                    Copyright &copy; {{ date('Y') }}
                                    <a href="#" class="link-secondary">{{ config('app.name') }}</a>.
                                    All rights reserved.
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <!-- Custom Scripts -->
    @stack('scripts')
    
    <!-- Bootstrap Debug Script -->
    <script>
        // Debug Bootstrap loading
        document.addEventListener('DOMContentLoaded', function() {
            console.log('🔍 Bootstrap Debug Info:');
            console.log('- Bootstrap available:', typeof window.bootstrap !== 'undefined');
            console.log('- jQuery available:', typeof window.$ !== 'undefined');
            console.log('- Popper available:', typeof window.Popper !== 'undefined');
            
            if (window.bootstrap) {
                console.log('- Bootstrap version:', window.bootstrap);
                console.log('- Dropdown class:', window.bootstrap.Dropdown);
                console.log('- Modal class:', window.bootstrap.Modal);
                console.log('- Tooltip class:', window.bootstrap.Tooltip);
            }
        });
    </script>
</body>
</html>
