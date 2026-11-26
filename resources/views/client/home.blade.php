@extends('layouts.client')

@section('title', 'tinh. - Tinh Chọn Công Nghệ, Trọn Vẹn Trải Nghiệm')

@section('content')
<div class="space-y-16 py-6 sm:py-10">

    <!-- ==================== 1. HERO BANNER ==================== -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-slate-900 via-slate-800 to-indigo-950 text-white shadow-xl">
            <!-- Decorative background glow -->
            <div class="absolute -top-24 -left-24 w-96 h-96 bg-blue-500/20 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-24 -right-24 w-96 h-96 bg-indigo-500/20 rounded-full blur-3xl pointer-events-none"></div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center p-8 sm:p-12 lg:p-16 relative z-10">
                <!-- Left text content -->
                <div class="lg:col-span-7 space-y-6">
                    <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white/10 backdrop-blur-md border border-white/15 text-[11px] font-bold uppercase tracking-widest text-blue-300">
                        <span class="w-2 h-2 rounded-full bg-blue-400 animate-pulse"></span>
                        <span>Công nghệ trọn vẹn. Trải nghiệm trọn vẹn.</span>
                    </div>

                    <h1 class="text-3xl sm:text-5xl lg:text-6xl font-black tracking-tight leading-tight sm:leading-tight">
                        Nâng tầm công việc.<br>
                        <span class="bg-gradient-to-r from-blue-300 via-indigo-200 to-white bg-clip-text text-transparent">Khởi nguồn cảm hứng.</span>
                    </h1>

                    <p class="text-sm sm:text-base text-slate-300 max-w-xl leading-relaxed font-normal">
                        Thiết bị bạn yêu. Hiệu năng bạn cần. Khám phá hệ sinh thái máy tính, điện thoại và phụ kiện tuyển chọn tinh tế cho mỗi ngày của bạn.
                    </p>

                    <div class="flex flex-wrap items-center gap-3 pt-2">
                        <a 
                            href="{{ route('shop.index') }}" 
                            class="px-6 py-3.5 rounded-2xl bg-blue-600 hover:bg-blue-500 text-white text-sm font-bold shadow-lg shadow-blue-600/30 transition-all flex items-center gap-2 hover:-translate-y-0.5"
                        >
                            <span>Khám phá sản phẩm</span>
                            <i class="fa-solid fa-arrow-right text-xs"></i>
                        </a>

                        <button 
                            type="button" 
                            onclick="window.SmartTechAI && window.SmartTechAI.openChat();"
                            class="px-6 py-3.5 rounded-2xl bg-white/10 hover:bg-white/15 backdrop-blur-md border border-white/20 text-white text-sm font-bold transition-all flex items-center gap-2 hover:-translate-y-0.5"
                        >
                            <i class="fa-solid fa-sparkles text-amber-300 text-xs"></i>
                            <span>Tư vấn chọn máy</span>
                        </button>
                    </div>

                    <!-- Price highlight -->
                    <div class="pt-4 border-t border-white/10 flex items-center gap-4 text-xs text-slate-300">
                        <div>
                            <span class="text-slate-400 block text-[11px]">Chỉ từ</span>
                            <span class="text-lg font-black text-white">18.990.000 đ</span>
                        </div>
                        <div class="h-8 w-px bg-white/15"></div>
                        <div class="space-y-0.5 text-[11px] text-slate-300">
                            <div class="flex items-center gap-1.5">
                                <i class="fa-solid fa-check text-emerald-400"></i>
                                <span>Trả góp 0% lãi suất</span>
                            </div>
                            <div class="flex items-center gap-1.5">
                                <i class="fa-solid fa-shield-halved text-blue-400"></i>
                                <span>Bảo hành chính hãng 12-24 tháng</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right visual element -->
                <div class="lg:col-span-5 relative">
                    <div class="relative mx-auto max-w-md">
                        <div class="rounded-3xl bg-gradient-to-tr from-white/10 to-white/5 p-6 backdrop-blur-md border border-white/15 shadow-2xl">
                            <img 
                                src="/uploads/products/macbook-air-13-inch-m4-2025-v1-main.jpg" 
                                alt="Laptop công nghệ" 
                                class="w-full h-64 sm:h-72 object-contain drop-shadow-2xl"
                                onerror="this.src='/images/placeholder.svg'"
                            >
                            
                            <!-- Floating tag -->
                            <div class="mt-4 p-3.5 rounded-2xl bg-white/10 backdrop-blur-md border border-white/15 flex items-center gap-3">
                                <div class="w-9 h-9 rounded-xl bg-blue-500/20 text-blue-300 flex items-center justify-center shrink-0">
                                    <i class="fa-solid fa-wand-magic-sparkles text-sm"></i>
                                </div>
                                <div class="text-xs">
                                    <div class="font-bold text-white">Lựa chọn thông minh</div>
                                    <div class="text-slate-300 text-[11px]">Cùng bạn bứt phá phong cách và hiệu suất</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Trust badges bar -->
            <div class="border-t border-white/10 bg-white/5 backdrop-blur-md px-6 py-4 grid grid-cols-2 md:grid-cols-4 gap-4 text-xs text-slate-300">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-xl bg-white/10 flex items-center justify-center text-blue-400 shrink-0">
                        <i class="fa-solid fa-shield-check"></i>
                    </div>
                    <div>
                        <div class="font-bold text-white text-[12px]">100% chính hãng</div>
                        <div class="text-[10px] text-slate-400">Từ nhà sản xuất hàng đầu</div>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-xl bg-white/10 flex items-center justify-center text-emerald-400 shrink-0">
                        <i class="fa-solid fa-truck-fast"></i>
                    </div>
                    <div>
                        <div class="font-bold text-white text-[12px]">Giao nhanh toàn quốc</div>
                        <div class="text-[10px] text-slate-400">Miễn phí ship đơn từ 2 triệu</div>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-xl bg-white/10 flex items-center justify-center text-amber-400 shrink-0">
                        <i class="fa-solid fa-rotate-left"></i>
                    </div>
                    <div>
                        <div class="font-bold text-white text-[12px]">Đổi mới trong 30 ngày</div>
                        <div class="text-[10px] text-slate-400">Nếu có lỗi phần cứng</div>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-xl bg-white/10 flex items-center justify-center text-indigo-400 shrink-0">
                        <i class="fa-solid fa-headset"></i>
                    </div>
                    <div>
                        <div class="font-bold text-white text-[12px]">Tư vấn AI tận tâm</div>
                        <div class="text-[10px] text-slate-400">Đúng nhu cầu, đúng ngân sách</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ==================== 2. BẠN ĐANG TÌM GÌ? (CATEGORY FILTERS) ==================== -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-6">
            <h2 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">Bạn đang tìm gì?</h2>
            <p class="text-xs sm:text-sm text-slate-500">Công nghệ phù hợp với mọi nhu cầu học tập, sáng tạo và làm việc</p>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
            @foreach($categoryCards as $card)
            <a 
                href="{{ route('shop.index', ['category' => $card['slug']]) }}"
                class="group p-5 rounded-3xl bg-white hover:bg-slate-50 border border-slate-200/80 hover:border-blue-400/80 shadow-xs hover:shadow-md transition-all flex flex-col items-center text-center hover:-translate-y-1"
            >
                <div class="w-14 h-14 rounded-2xl bg-slate-50 group-hover:bg-blue-600 text-slate-700 group-hover:text-white flex items-center justify-center text-2xl mb-3 transition-colors shadow-2xs">
                    <i class="{{ $card['icon'] }}"></i>
                </div>
                <h3 class="font-bold text-xs sm:text-sm text-slate-800 group-hover:text-blue-600 transition-colors mb-1">
                    {{ $card['name'] }}
                </h3>
                <span class="text-[11px] text-slate-400 font-medium">
                    {{ $card['count'] }} thiết bị
                </span>
            </a>
            @endforeach
        </div>
    </section>

    <!-- ==================== 3. ĐƯỢC YÊU THÍCH. ĐÁNG ĐỂ SỞ HỮU. (TABS BỘ LỌC NHANH) ==================== -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-6">
            <div>
                <h2 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">Được yêu thích. Đáng để sở hữu.</h2>
                <p class="text-xs sm:text-sm text-slate-500">Những lựa chọn xuất sắc, sẵn sàng cho hành trình mới của bạn.</p>
            </div>
            <a href="{{ route('shop.index') }}" class="text-xs font-bold text-blue-600 hover:text-blue-700 flex items-center gap-1 group">
                <span>Xem tất cả sản phẩm</span>
                <i class="fa-solid fa-arrow-right text-[10px] group-hover:translate-x-1 transition-transform"></i>
            </a>
        </div>

        <!-- Filter Tabs -->
        <div class="flex items-center gap-2 overflow-x-auto pb-2 scrollbar-none mb-6">
            <button 
                type="button" 
                onclick="switchFeaturedTab('featured', this)"
                class="tab-btn px-4 py-2 rounded-xl text-xs font-bold transition-all bg-slate-900 text-white shadow-xs"
                data-tab="featured"
            >
                🔥 Nổi bật
            </button>
            <button 
                type="button" 
                onclick="switchFeaturedTab('study', this)"
                class="tab-btn px-4 py-2 rounded-xl text-xs font-bold transition-all bg-slate-100 hover:bg-slate-200 text-slate-600"
                data-tab="study"
            >
                💼 Học tập & Làm việc
            </button>
            <button 
                type="button" 
                onclick="switchFeaturedTab('gaming', this)"
                class="tab-btn px-4 py-2 rounded-xl text-xs font-bold transition-all bg-slate-100 hover:bg-slate-200 text-slate-600"
                data-tab="gaming"
            >
                ⚡ Năng lực & Đa dụng
            </button>
            <button 
                type="button" 
                onclick="switchFeaturedTab('design', this)"
                class="tab-btn px-4 py-2 rounded-xl text-xs font-bold transition-all bg-slate-100 hover:bg-slate-200 text-slate-600"
                data-tab="design"
            >
                🎨 Thiết kế & Đồ họa
            </button>
        </div>

        <!-- Featured Products Grid -->
        <div id="featured-products-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            @foreach($featuredProducts as $product)
            <div class="group relative rounded-3xl bg-white p-4 border border-slate-200/80 hover:border-blue-400 hover:shadow-lg transition-all flex flex-col justify-between">
                <!-- Badges -->
                <div class="absolute top-4 left-4 z-10 flex flex-wrap gap-1">
                    @if($product['is_on_sale'])
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-black bg-red-600 text-white shadow-xs">
                            GIẢM GIÁ
                        </span>
                    @else
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-black bg-blue-600 text-white shadow-xs">
                            MỚI
                        </span>
                    @endif
                </div>

                <!-- Product Thumbnail -->
                <div class="w-full h-48 rounded-2xl bg-slate-50/70 p-4 flex items-center justify-center overflow-hidden mb-3 border border-slate-100">
                    <img 
                        src="{{ $product['image_url'] }}" 
                        alt="{{ $product['name'] }}" 
                        class="w-full h-full object-contain group-hover:scale-105 transition-transform duration-300"
                        onerror="this.src='/images/placeholder.svg'"
                    >
                </div>

                <!-- Info -->
                <div class="space-y-2 flex-1">
                    <div class="flex items-center justify-between text-[11px]">
                        <span class="font-extrabold text-blue-600 uppercase tracking-wider">{{ $product['brand'] }}</span>
                        <span class="text-amber-500 font-bold text-[10px] flex items-center gap-1">
                            <i class="fa-solid fa-star"></i> 4.9
                        </span>
                    </div>

                    <h3 class="font-bold text-sm text-slate-900 group-hover:text-blue-600 transition-colors line-clamp-2 leading-snug">
                        {{ $product['name'] }}
                    </h3>

                    <!-- Specs pills -->
                    <div class="flex flex-wrap gap-1 text-[10px] font-medium text-slate-500">
                        @if(!empty($product['weight_grams']))
                            <span class="px-2 py-0.5 rounded-md bg-slate-100">🪶 {{ $product['weight_grams'] < 1000 ? $product['weight_grams'].'g' : number_format($product['weight_grams']/1000, 2).'kg' }}</span>
                        @endif
                        @if(!empty($product['battery_mah']))
                            <span class="px-2 py-0.5 rounded-md bg-slate-100">🔋 {{ number_format($product['battery_mah'], 0, ',', '.') }}mAh</span>
                        @endif
                        @if(!empty($product['screen_size']))
                            <span class="px-2 py-0.5 rounded-md bg-slate-100">💻 {{ $product['screen_size'] }}"</span>
                        @endif
                    </div>
                </div>

                <!-- Price & Action -->
                <div class="pt-3 mt-3 border-t border-slate-100">
                    <div class="flex items-baseline gap-2 mb-3">
                        <span class="text-base font-extrabold text-red-600">
                            {{ $product['price_formatted'] }}
                        </span>
                        @if($product['is_on_sale'])
                            <span class="text-xs text-slate-400 line-through">
                                {{ $product['base_price_formatted'] }}
                            </span>
                        @endif
                    </div>

                    <div class="grid grid-cols-2 gap-2">
                        <button 
                            type="button"
                            onclick="addToCompare({{ $product['id'] }})"
                            class="py-2 px-2 rounded-xl bg-slate-100 hover:bg-indigo-50 text-slate-700 hover:text-indigo-600 text-xs font-semibold text-center border border-slate-200/80 transition-colors flex items-center justify-center gap-1"
                            title="So sánh với máy khác"
                        >
                            <i class="fa-solid fa-scale-balanced text-[11px]"></i>
                            <span>So sánh</span>
                        </button>

                        <button 
                            type="button"
                            onclick="window.SmartTechAI && window.SmartTechAI.setProductContext({{ $product['id'] }}); window.SmartTechAI && window.SmartTechAI.openChat();"
                            class="py-2 px-2 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white text-xs font-semibold text-center shadow-xs transition-all flex items-center justify-center gap-1"
                            title="Hỏi AI về máy này"
                        >
                            <i class="fa-solid fa-sparkles text-[11px]"></i>
                            <span>Hỏi AI</span>
                        </button>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </section>

    <!-- ==================== 4. KHÔNG RÀNH CÔNG NGHỆ? ĐÃ CÓ TINH HIỂU BẠN. (TRỢ LÝ AI & BỘ LỌC GỢI Ý) ==================== -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="rounded-3xl bg-gradient-to-br from-indigo-50/80 via-blue-50/60 to-white p-8 sm:p-12 border border-indigo-100/80 shadow-xs relative overflow-hidden">
            <div class="absolute -top-16 -right-16 w-64 h-64 bg-indigo-300/20 rounded-full blur-3xl pointer-events-none"></div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center relative z-10">
                <!-- Left promo -->
                <div class="lg:col-span-5 space-y-4">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-indigo-100 text-indigo-700 font-extrabold text-[11px] uppercase tracking-wider">
                        <i class="fa-solid fa-sparkles text-xs"></i>
                        Trợ lý tư vấn AI 24/7
                    </span>

                    <h2 class="text-2xl sm:text-4xl font-black text-slate-900 tracking-tight leading-tight">
                        Không rành công nghệ?<br>
                        <span class="text-indigo-600">Đã có Tinh hiểu bạn.</span>
                    </h2>

                    <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                        Chia sẻ nhu cầu và ngân sách của bạn, Trợ lý AI được huấn luyện chuyên sâu sẽ phân tích thông số và gợi ý thiết bị tối ưu nhất chỉ trong tích tắc.
                    </p>

                    <div class="pt-2">
                        <button 
                            type="button" 
                            onclick="window.SmartTechAI && window.SmartTechAI.openChat();"
                            class="px-5 py-3 rounded-2xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold shadow-md shadow-indigo-600/20 transition-all flex items-center gap-2"
                        >
                            <span>Tìm thiết bị phù hợp</span>
                            <i class="fa-solid fa-arrow-right text-xs"></i>
                        </button>
                    </div>
                </div>

                <!-- Right interactive suggestion card -->
                <div class="lg:col-span-7">
                    <div class="rounded-2xl bg-white p-6 border border-indigo-100 shadow-md space-y-4">
                        <div class="flex items-center justify-between text-xs text-slate-400">
                            <span class="flex items-center gap-1.5 text-emerald-600 font-bold">
                                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-ping"></span>
                                Luôn sẵn sàng hỗ trợ bạn
                            </span>
                            <span class="text-[11px]">Mô phỏng tư vấn AI</span>
                        </div>

                        <!-- User query simulation box -->
                        <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-100 text-xs text-slate-700 font-medium flex items-center gap-2.5">
                            <div class="w-7 h-7 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center shrink-0 text-xs">
                                <i class="fa-solid fa-user"></i>
                            </div>
                            <div>
                                <span class="text-slate-400 text-[10px] block">Câu hỏi mẫu:</span>
                                <span class="font-semibold text-slate-800">"Tôi cần máy tính học đồ họa & thiết kế, ngân sách khoảng 25-30 triệu."</span>
                            </div>
                        </div>

                        <!-- AI Response simulation -->
                        <div class="p-3.5 rounded-xl bg-indigo-50/70 border border-indigo-100 text-xs text-indigo-950 space-y-2">
                            <div class="flex items-center gap-2 text-indigo-700 font-bold text-xs">
                                <i class="fa-solid fa-wand-magic-sparkles"></i>
                                <span>Gợi ý từ Trợ lý Tinh AI:</span>
                            </div>
                            <p class="text-[12px] leading-relaxed text-slate-700">
                                Để làm đồ họa mượt mà, bạn nên ưu tiên màn hình chuẩn màu (sRGB &gt; 99%), RAM tối thiểu 16GB và card đồ họa chuyên dụng. Dưới đây là các tiêu chí chọn nhanh:
                            </p>

                            <!-- Clickable Filter Chips (Kết nối trực tiếp vào bộ lọc shop) -->
                            <div class="flex flex-wrap gap-2 pt-1">
                                <a 
                                    href="{{ route('shop.index', ['max_price' => 15]) }}" 
                                    class="px-3 py-1.5 rounded-xl bg-white hover:bg-indigo-600 hover:text-white text-slate-700 font-bold text-[11px] border border-indigo-200 transition-colors shadow-2xs flex items-center gap-1.5"
                                >
                                    <i class="fa-solid fa-tag text-[10px] text-indigo-500"></i>
                                    <span>Ngân sách dưới 15tr</span>
                                </a>

                                <a 
                                    href="{{ route('shop.index', ['battery' => 'high']) }}" 
                                    class="px-3 py-1.5 rounded-xl bg-white hover:bg-indigo-600 hover:text-white text-slate-700 font-bold text-[11px] border border-indigo-200 transition-colors shadow-2xs flex items-center gap-1.5"
                                >
                                    <i class="fa-solid fa-battery-full text-[10px] text-emerald-500"></i>
                                    <span>Pin trâu (&gt; 5500mAh)</span>
                                </a>

                                <a 
                                    href="{{ route('shop.index', ['weight' => 'ultralight']) }}" 
                                    class="px-3 py-1.5 rounded-xl bg-white hover:bg-indigo-600 hover:text-white text-slate-700 font-bold text-[11px] border border-indigo-200 transition-colors shadow-2xs flex items-center gap-1.5"
                                >
                                    <i class="fa-solid fa-feather text-[10px] text-amber-500"></i>
                                    <span>Mỏng nhẹ (&lt; 1.3kg)</span>
                                </a>

                                <a 
                                    href="{{ route('shop.index', ['category' => 'laptop-do-hoa']) }}" 
                                    class="px-3 py-1.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-[11px] transition-colors shadow-2xs flex items-center gap-1.5"
                                >
                                    <i class="fa-solid fa-palette text-[10px]"></i>
                                    <span>Chuyên đồ họa</span>
                                </a>
                            </div>
                        </div>

                        <div class="pt-1 flex items-center justify-between text-xs">
                            <span class="text-slate-400">Thuật toán đối chiếu tự động 250 sản phẩm</span>
                            <a href="{{ route('shop.index', ['category' => 'laptop-do-hoa']) }}" class="font-bold text-indigo-600 hover:text-indigo-700 flex items-center gap-1">
                                <span>Khám phá các lựa chọn dành cho bạn</span>
                                <i class="fa-solid fa-arrow-right text-[10px]"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ==================== 5. CẢM HỨNG CÔNG NGHỆ (BLOG / TIPS) ==================== -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-6">
            <div>
                <h2 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">Một chút cảm hứng công nghệ.</h2>
                <p class="text-xs sm:text-sm text-slate-500">Gợi ý hữu ích để bạn chọn đúng thiết bị và trải nghiệm thích hơn.</p>
            </div>
            <a href="{{ route('shop.index') }}" class="text-xs font-bold text-blue-600 hover:text-blue-700 flex items-center gap-1 group">
                <span>Khám phá Góc công nghệ</span>
                <i class="fa-solid fa-arrow-right text-[10px] group-hover:translate-x-1 transition-transform"></i>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Article 1 -->
            <div class="rounded-3xl bg-white border border-slate-200/80 overflow-hidden shadow-xs hover:shadow-md transition-all group">
                <div class="h-44 bg-slate-100 overflow-hidden relative">
                    <img 
                        src="/uploads/products/macbook-pro-14-inch-m4-pro-v1-main.jpg" 
                        alt="Góc làm việc" 
                        class="w-full h-full object-contain p-4 group-hover:scale-105 transition-transform duration-300"
                        onerror="this.src='/images/placeholder.svg'"
                    >
                    <span class="absolute top-3 left-3 px-2.5 py-1 rounded-full bg-white/90 backdrop-blur-xs text-[10px] font-bold text-slate-700">
                        GÓC LÀM VIỆC
                    </span>
                </div>
                <div class="p-5 space-y-2">
                    <h3 class="font-bold text-sm text-slate-900 group-hover:text-blue-600 transition-colors leading-snug">
                        Gọn một góc bàn. Rộng một khoảng sáng tạo.
                    </h3>
                    <p class="text-xs text-slate-500 line-clamp-2">
                        Bí quyết sắp xếp laptop, màn hình và phụ kiện không dây tối giản để tăng 40% khả năng tập trung mỗi ngày.
                    </p>
                    <div class="pt-2 text-[11px] text-slate-400 font-medium">5 phút đọc · Cẩm nang tối giản</div>
                </div>
            </div>

            <!-- Article 2 -->
            <div class="rounded-3xl bg-white border border-slate-200/80 overflow-hidden shadow-xs hover:shadow-md transition-all group">
                <div class="h-44 bg-slate-100 overflow-hidden relative">
                    <img 
                        src="/uploads/products/macbook-air-15-inch-m4-2025-v1-main.jpg" 
                        alt="Chọn máy tính" 
                        class="w-full h-full object-contain p-4 group-hover:scale-105 transition-transform duration-300"
                        onerror="this.src='/images/placeholder.svg'"
                    >
                    <span class="absolute top-3 left-3 px-2.5 py-1 rounded-full bg-white/90 backdrop-blur-xs text-[10px] font-bold text-slate-700">
                        TƯ VẤN CHỌN MUA
                    </span>
                </div>
                <div class="p-5 space-y-2">
                    <h3 class="font-bold text-sm text-slate-900 group-hover:text-blue-600 transition-colors leading-snug">
                        Chọn máy tính đầu tiên: Bắt đầu từ điều bạn cần.
                    </h3>
                    <p class="text-xs text-slate-500 line-clamp-2">
                        Phân biệt CPU dòng U và H, chọn RAM 8GB hay 16GB để dùng bền 4 năm đại học mà không lo lỗi thời.
                    </p>
                    <div class="pt-2 text-[11px] text-slate-400 font-medium">7 phút đọc · Hướng dẫn thực tế</div>
                </div>
            </div>

            <!-- Article 3 -->
            <div class="rounded-3xl bg-white border border-slate-200/80 overflow-hidden shadow-xs hover:shadow-md transition-all group">
                <div class="h-44 bg-slate-100 overflow-hidden relative">
                    <img 
                        src="/uploads/products/macbook-air-13-inch-m3-v1-main.jpg" 
                        alt="Pin bền" 
                        class="w-full h-full object-contain p-4 group-hover:scale-105 transition-transform duration-300"
                        onerror="this.src='/images/placeholder.svg'"
                    >
                    <span class="absolute top-3 left-3 px-2.5 py-1 rounded-full bg-white/90 backdrop-blur-xs text-[10px] font-bold text-slate-700">
                        MẸO SỬ DỤNG
                    </span>
                </div>
                <div class="p-5 space-y-2">
                    <h3 class="font-bold text-sm text-slate-900 group-hover:text-blue-600 transition-colors leading-snug">
                        Pin bền hơn từ những thói quen nhỏ mỗi ngày.
                    </h3>
                    <p class="text-xs text-slate-500 line-clamp-2">
                        Cách sạc máy thông minh theo quy tắc 20-80% và thiết lập chế độ tản nhiệt giúp pin giữ dung lượng đỉnh suốt nhiều năm.
                    </p>
                    <div class="pt-2 text-[11px] text-slate-400 font-medium">4 phút đọc · Kỹ thuật pin</div>
                </div>
            </div>
        </div>
    </section>

    <!-- ==================== 6. NEWSLETTER SUBSCRIPTION ==================== -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="rounded-3xl bg-slate-900 text-white p-8 sm:p-12 flex flex-col md:flex-row items-center justify-between gap-6">
            <div class="space-y-1 text-center md:text-left">
                <h3 class="text-xl sm:text-2xl font-black tracking-tight">Điều hay tiếp theo, gửi riêng bạn.</h3>
                <p class="text-xs sm:text-sm text-slate-400">Nhận ưu đãi mới và những gợi ý công nghệ đáng đọc. Vừa đủ hữu ích, không làm phiền.</p>
            </div>

            <form onsubmit="event.preventDefault(); alert('Cảm ơn bạn đã đăng ký nhận bản tin từ tinh.!');" class="w-full md:w-auto flex flex-col sm:flex-row gap-2 max-w-md shrink-0">
                <input 
                    type="email" 
                    placeholder="Địa chỉ email của bạn..." 
                    required
                    class="px-4 py-3 rounded-2xl bg-white/10 border border-white/20 text-white placeholder-slate-400 text-xs focus:outline-hidden focus:border-blue-400 w-full sm:w-72"
                >
                <button 
                    type="submit" 
                    class="px-5 py-3 rounded-2xl bg-blue-600 hover:bg-blue-500 text-white text-xs font-bold transition-colors whitespace-nowrap shadow-xs"
                >
                    Đăng ký nhận tin
                </button>
            </form>
        </div>
    </section>

