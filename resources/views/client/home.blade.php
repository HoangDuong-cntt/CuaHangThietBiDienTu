@extends('layouts.client')

@section('title', 'D&D - Tinh Chọn Công Nghệ, Trọn Vẹn Trải Nghiệm')

@section('content')
<div class="space-y-16 py-6 sm:py-8 bg-white">

    <!-- ==================== 1. HERO SECTION (CHUẨN FORM MẪU) ==================== -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="relative overflow-hidden rounded-3xl bg-[#f4f6f8] border border-slate-200/70 shadow-2xs">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center p-8 sm:p-12 lg:p-16 relative z-10">
                <!-- Left text content -->
                <div class="lg:col-span-7 space-y-6">
                    <div class="text-[11px] font-bold uppercase tracking-wider text-blue-600">
                        CÔNG NGHỆ TINH GỌN. TRẢI NGHIỆM TRỌN VẸN.
                    </div>

                    <h1 class="text-4xl sm:text-5xl lg:text-[54px] font-black tracking-tight leading-[1.12] text-slate-900">
                        Nâng tầm công việc.<br>
                        Khởi nguồn cảm hứng.
                    </h1>

                    <p class="text-sm sm:text-base text-slate-500 max-w-md leading-relaxed font-normal">
                        Thiết bị bạn yêu. Hiệu năng bạn cần.<br class="hidden sm:inline">Khám phá lựa chọn phù hợp cho mỗi ngày.
                    </p>

                    <div class="flex flex-wrap items-center gap-3 pt-2">
                        <a 
                            href="{{ route('shop.index') }}" 
                            class="px-6 py-3 rounded-lg bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold transition-all shadow-md shadow-blue-600/20 flex items-center gap-2 hover:-translate-y-0.5"
                        >
                            <span>Khám phá sản phẩm</span>
                            <span class="text-xs">&rarr;</span>
                        </a>

                        <button 
                            type="button" 
                            onclick="window.SmartTechAI && window.SmartTechAI.openChat();"
                            class="px-5 py-3 rounded-lg bg-white hover:bg-slate-50 text-slate-700 text-xs font-bold border border-slate-200 shadow-2xs transition-colors flex items-center gap-1.5 cursor-pointer hover:-translate-y-0.5"
                        >
                            <span>Tư vấn chọn máy</span>
                        </button>
                    </div>

                    <!-- Price highlight -->
                    <div class="pt-6 border-t border-slate-200/80">
                        <div class="text-xs text-slate-400">
                            Từ <strong class="text-slate-900 text-base font-black">18.990.000đ</strong>
                        </div>
                        <div class="text-[11px] text-slate-400 mt-0.5">
                            Trả góp 0% &bull; Bảo hành chính hãng
                        </div>
                    </div>
                </div>

                <!-- Right visual element with floating AI badge -->
                <div class="lg:col-span-5 relative">
                    <div class="relative mx-auto max-w-md">
                        <div class="rounded-2xl overflow-hidden bg-white/60 p-4 border border-white/80 shadow-md">
                            <img 
                                src="/storage/products/macbook-pro-14-inch-m4-pro-main.jpg" 
                                alt="Laptop tinh tế" 
                                class="w-full h-64 sm:h-72 object-contain"
                                onerror="this.src='/images/placeholder.svg'"
                            >
                            
                            <!-- Floating AI pill card on bottom right -->
                            <div class="mt-2 p-3 rounded-2xl bg-white/95 backdrop-blur-md border border-slate-100 shadow-md flex items-center gap-3">
                                <div class="w-8 h-8 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                                    <i class="fa-solid fa-wand-magic-sparkles text-xs"></i>
                                </div>
                                <div class="text-xs">
                                    <div class="font-bold text-slate-900">Lựa chọn thông minh hơn</div>
                                    <div class="text-slate-400 text-[11px]">Có trợ lý AI đồng hành cùng bạn</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ==================== 2. VALUE PROPOSITIONS (4 USPs) ==================== -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 py-4">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center shrink-0 text-sm">
                    <i class="fa-solid fa-shield-check"></i>
                </div>
                <div>
                    <div class="font-bold text-slate-900 text-xs">100% chính hãng</div>
                    <div class="text-[11px] text-slate-400">An tâm từ lần chạm đầu tiên</div>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center shrink-0 text-sm">
                    <i class="fa-solid fa-truck-fast"></i>
                </div>
                <div>
                    <div class="font-bold text-slate-900 text-xs">Giao nhanh toàn quốc</div>
                    <div class="text-[11px] text-slate-400">Miễn phí với đơn từ 2 triệu</div>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center shrink-0 text-sm">
                    <i class="fa-solid fa-rotate-left"></i>
                </div>
                <div>
                    <div class="font-bold text-slate-900 text-xs">Đổi mới 30 ngày</div>
                    <div class="text-[11px] text-slate-400">Nếu có lỗi từ nhà sản xuất</div>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center shrink-0 text-sm">
                    <i class="fa-solid fa-headset"></i>
                </div>
                <div>
                    <div class="font-bold text-slate-900 text-xs">Tư vấn tận tâm</div>
                    <div class="text-[11px] text-slate-400">Đúng nhu cầu, đúng ngân sách</div>
                </div>
            </div>
        </div>
    </section>

    <!-- ==================== 3. BẠN ĐANG TÌM GÌ? (6 CATEGORIES) ==================== -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-6">
            <h2 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">Bạn đang tìm gì?</h2>
            <p class="text-xs text-slate-400">Công nghệ phù hợp với mọi nhu cầu.</p>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
            <!-- Máy tính xách tay -->
            <a 
                href="{{ route('shop.index', ['category' => 'laptop']) }}"
                class="group p-5 rounded-2xl bg-white hover:bg-slate-50 border border-slate-200/80 hover:border-blue-400 shadow-2xs hover:shadow-md transition-all flex flex-col items-center text-center hover:-translate-y-1"
            >
                <div class="w-12 h-12 rounded-xl bg-slate-50 group-hover:bg-blue-600 text-slate-600 group-hover:text-white flex items-center justify-center text-xl mb-3 transition-colors">
                    <i class="fa-solid fa-laptop"></i>
                </div>
                <h3 class="font-bold text-xs text-slate-800 group-hover:text-blue-600 transition-colors">
                    Máy tính xách tay
                </h3>
            </a>

            <!-- Điện thoại -->
            <a 
                href="{{ route('shop.index', ['category' => 'dien-thoai']) }}"
                class="group p-5 rounded-2xl bg-white hover:bg-slate-50 border border-slate-200/80 hover:border-blue-400 shadow-2xs hover:shadow-md transition-all flex flex-col items-center text-center hover:-translate-y-1"
            >
                <div class="w-12 h-12 rounded-xl bg-slate-50 group-hover:bg-blue-600 text-slate-600 group-hover:text-white flex items-center justify-center text-xl mb-3 transition-colors">
                    <i class="fa-solid fa-mobile-screen-button"></i>
                </div>
                <h3 class="font-bold text-xs text-slate-800 group-hover:text-blue-600 transition-colors">
                    Điện thoại
                </h3>
            </a>

            <!-- Máy tính bảng -->
            <a 
                href="{{ route('shop.index', ['category' => 'may-tinh-bang']) }}"
                class="group p-5 rounded-2xl bg-white hover:bg-slate-50 border border-slate-200/80 hover:border-blue-400 shadow-2xs hover:shadow-md transition-all flex flex-col items-center text-center hover:-translate-y-1"
            >
                <div class="w-12 h-12 rounded-xl bg-slate-50 group-hover:bg-blue-600 text-slate-600 group-hover:text-white flex items-center justify-center text-xl mb-3 transition-colors">
                    <i class="fa-solid fa-tablet-screen-button"></i>
                </div>
                <h3 class="font-bold text-xs text-slate-800 group-hover:text-blue-600 transition-colors">
                    Máy tính bảng
                </h3>
            </a>

            <!-- Màn hình -->
            <a 
                href="{{ route('shop.index', ['category' => 'laptop-do-hoa']) }}"
                class="group p-5 rounded-2xl bg-white hover:bg-slate-50 border border-slate-200/80 hover:border-blue-400 shadow-2xs hover:shadow-md transition-all flex flex-col items-center text-center hover:-translate-y-1"
            >
                <div class="w-12 h-12 rounded-xl bg-slate-50 group-hover:bg-blue-600 text-slate-600 group-hover:text-white flex items-center justify-center text-xl mb-3 transition-colors">
                    <i class="fa-solid fa-desktop"></i>
                </div>
                <h3 class="font-bold text-xs text-slate-800 group-hover:text-blue-600 transition-colors">
                    Màn hình
                </h3>
            </a>

            <!-- Âm thanh -->
            <a 
                href="{{ route('shop.index', ['category' => 'am-thanh']) }}"
                class="group p-5 rounded-2xl bg-white hover:bg-slate-50 border border-slate-200/80 hover:border-blue-400 shadow-2xs hover:shadow-md transition-all flex flex-col items-center text-center hover:-translate-y-1"
            >
                <div class="w-12 h-12 rounded-xl bg-slate-50 group-hover:bg-blue-600 text-slate-600 group-hover:text-white flex items-center justify-center text-xl mb-3 transition-colors">
                    <i class="fa-solid fa-headphones"></i>
                </div>
                <h3 class="font-bold text-xs text-slate-800 group-hover:text-blue-600 transition-colors">
                    Âm thanh
                </h3>
            </a>

            <!-- Phụ kiện -->
            <a 
                href="{{ route('shop.index', ['category' => 'phu-kien']) }}"
                class="group p-5 rounded-2xl bg-white hover:bg-slate-50 border border-slate-200/80 hover:border-blue-400 shadow-2xs hover:shadow-md transition-all flex flex-col items-center text-center hover:-translate-y-1"
            >
                <div class="w-12 h-12 rounded-xl bg-slate-50 group-hover:bg-blue-600 text-slate-600 group-hover:text-white flex items-center justify-center text-xl mb-3 transition-colors">
                    <i class="fa-solid fa-computer-mouse"></i>
                </div>
                <h3 class="font-bold text-xs text-slate-800 group-hover:text-blue-600 transition-colors">
                    Phụ kiện
                </h3>
            </a>
        </div>
    </section>

    <!-- ==================== 4. ĐƯỢC YÊU THÍCH. ĐÁNG ĐỂ SỞ HỮU. ==================== -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-6">
            <div>
                <h2 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">Được yêu thích. Đáng để sở hữu.</h2>
                <p class="text-xs text-slate-400">Những lựa chọn nổi bật, sẵn sàng cho hành trình mới của bạn.</p>
            </div>
            <a href="{{ route('shop.index') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-700 flex items-center gap-1 group">
                <span>Xem tất cả sản phẩm</span>
                <span class="text-xs group-hover:translate-x-0.5 transition-transform">&rarr;</span>
            </a>
        </div>

        <!-- Filter Tabs matching screenshot: Nổi bật | Học tập & làm việc | Sáng tạo nội dung | Giải trí -->
        <div class="flex items-center gap-2 overflow-x-auto pb-2 scrollbar-none mb-6">
            <button 
                type="button" 
                onclick="switchFeaturedTab('featured', this)"
                class="tab-btn px-4 py-1.5 rounded-lg text-xs font-semibold transition-all bg-slate-900 text-white shadow-xs cursor-pointer"
                data-tab="featured"
            >
                Nổi bật
            </button>
            <button 
                type="button" 
                onclick="switchFeaturedTab('study', this)"
                class="tab-btn px-4 py-1.5 rounded-lg text-xs font-semibold transition-all bg-slate-100 hover:bg-slate-200 text-slate-600 cursor-pointer"
                data-tab="study"
            >
                Học tập &amp; làm việc
            </button>
            <button 
                type="button" 
                onclick="switchFeaturedTab('creative', this)"
                class="tab-btn px-4 py-1.5 rounded-lg text-xs font-semibold transition-all bg-slate-100 hover:bg-slate-200 text-slate-600 cursor-pointer"
                data-tab="creative"
            >
                Sáng tạo nội dung
            </button>
            <button 
                type="button" 
                onclick="switchFeaturedTab('entertainment', this)"
                class="tab-btn px-4 py-1.5 rounded-lg text-xs font-semibold transition-all bg-slate-100 hover:bg-slate-200 text-slate-600 cursor-pointer"
                data-tab="entertainment"
            >
                Giải trí
            </button>
        </div>

        <!-- Featured Products Grid (4 columns) -->
        <div id="featured-products-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            @php
                $badges = ['Bán chạy', 'Tiết kiệm 3 triệu', 'Giảm 18%', 'Lựa chọn của D&D'];
                $badgeColors = [
                    'bg-blue-50 text-blue-600 border-blue-100',
                    'bg-amber-50 text-amber-700 border-amber-100',
                    'bg-orange-50 text-orange-600 border-orange-100',
                    'bg-teal-50 text-teal-700 border-teal-100',
                ];
            @endphp

            @foreach($featuredProducts as $index => $product)
            @php
                $badgeText = $badges[$index % count($badges)];
                $badgeColor = $badgeColors[$index % count($badgeColors)];
            @endphp
            <div class="group relative rounded-2xl bg-white p-4 border border-slate-200/80 hover:border-slate-300 hover:shadow-lg transition-all flex flex-col justify-between">
                
                <!-- Badge on top-left -->
                <div class="absolute top-4 left-4 z-10">
                    <span class="px-2 py-0.5 rounded text-[10px] font-bold border {{ $badgeColor }}">
                        {{ $badgeText }}
                    </span>
                </div>

                <!-- Product Thumbnail linked to PDP -->
                <a href="{{ route('products.show', $product['slug']) }}" class="w-full h-44 rounded-xl bg-[#f8fafc] p-3 flex items-center justify-center overflow-hidden mb-3 block">
                    <img 
                        src="{{ $product['image_url'] }}" 
                        alt="{{ $product['name'] }}" 
                        class="w-full h-full object-contain group-hover:scale-105 transition-transform duration-300"
                        onerror="this.src='/images/placeholder.svg'"
                    >
                </a>

                <!-- Info -->
                <div class="space-y-1.5 flex-1">
                    <div class="flex items-center gap-1.5 text-[11px] text-amber-500 font-semibold">
                        <i class="fa-solid fa-star text-[10px]"></i>
                        <span>4.9 (128 đánh giá)</span>
                    </div>

                    <a href="{{ route('products.show', $product['slug']) }}" class="block">
                        <h3 class="font-bold text-sm text-slate-900 group-hover:text-blue-600 transition-colors line-clamp-1 leading-snug">
                            {{ $product['name'] }}
                        </h3>
                    </a>

                    <!-- Specs details matching screenshot: 16GB bộ nhớ · 512GB lưu trữ -->
                    <div class="text-[11px] text-slate-400 font-medium truncate">
                        @if(!empty($product['screen_size']))
                            <span>{{ $product['screen_size'] }}" &bull; </span>
                        @endif
                        @if(!empty($product['weight_grams']))
                            <span>{{ $product['weight_grams'] < 1000 ? $product['weight_grams'].'g' : number_format($product['weight_grams']/1000, 1).'kg' }} &bull; </span>
                        @endif
                        <span>Chính hãng 100%</span>
                    </div>
                </div>

                <!-- Price & Action -->
                <div class="pt-3 mt-3 border-t border-slate-100">
                    <div class="flex items-baseline justify-between gap-2 mb-3">
                        <div>
                            <span class="text-sm font-extrabold text-slate-900">
                                {{ $product['price_formatted'] }}
                            </span>
                            @if($product['is_on_sale'])
                                <span class="text-[11px] text-slate-400 line-through ml-1.5">
                                    {{ $product['base_price_formatted'] }}
                                </span>
                            @endif
                        </div>
                        <span class="text-[10px] text-emerald-600 font-medium flex items-center gap-1">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Còn hàng
                        </span>
                    </div>

                    <!-- Button: Thêm vào giỏ + (kết nối AJAX không reload trang) -->
                    <button 
                        type="button"
                        onclick="addToCart({{ $product['id'] }}, 1)"
                        class="w-full py-2 px-3 rounded-lg bg-blue-50 hover:bg-blue-600 text-blue-600 hover:text-white text-xs font-bold transition-all text-center flex items-center justify-center gap-1 cursor-pointer"
                    >
                        <span>Thêm vào giỏ</span>
                        <span class="font-normal text-sm leading-none">+</span>
                    </button>
                </div>
            </div>
            @endforeach
        </div>
    </section>

    <!-- ==================== 5. TRỢ LÝ AI (KHÔNG RÀNH CÔNG NGHỆ? ĐÃ CÓ TINH HIỂU BẠN) ==================== -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="rounded-3xl bg-[#f0f3ff] p-8 sm:p-12 border border-blue-100/70 shadow-2xs relative overflow-hidden">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center relative z-10">
                <!-- Left promo -->
                <div class="lg:col-span-6 space-y-4">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-blue-100/80 text-blue-700 font-bold text-[10px] tracking-wider uppercase">
                        <i class="fa-solid fa-sparkles text-[10px]"></i>
                        TRỢ LÝ AI CỦA D&D
                    </span>

                    <h2 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight leading-tight">
                        Không rành công nghệ?<br>
                        Đã có D&D hiểu bạn.
                    </h2>

                    <p class="text-xs sm:text-sm text-slate-500 leading-relaxed max-w-md">
                        Chia sẻ nhu cầu và ngân sách. Trợ lý AI sẽ giúp bạn tìm thiết bị phù hợp, chỉ trong vài phút.
                    </p>

                    <div class="pt-2">
                        <button 
                            type="button" 
                            onclick="window.SmartTechAI && window.SmartTechAI.openChat();"
                            class="px-5 py-2.5 rounded-lg bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold shadow-md shadow-blue-600/20 transition-all flex items-center gap-2 cursor-pointer"
                        >
                            <span>Tìm thiết bị phù hợp</span>
                            <span class="text-xs">&rarr;</span>
                        </button>
                    </div>
                </div>

                <!-- Right interactive suggestion card matching screenshot chat simulation -->
                <div class="lg:col-span-6">
                    <div class="rounded-2xl bg-white p-5 border border-slate-100 shadow-sm space-y-3.5 max-w-md ml-auto">
                        <div class="flex items-center gap-1.5 text-emerald-600 font-semibold text-[11px]">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                            <span>Luôn sẵn sàng lắng nghe</span>
                        </div>

                        <!-- User query bubble -->
                        <div class="p-3 rounded-xl bg-slate-100 text-xs text-slate-800 font-medium">
                            Tôi cần máy tính học thiết kế, ngân sách 25 triệu.
                        </div>

                        <!-- AI Response bubble -->
                        <div class="p-3 rounded-xl bg-blue-50/70 border border-blue-100/50 text-xs text-slate-800 space-y-2.5">
                            <div class="flex items-start gap-1.5">
                                <span class="text-blue-600 font-bold text-xs mt-0.5">✦</span>
                                <p class="text-[11px] leading-relaxed text-slate-600">
                                    Mình gợi ý máy có bộ nhớ từ 16GB và màn hình chuẩn màu để bạn sáng tạo thoải mái hơn.
                                </p>
                            </div>

                            <!-- Clickable Filter Chips (Kết nối trực tiếp vào bộ lọc shop) -->
                            <div class="flex flex-wrap gap-1.5 pt-0.5">
                                <a 
                                    href="{{ route('shop.index', ['category' => 'laptop-do-hoa']) }}" 
                                    class="px-2.5 py-1 rounded-md bg-white hover:bg-blue-600 hover:text-white text-slate-600 font-medium text-[10px] border border-slate-200 transition-colors shadow-2xs"
                                >
                                    Màn hình chuẩn màu
                                </a>

                                <a 
                                    href="{{ route('shop.index', ['category' => 'laptop']) }}" 
                                    class="px-2.5 py-1 rounded-md bg-white hover:bg-blue-600 hover:text-white text-slate-600 font-medium text-[10px] border border-slate-200 transition-colors shadow-2xs"
                                >
                                    Bộ nhớ 16GB
                                </a>

                                <a 
                                    href="{{ route('shop.index', ['weight' => 'ultralight']) }}" 
                                    class="px-2.5 py-1 rounded-md bg-white hover:bg-blue-600 hover:text-white text-slate-600 font-medium text-[10px] border border-slate-200 transition-colors shadow-2xs"
                                >
                                    Mỏng nhẹ
                                </a>
                            </div>
                        </div>

                        <div class="pt-1 text-right">
                            <a href="{{ route('shop.index', ['category' => 'laptop-do-hoa']) }}" class="text-xs font-semibold text-blue-600 hover:text-blue-700 flex items-center justify-end gap-1">
                                <span>Khám phá 3 lựa chọn dành cho bạn</span>
                                <span class="text-xs">&rarr;</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ==================== 6. MỘT CHÚT CẢM HỨNG CÔNG NGHỆ (GÓC CÔNG NGHỆ) ==================== -->
    <section id="goc-cong-nghe" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-6">
            <div>
                <h2 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">Một chút cảm hứng công nghệ.</h2>
                <p class="text-xs text-slate-400">Gợi ý hữu ích để bạn chọn đúng và dùng thích hơn.</p>
            </div>
            <a href="{{ route('shop.index') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-700 flex items-center gap-1 group">
                <span>Khám phá góc công nghệ</span>
                <span class="text-xs group-hover:translate-x-0.5 transition-transform">&nearr;</span>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Article 1: Gọn một góc bàn -->
            <a href="{{ route('shop.index') }}" class="rounded-2xl bg-white border border-slate-200/80 overflow-hidden shadow-2xs hover:shadow-md transition-all group block">
                <div class="h-44 bg-[#f8fafc] overflow-hidden relative p-4 flex items-center justify-center">
                    <img 
                        src="/storage/products/macbook-air-13-inch-m4-2025-main.jpg" 
                        alt="Góc làm việc" 
                        class="w-full h-full object-contain group-hover:scale-105 transition-transform duration-300"
                        onerror="this.src='/images/placeholder.svg'"
                    >
                </div>
                <div class="p-5 space-y-2">
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">
                        GÓC LÀM VIỆC
                    </span>
                    <h3 class="font-bold text-sm text-slate-900 group-hover:text-blue-600 transition-colors leading-snug">
                        Gọn một góc bàn.<br>Rộng một khoảng sáng tạo.
                    </h3>
                    <div class="pt-2 text-[11px] text-slate-400 font-medium flex items-center justify-between">
                        <span>5 phút đọc &bull; Cảm hứng mỗi ngày</span>
                        <span>&nearr;</span>
                    </div>
                </div>
            </a>

            <!-- Article 2: Chọn máy tính đầu tiên -->
            <a href="{{ route('shop.index') }}" class="rounded-2xl bg-white border border-slate-200/80 overflow-hidden shadow-2xs hover:shadow-md transition-all group block">
                <div class="h-44 bg-[#f8fafc] overflow-hidden relative p-4 flex items-center justify-center">
                    <img 
                        src="/storage/products/asus-expertbook-b9-oled-sieu-nhe-990g-main.jpg" 
                        alt="Chọn máy tính" 
                        class="w-full h-full object-contain group-hover:scale-105 transition-transform duration-300"
                        onerror="this.src='/images/placeholder.svg'"
                    >
                </div>
                <div class="p-5 space-y-2">
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">
                        TƯ VẤN CHỌN MUA
                    </span>
                    <h3 class="font-bold text-sm text-slate-900 group-hover:text-blue-600 transition-colors leading-snug">
                        Chọn máy tính đầu tiên:<br>Bắt đầu từ điều bạn cần.
                    </h3>
                    <div class="pt-2 text-[11px] text-slate-400 font-medium flex items-center justify-between">
                        <span>7 phút đọc &bull; Hướng dẫn chọn mua</span>
                        <span>&nearr;</span>
                    </div>
                </div>
            </a>

            <!-- Article 3: Pin bền hơn -->
            <a href="{{ route('shop.index') }}" class="rounded-2xl bg-white border border-slate-200/80 overflow-hidden shadow-2xs hover:shadow-md transition-all group block">
                <div class="h-44 bg-[#f8fafc] overflow-hidden relative p-4 flex items-center justify-center">
                    <img 
                        src="/storage/products/airpods-max-usb-c-2024-main.jpg" 
                        alt="Pin bền" 
                        class="w-full h-full object-contain group-hover:scale-105 transition-transform duration-300"
                        onerror="this.src='/images/placeholder.svg'"
                    >
                </div>
                <div class="p-5 space-y-2">
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">
                        MẸO HAY MỖI NGÀY
                    </span>
                    <h3 class="font-bold text-sm text-slate-900 group-hover:text-blue-600 transition-colors leading-snug">
                        Pin bền hơn từ những<br>thói quen nhỏ mỗi ngày.
                    </h3>
                    <div class="pt-2 text-[11px] text-slate-400 font-medium flex items-center justify-between">
                        <span>4 phút đọc &bull; Sử dụng thông minh</span>
                        <span>&nearr;</span>
                    </div>
                </div>
            </a>
        </div>
    </section>

    <!-- ==================== 7. ĐIỀU HAY TIẾP THEO, GỬI RIÊNG BẠN (NEWSLETTER) ==================== -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="rounded-2xl bg-[#f8fafc] border border-slate-200/60 p-8 sm:p-10 flex flex-col md:flex-row items-center justify-between gap-6">
            <div class="space-y-1 text-center md:text-left">
                <h3 class="text-lg font-bold text-slate-900 tracking-tight">Điều hay tiếp theo, gửi riêng bạn.</h3>
                <p class="text-xs text-slate-400">Nhận ưu đãi mới và những gợi ý công nghệ đáng đọc. Vừa đủ hữu ích, không làm phiền.</p>
            </div>

            <div class="w-full md:w-auto max-w-md shrink-0">
                <form onsubmit="event.preventDefault(); window.showToast('Cảm ơn bạn đã đăng ký nhận tin từ D&D.!'); this.reset();" class="flex flex-col sm:flex-row gap-2">
                    <input 
                        type="email" 
                        placeholder="Địa chỉ thư điện tử của bạn" 
                        required
                        class="px-4 py-2.5 rounded-xl bg-white border border-slate-200 text-slate-800 placeholder-slate-400 text-xs focus:outline-hidden focus:border-blue-500 w-full sm:w-64"
                    >
                    <button 
                        type="submit" 
                        class="px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold transition-colors whitespace-nowrap shadow-xs cursor-pointer flex items-center justify-center gap-1.5"
                    >
                        <span>Đăng ký nhận tin</span>
                        <span class="text-xs">&rarr;</span>
                    </button>
                </form>
                <p class="text-[10px] text-slate-400 mt-2 text-center md:text-left">Bằng việc đăng ký, bạn đồng ý với Chính sách bảo mật của D&D</p>
            </div>
        </div>
    </section>

