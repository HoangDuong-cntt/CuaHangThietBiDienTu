<!DOCTYPE html>
<html lang="vi" class="h-full bg-zinc-950 text-zinc-100 antialiased selection:bg-blue-600 selection:text-white">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Quản trị hệ thống' }} - SmartTech Admin Console</title>

    <!-- Fonts & Bundled Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-full flex bg-zinc-950 font-sans" x-data="{ sidebarOpen: false }">

    <!-- Mobile Sidebar Backdrop -->
    <div x-show="sidebarOpen" 
         x-cloak 
         @click="sidebarOpen = false" 
         class="fixed inset-0 z-40 bg-zinc-950/80 backdrop-blur-sm lg:hidden"
         x-transition:enter="transition-opacity ease-linear duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
    </div>

    <!-- Sidebar (Sharp Utilitarian Industrial Design) -->
    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
           class="fixed inset-y-0 left-0 z-50 w-64 bg-zinc-950 border-r border-zinc-850 flex flex-col justify-between transition-transform duration-200 ease-in-out lg:static shrink-0">
        
        <!-- Brand & Workspace Header -->
        <div>
            <div class="h-16 px-5 border-b border-zinc-850 flex items-center justify-between">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 group">
                    <div class="w-8 h-8 rounded bg-white text-zinc-950 flex items-center justify-center font-bold text-xs tracking-tighter">
                        ST
                    </div>
                    <div class="flex flex-col">
                        <span class="text-xs font-bold uppercase tracking-wider text-white">SmartTech</span>
                        <span class="text-[9px] font-mono text-zinc-500 uppercase tracking-widest">CONTROL PLANE</span>
                    </div>
                </a>

                <button @click="sidebarOpen = false" class="lg:hidden text-zinc-400 hover:text-white p-1">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <!-- Workspace Status Pill -->
            <div class="px-5 py-3 border-b border-zinc-850/60 bg-zinc-900/30 flex items-center justify-between text-[11px]">
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span class="font-mono text-zinc-400">NODE: PRODUCTION</span>
                </div>
                <span class="px-1.5 py-0.5 rounded text-[9px] font-mono font-bold bg-zinc-800 text-zinc-300 uppercase">
                    v2.6
                </span>
            </div>

            <!-- Navigation Sections -->
            <nav class="p-3 space-y-6 overflow-y-auto max-h-[calc(100vh-12rem)]">
                
                <!-- Group: CORE / TỔNG QUAN -->
                @if(auth()->user()->hasPermission('view_dashboard'))
                    <div class="space-y-1">
                        <span class="px-3 text-[10px] font-mono uppercase tracking-widest text-zinc-500 font-semibold">TỔNG QUAN</span>
                        
                        <a href="{{ route('admin.dashboard') }}" 
                           class="flex items-center gap-2.5 px-3 py-2 rounded-md text-xs font-medium transition-colors
                                  {{ request()->routeIs('admin.dashboard') ? 'bg-zinc-800/90 text-white font-semibold border-l-2 border-blue-500' : 'text-zinc-400 hover:text-white hover:bg-zinc-900' }}">
                            <svg class="w-4 h-4 {{ request()->routeIs('admin.dashboard') ? 'text-blue-400' : 'text-zinc-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                            </svg>
                            <span>Dashboard Báo cáo</span>
                        </a>
                    </div>
                @endif

                <!-- Group: BẢO MẬT & PHÂN QUYỀN (RBAC) -->
                @if(auth()->user()->hasPermission('manage_roles') || auth()->user()->hasPermission('manage_users'))
                    <div class="space-y-1">
                        <span class="px-3 text-[10px] font-mono uppercase tracking-widest text-zinc-500 font-semibold">BẢO MẬT &amp; NHÂN SỰ</span>

                        @if(auth()->user()->hasPermission('manage_roles'))
                            <a href="{{ route('admin.roles.index') }}" 
                               class="flex items-center gap-2.5 px-3 py-2 rounded-md text-xs font-medium transition-colors
                                      {{ request()->routeIs('admin.roles.*') ? 'bg-zinc-800/90 text-white font-semibold border-l-2 border-blue-500' : 'text-zinc-400 hover:text-white hover:bg-zinc-900' }}">
                                <svg class="w-4 h-4 {{ request()->routeIs('admin.roles.*') ? 'text-blue-400' : 'text-zinc-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                </svg>
                                <span>Ma trận Phân quyền (RBAC)</span>
                            </a>
                        @endif

                        @if(auth()->user()->hasPermission('manage_users'))
                            <a href="{{ route('admin.staff.index') }}" 
                               class="flex items-center gap-2.5 px-3 py-2 rounded-md text-xs font-medium transition-colors
                                      {{ request()->routeIs('admin.staff.*') ? 'bg-zinc-800/90 text-white font-semibold border-l-2 border-blue-500' : 'text-zinc-400 hover:text-white hover:bg-zinc-900' }}">
                                <svg class="w-4 h-4 {{ request()->routeIs('admin.staff.*') ? 'text-blue-400' : 'text-zinc-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                </svg>
                                <span>Quản lý Nhân sự</span>
                            </a>
                        @endif
                    </div>
                @endif

                <!-- Group: CÁC MODULE CHUYÊN MÔN -->
                @if(auth()->user()->hasPermission('manage_products') || auth()->user()->hasPermission('manage_orders') || auth()->user()->hasPermission('view_inventory') || auth()->user()->hasPermission('manage_coupons') || auth()->user()->hasPermission('manage_reviews') || auth()->user()->hasPermission('manage_banners'))
                    <div class="space-y-1">
                        <span class="px-3 text-[10px] font-mono uppercase tracking-widest text-zinc-500 font-semibold">NGHIỆP VỤ CHUYÊN MÔN</span>

                        @if(auth()->user()->hasPermission('manage_products'))
                            <a href="#products" class="flex items-center justify-between px-3 py-2 rounded-md text-xs text-zinc-400 hover:text-zinc-200 hover:bg-zinc-900/60 transition-colors">
                                <div class="flex items-center gap-2.5">
                                    <svg class="w-4 h-4 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                    </svg>
                                    <span>Sản phẩm &amp; 3D</span>
                                </div>
                                <span class="text-[9px] font-mono text-zinc-400 border border-zinc-800 px-1 rounded">P2</span>
                            </a>
                        @endif

                        @if(auth()->user()->hasPermission('manage_orders') || auth()->user()->hasPermission('view_inventory') || auth()->user()->isStaff())
                            <a href="{{ route('admin.orders.index') }}" class="flex items-center justify-between px-3 py-2 rounded-md text-xs text-zinc-400 hover:text-zinc-200 hover:bg-zinc-900/60 transition-colors {{ request()->routeIs('admin.orders.*') ? 'bg-zinc-800/90 text-white font-semibold border-l-2 border-emerald-500' : '' }}">
                                <div class="flex items-center gap-2.5">
                                    <svg class="w-4 h-4 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                    </svg>
                                    <span>Đơn hàng &amp; Giao vận</span>
                                </div>
                                <span class="text-[9px] font-mono text-zinc-400 border border-zinc-800 px-1 rounded">P4</span>
                            </a>
                            <a href="{{ route('admin.inventory.low-stock') }}" class="flex items-center justify-between px-3 py-2 rounded-md text-xs text-zinc-400 hover:text-zinc-200 hover:bg-zinc-900/60 transition-colors {{ request()->routeIs('admin.inventory.*') ? 'bg-zinc-800/90 text-white font-semibold border-l-2 border-amber-500' : '' }}">
                                <div class="flex items-center gap-2.5">
                                    <svg class="w-4 h-4 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                    </svg>
                                    <span>Cảnh báo tồn kho</span>
                                </div>
                                <span class="text-[9px] font-mono text-zinc-400 border border-zinc-800 px-1 rounded">P4</span>
                            </a>
                        @endif

                        @if(auth()->user()->hasPermission('manage_coupons'))
                            <a href="#coupons" class="flex items-center justify-between px-3 py-2 rounded-md text-xs text-zinc-400 hover:text-zinc-200 hover:bg-zinc-900/60 transition-colors">
                                <div class="flex items-center gap-2.5">
                                    <svg class="w-4 h-4 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                                    </svg>
                                    <span>Voucher &amp; Giảm giá</span>
                                </div>
                                <span class="text-[9px] font-mono text-zinc-400 border border-zinc-800 px-1 rounded">P4</span>
                            </a>
                        @endif

                        @if(auth()->user()->hasPermission('manage_reviews') || auth()->user()->hasPermission('manage_banners'))
                            <a href="#reviews" class="flex items-center justify-between px-3 py-2 rounded-md text-xs text-zinc-400 hover:text-zinc-200 hover:bg-zinc-900/60 transition-colors">
                                <div class="flex items-center gap-2.5">
                                    <svg class="w-4 h-4 text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                                    </svg>
                                    <span>Đánh giá &amp; Banners</span>
                                </div>
                                <span class="text-[9px] font-mono text-zinc-400 border border-zinc-800 px-1 rounded">P5</span>
                            </a>
                        @endif
                    </div>
                @endif

            </nav>
        </div>

        <!-- User Profile Quick Bar (Bottom) -->
        <div class="p-3 border-t border-zinc-850 bg-zinc-900/20">
            <div class="flex items-center justify-between p-2 rounded-md bg-zinc-900 border border-zinc-800">
                <div class="flex items-center gap-2.5 overflow-hidden">
                    <div class="w-7 h-7 rounded bg-blue-600 text-white flex items-center justify-center font-bold text-xs uppercase shrink-0">
                        {{ substr(auth()->user()->name, 0, 1) }}
                    </div>
                    <div class="flex flex-col overflow-hidden">
                        <span class="text-xs font-semibold text-white truncate">{{ auth()->user()->name }}</span>
                        <span class="text-[10px] font-mono text-zinc-400 uppercase leading-none">
                            {{ auth()->user()->roleModel->display_name ?? auth()->user()->role }}
                        </span>
                    </div>
                </div>

                <a href="{{ route('home') }}" title="Xem website" class="p-1 text-zinc-400 hover:text-white transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                    </svg>
                </a>
            </div>
        </div>

    </aside>

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col min-w-0 bg-zinc-900/50">
        
        <!-- Top Operational Header -->
        <header class="h-16 px-4 sm:px-6 lg:px-8 border-b border-zinc-850 bg-zinc-950/80 backdrop-blur-md sticky top-0 z-30 flex items-center justify-between gap-4">
            
            <div class="flex items-center gap-4">
                <button @click="sidebarOpen = true" class="lg:hidden text-zinc-400 hover:text-white p-1">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>

                <!-- Breadcrumb -->
                <div class="flex items-center gap-2 text-xs font-mono">
                    <a href="{{ route('admin.dashboard') }}" class="text-zinc-500 hover:text-zinc-300">ADMIN</a>
                    <span class="text-zinc-600">/</span>
                    <span class="text-zinc-200 uppercase font-semibold">{{ $title ?? 'CONSOLE' }}</span>
                </div>
            </div>

            <!-- Header Action Controls -->
            <div class="flex items-center gap-3">
                <a href="{{ url('/') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-md border border-zinc-700 bg-zinc-900 hover:bg-zinc-800 text-xs font-semibold text-zinc-200 transition-colors shadow-xs">
                    <svg class="w-3.5 h-3.5 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    <span>&larr; Về Cửa Hàng</span>
                </a>

                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="px-3 py-1.5 rounded-md bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 border border-rose-500/20 text-xs font-semibold transition-colors">
                        Đăng xuất
                    </button>
                </form>
            </div>
        </header>

        <!-- Flash Alerts (Sharp 1px border) -->
        <div class="p-4 sm:px-6 lg:px-8 pb-0 w-full">
            @if(session('success'))
                <div class="flex items-center gap-3 p-3.5 rounded-md border border-emerald-500/30 bg-emerald-500/10 text-emerald-300 text-xs font-medium shadow-sm">
                    <svg class="w-4 h-4 text-emerald-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="flex items-center gap-3 p-3.5 rounded-md border border-rose-500/30 bg-rose-500/10 text-rose-300 text-xs font-medium shadow-sm">
                    <svg class="w-4 h-4 text-rose-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div class="p-3.5 rounded-md border border-rose-500/30 bg-rose-500/10 text-rose-300 text-xs space-y-1">
                    <p class="font-bold">Vui lòng kiểm tra lại thông tin:</p>
                    <ul class="list-disc list-inside space-y-0.5 text-zinc-400">
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>

        <!-- Main Workspace Body -->
        <main class="flex-1 p-4 sm:p-6 lg:p-8">
            @hasSection('admin-content')
                @yield('admin-content')
            @else
                @yield('content')
            @endif
        </main>

        <!-- Subdued Terminal Footer -->
        <footer class="h-12 px-6 border-t border-zinc-850 bg-zinc-950 text-zinc-500 text-[11px] font-mono flex items-center justify-between">
            <div>SMARTTECH HARDWARE LAB &bull; KHOA CNTT ĐỒ ÁN 2026</div>
            <div>STATUS: SECURE_RBAC_ACTIVE</div>
        </footer>

    </div>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>
</html>
