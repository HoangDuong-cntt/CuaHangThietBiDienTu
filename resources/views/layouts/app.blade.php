<!DOCTYPE html>
<html lang="vi" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SmartTech - Hệ Thống Thiết Bị Công Nghệ Cao Cấp')</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Tailwind CSS CDN for instant robust styling -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50: '#eff6ff',
                            100: '#dbeafe',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                            900: '#1e3a8a',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
    @stack('styles')
</head>
<body class="flex flex-col min-h-screen text-slate-800 antialiased selection:bg-blue-500 selection:text-white">

    <!-- Top Announcement Bar -->
    <header class="bg-slate-900 text-slate-300 text-xs py-2 border-b border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-center">
            <div class="flex items-center space-x-6">
                <span><i class="fa-solid fa-headset mr-1.5 text-blue-400"></i> Hotline: <strong class="text-white">1800 6868</strong> (Miễn phí)</span>
                <span class="hidden md:inline-block"><i class="fa-solid fa-shield-halved mr-1.5 text-emerald-400"></i> 100% Hàng chính hãng phân phối</span>
                <span class="hidden lg:inline-block"><i class="fa-solid fa-truck-fast mr-1.5 text-amber-400"></i> Giao hàng siêu tốc 2h</span>
            </div>
            <div class="flex items-center space-x-4">
                <a href="#" class="hover:text-white transition"><i class="fa-solid fa-store mr-1"></i> Hệ thống 68 cửa hàng</a>
                <span class="text-slate-600">|</span>
                <a href="#" class="hover:text-white transition"><i class="fa-solid fa-clock-rotate-left mr-1"></i> Tra cứu bảo hành</a>
            </div>
        </div>
    </header>

    <!-- Main Navigation Bar -->
    <nav class="sticky top-0 z-40 bg-white/95 backdrop-blur-md border-b border-slate-200/80 shadow-xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-18">
                <!-- Logo -->
                <div class="flex items-center space-x-8">
                    <a href="{{ route('home') }}" class="flex items-center space-x-2.5 group">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-blue-600 via-indigo-600 to-sky-400 flex items-center justify-center text-white shadow-md shadow-blue-500/20 group-hover:scale-105 transition-transform duration-200">
                            <i class="fa-solid fa-bolt text-lg"></i>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-xl font-extrabold tracking-tight bg-gradient-to-r from-slate-900 via-blue-900 to-blue-700 bg-clip-text text-transparent">SmartTech</span>
                            <span class="text-[10px] tracking-wider uppercase font-semibold text-slate-400 -mt-1">Cửa Hàng Thiết Bị Điện Tử</span>
                        </div>
                    </a>

                    <!-- Nav Links -->
                    <div class="hidden lg:flex items-center space-x-1 font-medium text-sm text-slate-600">
                        <a href="{{ route('products.index') }}" class="px-3.5 py-2 rounded-lg hover:text-blue-600 hover:bg-blue-50 transition {{ request()->routeIs('products.*') ? 'text-blue-600 bg-blue-50/80 font-semibold' : '' }}">
                            <i class="fa-solid fa-layer-group mr-1.5 text-blue-500"></i> Tất cả sản phẩm
                        </a>
                        <a href="{{ route('products.index', ['category' => 'laptop-gaming']) }}" class="px-3.5 py-2 rounded-lg hover:text-blue-600 hover:bg-blue-50 transition">
                            Laptop Gaming
                        </a>
                        <a href="{{ route('products.index', ['category' => 'dien-thoai-thong-minh']) }}" class="px-3.5 py-2 rounded-lg hover:text-blue-600 hover:bg-blue-50 transition">
                            Điện Thoại
                        </a>
                        <a href="{{ route('products.index', ['category' => 'phu-kien-cao-cap']) }}" class="px-3.5 py-2 rounded-lg hover:text-blue-600 hover:bg-blue-50 transition">
                            Phụ Kiện
                        </a>
                    </div>
                </div>

                <!-- Search & Actions -->
                <div class="flex items-center space-x-4">
                    <!-- Search Bar (Prepared for Member 3 Autocomplete) -->
                    <form action="{{ route('products.index') }}" method="GET" class="relative hidden sm:block w-64 md:w-80">
                        <input
                            type="text"
                            name="keyword"
                            value="{{ request('keyword') }}"
                            placeholder="Tìm kiếm máy, SKU, thông số..."
                            class="w-full bg-slate-100 text-slate-800 text-sm rounded-full pl-10 pr-4 py-2 border border-transparent focus:border-blue-500 focus:bg-white focus:outline-hidden transition"
                        >
                        <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-2.5 text-slate-400 text-sm"></i>
                        @if(request('category'))
                            <input type="hidden" name="category" value="{{ request('category') }}">
                        @endif
                        @if(request('brand'))
                            <input type="hidden" name="brand" value="{{ request('brand') }}">
                        @endif
                    </form>

                    <!-- Compare / Wishlist / Cart Icons -->
                    <div class="flex items-center space-x-2">
                        <a href="#" class="p-2.5 text-slate-600 hover:text-blue-600 hover:bg-blue-50 rounded-full transition relative" title="So sánh">
                            <i class="fa-solid fa-code-compare text-base"></i>
                        </a>
                        <a href="#" class="p-2.5 text-slate-600 hover:text-rose-600 hover:bg-rose-50 rounded-full transition relative" title="Yêu thích">
                            <i class="fa-regular fa-heart text-base"></i>
                        </a>
                        <a href="#" class="flex items-center space-x-2 bg-blue-600 hover:bg-blue-700 text-white px-3.5 py-2 rounded-xl text-sm font-semibold shadow-xs shadow-blue-500/30 transition duration-150" title="Giỏ hàng">
                            <i class="fa-solid fa-bag-shopping text-base"></i>
                            <span class="hidden md:inline">Giỏ hàng</span>
                            <span class="bg-blue-500 text-white text-xs px-1.5 py-0.5 rounded-full font-bold ml-1">0</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main View Content -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-slate-950 text-slate-400 mt-20 pt-16 pb-12 border-t border-slate-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-10 pb-12 border-b border-slate-800">
                <!-- Col 1: About -->
                <div class="lg:col-span-2 space-y-4">
                    <div class="flex items-center space-x-2.5">
                        <div class="w-9 h-9 rounded-xl bg-blue-600 flex items-center justify-center text-white">
                            <i class="fa-solid fa-bolt"></i>
                        </div>
                        <span class="text-xl font-extrabold text-white">SmartTech</span>
                    </div>
                    <p class="text-sm leading-relaxed text-slate-400 max-w-sm">
                        Hệ thống bán lẻ thiết bị công nghệ chính hãng hàng đầu Việt Nam. Cam kết 100% máy phân phối chính ngạch, bảo hành đổi mới toàn quốc.
                    </p>
                    <div class="pt-2 text-xs space-y-2 text-slate-400">
                        <div><i class="fa-solid fa-location-dot mr-2 text-blue-400"></i> 123 Đường Công Nghệ, Khu Công Nghệ Cao, TP. Hồ Chí Minh</div>
                        <div><i class="fa-solid fa-phone mr-2 text-blue-400"></i> Tổng đài hỗ trợ: <strong>1800 6868</strong> (08:00 - 21:30)</div>
                        <div><i class="fa-solid fa-envelope mr-2 text-blue-400"></i> Email: support@smarttech.vn</div>
                    </div>
                </div>

                <!-- Col 2: Categories -->
                <div>
                    <h4 class="text-sm font-bold uppercase tracking-wider text-white mb-4">Danh Mục Thiết Bị</h4>
                    <ul class="space-y-2.5 text-sm">
                        <li><a href="{{ route('products.index', ['category' => 'laptop-gaming']) }}" class="hover:text-white transition">Laptop Gaming</a></li>
                        <li><a href="{{ route('products.index', ['category' => 'laptop-van-phong']) }}" class="hover:text-white transition">Laptop Mỏng Nhẹ</a></li>
                        <li><a href="{{ route('products.index', ['category' => 'dien-thoai-thong-minh']) }}" class="hover:text-white transition">Điện Thoại Cao Cấp</a></li>
                        <li><a href="{{ route('products.index', ['category' => 'may-tinh-bang']) }}" class="hover:text-white transition">Máy Tính Bảng</a></li>
                        <li><a href="{{ route('products.index', ['category' => 'phu-kien-cao-cap']) }}" class="hover:text-white transition">Phụ Kiện Chính Hãng</a></li>
                    </ul>
                </div>

                <!-- Col 3: Policy -->
                <div>
                    <h4 class="text-sm font-bold uppercase tracking-wider text-white mb-4">Chính Sách & Hỗ Trợ</h4>
                    <ul class="space-y-2.5 text-sm">
                        <li><a href="#" class="hover:text-white transition">Chính sách bảo hành 1 đổi 1</a></li>
                        <li><a href="#" class="hover:text-white transition">Chính sách giao hàng 2h</a></li>
                        <li><a href="#" class="hover:text-white transition">Chính sách trả góp 0%</a></li>
                        <li><a href="#" class="hover:text-white transition">Bảo mật thông tin khách hàng</a></li>
                        <li><a href="#" class="hover:text-white transition">Quy chế hoạt động sàn TMĐT</a></li>
                    </ul>
                </div>

                <!-- Col 4: Payments & Cert -->
                <div>
                    <h4 class="text-sm font-bold uppercase tracking-wider text-white mb-4">Thanh Toán An Toàn</h4>
                    <div class="flex flex-wrap gap-2 text-xs">
                        <span class="bg-slate-900 border border-slate-800 text-slate-300 px-3 py-1.5 rounded-lg"><i class="fa-solid fa-money-bill-wave mr-1 text-emerald-400"></i> COD Tiền mặt</span>
                        <span class="bg-slate-900 border border-slate-800 text-slate-300 px-3 py-1.5 rounded-lg"><i class="fa-solid fa-qrcode mr-1 text-blue-400"></i> VNPay-QR</span>
                        <span class="bg-slate-900 border border-slate-800 text-slate-300 px-3 py-1.5 rounded-lg"><i class="fa-regular fa-credit-card mr-1 text-purple-400"></i> Visa / Master</span>
                    </div>
                    <div class="mt-6">
                        <h5 class="text-xs font-semibold text-slate-300 mb-2">Đăng ký nhận tin khuyến mãi:</h5>
                        <div class="flex">
                            <input type="email" placeholder="Nhập email của bạn..." class="bg-slate-900 border border-slate-800 text-xs rounded-l-lg px-3 py-2 text-white focus:outline-hidden w-full">
                            <button class="bg-blue-600 hover:bg-blue-500 text-white text-xs px-3 rounded-r-lg font-bold"><i class="fa-solid fa-paper-plane"></i></button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="pt-8 flex flex-col md:flex-row justify-between items-center text-xs text-slate-400 space-y-4 md:space-y-0">
                <div>© {{ date('Y') }} SmartTech E-Commerce. Dự án thực tập tốt nghiệp phát triển bởi Nhóm 4 thành viên.</div>
                <div class="flex space-x-6 text-slate-400">
                    <span>Phiên bản Laravel 13 - PHP 8.5</span>
                    <span>MySQL Database Cluster</span>
                </div>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
