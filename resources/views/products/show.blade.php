@extends('layouts.client')

@section('title', $product->name . ' Chính Hãng - D&D.')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-8">
    @php
        $initialVariant = $product->variants->first();
        $initialImages = $initialVariant
            ? $product->images->where('variant_id', $initialVariant->id)
            : $product->images;
    @endphp

    <!-- Breadcrumbs -->
    <nav class="flex items-center space-x-2 text-xs text-slate-500">
        <a href="{{ route('home') }}" class="hover:text-blue-600 transition">Trang chủ</a>
        <i class="fa-solid fa-chevron-right text-[10px] text-slate-300"></i>
        <a href="{{ route('shop.index') }}" class="hover:text-blue-600 transition">Cửa hàng</a>
        <i class="fa-solid fa-chevron-right text-[10px] text-slate-300"></i>
        <a href="{{ route('shop.index', ['category' => $product->category?->slug]) }}" class="hover:text-blue-600 transition">
            {{ $product->category?->name }}
        </a>
        <i class="fa-solid fa-chevron-right text-[10px] text-slate-300"></i>
        <span class="text-slate-800 font-semibold truncate max-w-xs sm:max-w-md">{{ $product->name }}</span>
    </nav>

    <!-- Main PDP Container (2 Columns) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

        <!-- LEFT COLUMN: Multi-angle Media Gallery (5 cols) -->
        <div class="lg:col-span-5 space-y-4 lg:sticky lg:top-24">
            <div class="bg-white rounded-3xl border border-slate-200/90 p-6 shadow-sm relative overflow-hidden">
                <!-- Angle Selector Pills -->
                <div class="flex items-center gap-1.5 mb-4 bg-slate-100 p-1 rounded-xl text-xs font-semibold text-slate-600 w-fit">
                    <button type="button" onclick="filterGalleryAngle('all')" class="gallery-angle-btn active px-3 py-1 rounded-lg transition bg-white text-blue-600 shadow-xs" data-angle="all">
                        Tất cả góc
                    </button>
                    <button type="button" onclick="filterGalleryAngle('main')" class="gallery-angle-btn px-3 py-1 rounded-lg transition hover:text-blue-600" data-angle="main">
                        Mặt trước
                    </button>
                    <button type="button" onclick="filterGalleryAngle('back')" class="gallery-angle-btn px-3 py-1 rounded-lg transition hover:text-blue-600" data-angle="back">
                        Mặt sau
                    </button>
                    <button type="button" onclick="filterGalleryAngle('ports')" class="gallery-angle-btn px-3 py-1 rounded-lg transition hover:text-blue-600" data-angle="ports">
                        Cổng kết nối
                    </button>
                </div>

                <!-- Main Active Image -->
                <div class="relative aspect-square flex items-center justify-center p-4 bg-slate-50/50 rounded-2xl overflow-hidden">
                    @php
                        $defaultImg = $initialImages->first()?->image_url ?? '/images/placeholder.svg';
                    @endphp
                    <img
                        id="mainProductImage"
                        src="{{ $defaultImg }}"
                        alt="{{ $product->name }}"
                        class="max-h-full max-w-full object-contain transition-all duration-300 transform hover:scale-110 cursor-zoom-in"
                    >
                    <span id="activeAngleBadge" class="absolute bottom-3 right-3 text-[10px] font-bold uppercase bg-slate-900/80 text-white px-2.5 py-1 rounded-md backdrop-blur-xs">
                        Góc: {{ $initialImages->first()?->angle_type ?? 'main' }}
                    </span>
                </div>

                <!-- Thumbnail Strip -->
                <div class="flex items-center gap-3 mt-4 overflow-x-auto pb-2 scrollbar-none" id="galleryThumbnails">
                    @foreach($initialImages as $index => $img)
                        <button
                            type="button"
                            onclick="selectGalleryImage('{{ $img->image_url }}', '{{ $img->angle_type }}', {{ $img->variant_id ?? 'null' }}, this)"
                            data-variant-id="{{ $img->variant_id ?? '' }}"
                            data-angle="{{ $img->angle_type }}"
                            class="gallery-thumb-btn shrink-0 w-16 h-16 rounded-xl border-2 overflow-hidden p-1 transition {{ $index === 0 ? 'border-blue-600 bg-blue-50/30' : 'border-slate-200 hover:border-slate-400' }}"
                        >
                            <img src="{{ $img->image_url }}" alt="Angle {{ $img->angle_type }}" class="w-full h-full object-contain">
                        </button>
                    @endforeach
                </div>
            </div>

            <!-- Assurance Cards -->
            <div class="grid grid-cols-2 gap-3 text-xs">
                <div class="flex items-center gap-2.5 bg-white p-3.5 rounded-2xl border border-slate-200/80">
                    <div class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-shield-check text-base"></i>
                    </div>
                    <div>
                        <div class="font-bold text-slate-800">Bảo hành chính hãng</div>
                        <div class="text-slate-500 text-[11px]">{{ $product->warranty_months }} tháng toàn quốc</div>
                    </div>
                </div>
                <div class="flex items-center gap-2.5 bg-white p-3.5 rounded-2xl border border-slate-200/80">
                    <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-arrows-rotate text-base"></i>
                    </div>
                    <div>
                        <div class="font-bold text-slate-800">1 Đổi 1 trong 30 ngày</div>
                        <div class="text-slate-500 text-[11px]">Nếu phát sinh lỗi NSX</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- RIGHT COLUMN: Variant Matrix & Purchase Form (7 cols) -->
        <div class="lg:col-span-7 space-y-6">

            <!-- Title & Brand Header -->
            <div class="bg-white rounded-3xl border border-slate-200/90 p-6 md:p-8 shadow-xs space-y-4">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <a href="{{ route('products.index', ['brand' => $product->brand?->slug]) }}" class="inline-flex items-center gap-2 px-3 py-1 rounded-lg bg-slate-100 hover:bg-slate-200 text-xs font-bold text-slate-700 transition">
                        @if($product->brand?->logo_url)
                            <img src="{{ $product->brand->logo_url }}" alt="{{ $product->brand->name }}" class="h-3.5 object-contain">
                        @endif
                        <span>{{ $product->brand?->name }}</span>
                    </a>
                    <div class="flex items-center gap-3 text-xs text-slate-500">
                        <span>Mã SKU: <strong id="skuDisplay" class="text-slate-800 font-mono">{{ $product->sku_master }}</strong></span>
                        <span>•</span>
                        <span>Đã bán: <strong class="text-slate-800">{{ $product->total_sold }}</strong></span>
                    </div>
                </div>

                <h1 class="text-2xl md:text-3xl font-extrabold text-slate-900 tracking-tight leading-tight">
                    {{ $product->name }}
                </h1>

                <!-- Rating and Key Specs bar -->
                <div class="flex flex-wrap items-center gap-4 text-xs pt-1 border-b border-slate-100 pb-4">
                    <div class="flex items-center text-amber-400 gap-1">
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star-half-stroke"></i>
                        <span class="font-bold text-slate-700 ml-1">4.9 / 5.0</span>
                    </div>
                    <span class="text-slate-300">|</span>
                    @if($product->screen_size)
                        <span class="text-slate-600"><i class="fa-solid fa-tv text-slate-400 mr-1"></i> Màn hình: <strong>{{ $product->screen_size }}"</strong></span>
                    @endif
                    @if($product->weight_grams)
                        <span class="text-slate-600"><i class="fa-solid fa-feather text-slate-400 mr-1"></i> Trọng lượng: <strong>{{ round($product->weight_grams / 1000, 2) }}kg</strong></span>
                    @endif
                    @if($product->battery_mah || $product->battery_wh)
                        <span class="text-slate-600"><i class="fa-solid fa-battery-three-quarters text-slate-400 mr-1"></i> Pin: <strong>{{ $product->battery_mah ? $product->battery_mah . 'mAh' : $product->battery_wh . 'Wh' }}</strong></span>
                    @endif
                </div>

                <!-- Live Price Card -->
                <div class="bg-slate-50 rounded-2xl p-4 md:p-5 flex flex-wrap items-baseline justify-between gap-4">
                    <div>
                        <span class="text-xs text-slate-500 font-medium block">Giá bán chính hãng (Đã gồm VAT):</span>
                        <div class="flex items-baseline gap-3 mt-1">
                            <span id="salePriceDisplay" class="text-3xl md:text-4xl font-black text-rose-600 tracking-tight">
                                {{ number_format($product->sale_price, 0, ',', '.') }}₫
                            </span>
                            <span id="basePriceDisplay" class="text-base text-slate-400 line-through font-semibold {{ $product->base_price <= $product->sale_price ? 'hidden' : '' }}">
                                {{ number_format($product->base_price, 0, ',', '.') }}₫
                            </span>
                            <span id="discountBadge" class="bg-rose-500 text-white text-xs font-black px-2 py-0.5 rounded-md shadow-xs">
                                Tiết kiệm {{ number_format($product->base_price - $product->sale_price, 0, ',', '.') }}₫
                            </span>
                        </div>
                    </div>

                    <!-- Installment Box -->
                    <div class="text-right">
                        <span class="text-[11px] font-semibold text-emerald-600 bg-emerald-50 px-2.5 py-1 rounded-md border border-emerald-200">
                            <i class="fa-solid fa-bolt mr-1"></i> Trả góp 0%
                        </span>
                        <div id="installmentEstimate" class="text-xs text-slate-500 mt-1">
                            Chỉ từ <strong>{{ number_format(round($product->sale_price / 12), 0, ',', '.') }}₫</strong>/tháng
                        </div>
                    </div>
                </div>

                <!-- Variant Selector: Colors (Ma Trận Biến Thể) -->
                @php
                    $colors = $product->variants->unique('color_name');
                    $rams = $product->variants->pluck('ram')->filter()->unique();
                    $storages = $product->variants->pluck('storage')->filter()->unique();
                @endphp

                <!-- 1. COLOR MATRIX -->
                <div class="space-y-2.5 pt-2">
                    <div class="flex justify-between items-center text-xs">
                        <span class="font-bold text-slate-700 uppercase tracking-wider">
                            1. Chọn màu sắc: <span id="selectedColorName" class="text-blue-600 font-extrabold">{{ $product->variants->first()?->color_name }}</span>
                        </span>
                    </div>
                    <div class="flex flex-wrap gap-3">
                        @foreach($colors as $index => $c)
                            <button
                                type="button"
                                onclick="selectColor('{{ $c->color_name }}', this)"
                                class="color-btn flex items-center gap-2.5 px-4 py-2.5 rounded-xl border-2 text-xs font-bold transition {{ $index === 0 ? 'border-blue-600 bg-blue-50/50 text-blue-700 shadow-xs' : 'border-slate-200 bg-white text-slate-700 hover:border-slate-300' }}"
                                data-color="{{ $c->color_name }}"
                            >
                                <span class="w-4 h-4 rounded-full border border-slate-300 shadow-2xs" style="background-color: {{ $c->color_hex ?? '#333333' }};"></span>
                                <span>{{ $c->color_name }}</span>
                            </button>
                        @endforeach
                    </div>
                </div>

                <!-- 2. RAM / STORAGE MATRIX -->
                @if($rams->isNotEmpty() || $storages->isNotEmpty())
                    <div class="space-y-2.5 pt-2">
                        <div class="flex justify-between items-center text-xs">
                            <span class="font-bold text-slate-700 uppercase tracking-wider">
                                2. Cấu hình phần cứng:
                            </span>
                        </div>
                        <div class="flex flex-wrap gap-3">
                            @foreach($product->variants as $index => $v)
                                <button
                                    type="button"
                                    onclick="selectVariant({{ $v->id }}, this)"
                                    class="variant-btn flex flex-col items-start px-4 py-2.5 rounded-xl border-2 text-xs font-bold transition text-left {{ $index === 0 ? 'border-blue-600 bg-blue-50/50 text-blue-700 shadow-xs' : 'border-slate-200 bg-white text-slate-700 hover:border-slate-300' }}"
                                    data-variant-id="{{ $v->id }}"
                                    data-color="{{ $v->color_name }}"
                                    data-ram="{{ $v->ram }}"
                                    data-storage="{{ $v->storage }}"
                                >
                                    <span class="text-slate-900 font-extrabold">{{ $v->ram ? $v->ram . ' - ' : '' }}{{ $v->storage }}</span>
                                    <span class="text-[11px] font-semibold text-rose-600 mt-0.5">{{ number_format($v->sale_price, 0, ',', '.') }}₫</span>
                                </button>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Stock Availability Badge -->
                <div class="flex items-center gap-2 pt-2 text-xs">
                    <span id="stockBadge" class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        <span id="stockText">Còn hàng ({{ $product->variants->first()?->stock_quantity ?? 10 }} máy sẵn tại kho)</span>
                    </span>
                    <span class="text-slate-400">• Đặt online nhận hàng trong 2 giờ</span>
                </div>

                <!-- Action CTA Buttons (Connecting clean for Member 4) -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-4">
                    <button
                        type="button"
                        id="btnAddToCart"
                        onclick="addToCart()"
                        class="w-full bg-slate-900 hover:bg-slate-800 text-white font-extrabold text-sm py-4 rounded-2xl shadow-lg transition duration-200 flex items-center justify-center gap-2 group"
                    >
                        <i class="fa-solid fa-cart-plus text-base group-hover:scale-110 transition-transform"></i>
                        <span>Thêm vào giỏ hàng</span>
                    </button>
                    <button
                        type="button"
                        id="btnBuyNow"
                        onclick="buyNow()"
                        class="w-full bg-gradient-to-r from-rose-600 to-red-600 hover:from-rose-500 hover:to-red-500 text-white font-extrabold text-sm py-4 rounded-2xl shadow-lg shadow-rose-500/30 transition duration-200 flex flex-col items-center justify-center"
                    >
                        <span>MUA NGAY VỚI GIÁ ƯU ĐÃI</span>
                        <span class="text-[10px] font-medium opacity-90">Giao hàng tận nơi hoặc nhận tại cửa hàng</span>
                    </button>
                </div>
            </div>

            <!-- AI Summary Widget (Phân hệ AI Member 2 & 3) -->
            @if($product->ai_summary)
                <div class="bg-[#f0f3ff] rounded-3xl p-6 md:p-8 text-slate-800 border border-blue-100/70 shadow-2xs relative overflow-hidden">
                    <div class="relative z-10 space-y-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded-lg bg-blue-100 border border-blue-200 flex items-center justify-center text-blue-600">
                                    <i class="fa-solid fa-wand-magic-sparkles text-sm"></i>
                                </div>
                                <h3 class="font-extrabold text-base tracking-tight text-slate-900">AI Smart Summary</h3>
                            </div>
                            <span class="text-[10px] font-bold uppercase tracking-wider bg-white text-blue-600 border border-blue-200 px-2.5 py-1 rounded-full shadow-2xs">
                                Phân tích kỹ thuật tự động
                            </span>
                        </div>

                        <!-- Target Audience -->
                        @if(isset($product->ai_summary['target_users']))
                            <div class="bg-white rounded-xl p-3 text-xs flex items-center gap-2 border border-slate-200/80 shadow-2xs">
                                <i class="fa-solid fa-user-check text-blue-600 text-sm"></i>
                                <span class="text-slate-700"><strong>Phù hợp nhất với:</strong> {{ $product->ai_summary['target_users'] }}</span>
                            </div>
                        @endif

                        <!-- Pros & Cons Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs pt-1">
                            <!-- Pros -->
                            <div class="space-y-2 bg-emerald-50/80 border border-emerald-200/80 rounded-2xl p-4">
                                <span class="font-bold text-emerald-800 uppercase tracking-wider flex items-center gap-1.5 text-[11px]">
                                    <i class="fa-solid fa-circle-check text-emerald-600"></i> Ưu điểm nổi trội
                                </span>
                                <ul class="space-y-1.5 text-slate-700">
                                    @foreach($product->ai_summary['pros'] ?? [] as $pro)
                                        <li class="flex items-start gap-1.5">
                                            <span class="text-emerald-600 font-bold">•</span>
                                            <span>{{ $pro }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>

                            <!-- Cons -->
                            <div class="space-y-2 bg-rose-50/80 border border-rose-200/80 rounded-2xl p-4">
                                <span class="font-bold text-rose-800 uppercase tracking-wider flex items-center gap-1.5 text-[11px]">
                                    <i class="fa-solid fa-circle-exclamation text-rose-600"></i> Lưu ý / Nhược điểm
                                </span>
                                <ul class="space-y-1.5 text-slate-700">
                                    @foreach($product->ai_summary['cons'] ?? [] as $con)
                                        <li class="flex items-start gap-1.5">
                                            <span class="text-rose-600 font-bold">•</span>
                                            <span>{{ $con }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </div>

    <!-- LOWER SECTION: Specs Table & Detailed Description -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 pt-6">

        <!-- Left: Product Overview Article (7 cols) -->
        <div class="lg:col-span-7 space-y-6">
            <div class="bg-white rounded-3xl border border-slate-200/90 p-6 md:p-8 shadow-xs space-y-4">
                <h3 class="text-lg font-extrabold text-slate-900 uppercase tracking-wider flex items-center gap-2">
                    <i class="fa-solid fa-newspaper text-blue-600"></i> Đánh Giá Chi Tiết & Giới Thiệu Sản Phẩm
                </h3>
                <div class="prose prose-slate max-w-none text-sm leading-relaxed text-slate-700 space-y-4">
                    {!! $product->description !!}
                    <p>
                        Sản phẩm <strong>{{ $product->name }}</strong> là một trong những sản phẩm tiêu biểu của thương hiệu <strong>{{ $product->brand?->name }}</strong> tại phân khúc {{ strtolower($product->category?->name) }}. Được hoàn thiện với tiêu chuẩn khắt khe, tối ưu hóa trọng lượng chỉ <strong>{{ round($product->weight_grams / 1000, 2) }}kg</strong>, giúp người dùng dễ dàng mang theo di chuyển hằng ngày mà không lo cồng kềnh.
                    </p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 my-4">
                        <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200">
                            <div class="font-bold text-slate-800 text-xs mb-1">Thiết kế công thái học</div>
                            <div class="text-slate-500 text-xs">Vỏ máy chế tác tinh xảo, tản nhiệt thông minh và đường viền siêu mỏng.</div>
                        </div>
                        <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200">
                            <div class="font-bold text-slate-800 text-xs mb-1">Thời lượng pin bền bỉ</div>
                            <div class="text-slate-500 text-xs">Dung lượng {{ $product->battery_mah ? $product->battery_mah . 'mAh' : $product->battery_wh . 'Wh' }} cho trải nghiệm suốt ngày dài làm việc.</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right: Full Technical Specifications Table (5 cols) -->
        <div class="lg:col-span-5 space-y-6">
            <div class="bg-white rounded-3xl border border-slate-200/90 p-6 md:p-8 shadow-xs space-y-4">
                <h3 class="text-lg font-extrabold text-slate-900 uppercase tracking-wider flex items-center gap-2">
                    <i class="fa-solid fa-microchip text-blue-600"></i> Bảng Thông Số Kỹ Thuật
                </h3>

                <div class="space-y-4 text-xs">
                    @foreach($specificationsByGroup as $groupName => $specs)
                        <div class="border border-slate-200 rounded-2xl overflow-hidden">
                            <div class="bg-slate-100/80 px-4 py-2.5 font-bold text-slate-800 uppercase tracking-wider">
                                {{ $groupName }}
                            </div>
                            <div class="divide-y divide-slate-100">
                                @foreach($specs as $item)
                                    <div class="px-4 py-2.5 flex justify-between gap-4">
                                        <span class="text-slate-500 font-medium">{{ $item->specification?->name ?? 'Thuộc tính' }}</span>
                                        <span class="text-slate-900 font-bold text-right">{{ $item->value_text }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach

                    <!-- Weight and Battery highlights -->
                    <div class="border border-slate-200 rounded-2xl overflow-hidden">
                        <div class="bg-slate-100/80 px-4 py-2.5 font-bold text-slate-800 uppercase tracking-wider">
                            Kích Thước & Trọng Lượng
                        </div>
                        <div class="divide-y divide-slate-100">
                            <div class="px-4 py-2.5 flex justify-between gap-4">
                                <span class="text-slate-500 font-medium">Trọng lượng thân máy</span>
                                <span class="text-slate-900 font-bold text-right">{{ round($product->weight_grams / 1000, 2) }} kg ({{ $product->weight_grams }}g)</span>
                            </div>
                            @if($product->battery_mah || $product->battery_wh)
                                <div class="px-4 py-2.5 flex justify-between gap-4">
                                    <span class="text-slate-500 font-medium">Dung lượng pin thực tế</span>
                                    <span class="text-slate-900 font-bold text-right">{{ $product->battery_mah ? $product->battery_mah . ' mAh' : $product->battery_wh . ' Wh' }}</span>
                                </div>
                            @endif
                            <div class="px-4 py-2.5 flex justify-between gap-4">
                                <span class="text-slate-500 font-medium">Thời hạn bảo hành</span>
                                <span class="text-slate-900 font-bold text-right">{{ $product->warranty_months }} Tháng</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- RELATED PRODUCTS -->
    @if($relatedProducts->isNotEmpty())
        <div class="pt-8 space-y-4">
            <h3 class="text-xl font-extrabold text-slate-900 tracking-tight">Sản Phẩm Cùng Phân Khúc</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($relatedProducts as $rel)
                    @php
                        $relImg = $rel->images->first()?->image_url ?? '/images/placeholder.svg';
                    @endphp
                    <a href="{{ route('products.show', $rel->slug) }}" class="group bg-white rounded-2xl border border-slate-200/90 hover:border-blue-400 p-4 shadow-2xs hover:shadow-lg transition block">
                        <div class="aspect-square bg-slate-50 rounded-xl overflow-hidden p-4 mb-3">
                            <img src="{{ $relImg }}" alt="{{ $rel->name }}" class="w-full h-full object-contain group-hover:scale-105 transition">
                        </div>
                        <span class="text-[10px] font-bold uppercase text-slate-400">{{ $rel->brand?->name }}</span>
                        <div class="text-xs font-bold text-slate-800 line-clamp-2 mt-1 group-hover:text-blue-600 transition">{{ $rel->name }}</div>
                        <div class="text-sm font-extrabold text-rose-600 mt-2">{{ number_format($rel->sale_price, 0, ',', '.') }}₫</div>
                    </a>
                @endforeach
            </div>
        </div>
    @endif

</div>
@endsection

@push('scripts')
<script>
    // ===== Embedded JSON data from Laravel =====
    const variants = @json($product->variants);
    const images = @json($product->images);
    let currentVariant = variants.length > 0 ? variants[0] : null;
    let currentAngleFilter = 'all';

    function formatVND(amount) {
        return new Intl.NumberFormat('vi-VN').format(amount) + '₫';
    }

    // ===== Build Gallery Thumbnails Dynamically =====
    function buildGalleryForVariant(variantId) {
        const container = document.getElementById('galleryThumbnails');
        // Filter images for this variant
        const variantImages = images.filter(img => img.variant_id === variantId);

        // Clear old thumbnails
        container.innerHTML = '';

        if (variantImages.length === 0) return;

        variantImages.forEach((img, index) => {
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.setAttribute('data-variant-id', img.variant_id || '');
            btn.setAttribute('data-angle', img.angle_type);
            btn.className = 'gallery-thumb-btn shrink-0 w-16 h-16 rounded-xl border-2 overflow-hidden p-1 transition '
                + (index === 0 ? 'border-blue-600 bg-blue-50/30' : 'border-slate-200 hover:border-slate-400');

            // Apply angle filter visibility
            if (currentAngleFilter !== 'all' && img.angle_type !== currentAngleFilter) {
                btn.style.display = 'none';
            }

            const imgEl = document.createElement('img');
            imgEl.src = img.image_url;
            imgEl.alt = 'Góc ' + img.angle_type;
            imgEl.className = 'w-full h-full object-contain';
            btn.appendChild(imgEl);

            btn.addEventListener('click', function() {
                selectGalleryImage(img.image_url, img.angle_type, img.variant_id, this);
            });

            container.appendChild(btn);
        });

        // Auto-select the first visible thumbnail
        const firstVisible = container.querySelector('.gallery-thumb-btn:not([style*="display: none"])');
        if (firstVisible) {
            firstVisible.click();
        }
    }

    // ===== Interactive Variant Selection =====
    function selectVariant(variantId, btnElement) {
        const found = variants.find(v => v.id === variantId);
        if (!found) return;
        currentVariant = found;

        // Update active button state
        document.querySelectorAll('.variant-btn').forEach(btn => {
            btn.classList.remove('border-blue-600', 'bg-blue-50/50', 'text-blue-700', 'shadow-xs');
            btn.classList.add('border-slate-200', 'bg-white', 'text-slate-700');
        });
        if (btnElement) {
            btnElement.classList.add('border-blue-600', 'bg-blue-50/50', 'text-blue-700', 'shadow-xs');
            btnElement.classList.remove('border-slate-200', 'bg-white', 'text-slate-700');
        }

        // Update Price and SKU
        document.getElementById('skuDisplay').textContent = currentVariant.sku;
        document.getElementById('salePriceDisplay').textContent = formatVND(currentVariant.sale_price);

        const basePriceEl = document.getElementById('basePriceDisplay');
        const discountBadgeEl = document.getElementById('discountBadge');

        if (Number(currentVariant.price) > Number(currentVariant.sale_price)) {
            basePriceEl.textContent = formatVND(currentVariant.price);
            basePriceEl.classList.remove('hidden');
            const diff = Number(currentVariant.price) - Number(currentVariant.sale_price);
            discountBadgeEl.textContent = 'Tiết kiệm ' + formatVND(diff);
            discountBadgeEl.classList.remove('hidden');
        } else {
            basePriceEl.classList.add('hidden');
            discountBadgeEl.classList.add('hidden');
        }

        // Update Installment estimate
        const installmentMonthly = Math.round(Number(currentVariant.sale_price) / 12);
        document.getElementById('installmentEstimate').innerHTML = `Chỉ từ <strong>${formatVND(installmentMonthly)}</strong>/tháng`;

        // Update Stock status
        const stockText = document.getElementById('stockText');
        if (currentVariant.stock_quantity > 0) {
            stockText.textContent = `Còn hàng (${currentVariant.stock_quantity} máy sẵn tại kho)`;
        } else {
            stockText.textContent = 'Tạm hết hàng (Đặt trước giao sau 48h)';
        }

        // ★ Rebuild the gallery thumbnails for this variant's images
        buildGalleryForVariant(variantId);
    }

    // ===== Color Swatch Selection =====
    function selectColor(colorName, btnElement) {
        document.getElementById('selectedColorName').textContent = colorName;

        // Toggle active border on color button
        document.querySelectorAll('.color-btn').forEach(btn => {
            btn.classList.remove('border-blue-600', 'bg-blue-50/50', 'text-blue-700', 'shadow-xs');
            btn.classList.add('border-slate-200', 'bg-white', 'text-slate-700');
        });
        if (btnElement) {
            btnElement.classList.add('border-blue-600', 'bg-blue-50/50', 'text-blue-700', 'shadow-xs');
            btnElement.classList.remove('border-slate-200', 'bg-white', 'text-slate-700');
        }

        // Find variant with this color and select it
        const variantWithColor = variants.find(v => v.color_name === colorName);
        if (variantWithColor) {
            const correspondingBtn = document.querySelector(`.variant-btn[data-variant-id="${variantWithColor.id}"]`);
            selectVariant(variantWithColor.id, correspondingBtn);
        }
    }

    // ===== Gallery helpers =====
    function switchMainImage(url, angle) {
        const mainImg = document.getElementById('mainProductImage');
        mainImg.style.opacity = '0';
        setTimeout(() => {
            mainImg.src = url;
            mainImg.style.opacity = '1';
        }, 150);

        const badge = document.getElementById('activeAngleBadge');
        if (badge) {
            const labelMap = { main: 'Mặt trước', back: 'Mặt sau', ports: 'Cổng kết nối' };
            badge.textContent = 'Góc: ' + (labelMap[angle] || angle);
        }
    }

    function selectGalleryImage(url, angle, variantId, btnElement) {
        switchMainImage(url, angle);
        document.querySelectorAll('.gallery-thumb-btn').forEach(btn => {
            btn.classList.remove('border-blue-600', 'bg-blue-50/30');
            btn.classList.add('border-slate-200');
        });
        if (btnElement) {
            btnElement.classList.add('border-blue-600', 'bg-blue-50/30');
            btnElement.classList.remove('border-slate-200');
        }
    }

    function filterGalleryAngle(angle) {
        currentAngleFilter = angle;

        document.querySelectorAll('.gallery-angle-btn').forEach(btn => {
            btn.classList.remove('bg-white', 'text-blue-600', 'shadow-xs');
            btn.classList.add('hover:text-blue-600');
        });
        const activeBtn = document.querySelector(`.gallery-angle-btn[data-angle="${angle}"]`);
        if (activeBtn) {
            activeBtn.classList.add('bg-white', 'text-blue-600', 'shadow-xs');
            activeBtn.classList.remove('hover:text-blue-600');
        }

        const thumbs = document.querySelectorAll('.gallery-thumb-btn');
        thumbs.forEach(t => {
            if (angle === 'all' || t.getAttribute('data-angle') === angle) {
                t.style.display = 'block';
            } else {
                t.style.display = 'none';
            }
        });

        // Pick first visible thumb
        const firstVisible = Array.from(thumbs).find(t => t.style.display !== 'none');
        if (firstVisible) {
            firstVisible.click();
        }
    }

    // ===== Real Add to Cart & Buy Now Integration =====
    async function addToCart() {
        if (!currentVariant) {
            window.showToast('Vui lòng chọn cấu hình sản phẩm trước khi mua', 'error');
            return;
        }

        const btn = document.getElementById('btnAddToCart');
        const originalText = btn.innerHTML;
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i><span>Đang thêm...</span>';
        btn.disabled = true;

        try {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            const res = await fetch('/cart/add', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    variant_id: currentVariant.id,
                    quantity: 1
                })
            });

            if (res.redirected) {
                window.location.href = res.url;
                return;
            }

            // Update badge
            const badges = document.querySelectorAll('[data-cart-count]');
            badges.forEach(badge => {
                const current = parseInt(badge.textContent.trim(), 10) || 0;
                badge.textContent = current + 1;
            });

            window.showToast(`Đã thêm ${currentVariant.sku || 'sản phẩm'} vào giỏ hàng!`, 'success');
        } catch (e) {
            window.location.href = '/cart';
        } finally {
            btn.innerHTML = originalText;
            btn.disabled = false;
        }
    }

    async function buyNow() {
        if (!currentVariant) {
            window.showToast('Vui lòng chọn phiên bản sản phẩm', 'error');
            return;
        }

        const btn = document.getElementById('btnBuyNow');
        btn.innerHTML = '<span>Đang chuyển sang giỏ hàng...</span>';
        btn.disabled = true;

        try {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            await fetch('/cart/add', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    variant_id: currentVariant.id,
                    quantity: 1
                })
            });

            window.location.href = '{{ route('cart.index') }}';
        } catch (e) {
            window.location.href = '{{ route('cart.index') }}';
        }
    }

    if (currentVariant) {
        buildGalleryForVariant(currentVariant.id);
    }
</script>
@endpush
