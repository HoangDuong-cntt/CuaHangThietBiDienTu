<!DOCTYPE html>
<html lang="vi" class="h-full bg-zinc-50 text-zinc-900 antialiased selection:bg-zinc-900 selection:text-white dark:bg-zinc-950 dark:text-zinc-100">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'SmartTech E-Commerce' }} - Thiết Bị Công Nghệ Thông Minh</title>

    <!-- Scripts & Styles via Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-full flex flex-col font-sans">

    <!-- Top Announcement Bar (Subtle & Crisp) -->
    <div class="bg-zinc-900 text-zinc-300 text-xs py-1.5 px-4 border-b border-zinc-800 text-center tracking-wide font-medium">
        <span>⚡ Hệ thống Thương mại Điện tử Thiết bị Công nghệ Thế hệ Mới &bull; Trả góp 0% qua VietQR & VNPay</span>
    </div>

    <!-- Main Navigation Bar -->
    <header class="sticky top-0 z-40 w-full border-b border-zinc-200 bg-white/95 backdrop-blur-md dark:border-zinc-800 dark:bg-zinc-950/95">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between gap-4">
            
            <!-- Logo & Brand -->
            <div class="flex items-center gap-8">
                <a href="{{ url('/') }}" class="flex items-center gap-2.5 group">
                    <div class="w-8 h-8 rounded-md bg-zinc-900 text-white dark:bg-white dark:text-zinc-950 flex items-center justify-center font-bold text-sm tracking-tighter transition-transform group-hover:scale-95">
                        ST
                    </div>
                    <div class="flex flex-col">
                        <span class="text-base font-bold tracking-tight text-zinc-900 dark:text-white uppercase leading-none">SmartTech</span>
                        <span class="text-[9px] font-mono tracking-widest uppercase text-zinc-500 leading-tight">HARDWARE LAB</span>
                    </div>
                </a>

                <!-- Navigation Links -->
                <nav class="hidden md:flex items-center gap-1">
                    <a href="{{ url('/') }}" class="px-3 py-1.5 text-xs font-semibold uppercase tracking-wider text-zinc-600 hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-white transition-colors">
                        Trang chủ
                    </a>
                    <a href="#products" class="px-3 py-1.5 text-xs font-semibold uppercase tracking-wider text-zinc-600 hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-white transition-colors">
                        Sản phẩm
                    </a>
                    <a href="#coupons" class="px-3 py-1.5 text-xs font-semibold uppercase tracking-wider text-zinc-600 hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-white transition-colors">
                        Khuyến mãi
                    </a>
                </nav>
            </div>

            <!-- Right Actions: Search / Cart / Auth -->
            <div class="flex items-center gap-3">
                
                <!-- Search Mockup -->
                <div class="hidden sm:flex items-center gap-2 px-3 py-1.5 rounded-md border border-zinc-200 bg-zinc-50 text-xs text-zinc-500 dark:border-zinc-800 dark:bg-zinc-900 w-48 lg:w-64">
                    <svg class="w-3.5 h-3.5 text-zinc-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <span class="truncate">Tìm kiếm thiết bị...</span>
                    <span class="ml-auto font-mono text-[10px] text-zinc-400 border border-zinc-200 dark:border-zinc-700 rounded px-1">⌘K</span>
                </div>

                <!-- User Authentication Status -->
                @guest
                    <div class="flex items-center gap-2">
                        <a href="{{ route('login') }}" class="px-3.5 py-1.5 text-xs font-semibold text-zinc-700 hover:text-zinc-950 dark:text-zinc-300 dark:hover:text-white transition-colors">
                            Đăng nhập
                        </a>
                        <a href="{{ route('register') }}" class="px-3.5 py-1.5 rounded-md bg-zinc-900 text-white text-xs font-semibold hover:bg-zinc-800 dark:bg-white dark:text-zinc-900 dark:hover:bg-zinc-200 transition-colors shadow-sm">
                            Đăng ký
                        </a>
                    </div>
                @else
                    <!-- Quick Admin Portal Link for Staff & Admin -->
                    @if(Auth::user()->isAdmin() || Auth::user()->isStaff())
                        <a href="{{ route('admin.dashboard') }}" 
                           class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1.5 rounded-md bg-zinc-900 border border-zinc-750 text-blue-400 hover:text-white hover:bg-zinc-800 text-xs font-semibold shadow-xs transition-colors">
                            <svg class="w-3.5 h-3.5 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <span>🛡️ Quản trị Admin</span>
                        </a>
                    @endif

                    <!-- Authenticated Dropdown -->
                    <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                        <button @click="open = !open" type="button" class="flex items-center gap-2.5 p-1.5 rounded-md border border-zinc-200 dark:border-zinc-800 hover:bg-zinc-100 dark:hover:bg-zinc-900 transition-colors">
                            <div class="w-7 h-7 rounded bg-zinc-800 text-white flex items-center justify-center font-bold text-xs uppercase">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </div>
                            <div class="hidden sm:flex flex-col text-left">
                                <span class="text-xs font-semibold text-zinc-900 dark:text-white leading-tight truncate max-w-[120px]">
                                    {{ Auth::user()->name }}
                                </span>
                                <span class="text-[10px] font-mono text-zinc-500 uppercase leading-none">
                                    {{ Auth::user()->role }}
                                </span>
                            </div>
                            <svg class="w-3.5 h-3.5 text-zinc-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>

                        <!-- Dropdown Menu (Sharp 1px Border) -->
                        <div x-show="open" 
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="transform opacity-0 scale-95"
                             x-transition:enter-end="transform opacity-100 scale-100"
                             class="absolute right-0 mt-2 w-56 rounded-md bg-white border border-zinc-200 dark:bg-zinc-900 dark:border-zinc-800 shadow-xl py-1 z-50 text-xs">
                            
                            <div class="px-4 py-2 border-b border-zinc-100 dark:border-zinc-800">
                                <p class="text-[11px] font-semibold text-zinc-500 uppercase tracking-wider">Tài khoản</p>
                                <p class="font-medium text-zinc-900 dark:text-zinc-100 truncate">{{ Auth::user()->email }}</p>
                            </div>

                            <a href="{{ route('profile.edit') }}" class="flex items-center gap-2.5 px-4 py-2.5 text-zinc-700 hover:bg-zinc-100 dark:text-zinc-300 dark:hover:bg-zinc-800 transition-colors">
                                <svg class="w-4 h-4 text-zinc-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                <span>Hồ sơ & Sổ địa chỉ</span>
                            </a>

                            @if(Auth::user()->isAdmin() || Auth::user()->isStaff())
                                <a href="{{ url('/admin/dashboard') }}" class="flex items-center gap-2.5 px-4 py-2.5 text-blue-600 hover:bg-zinc-100 dark:text-blue-400 dark:hover:bg-zinc-800 transition-colors font-medium border-t border-zinc-100 dark:border-zinc-800">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                    </svg>
                                    <span>Trang Quản trị Admin</span>
                                </a>
                            @endif

                            <div class="border-t border-zinc-100 dark:border-zinc-800 my-1"></div>

                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full text-left flex items-center gap-2.5 px-4 py-2.5 text-rose-600 hover:bg-rose-50 dark:text-rose-400 dark:hover:bg-rose-950/30 transition-colors">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                    </svg>
                                    <span>Đăng xuất an toàn</span>
                                </button>
                            </form>
                        </div>
                    </div>
                @endguest

            </div>
        </div>
    </header>

    <!-- Global Alert / Flash Messages (Sharp 1px Border Style) -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4 w-full">
        @if(session('success'))
            <div class="flex items-center gap-3 p-3.5 rounded-md border border-emerald-500/30 bg-emerald-500/10 text-emerald-800 dark:text-emerald-300 text-xs font-medium">
                <svg class="w-4 h-4 text-emerald-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="flex items-center gap-3 p-3.5 rounded-md border border-rose-500/30 bg-rose-500/10 text-rose-800 dark:text-rose-300 text-xs font-medium">
                <svg class="w-4 h-4 text-rose-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif
    </div>

    <!-- Main Dynamic Content -->
    <main class="flex-1">
        @yield('content')
    </main>

    <!-- Footer (Minimal Industrial Style) -->
    <footer class="border-t border-zinc-200 bg-white dark:border-zinc-800 dark:bg-zinc-950 py-8 text-xs text-zinc-500">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-2">
                <span class="font-bold text-zinc-900 dark:text-white uppercase tracking-tight">SmartTech E-Commerce</span>
                <span>&bull;</span>
                <span class="font-mono text-[11px]">PHP 8.5 &bull; Laravel Framework</span>
            </div>
            <p>&copy; 2026 SmartTech Hardware Lab. Đồ án Thực tập chuyên ngành CNTT.</p>
        </div>
    </footer>

    <!-- Alpine.js for lightweight dropdown interaction -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>
</html>
