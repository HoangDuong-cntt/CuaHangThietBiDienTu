@extends('layouts.client')

@section('title', 'Cửa Hàng Thiết Bị Công Nghệ - SmartTech')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <!-- BREADCRUMB & HEADER BANNER -->
    <div class="mb-8">
        <nav class="flex items-center gap-2 text-xs text-slate-400 mb-3">
            <a href="{{ route('home') }}" class="hover:text-blue-600 transition-colors">Trang chủ</a>
            <i class="fa-solid fa-chevron-right text-[10px]"></i>
            <span class="text-slate-700 font-semibold">Cửa hàng</span>
            @if(request('category'))
                <i class="fa-solid fa-chevron-right text-[10px]"></i>
                <span class="text-blue-600 font-semibold">{{ request('category') }}</span>
            @endif
        </nav>

        <div class="bg-gradient-to-r from-slate-900 via-indigo-950 to-blue-950 rounded-3xl p-6 sm:p-10 text-white relative overflow-hidden shadow-xl">
            <div class="absolute -right-16 -bottom-16 w-80 h-80 bg-blue-500/20 rounded-full blur-3xl pointer-events-none"></div>
            <div class="relative z-10 max-w-2xl">
                <span class="px-3 py-1 rounded-full text-[11px] font-bold uppercase tracking-wider bg-blue-500/30 text-blue-300 border border-blue-400/30 inline-block mb-3">
                    🚀 Khám Phá Công Nghệ Mới
                </span>
                <h1 class="text-2xl sm:text-4xl font-extrabold tracking-tight mb-3">
                    Hệ Thống Thiết Bị Thông Minh Chính Hãng
                </h1>
                <p class="text-xs sm:text-sm text-slate-300 leading-relaxed">
                    Khám phá hơn 250 sản phẩm cao cấp từ Apple, Asus, Dell, MSI, Lenovo, Samsung, Xiaomi. Sử dụng bộ lọc thông số sâu và trợ lý AI để chọn máy ưng ý nhất!
                </p>
            </div>
        </div>
    </div>

    <!-- MAIN SHOP LAYOUT: SIDEBAR + PRODUCT GRID -->
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">

        <!-- ==================== SIDEBAR FILTER ==================== -->
        <aside class="lg:col-span-1 space-y-6">
            <form id="filter-form" action="{{ route('shop.index') }}" method="GET">
                @if(request('q'))
                    <input type="hidden" name="q" value="{{ request('q') }}">
                @endif
                @if(request('category'))
                    <input type="hidden" name="category" value="{{ request('category') }}">
                @endif
                @if(request('sort'))
                    <input type="hidden" name="sort" id="form-sort-input" value="{{ request('sort') }}">
                @endif

                <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-xs space-y-6">
                    <!-- Filter Header -->
                    <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-sliders text-blue-600 text-sm"></i>
                            <h3 class="font-bold text-sm text-slate-900">Bộ Lọc Nâng Cao</h3>
                        </div>
                        <a href="{{ route('shop.index') }}" class="text-xs font-semibold text-slate-400 hover:text-red-500 transition-colors">
                            Xóa tất cả
                        </a>
                    </div>

                    <!-- 1. Danh Mục Sản Phẩm -->
                    <div>
                        <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3">Danh Mục</h4>
                        <div class="space-y-1.5 max-h-48 overflow-y-auto pr-1 text-xs">
                            <a href="{{ route('shop.index', array_merge(request()->except('category', 'page'))) }}" 
                               class="flex items-center justify-between py-1.5 px-2.5 rounded-xl transition-colors {{ !request('category') ? 'bg-blue-50 text-blue-700 font-bold' : 'text-slate-600 hover:bg-slate-50' }}">
                                <span>Tất cả danh mục</span>
                                <span class="text-[10px] text-slate-400">{{ \App\Models\Product::where('is_active', true)->count() }}</span>
                            </a>
                            @foreach($categories as $cat)
                            <a href="{{ route('shop.index', array_merge(request()->except('page'), ['category' => $cat->slug])) }}" 
                               class="flex items-center justify-between py-1.5 px-2.5 rounded-xl transition-colors {{ request('category') === $cat->slug ? 'bg-blue-50 text-blue-700 font-bold' : 'text-slate-600 hover:bg-slate-50' }}">
                                <span>{{ $cat->name }}</span>
                                <span class="text-[10px] text-slate-400">{{ $cat->products_count }}</span>
                            </a>
                            @endforeach
                        </div>
                    </div>

                    <!-- 2. Thương Hiệu (Brands) -->
                    <div class="pt-4 border-t border-slate-100">
                        <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3">Thương Hiệu</h4>
                        <div class="space-y-2 max-h-48 overflow-y-auto pr-1 text-xs">
                            @php
                                $selectedBrands = (array) (request('brands') ? (is_array(request('brands')) ? request('brands') : explode(',', request('brands'))) : []);
                            @endphp
                            @foreach($brands as $brand)
                            <label class="flex items-center justify-between cursor-pointer group hover:text-blue-600">
                                <div class="flex items-center gap-2">
                                    <input 
                                        type="checkbox" 
                                        name="brands[]" 
                                        value="{{ $brand->id }}"
                                        {{ in_array($brand->id, $selectedBrands) ? 'checked' : '' }}
                                        onchange="document.getElementById('filter-form').submit()"
                                        class="rounded-sm border-slate-300 text-blue-600 focus:ring-blue-500 w-4 h-4 cursor-pointer"
                                    >
                                    <span class="text-slate-700 text-xs group-hover:text-blue-600 transition-colors">{{ $brand->name }}</span>
                                </div>
                                <span class="text-[10px] text-slate-400 font-normal">({{ $brand->products_count }})</span>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- 3. Khoảng Giá (Price Filter) -->
                    <div class="pt-4 border-t border-slate-100">
                        <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3">Khoảng Giá (VNĐ)</h4>
                        <div class="grid grid-cols-2 gap-2 text-xs mb-2.5">
                            <div>
                                <label class="text-[10px] text-slate-400 block mb-1">Từ (triệu):</label>
                                <input 
                                    type="number" 
                                    name="min_price" 
                                    value="{{ request('min_price') }}"
                                    placeholder="0"
                                    class="w-full px-2.5 py-1.5 rounded-lg border border-slate-200 text-xs focus:border-blue-500 focus:outline-hidden"
                                >
                            </div>
                            <div>
                                <label class="text-[10px] text-slate-400 block mb-1">Đến (triệu):</label>
                                <input 
                                    type="number" 
                                    name="max_price" 
                                    value="{{ request('max_price') }}"
                                    placeholder="100"
                                    class="w-full px-2.5 py-1.5 rounded-lg border border-slate-200 text-xs focus:border-blue-500 focus:outline-hidden"
                                >
                            </div>
                        </div>
                        <button type="submit" class="w-full py-1.5 bg-slate-100 hover:bg-blue-600 hover:text-white text-slate-700 text-xs font-semibold rounded-lg transition-colors">
                            Áp dụng giá
                        </button>

                        <!-- Quick Price Chips -->
                        <div class="grid grid-cols-2 gap-1.5 mt-2 text-[10px]">
                            <a href="{{ route('shop.index', array_merge(request()->except('page', 'min_price', 'max_price'), ['max_price' => 15])) }}" class="px-2 py-1 rounded-md text-center border transition-colors {{ request('max_price') == 15 && !request('min_price') ? 'bg-blue-50 text-blue-700 font-bold border-blue-200' : 'bg-slate-50 text-slate-600 hover:bg-slate-100 border-slate-200/60' }}">
                                &lt; 15 triệu
                            </a>
                            <a href="{{ route('shop.index', array_merge(request()->except('page', 'min_price', 'max_price'), ['min_price' => 15, 'max_price' => 25])) }}" class="px-2 py-1 rounded-md text-center border transition-colors {{ request('min_price') == 15 && request('max_price') == 25 ? 'bg-blue-50 text-blue-700 font-bold border-blue-200' : 'bg-slate-50 text-slate-600 hover:bg-slate-100 border-slate-200/60' }}">
                                15 - 25 triệu
                            </a>
                            <a href="{{ route('shop.index', array_merge(request()->except('page', 'min_price', 'max_price'), ['min_price' => 25, 'max_price' => 40])) }}" class="px-2 py-1 rounded-md text-center border transition-colors {{ request('min_price') == 25 && request('max_price') == 40 ? 'bg-blue-50 text-blue-700 font-bold border-blue-200' : 'bg-slate-50 text-slate-600 hover:bg-slate-100 border-slate-200/60' }}">
                                25 - 40 triệu
                            </a>
                            <a href="{{ route('shop.index', array_merge(request()->except('page', 'min_price', 'max_price'), ['min_price' => 40])) }}" class="px-2 py-1 rounded-md text-center border transition-colors {{ request('min_price') == 40 && !request('max_price') ? 'bg-blue-50 text-blue-700 font-bold border-blue-200' : 'bg-slate-50 text-slate-600 hover:bg-slate-100 border-slate-200/60' }}">
                                &gt; 40 triệu
                            </a>
                        </div>
                    </div>

                    <!-- 4. Lọc Theo Trọng Lượng (Weight Range) -->
                    <div class="pt-4 border-t border-slate-100">
                        <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-2.5">
                            <i class="fa-solid fa-feather-pointed text-amber-500 mr-1"></i>Trọng Lượng Thực Tế
                        </h4>
                        <div class="flex flex-col gap-1.5 text-xs">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="weight" value="" {{ !request('weight') ? 'checked' : '' }} onchange="document.getElementById('filter-form').submit()" class="text-blue-600">
                                <span>Tất cả trọng lượng</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="weight" value="ultralight" {{ request('weight') === 'ultralight' ? 'checked' : '' }} onchange="document.getElementById('filter-form').submit()" class="text-blue-600">
                                <span>🪶 Siêu nhẹ (&lt; 1.3 kg)</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="weight" value="standard" {{ request('weight') === 'standard' ? 'checked' : '' }} onchange="document.getElementById('filter-form').submit()" class="text-blue-600">
                                <span>💼 Tiêu chuẩn (1.3 - 1.8 kg)</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="weight" value="heavy" {{ request('weight') === 'heavy' ? 'checked' : '' }} onchange="document.getElementById('filter-form').submit()" class="text-blue-600">
                                <span>🏋️ Gaming / Trạm (&gt; 1.8 kg)</span>
                            </label>
                        </div>
                    </div>

                    <!-- 5. Lọc Theo Dung Lượng Pin (Battery) -->
                    <div class="pt-4 border-t border-slate-100">
                        <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-2.5">
                            <i class="fa-solid fa-battery-half text-emerald-500 mr-1"></i>Dung Lượng Pin
                        </h4>
                        <div class="flex flex-col gap-1.5 text-xs">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="battery" value="" {{ !request('battery') ? 'checked' : '' }} onchange="document.getElementById('filter-form').submit()" class="text-blue-600">
                                <span>Tất cả</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="battery" value="high" {{ request('battery') === 'high' ? 'checked' : '' }} onchange="document.getElementById('filter-form').submit()" class="text-blue-600">
                                <span>🔋 Pin khủng (&gt; 5.500 mAh / 70Wh)</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="battery" value="medium" {{ request('battery') === 'medium' ? 'checked' : '' }} onchange="document.getElementById('filter-form').submit()" class="text-blue-600">
                                <span>⚡ Trung bình (4.000 - 5.500 mAh)</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="battery" value="low" {{ request('battery') === 'low' ? 'checked' : '' }} onchange="document.getElementById('filter-form').submit()" class="text-blue-600">
                                <span>Tiêu chuẩn (&lt; 4.000 mAh)</span>
                            </label>
                        </div>
                    </div>

                    <!-- 6. Lọc Theo Màn Hình (Screen) -->
                    <div class="pt-4 border-t border-slate-100">
                        <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-2.5">
                            <i class="fa-solid fa-display text-blue-500 mr-1"></i>Kích Thước Màn Hình
                        </h4>
                        <div class="flex flex-col gap-1.5 text-xs">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="screen" value="" {{ !request('screen') ? 'checked' : '' }} onchange="document.getElementById('filter-form').submit()" class="text-blue-600">
                                <span>Tất cả</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="screen" value="small" {{ request('screen') === 'small' ? 'checked' : '' }} onchange="document.getElementById('filter-form').submit()" class="text-blue-600">
                                <span>Nhỏ gọn (&lt; 14 inch)</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="screen" value="medium" {{ request('screen') === 'medium' ? 'checked' : '' }} onchange="document.getElementById('filter-form').submit()" class="text-blue-600">
                                <span>Phổ thông (14 - 15.6 inch)</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="screen" value="large" {{ request('screen') === 'large' ? 'checked' : '' }} onchange="document.getElementById('filter-form').submit()" class="text-blue-600">
                                <span>Màn lớn (&gt; 15.6 inch)</span>
                            </label>
                        </div>
                    </div>
                </div>
            </form>

            <!-- 2 WIDGETS TRỰC QUAN HÓA THÔNG SỐ (THÀNH VIÊN 3) -->
            <div class="space-y-4">
                @include('components.weight-converter', ['grams' => 1240])
                @include('components.battery-simulator', ['mah' => 5000, 'wh' => 75])
            </div>
        </aside>

        <!-- ==================== PRODUCT GRID ==================== -->
        <section class="lg:col-span-3">

            <!-- Control Bar (Total count + Sorting dropdown) -->
            <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-xs mb-6 flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="text-xs text-slate-500">
                    Hiển thị <strong class="text-slate-900 font-bold">{{ $products->firstItem() ?? 0 }}-{{ $products->lastItem() ?? 0 }}</strong> trên tổng số <strong class="text-slate-900 font-bold">{{ $products->total() }}</strong> sản phẩm
                    @if(request('q'))
                        khớp từ khóa "<span class="text-blue-600 font-bold">{{ request('q') }}</span>"
                    @endif
                </div>

                <!-- Sorting Dropdown -->
                <div class="flex items-center gap-2 text-xs">
                    <label class="text-slate-400 font-medium shrink-0">Sắp xếp theo:</label>
                    <select 
                        id="sort-select"
                        onchange="handleSortChange(this.value)"
                        class="px-3 py-1.5 bg-slate-50 hover:bg-slate-100 rounded-xl border border-slate-200 text-slate-700 text-xs font-semibold focus:outline-hidden focus:border-blue-500 cursor-pointer shadow-2xs"
                    >
                        <option value="newest" {{ request('sort', 'newest') === 'newest' ? 'selected' : '' }}>Mới nhất</option>
                        <option value="price_asc" {{ request('sort') === 'price_asc' ? 'selected' : '' }}>Giá: Thấp đến Cao</option>
                        <option value="price_desc" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>Giá: Cao đến Thấp</option>
                        <option value="bestseller" {{ request('sort') === 'bestseller' ? 'selected' : '' }}>Bán chạy nhất</option>
                        <option value="weight_asc" {{ request('sort') === 'weight_asc' ? 'selected' : '' }}>Trọng lượng nhẹ nhất</option>
                        <option value="battery_desc" {{ request('sort') === 'battery_desc' ? 'selected' : '' }}>Pin trâu nhất</option>
                    </select>
                </div>
            </div>

            <!-- Product Items Grid -->
            @if($products->isEmpty())
                <div class="bg-white rounded-3xl p-12 text-center border border-slate-200 shadow-xs">
                    <div class="w-16 h-16 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 text-2xl mx-auto mb-4">
                        <i class="fa-solid fa-box-open"></i>
                    </div>
                    <h3 class="text-base font-bold text-slate-800 mb-1">Không tìm thấy sản phẩm nào</h3>
                    <p class="text-xs text-slate-500 max-w-sm mx-auto mb-6">Thử thay đổi bộ lọc khoảng giá, thương hiệu hoặc từ khóa tìm kiếm để khám phá nhiều sản phẩm hơn.</p>
                    <a href="{{ route('shop.index') }}" class="px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold transition-colors inline-block">
                        Khôi phục bộ lọc
                    </a>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-5">
                    @foreach($products as $product)
                    <div class="bg-white rounded-3xl p-4 border border-slate-200/80 hover:border-blue-300 hover:shadow-xl transition-all duration-300 flex flex-col justify-between group relative">

                        <!-- Badge Giảm giá hoặc Bán chạy -->
                        <div class="absolute top-4 left-4 z-10 flex flex-col gap-1">
                            @if($product->is_on_sale)
                                @php
                                    $discountPercent = round((($product->base_price - $product->sale_price) / $product->base_price) * 100);
                                @endphp
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-black bg-red-600 text-white shadow-xs">
                                    -{{ $discountPercent }}%
                                </span>
                            @endif
                            @if($product->total_sold > 150)
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-black bg-amber-500 text-white shadow-xs">
                                    🔥 HOT
                                </span>
                            @endif
                        </div>

                        <!-- Wishlist Toggle Button -->
                        <button 
                            type="button" 
                            onclick="toggleWishlist({{ $product->id }}, this)" 
                            class="absolute top-4 right-4 z-10 w-8 h-8 rounded-full bg-white/80 backdrop-blur-xs text-slate-400 hover:text-red-500 hover:bg-white flex items-center justify-center shadow-xs border border-slate-200 transition-colors"
                            title="Thêm vào yêu thích"
                        >
                            <i class="fa-regular fa-heart text-xs"></i>
                        </button>

                        <!-- Product Thumbnail -->
                        <div>
                            <a href="{{ route('products.show', $product->slug) }}" class="w-full h-44 rounded-2xl bg-slate-50/70 p-4 flex items-center justify-center overflow-hidden mb-3 border border-slate-100 block group-hover:border-blue-200 transition-colors">
                                <img 
                                    src="{{ $product->images->first()?->image_url ?? '/images/placeholder.svg' }}" 
                                    alt="{{ $product->name }}" 
                                    class="w-full h-full object-contain group-hover:scale-105 transition-transform duration-300"
                                    onerror="this.src='/images/placeholder.svg'"
                                >
                            </a>

                            <!-- Brand & SKU -->
                            <div class="flex items-center justify-between text-[11px] mb-1">
                                <span class="font-extrabold text-blue-600 uppercase tracking-wider">{{ $product->brand?->name }}</span>
                                <span class="text-slate-400 font-mono text-[10px]">{{ $product->sku_master }}</span>
                            </div>

                            <!-- Product Name (Linked to Detail Page) -->
                            <a href="{{ route('products.show', $product->slug) }}" class="block mb-2">
                                <h3 class="font-bold text-sm text-slate-900 group-hover:text-blue-600 transition-colors line-clamp-2 leading-snug">
                                    {{ $product->name }}
                                </h3>
                            </a>

                            <!-- Key Specs Pills (Cân nặng, Pin, Màn hình) -->
                            <div class="flex flex-wrap gap-1.5 mb-3 text-[10px] font-medium text-slate-600">
                                @if($product->weight_grams)
                                    <span class="px-2 py-0.5 rounded-md bg-slate-100 border border-slate-200/60" title="Trọng lượng thực tế">
                                        🪶 {{ $product->weight_grams < 1000 ? $product->weight_grams . 'g' : number_format($product->weight_grams / 1000, 2) . 'kg' }}
                                    </span>
                                @endif
                                @if($product->battery_mah)
                                    <span class="px-2 py-0.5 rounded-md bg-slate-100 border border-slate-200/60" title="Dung lượng pin">
                                        🔋 {{ number_format($product->battery_mah, 0, ',', '.') }}mAh
                                    </span>
                                @endif
                                @if($product->screen_size)
                                    <span class="px-2 py-0.5 rounded-md bg-slate-100 border border-slate-200/60" title="Màn hình">
                                        💻 {{ $product->screen_size }}"
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Price & Action Buttons -->
                        <div class="pt-3 border-t border-slate-100">
                            <div class="flex items-baseline gap-2 mb-3">
                                <span class="text-base font-extrabold text-red-600">
                                    {{ number_format($product->current_price, 0, ',', '.') }} đ
                                </span>
                                @if($product->is_on_sale)
                                    <span class="text-xs text-slate-400 line-through">
                                        {{ number_format($product->base_price, 0, ',', '.') }} đ
                                    </span>
                                @endif
                            </div>

                            <!-- Action Bar: Thêm Giỏ + So Sánh + Hỏi AI -->
                            <div class="grid grid-cols-3 gap-1.5">
                                <button 
                                    type="button" 
                                    onclick="addToCart({{ $product->variants->first()?->id ?? $product->id }}, 1)" 
                                    class="py-2 px-2 rounded-xl bg-blue-50 hover:bg-blue-600 text-blue-600 hover:text-white text-xs font-bold transition-all text-center flex items-center justify-center gap-1 cursor-pointer"
                                    title="Thêm vào giỏ hàng"
                                >
                                    <i class="fa-solid fa-cart-plus text-[11px]"></i>
                                    <span>+ Giỏ</span>
                                </button>

                                <button 
                                    type="button" 
                                    onclick="addToCompare({{ $product->id }}, {{ $product->category?->getRoot()->id ?? $product->category_id }}, '{{ addslashes($product->category?->getRoot()->name ?? $product->category?->name) }}')" 
                                    class="py-2 px-2 rounded-xl bg-slate-100 hover:bg-indigo-50 text-slate-700 hover:text-indigo-600 text-xs font-semibold text-center border border-slate-200/80 transition-colors flex items-center justify-center gap-1 cursor-pointer"
                                    title="So sánh với máy cùng loại"
                                >
                                    <i class="fa-solid fa-scale-balanced text-[11px]"></i>
                                    <span>So sánh</span>
                                </button>

                                <button 
                                    type="button" 
                                    onclick="window.SmartTechAI && window.SmartTechAI.setProductContext({{ $product->id }}); window.SmartTechAI && window.SmartTechAI.openChat();" 
                                    class="py-2 px-2 rounded-xl bg-slate-100 hover:bg-blue-50 text-slate-700 hover:text-blue-600 text-xs font-semibold text-center border border-slate-200/80 transition-colors flex items-center justify-center gap-1 cursor-pointer"
                                    title="Hỏi AI về máy này"
                                >
                                    <i class="fa-solid fa-sparkles text-amber-500 text-[11px]"></i>
                                    <span>Hỏi AI</span>
                                </button>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-8">
                    {{ $products->links() }}
                </div>
            @endif
        </section>
    </div>
