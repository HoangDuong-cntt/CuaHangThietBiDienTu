<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Quản trị') - {{ config('app.name', 'SmartTech') }}</title>
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>
<body class="admin-shell">
    <aside class="admin-sidebar">
        <a class="brand" href="{{ url('/') }}">SMARTTECH</a>
        <p class="sidebar-label">QUẢN TRỊ</p>
        <nav aria-label="Điều hướng quản trị">
            <a href="{{ route('cart.index') }}">Cửa hàng</a>
            @yield('admin-navigation')
        </nav>
    </aside>
    <main class="admin-main">
        <header class="admin-topbar">
            <span>@yield('page-heading', 'Bảng điều khiển')</span>
            <span>{{ auth()->user()?->name }}</span>
        </header>
        <section class="admin-content">
            @yield('admin-content')
        </section>
    </main>
</body>
</html>
