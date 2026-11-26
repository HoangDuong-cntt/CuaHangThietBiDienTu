<!DOCTYPE html>
<html lang="vi" class="h-full bg-slate-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'SmartTech - Cửa Hàng Thiết Bị Công Nghệ Thông Minh')</title>

    <!-- Google Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Styles / Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .glass-header {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }
    </style>
    @stack('styles')
</head>
<body class="flex flex-col min-h-screen text-slate-800 antialiased selection:bg-blue-500 selection:text-white">

    <!-- TOP ANNOUNCEMENT BAR -->
    <div class="bg-slate-900 text-slate-300 text-xs font-medium py-2 px-4 text-center border-b border-slate-800">
        <span>Giao hàng miễn phí cho đơn từ 2 triệu — Đổi mới trong 30 ngày | </span>
        <a href="{{ route('shop.index') }}" class="text-white hover:text-blue-400 font-bold underline underline-offset-2 ml-1">Khám phá ngay &rarr;</a>
    </div>

    <!-- MAIN NAVBAR -->
    <header class="sticky top-0 z-40 glass-header border-b border-slate-200/80 shadow-xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16 gap-4">

                <!-- Logo tinh. -->
                <a href="{{ route('home') }}" class="flex items-center gap-1.5 shrink-0 group">
                    <span class="text-2xl font-black tracking-tight text-slate-900 group-hover:text-blue-600 transition-colors">tinh<span class="text-blue-600">.</span></span>
                </a>

                <!-- Search Bar with Live Autocomplete -->
                <div class="flex-1 max-w-xl relative" id="autocomplete-wrapper">
                    <form action="{{ route('shop.index') }}" method="GET" class="relative">
                        <div class="relative flex items-center">
                            <i class="fa-solid fa-magnifying-glass absolute left-3.5 text-slate-400 text-sm"></i>
                            <input 
                                type="text" 
                                name="q" 
                                id="autocomplete-input"
                                value="{{ request('q') }}"
                                placeholder="Bạn đang tìm thiết bị nào? (MacBook, iPhone, Dell XPS...)" 
                                autocomplete="off"
                                class="w-full pl-10 pr-10 py-2 text-sm bg-slate-100 hover:bg-slate-100/80 focus:bg-white text-slate-900 rounded-full border border-transparent focus:border-blue-500 focus:outline-hidden focus:ring-4 focus:ring-blue-500/10 transition-all placeholder:text-slate-400"
                            >
                            <button type="button" id="clear-search-btn" class="hidden absolute right-3 text-slate-400 hover:text-slate-600 text-xs">
                                <i class="fa-solid fa-circle-xmark"></i>
                            </button>
                        </div>
                    </form>

                    <!-- Autocomplete Dropdown Popup -->
                    <div id="autocomplete-dropdown" class="hidden absolute left-0 right-0 top-full mt-2 bg-white rounded-2xl shadow-2xl border border-slate-100 overflow-hidden z-50">
                        <div class="p-3 border-b border-slate-100 flex items-center justify-between text-xs text-slate-500 font-medium">
                            <span><i class="fa-solid fa-bolt text-amber-500 mr-1.5"></i>Gợi ý sản phẩm nhanh</span>
                            <span id="autocomplete-count" class="text-slate-400 font-normal"></span>
                        </div>
                        <div id="autocomplete-results" class="max-h-96 overflow-y-auto divide-y divide-slate-50">
                            <!-- Injected by JavaScript -->
                        </div>
                        <div class="p-2.5 bg-slate-50 text-center border-t border-slate-100">
                            <a href="#" id="view-all-search" class="text-xs font-semibold text-blue-600 hover:text-blue-700">Xem tất cả kết quả &rarr;</a>
                        </div>
                    </div>
                </div>

                <!-- Navigation Links & Actions -->
                <nav class="flex items-center gap-1.5 sm:gap-3">
                    <button 
                        type="button" 
                        onclick="window.SmartTechAI && window.SmartTechAI.openChat()" 
                        class="px-3 py-1.5 text-xs font-bold text-slate-700 hover:text-blue-600 rounded-xl hover:bg-slate-100/60 transition-colors flex items-center gap-1.5"
                    >
                        <i class="fa-solid fa-headset text-slate-400"></i>
                        <span>Tư vấn</span>
                    </button>

                    <a href="{{ route('compare.index') }}" class="px-3 py-1.5 text-xs font-bold text-slate-700 hover:text-blue-600 rounded-xl hover:bg-slate-100/60 transition-colors {{ request()->routeIs('compare.index') ? 'text-blue-600 bg-blue-50/60' : '' }}">
                        <i class="fa-solid fa-scale-balanced text-slate-400 mr-1"></i>So Sánh
                    </a>

                    <!-- Wishlist & Recently Viewed button -->
                    <button 
                        type="button" 
                        onclick="window.openWishlistDrawer && window.openWishlistDrawer()" 
                        class="p-2 text-slate-600 hover:text-red-500 hover:bg-slate-100 rounded-full transition-colors relative cursor-pointer" 
                        title="Yêu thích & Đã xem"
                    >
                        <i class="fa-regular fa-heart text-base"></i>
                        <span id="wishlist-badge" class="hidden absolute top-1 right-1 min-w-[14px] h-[14px] px-1 bg-red-500 text-[9px] font-black text-white rounded-full flex items-center justify-center ring-2 ring-white"></span>
                    </button>

                    <!-- Cart button -->
                    <a href="{{ route('shop.index') }}" class="p-2 text-slate-600 hover:text-blue-600 hover:bg-slate-100 rounded-full transition-colors relative" title="Cửa hàng">
                        <i class="fa-solid fa-bag-shopping text-base"></i>
                    </a>

                    <!-- User Account / Auth (Member 1 Integration) -->
                    @guest
                        <div class="flex items-center gap-1.5 pl-2 border-l border-slate-200">
                            <a href="{{ route('login') }}" class="px-3 py-1.5 text-xs font-bold text-slate-700 hover:text-blue-600 rounded-xl hover:bg-slate-100/80 transition-colors">
                                <i class="fa-regular fa-user mr-1 text-slate-400"></i>Đăng nhập
                            </a>
                            <a href="{{ route('register') }}" class="hidden sm:inline-block px-3 py-1.5 text-xs font-bold text-white bg-slate-900 hover:bg-blue-600 rounded-xl shadow-xs transition-colors">
                                Đăng ký
                            </a>
                        </div>
                    @else
                        <div class="relative pl-2 border-l border-slate-200" id="user-menu-container">
                            <button 
                                type="button" 
                                onclick="const dd = document.getElementById('user-dropdown'); dd.classList.toggle('hidden');" 
                                class="flex items-center gap-2 px-2.5 py-1.5 rounded-xl hover:bg-slate-100 text-xs font-bold text-slate-700 transition-colors cursor-pointer"
                            >
                                <div class="w-6 h-6 rounded-full bg-blue-600 text-white flex items-center justify-center text-[10px] font-extrabold uppercase shadow-xs">
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                </div>
                                <span class="max-w-[100px] truncate hidden sm:inline">{{ Auth::user()->name }}</span>
                                <i class="fa-solid fa-chevron-down text-[10px] text-slate-400"></i>
                            </button>

                            <!-- User Dropdown Menu -->
                            <div id="user-dropdown" class="hidden absolute right-0 mt-2 w-52 bg-white rounded-2xl shadow-xl border border-slate-100 py-1.5 z-50 text-xs">
                                <div class="px-3 py-2 border-b border-slate-100">
                                    <p class="font-bold text-slate-900 truncate">{{ Auth::user()->name }}</p>
                                    <p class="text-[10px] text-slate-400 truncate">{{ Auth::user()->email }}</p>
                                    <span class="inline-block mt-1 px-1.5 py-0.5 rounded text-[9px] font-mono font-bold bg-slate-100 text-slate-600 uppercase">{{ Auth::user()->role }}</span>
                                </div>
                                
                                @if(Auth::user()->isAdmin() || Auth::user()->isStaff())
                                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 px-3 py-2 text-indigo-600 hover:bg-indigo-50 font-bold transition-colors">
                                        <i class="fa-solid fa-shield-halved text-xs text-indigo-500"></i>
                                        <span>Quản trị Admin</span>
                                    </a>
                                @endif

                                <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 px-3 py-2 text-slate-700 hover:bg-slate-50 font-medium transition-colors">
                                    <i class="fa-regular fa-id-card text-xs text-slate-400"></i>
                                    <span>Hồ sơ & Sổ địa chỉ</span>
                                </a>

                                <form method="POST" action="{{ route('logout') }}" class="border-t border-slate-100">
                                    @csrf
                                    <button type="submit" class="w-full flex items-center gap-2 px-3 py-2 text-rose-600 hover:bg-rose-50 font-medium text-left transition-colors cursor-pointer">
                                        <i class="fa-solid fa-arrow-right-from-bracket text-xs text-rose-400"></i>
                                        <span>Đăng xuất</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endguest
                </nav>
            </div>
        </div>

        <!-- SECONDARY CATEGORY BAR (QUICK FILTERS) -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 border-t border-slate-100 py-2.5">
            <div class="flex items-center justify-between overflow-x-auto scrollbar-none text-xs font-medium text-slate-600 gap-6 whitespace-nowrap">
                <div class="flex items-center gap-5">
                    <a href="{{ route('shop.index', ['category' => 'laptop']) }}" class="hover:text-blue-600 transition-colors flex items-center gap-1.5 {{ request('category') === 'laptop' ? 'text-blue-600 font-bold' : '' }}">
                        <i class="fa-solid fa-laptop text-[11px] text-slate-400"></i>
                        <span>Máy tính xách tay</span>
                    </a>
                    <a href="{{ route('shop.index', ['category' => 'dien-thoai']) }}" class="hover:text-blue-600 transition-colors flex items-center gap-1.5 {{ request('category') === 'dien-thoai' ? 'text-blue-600 font-bold' : '' }}">
                        <i class="fa-solid fa-mobile-screen text-[11px] text-slate-400"></i>
                        <span>Điện thoại</span>
                    </a>
                    <a href="{{ route('shop.index', ['category' => 'may-tinh-bang']) }}" class="hover:text-blue-600 transition-colors flex items-center gap-1.5 {{ request('category') === 'may-tinh-bang' ? 'text-blue-600 font-bold' : '' }}">
                        <i class="fa-solid fa-tablet-screen-button text-[11px] text-slate-400"></i>
                        <span>Máy tính bảng</span>
                    </a>
                    <a href="{{ route('shop.index', ['category' => 'laptop-do-hoa']) }}" class="hover:text-blue-600 transition-colors flex items-center gap-1.5 {{ request('category') === 'laptop-do-hoa' ? 'text-blue-600 font-bold' : '' }}">
                        <i class="fa-solid fa-display text-[11px] text-slate-400"></i>
                        <span>Màn hình & Trạm</span>
                    </a>
                    <a href="{{ route('shop.index', ['category' => 'am-thanh']) }}" class="hover:text-blue-600 transition-colors flex items-center gap-1.5 {{ request('category') === 'am-thanh' ? 'text-blue-600 font-bold' : '' }}">
                        <i class="fa-solid fa-headphones text-[11px] text-slate-400"></i>
                        <span>Âm thanh</span>
                    </a>
                    <a href="{{ route('shop.index', ['category' => 'phu-kien']) }}" class="hover:text-blue-600 transition-colors flex items-center gap-1.5 {{ request('category') === 'phu-kien' ? 'text-blue-600 font-bold' : '' }}">
                        <i class="fa-solid fa-keyboard text-[11px] text-slate-400"></i>
                        <span>Phụ kiện</span>
                    </a>
                </div>

                <div class="flex items-center gap-4 text-slate-500 pl-4 border-l border-slate-200">
                    <a href="{{ route('shop.index', ['sort' => 'sale']) }}" class="hover:text-red-600 font-semibold text-red-500 transition-colors flex items-center gap-1">
                        <i class="fa-solid fa-fire text-[10px]"></i>
                        <span>Ưu đãi hôm nay</span>
                    </a>
                    <a href="{{ route('compare.index') }}" class="hover:text-blue-600 font-semibold transition-colors flex items-center gap-1">
                        <i class="fa-solid fa-scale-balanced text-[10px] text-blue-500"></i>
                        <span>So sánh thiết bị</span>
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- PAGE CONTENT -->
    <main class="flex-1">
        @yield('content')
    </main>

    <!-- FOOTER -->
    <footer class="bg-slate-900 text-slate-400 text-sm mt-16 border-t border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <div class="flex items-center gap-1.5 mb-3">
                        <span class="text-2xl font-black text-white">tinh<span class="text-blue-500">.</span></span>
                    </div>
                    <p class="text-xs text-slate-400 leading-relaxed mb-3">Tinh chọn công nghệ. Trọn vẹn trải nghiệm. Hệ thống đối chiếu thông số và tư vấn mua sắm thông minh.</p>
                    <div class="text-xs text-slate-500">Hotline: <strong class="text-slate-300">1900 8888</strong> (8:00 - 21:30)</div>
                </div>
                <div>
                    <h4 class="text-white text-xs font-bold uppercase tracking-wider mb-3">Khám Phá Tinh</h4>
                    <ul class="space-y-2 text-xs">
                        <li><a href="{{ route('shop.index', ['category' => 'laptop']) }}" class="hover:text-white transition-colors">Máy tính xách tay</a></li>
                        <li><a href="{{ route('shop.index', ['category' => 'dien-thoai']) }}" class="hover:text-white transition-colors">Điện thoại di động</a></li>
                        <li><a href="{{ route('shop.index', ['category' => 'may-tinh-bang']) }}" class="hover:text-white transition-colors">Máy tính bảng</a></li>
                        <li><a href="{{ route('compare.index') }}" class="hover:text-white transition-colors">Bảng so sánh 3 thiết bị</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white text-xs font-bold uppercase tracking-wider mb-3">Hỗ Trợ Khách Hàng</h4>
                    <ul class="space-y-2 text-xs">
                        <li>Bảo hành chính hãng 12 - 36 tháng</li>
                        <li>Đổi mới 1-1 trong 30 ngày</li>
                        <li>Giao hàng miễn phí toàn quốc</li>
                        <li>Hỗ trợ kỹ thuật 24/7</li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white text-xs font-bold uppercase tracking-wider mb-3">Luôn Ở Đây Vì Bạn</h4>
                    <p class="text-xs text-slate-400 leading-relaxed mb-2">Tư vấn chọn cấu hình đúng với ngành học, công việc và ngân sách thông qua Trợ lý AI.</p>
                    <button type="button" onclick="window.SmartTechAI && window.SmartTechAI.openChat()" class="text-xs text-blue-400 hover:text-blue-300 font-semibold flex items-center gap-1">
                        <span>Chat với Trợ lý AI</span>
                        <i class="fa-solid fa-arrow-right text-[10px]"></i>
                    </button>
                </div>
            </div>
            <div class="border-t border-slate-800 mt-8 pt-6 flex flex-col sm:flex-row items-center justify-between text-xs text-slate-500 gap-2">
                <div>&copy; {{ date('Y') }} tinh. - Hệ thống Thương mại Điện tử Thiết bị Công nghệ Thông minh.</div>
                <div class="flex items-center gap-4 text-[11px]">
                    <span>Bảo mật thông tin</span>
                    <span>Điều khoản sử dụng</span>
                </div>
            </div>
        </div>
    </footer>

    <!-- FLOATING AI CHATBOT COMPONENT (Thành viên 3) -->
    @include('components.ai-chat')

    <!-- AUTOCOMPLETE JAVASCRIPT LOGIC -->
    <script>
        (function() {
            const input = document.getElementById('autocomplete-input');
            const dropdown = document.getElementById('autocomplete-dropdown');
            const resultsContainer = document.getElementById('autocomplete-results');
            const countLabel = document.getElementById('autocomplete-count');
            const viewAllLink = document.getElementById('view-all-search');
            const clearBtn = document.getElementById('clear-search-btn');
            let debounceTimer = null;

            if (!input || !dropdown) return;

            input.addEventListener('input', function() {
                const query = this.value.trim();

                if (query.length > 0) {
                    clearBtn.classList.remove('hidden');
                } else {
                    clearBtn.classList.add('hidden');
                    dropdown.classList.add('hidden');
                    return;
                }

                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => {
                    fetchAutocomplete(query);
                }, 280);
            });

            clearBtn.addEventListener('click', function() {
                input.value = '';
                clearBtn.classList.add('hidden');
                dropdown.classList.add('hidden');
                input.focus();
            });

            async function fetchAutocomplete(query) {
                try {
                    const res = await fetch(`/api/products/autocomplete?q=${encodeURIComponent(query)}`);
                    const json = await res.json();

                    if (!json.success || !json.results || json.results.length === 0) {
                        resultsContainer.innerHTML = `
                            <div class="p-6 text-center text-slate-400 text-xs">
                                <i class="fa-solid fa-face-frown text-slate-300 text-2xl mb-2 block"></i>
                                Không tìm thấy thiết bị nào khớp với "<strong>${escapeHtml(query)}</strong>"
                            </div>
                        `;
                        countLabel.textContent = '0 kết quả';
                        viewAllLink.href = `/shop?q=${encodeURIComponent(query)}`;
                        dropdown.classList.remove('hidden');
                        return;
                    }

                    countLabel.textContent = `${json.results.length} gợi ý hàng đầu`;
                    viewAllLink.href = `/shop?q=${encodeURIComponent(query)}`;

                    resultsContainer.innerHTML = json.results.map(item => `
                        <a href="/shop?q=${encodeURIComponent(item.name)}" class="flex items-center gap-3 p-3 hover:bg-slate-50 transition-colors group">
                            <div class="w-12 h-12 rounded-lg bg-slate-100 flex items-center justify-center p-1 overflow-hidden shrink-0 border border-slate-200">
                                <img src="${item.image_url}" alt="${escapeHtml(item.name)}" class="w-full h-full object-contain group-hover:scale-110 transition-transform duration-300" onerror="this.src='/images/placeholder.svg'">
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-1.5">
                                    <span class="text-[10px] font-bold text-blue-600 uppercase tracking-wider">${escapeHtml(item.brand || '')}</span>
                                    <span class="text-[10px] text-slate-400">• ${escapeHtml(item.sku)}</span>
                                </div>
                                <h4 class="text-xs font-semibold text-slate-800 truncate group-hover:text-blue-600 transition-colors">${escapeHtml(item.name)}</h4>
                                <div class="flex items-baseline gap-2 mt-0.5">
                                    <span class="text-xs font-bold text-red-600">${item.sale_price ? item.sale_price : item.price}</span>
                                    ${item.sale_price ? `<span class="text-[10px] text-slate-400 line-through">${item.price}</span>` : ''}
                                </div>
                            </div>
                            <i class="fa-solid fa-chevron-right text-slate-300 text-xs group-hover:text-blue-500 group-hover:translate-x-0.5 transition-all"></i>
                        </a>
                    `).join('');

                    dropdown.classList.remove('hidden');
                } catch (e) {
                    console.error('Lỗi tìm kiếm gợi ý:', e);
                }
            }

            // Đóng dropdown khi click ra ngoài
            document.addEventListener('click', function(e) {
                if (!document.getElementById('autocomplete-wrapper').contains(e.target)) {
                    dropdown.classList.add('hidden');
                }
            });

            function escapeHtml(str) {
                return (str || '').replace(/[&<>"']/g, function(m) {
                    return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' }[m];
                });
            }
        })();
    </script>

    <!-- DRAWER: DANH SÁCH YÊU THÍCH & ĐÃ XEM GẦN ĐÂY (THÀNH VIÊN 3) -->
    <div id="wishlist-backdrop" onclick="closeWishlistDrawer()" class="fixed inset-0 bg-slate-900/50 backdrop-blur-xs z-50 hidden transition-opacity opacity-0"></div>
    <div id="wishlist-drawer" class="fixed top-0 right-0 bottom-0 w-full max-w-md bg-white shadow-2xl z-50 transform translate-x-full transition-transform duration-300 flex flex-col">
        <!-- Drawer Header -->
        <div class="p-4 sm:p-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-xl bg-red-50 text-red-500 flex items-center justify-center text-sm shadow-xs border border-red-100">
                    <i class="fa-solid fa-heart"></i>
                </div>
                <div>
                    <h3 class="font-extrabold text-sm text-slate-800">Bộ Sưu Tập Của Bạn</h3>
                    <p class="text-[11px] text-slate-400">Yêu thích & Sản phẩm vừa xem</p>
                </div>
            </div>
            <button type="button" onclick="closeWishlistDrawer()" class="w-8 h-8 rounded-xl hover:bg-slate-200/60 text-slate-400 hover:text-slate-600 flex items-center justify-center transition-colors">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>
        </div>

        <!-- Drawer Tabs -->
        <div class="flex p-2 bg-slate-100/80 border-b border-slate-200/60 text-xs font-semibold gap-1">
            <button 
                type="button" 
                id="tab-btn-wishlist" 
                onclick="switchWishlistTab('wishlist')" 
                class="flex-1 py-2 px-3 rounded-xl bg-white text-slate-800 shadow-xs transition-all flex items-center justify-center gap-1.5"
            >
                <i class="fa-solid fa-heart text-red-500 text-xs"></i>
                <span>Yêu thích</span>
                <span id="drawer-wishlist-count" class="ml-1 px-1.5 py-0.2 bg-red-100 text-red-700 text-[10px] font-bold rounded-full">0</span>
            </button>
            <button 
                type="button" 
                id="tab-btn-recent" 
                onclick="switchWishlistTab('recent')" 
                class="flex-1 py-2 px-3 rounded-xl text-slate-500 hover:text-slate-800 transition-all flex items-center justify-center gap-1.5"
            >
                <i class="fa-solid fa-clock-rotate-left text-blue-500 text-xs"></i>
                <span>Đã xem gần đây</span>
            </button>
        </div>

        <!-- Drawer Content List -->
        <div id="drawer-items-list" class="flex-1 overflow-y-auto p-4 space-y-3">
            <!-- Items injected by JS -->
            <div class="py-12 text-center text-slate-400 text-xs">
                <i class="fa-solid fa-spinner fa-spin text-2xl text-blue-500 mb-2 block"></i>
                Đang tải dữ liệu...
            </div>
        </div>

        <!-- Drawer Footer -->
        <div class="p-4 border-t border-slate-100 bg-slate-50/50 flex items-center justify-between">
            <a href="{{ route('shop.index') }}" class="text-xs font-bold text-blue-600 hover:text-blue-700 flex items-center gap-1">
                <span>Khám phá thêm sản phẩm</span>
                <i class="fa-solid fa-arrow-right text-[10px]"></i>
            </a>
            <a href="{{ route('compare.index') }}" class="text-xs font-semibold text-slate-600 hover:text-slate-900 flex items-center gap-1">
                <i class="fa-solid fa-scale-balanced text-xs text-slate-400"></i>
                <span>Đến So Sánh</span>
            </a>
        </div>
    </div>

    <script>
        let currentDrawerTab = 'wishlist';

        function openWishlistDrawer(tab = 'wishlist') {
            const backdrop = document.getElementById('wishlist-backdrop');
            const drawer = document.getElementById('wishlist-drawer');

            backdrop.classList.remove('hidden');
            setTimeout(() => {
                backdrop.classList.remove('opacity-0');
                drawer.classList.remove('translate-x-full');
            }, 10);

            switchWishlistTab(tab);
        }

        function closeWishlistDrawer() {
            const backdrop = document.getElementById('wishlist-backdrop');
            const drawer = document.getElementById('wishlist-drawer');

            backdrop.classList.add('opacity-0');
            drawer.classList.add('translate-x-full');
            setTimeout(() => {
                backdrop.classList.add('hidden');
            }, 300);
        }

        function switchWishlistTab(tab) {
            currentDrawerTab = tab;
            const tabWishlist = document.getElementById('tab-btn-wishlist');
            const tabRecent = document.getElementById('tab-btn-recent');

            if (tab === 'wishlist') {
                tabWishlist.className = 'flex-1 py-2 px-3 rounded-xl bg-white text-slate-800 shadow-xs transition-all flex items-center justify-center gap-1.5';
                tabRecent.className = 'flex-1 py-2 px-3 rounded-xl text-slate-500 hover:text-slate-800 transition-all flex items-center justify-center gap-1.5';
                loadWishlistItems();
            } else {
                tabRecent.className = 'flex-1 py-2 px-3 rounded-xl bg-white text-slate-800 shadow-xs transition-all flex items-center justify-center gap-1.5';
                tabWishlist.className = 'flex-1 py-2 px-3 rounded-xl text-slate-500 hover:text-slate-800 transition-all flex items-center justify-center gap-1.5';
                loadRecentlyViewedItems();
            }
        }

        async function loadWishlistItems() {
            const listEl = document.getElementById('drawer-items-list');
            listEl.innerHTML = `
                <div class="py-12 text-center text-slate-400 text-xs">
                    <i class="fa-solid fa-circle-notch fa-spin text-2xl text-red-500 mb-2 block"></i>
                    Đang tải danh sách yêu thích...
                </div>
            `;

            try {
                const res = await fetch('/api/wishlist');
                const data = await res.json();
                const items = data.items || [];

                // Cập nhật badge
                const badge = document.getElementById('wishlist-badge');
                const countLabel = document.getElementById('drawer-wishlist-count');
                countLabel.textContent = items.length;
                if (items.length > 0) {
                    badge.textContent = items.length;
                    badge.classList.remove('hidden');
                } else {
                    badge.classList.add('hidden');
                }

                if (items.length === 0) {
                    listEl.innerHTML = `
                        <div class="py-12 text-center text-slate-400 text-xs">
                            <i class="fa-regular fa-heart text-4xl text-slate-300 mb-3 block"></i>
                            <p class="font-bold text-slate-600 mb-1">Chưa có sản phẩm yêu thích</p>
                            <p class="text-slate-400 mb-4">Hãy bấm vào biểu tượng trái tim ở các sản phẩm bạn ưng ý nhé.</p>
                            <a href="/shop" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs transition-colors">
                                Khám phá thiết bị ngay
                            </a>
                        </div>
                    `;
                    return;
                }

                listEl.innerHTML = items.map(item => `
                    <div class="p-3 rounded-2xl border border-slate-100 hover:border-slate-200 bg-white shadow-2xs flex items-center gap-3 transition-all group">
                        <div class="w-16 h-16 rounded-xl bg-slate-50 border border-slate-100 p-1 flex items-center justify-center shrink-0">
                            <img src="${item.image_url}" alt="${item.name}" class="w-full h-full object-contain group-hover:scale-105 transition-transform" onerror="this.src='/images/placeholder.svg'">
                        </div>
                        <div class="flex-1 min-w-0">
                            <span class="text-[10px] font-bold text-blue-600 uppercase">${item.brand || ''}</span>
                            <h4 class="text-xs font-bold text-slate-800 truncate mb-1">${item.name}</h4>
                            <div class="text-xs font-black text-red-600">${item.price}</div>
                            <div class="flex items-center gap-2 mt-2">
                                <a href="/shop?q=${encodeURIComponent(item.name)}" class="text-[11px] font-semibold text-blue-600 hover:underline">
                                    Xem hàng
                                </a>
                                <span class="text-slate-200 text-xs">•</span>
                                <button type="button" onclick="addToCompareFromDrawer(${item.id}, ${item.root_category_id || 0}, '${escapeHtml(item.root_category_name || '')}')" class="text-[11px] text-slate-500 hover:text-slate-800 cursor-pointer">
                                    + So sánh
                                </button>
                            </div>
                        </div>
                        <button 
                            type="button" 
                            onclick="removeWishlistItem(${item.id})" 
                            class="w-7 h-7 rounded-lg hover:bg-red-50 text-slate-300 hover:text-red-500 flex items-center justify-center transition-colors shrink-0" 
                            title="Xóa khỏi yêu thích"
                        >
                            <i class="fa-solid fa-trash-can text-xs"></i>
                        </button>
                    </div>
                `).join('');
            } catch (e) {
                listEl.innerHTML = `<div class="p-6 text-center text-xs text-red-500">Lỗi khi tải danh sách: ${e.message}</div>`;
            }
        }

        async function loadRecentlyViewedItems() {
            const listEl = document.getElementById('drawer-items-list');
            listEl.innerHTML = `
                <div class="py-12 text-center text-slate-400 text-xs">
                    <i class="fa-solid fa-circle-notch fa-spin text-2xl text-blue-500 mb-2 block"></i>
                    Đang tải sản phẩm vừa xem...
                </div>
            `;

            try {
                const res = await fetch('/api/recently-viewed');
                const data = await res.json();
                const items = data.items || [];

                if (items.length === 0) {
                    listEl.innerHTML = `
                        <div class="py-12 text-center text-slate-400 text-xs">
                            <i class="fa-solid fa-clock-rotate-left text-4xl text-slate-300 mb-3 block"></i>
                            <p class="font-bold text-slate-600 mb-1">Chưa có lịch sử xem</p>
                            <p class="text-slate-400 mb-4">Các thiết bị bạn xem qua sẽ tự động lưu lại ở đây.</p>
                            <a href="/shop" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs transition-colors">
                                Khám phá thiết bị ngay
                            </a>
                        </div>
                    `;
                    return;
                }

                listEl.innerHTML = items.map(item => `
                    <div class="p-3 rounded-2xl border border-slate-100 hover:border-slate-200 bg-white shadow-2xs flex items-center gap-3 transition-all group">
                        <div class="w-16 h-16 rounded-xl bg-slate-50 border border-slate-100 p-1 flex items-center justify-center shrink-0">
                            <img src="${item.image_url}" alt="${item.name}" class="w-full h-full object-contain group-hover:scale-105 transition-transform" onerror="this.src='/images/placeholder.svg'">
                        </div>
                        <div class="flex-1 min-w-0">
                            <span class="text-[10px] font-bold text-blue-600 uppercase">${item.brand || ''}</span>
                            <h4 class="text-xs font-bold text-slate-800 truncate mb-1">${item.name}</h4>
                            <div class="text-xs font-black text-red-600">${item.price}</div>
                            <div class="flex items-center gap-2 mt-2">
                                <a href="/shop?q=${encodeURIComponent(item.name)}" class="text-[11px] font-semibold text-blue-600 hover:underline">
                                    Xem hàng
                                </a>
                                <span class="text-slate-200 text-xs">•</span>
                                <button type="button" onclick="addToCompareFromDrawer(${item.id}, ${item.root_category_id || 0}, '${escapeHtml(item.root_category_name || '')}')" class="text-[11px] text-slate-500 hover:text-slate-800 cursor-pointer">
                                    + So sánh
                                </button>
                            </div>
                        </div>
                    </div>
                `).join('');
            } catch (e) {
                listEl.innerHTML = `<div class="p-6 text-center text-xs text-red-500">Lỗi khi tải lịch sử: ${e.message}</div>`;
            }
        }

        async function removeWishlistItem(productId) {
            try {
                const res = await fetch('/api/wishlist/toggle', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                    },
                    body: JSON.stringify({ product_id: productId })
                });
                loadWishlistItems();
            } catch (e) {
                console.error(e);
            }
        }

        function addToCompareFromDrawer(productId, rootCatId, rootCatName) {
            productId = parseInt(productId, 10);
            if (isNaN(productId) || productId <= 0) return;

            let ids = [];
            const compareCatId = localStorage.getItem('smarttech_compare_root_id');
            const compareCatName = localStorage.getItem('smarttech_compare_root_name') || 'khác';

            try {
                ids = JSON.parse(localStorage.getItem('smarttech_compare_ids') || '[]');
                if (!Array.isArray(ids)) ids = [];
            } catch (e) {
                ids = [];
            }

            ids = ids.map(id => parseInt(id, 10)).filter(id => !isNaN(id) && id > 0);

            if (ids.length > 0 && compareCatId && rootCatId && parseInt(compareCatId, 10) !== parseInt(rootCatId, 10)) {
                const confirmChange = confirm(
                    `Bảng so sánh hiện đang có thiết bị thuộc nhóm "${compareCatName}".\n\n` +
                    `Hệ thống chỉ hỗ trợ so sánh giữa các thiết bị cùng nhóm danh mục (ví dụ: Máy tính với Máy tính, Điện thoại với Điện thoại).\n\n` +
                    `Bạn có muốn tạo bảng so sánh mới cho nhóm "${rootCatName}" với thiết bị này không?`
                );

                if (!confirmChange) {
                    return;
                }

                ids = [productId];
                localStorage.setItem('smarttech_compare_ids', JSON.stringify(ids));
                if (rootCatId) {
                    localStorage.setItem('smarttech_compare_root_id', rootCatId);
                    localStorage.setItem('smarttech_compare_root_name', rootCatName);
                }
                window.location.href = `/compare?ids=${productId}`;
                return;
            }

            if (!ids.includes(productId)) {
                if (ids.length >= 3) {
                    alert('Bảng so sánh đã đủ 3 thiết bị. Chuyển sang bảng so sánh để quản lý.');
                    window.location.href = `/compare?ids=${ids.join(',')}`;
                    return;
                }
                ids.push(productId);
                localStorage.setItem('smarttech_compare_ids', JSON.stringify(ids));
                if (rootCatId) {
                    localStorage.setItem('smarttech_compare_root_id', rootCatId);
                    localStorage.setItem('smarttech_compare_root_name', rootCatName);
                }
            }
            window.location.href = `/compare?ids=${ids.join(',')}`;
        }

        // Đóng User Dropdown khi click ra ngoài
        document.addEventListener('click', function(e) {
            const container = document.getElementById('user-menu-container');
            const dropdown = document.getElementById('user-dropdown');
            if (container && dropdown && !container.contains(e.target)) {
                dropdown.classList.add('hidden');
            }
        });

        // Tự động kiểm tra badge yêu thích khi load trang
        document.addEventListener('DOMContentLoaded', function() {
            fetch('/api/wishlist')
                .then(r => r.json())
                .then(data => {
                    const badge = document.getElementById('wishlist-badge');
                    if (data && data.items && data.items.length > 0) {
                        badge.textContent = data.items.length;
                        badge.classList.remove('hidden');
                    }
                })
                .catch(() => {});
        });
    </script>
    @stack('scripts')
</body>
</html>
