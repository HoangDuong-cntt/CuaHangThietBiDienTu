<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name', 'SmartTech'))</title>
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>
<body>
    <header class="site-header">
        <div class="container header-content">
            <a class="brand" href="{{ url('/') }}">SMARTTECH</a>
            <nav class="main-nav" aria-label="Điều hướng chính">
                <a href="{{ route('cart.index') }}">Giỏ hàng <span class="cart-badge" data-cart-count>{{ $cartCount ?? 0 }}</span></a>
                <a href="{{ route('orders.track.form') }}">Tra cứu đơn hàng</a>
                <a href="{{ route('warranty.form') }}">Bảo hành</a>
                @if (Route::has('login'))
                    @auth
                        <span class="nav-user">{{ auth()->user()->name }}</span>
                    @else
                        <a href="{{ route('login') }}">Đăng nhập</a>
                    @endauth
                @endif
                @auth
                    @if (in_array(auth()->user()->role, ['admin', 'staff'], true))
                        <a href="{{ route('admin.orders.index') }}">Quản trị</a>
                    @endif
                @endauth
            </nav>
        </div>
    </header>

    @if (session('status'))
        <div class="container flash-message" role="status">{{ session('status') }}</div>
    @endif

    <main class="container page-content">
        @yield('content')
    </main>

    <footer class="site-footer">
        <div class="container">SmartTech - Thiết bị công nghệ chính hãng</div>
    </footer>
</body>
</html>