</div>

@push('scripts')
<script>
async function switchFeaturedTab(tabName, btn) {
    // 1. Cập nhật trạng thái nút tab
    document.querySelectorAll('.tab-btn').forEach(b => {
        b.className = 'tab-btn px-4 py-2 rounded-xl text-xs font-bold transition-all bg-slate-100 hover:bg-slate-200 text-slate-600';
    });
    btn.className = 'tab-btn px-4 py-2 rounded-xl text-xs font-bold transition-all bg-slate-900 text-white shadow-xs';

    // 2. Hiển thị loading skeleton
    const grid = document.getElementById('featured-products-grid');
    grid.innerHTML = Array(4).fill(0).map(() => `
        <div class="rounded-3xl bg-white p-4 border border-slate-200/80 animate-pulse space-y-3">
            <div class="w-full h-48 bg-slate-100 rounded-2xl"></div>
            <div class="h-3 bg-slate-100 rounded-sm w-1/3"></div>
            <div class="h-4 bg-slate-100 rounded-sm w-3/4"></div>
            <div class="h-5 bg-slate-100 rounded-sm w-1/2"></div>
        </div>
    `).join('');

    // 3. Tải sản phẩm theo tab
    try {
        const res = await fetch(`/api/products/featured-tabs?tab=${encodeURIComponent(tabName)}`);
        const data = await res.json();

        if (data.success && data.products) {
            grid.innerHTML = data.products.map(p => `
                <div class="group relative rounded-3xl bg-white p-4 border border-slate-200/80 hover:border-blue-400 hover:shadow-lg transition-all flex flex-col justify-between">
                    <div class="absolute top-4 left-4 z-10">
                        ${p.is_on_sale ? `
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-black bg-red-600 text-white shadow-xs">GIẢM GIÁ</span>
                        ` : `
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-black bg-blue-600 text-white shadow-xs">MỚI</span>
                        `}
                    </div>

                    <div class="w-full h-48 rounded-2xl bg-slate-50/70 p-4 flex items-center justify-center overflow-hidden mb-3 border border-slate-100">
                        <img 
                            src="${p.image_url}" 
                            alt="${p.name}" 
                            class="w-full h-full object-contain group-hover:scale-105 transition-transform duration-300"
                            onerror="this.src='/images/placeholder.svg'"
                        >
                    </div>

                    <div class="space-y-2 flex-1">
                        <div class="flex items-center justify-between text-[11px]">
                            <span class="font-extrabold text-blue-600 uppercase tracking-wider">${p.brand || ''}</span>
                            <span class="text-amber-500 font-bold text-[10px] flex items-center gap-1">
                                <i class="fa-solid fa-star"></i> 4.9
                            </span>
                        </div>

                        <h3 class="font-bold text-sm text-slate-900 group-hover:text-blue-600 transition-colors line-clamp-2 leading-snug">
                            ${p.name}
                        </h3>

                        <div class="flex flex-wrap gap-1 text-[10px] font-medium text-slate-500">
                            ${p.weight_grams ? `<span class="px-2 py-0.5 rounded-md bg-slate-100">🪶 ${p.weight_grams < 1000 ? p.weight_grams + 'g' : (p.weight_grams/1000).toFixed(2) + 'kg'}</span>` : ''}
                            ${p.battery_mah ? `<span class="px-2 py-0.5 rounded-md bg-slate-100">🔋 ${Number(p.battery_mah).toLocaleString('vi-VN')}mAh</span>` : ''}
                            ${p.screen_size ? `<span class="px-2 py-0.5 rounded-md bg-slate-100">💻 ${p.screen_size}"</span>` : ''}
                        </div>
                    </div>

                    <div class="pt-3 mt-3 border-t border-slate-100">
                        <div class="flex items-baseline gap-2 mb-3">
                            <span class="text-base font-extrabold text-red-600">${p.price_formatted}</span>
                            ${p.is_on_sale ? `<span class="text-xs text-slate-400 line-through">${p.base_price_formatted}</span>` : ''}
                        </div>

                        <div class="grid grid-cols-2 gap-2">
                            <button 
                                type="button"
                                onclick="addToCompare(${p.id})"
                                class="py-2 px-2 rounded-xl bg-slate-100 hover:bg-indigo-50 text-slate-700 hover:text-indigo-600 text-xs font-semibold text-center border border-slate-200/80 transition-colors flex items-center justify-center gap-1"
                                title="So sánh với máy khác"
                            >
                                <i class="fa-solid fa-scale-balanced text-[11px]"></i>
                                <span>So sánh</span>
                            </button>

                            <button 
                                type="button"
                                onclick="window.SmartTechAI && window.SmartTechAI.setProductContext(${p.id}); window.SmartTechAI && window.SmartTechAI.openChat();"
                                class="py-2 px-2 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white text-xs font-semibold text-center shadow-xs transition-all flex items-center justify-center gap-1"
                                title="Hỏi AI về máy này"
                            >
                                <i class="fa-solid fa-sparkles text-[11px]"></i>
                                <span>Hỏi AI</span>
                            </button>
                        </div>
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