</div>

@push('scripts')
<script>
function handleSortChange(sortValue) {
    if (!sortValue) return;
    const params = new URLSearchParams(window.location.search);
    params.set('sort', sortValue);
    params.delete('page');
    window.location.href = window.location.pathname + '?' + params.toString();
}

function addToCompare(productId, rootCatId, rootCatName) {
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

    // Ràng buộc nghiêm ngặt: Nếu đã có thiết bị trong bảng so sánh và thiết bị mới khác nhóm danh mục
    if (ids.length > 0 && compareCatId && rootCatId && parseInt(compareCatId, 10) !== parseInt(rootCatId, 10)) {
        const confirmChange = confirm(
            `Bảng so sánh hiện đang có thiết bị thuộc nhóm "${compareCatName}".\n\n` +
            `Hệ thống chỉ hỗ trợ so sánh giữa các thiết bị cùng nhóm danh mục (ví dụ: Máy tính với Máy tính, Điện thoại với Điện thoại).\n\n` +
            `Bạn có muốn tạo bảng so sánh mới cho nhóm "${rootCatName}" với thiết bị này không?`
        );

        if (!confirmChange) {
            return;
        }

        // Tạo bảng so sánh mới chỉ với thiết bị mới này
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
            alert('Bảng so sánh đã đủ 3 thiết bị. Chuyển sang bảng so sánh để xem hoặc bỏ bớt máy.');
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

function toggleWishlist(productId, btn) {
    fetch('/api/wishlist/toggle', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        },
        body: JSON.stringify({ product_id: productId })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            const icon = btn.querySelector('i');
            if (data.is_favorited) {
                icon.className = 'fa-solid fa-heart text-xs text-red-500';
            } else {
                icon.className = 'fa-regular fa-heart text-xs text-slate-400';
            }
        }
    });
}
</script>
@endpush
@endsection