</div>

@push('scripts')
<script>
async function switchFeaturedTab(tabName, btn) {
    // 1. Cập nhật giao diện nút tab
    document.querySelectorAll('.tab-btn').forEach(b => {
        b.className = 'tab-btn px-4 py-1.5 rounded-lg text-xs font-semibold transition-all bg-slate-100 hover:bg-slate-200 text-slate-600 cursor-pointer';
    });
    btn.className = 'tab-btn px-4 py-1.5 rounded-lg text-xs font-semibold transition-all bg-slate-900 text-white shadow-xs cursor-pointer';

    // 2. Loading skeleton
    const grid = document.getElementById('featured-products-grid');
    grid.innerHTML = Array(4).fill(0).map(() => `
        <div class="rounded-2xl bg-white p-4 border border-slate-200/80 animate-pulse space-y-3">
            <div class="w-full h-44 bg-slate-100 rounded-xl"></div>
            <div class="h-3 bg-slate-100 rounded-sm w-1/3"></div>
            <div class="h-4 bg-slate-100 rounded-sm w-3/4"></div>
            <div class="h-4 bg-slate-100 rounded-sm w-1/2"></div>
        </div>
    `).join('');

    const badges = ['Bán chạy', 'Tiết kiệm 3 triệu', 'Giảm 18%', 'Lựa chọn của D&D'];
    const badgeColors = [
        'bg-blue-50 text-blue-600 border-blue-100',
        'bg-amber-50 text-amber-700 border-amber-100',
        'bg-orange-50 text-orange-600 border-orange-100',
        'bg-teal-50 text-teal-700 border-teal-100',
    ];

    // 3. Gọi API lấy sản phẩm theo tab
    try {
        const res = await fetch(`/api/products/featured-tabs?tab=${encodeURIComponent(tabName)}`);
        const data = await res.json();

        if (data.success && data.products) {
            grid.innerHTML = data.products.map((p, idx) => `
                <div class="group relative rounded-2xl bg-white p-4 border border-slate-200/80 hover:border-slate-300 hover:shadow-lg transition-all flex flex-col justify-between">
                    <div class="absolute top-4 left-4 z-10">
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold border ${badgeColors[idx % badgeColors.length]}">
                            ${badges[idx % badges.length]}
                        </span>
                    </div>

                    <a href="/san-pham/${p.slug}" class="w-full h-44 rounded-xl bg-[#f8fafc] p-3 flex items-center justify-center overflow-hidden mb-3 block">
                        <img 
                            src="${p.image_url}" 
                            alt="${p.name}" 
                            class="w-full h-full object-contain group-hover:scale-105 transition-transform duration-300"
                            onerror="this.src='/images/placeholder.svg'"
                        >
                    </a>

                    <div class="space-y-1.5 flex-1">
                        <div class="flex items-center gap-1.5 text-[11px] text-amber-500 font-semibold">
                            <i class="fa-solid fa-star text-[10px]"></i>
                            <span>4.9 (128 đánh giá)</span>
                        </div>

                        <a href="/san-pham/${p.slug}" class="block">
                            <h3 class="font-bold text-sm text-slate-900 group-hover:text-blue-600 transition-colors line-clamp-1 leading-snug">
                                ${p.name}
                            </h3>
                        </a>

                        <div class="text-[11px] text-slate-400 font-medium truncate">
                            ${p.screen_size ? `<span>${p.screen_size}" &bull; </span>` : ''}
                            ${p.weight_grams ? `<span>${p.weight_grams < 1000 ? p.weight_grams + 'g' : (p.weight_grams/1000).toFixed(1) + 'kg'} &bull; </span>` : ''}
                            <span>Chính hãng 100%</span>
                        </div>
                    </div>

                    <div class="pt-3 mt-3 border-t border-slate-100">
                        <div class="flex items-baseline justify-between gap-2 mb-3">
                            <div>
                                <span class="text-sm font-extrabold text-slate-900">${p.price_formatted}</span>
                                ${p.is_on_sale ? `<span class="text-[11px] text-slate-400 line-through ml-1.5">${p.base_price_formatted}</span>` : ''}
                            </div>
                            <span class="text-[10px] text-emerald-600 font-medium flex items-center gap-1">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Còn hàng
                            </span>
                        </div>

                        <button 
                            type="button"
                            onclick="addToCart(${p.id}, 1)"
                            class="w-full py-2 px-3 rounded-lg bg-blue-50 hover:bg-blue-600 text-blue-600 hover:text-white text-xs font-bold transition-all text-center flex items-center justify-center gap-1 cursor-pointer"
                        >
                            <span>Thêm vào giỏ</span>
                            <span class="font-normal text-sm leading-none">+</span>
                        </button>
                    </div>
                </div>
            `).join('');
        }
    } catch (e) {
        console.error('Lỗi khi chuyển tab sản phẩm:', e);
    }
}
</script>
@endpush
@endsection
