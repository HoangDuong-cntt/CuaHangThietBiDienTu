@extends('layouts.client')

@section('title', 'Danh Sách Thiết Bị Công Nghệ Chính Hãng - D&D.')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-6">

    <!-- Hero Banner (Chuẩn form mẫu D&D.) -->
    <div class="relative overflow-hidden rounded-3xl bg-[#f4f6f8] border border-slate-200/70 p-8 sm:p-10">
        <div class="relative z-10 max-w-2xl space-y-3">
            <span class="text-blue-600 font-bold text-[11px] uppercase tracking-wider block">
                CÔNG NGHỆ TINH GỌN &bull; TRẢI NGHIỆM TRỌN VẸN
            </span>
            <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-slate-900">
                Thiết Bị Công Nghệ Cao Cấp Chính Hãng
            </h1>
            <p class="text-xs sm:text-sm text-slate-500 leading-relaxed font-normal">
                Khám phá bộ sưu tập hơn 250+ mẫu Laptop Gaming, Laptop Doanh nhân, Flagship Smartphone và Phụ kiện cao cấp với chính sách bảo hành 1 đổi 1.
            </p>
            <div class="flex flex-wrap gap-3 pt-2">
                <div class="flex items-center gap-2 text-xs font-semibold text-slate-700 bg-white border border-slate-200 px-3 py-1.5 rounded-lg shadow-2xs">
                    <i class="fa-solid fa-award text-amber-500 text-xs"></i> 100% Chính Hãng
                </div>
                <div class="flex items-center gap-2 text-xs font-semibold text-slate-700 bg-white border border-slate-200 px-3 py-1.5 rounded-lg shadow-2xs">
                    <i class="fa-solid fa-bolt-lightning text-blue-500 text-xs"></i> Trả Góp 0% Lãi Suất
                </div>
                <div class="flex items-center gap-2 text-xs font-semibold text-slate-700 bg-white border border-slate-200 px-3 py-1.5 rounded-lg shadow-2xs">
                    <i class="fa-solid fa-repeat text-emerald-500 text-xs"></i> Đổi Mới 30 Ngày
                </div>
            </div>
        </div>
    </div>

    <!-- Brand Filter Strip -->
    <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs space-y-3">
        <div class="flex items-center justify-between">
            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500">
                <i class="fa-solid fa-certificate text-blue-600 mr-1.5"></i> Thương hiệu đối tác chính thức
            </h3>
            @if(request('brand'))
                <a href="{{ route('products.index', array_merge(request()->except('brand', 'page'))) }}" class="text-xs text-rose-600 hover:underline font-semibold flex items-center gap-1">
                    <i class="fa-solid fa-xmark"></i> Bỏ lọc hãng ({{ $currentBrand?->name }})
                </a>
            @endif
        </div>
        <div class="flex items-center gap-3 overflow-x-auto pb-2 scrollbar-none">
            <a
                href="{{ route('products.index', array_merge(request()->except('brand', 'page'))) }}"
                class="shrink-0 px-4 py-2 rounded-xl text-xs font-bold transition border {{ !request('brand') ? 'bg-blue-600 text-white border-blue-600 shadow-sm shadow-blue-500/30' : 'bg-slate-50 text-slate-700 border-slate-200 hover:border-blue-400 hover:bg-blue-50/50' }}"
            >
                Tất cả hãng
            </a>
            @foreach($brands as $b)
                <a
                    href="{{ route('products.index', array_merge(request()->except('page'), ['brand' => $b->slug])) }}"
                    class="shrink-0 flex items-center gap-2 px-3.5 py-2 rounded-xl text-xs font-semibold transition border {{ request('brand') === $b->slug ? 'bg-blue-600 text-white border-blue-600 shadow-sm shadow-blue-500/30' : 'bg-white text-slate-700 border-slate-200 hover:border-blue-400 hover:bg-blue-50/50' }}"
                >
                    @if($b->logo_url)
                        <img src="{{ $b->logo_url }}" alt="{{ $b->name }}" class="h-4 max-w-16 object-contain {{ request('brand') === $b->slug ? 'brightness-0 invert' : '' }}">
                    @endif
                    <span>{{ $b->name }}</span>
                </a>
            @endforeach
        </div>
    </div>

    <!-- Category Pills & Sort Bar -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <!-- Categories Pills -->
        <div class="flex items-center gap-2 overflow-x-auto pb-1 scrollbar-none">
            <a
                href="{{ route('products.index', array_merge(request()->except('category', 'page'))) }}"
                class="shrink-0 px-3.5 py-2 rounded-xl text-xs font-bold transition border {{ !request('category') ? 'bg-slate-900 text-white border-slate-900 shadow-xs' : 'bg-white text-slate-600 border-slate-200 hover:bg-slate-100' }}"
            >
                Tất cả danh mục
            </a>
            @foreach($categories as $cat)
                <a
                    href="{{ route('products.index', array_merge(request()->except('page'), ['category' => $cat->slug])) }}"
                    class="shrink-0 px-3.5 py-2 rounded-xl text-xs font-semibold transition border {{ request('category') === $cat->slug ? 'bg-slate-900 text-white border-slate-900 shadow-xs' : 'bg-white text-slate-600 border-slate-200 hover:bg-slate-100' }}"
                >
                    {{ $cat->name }}
                </a>
            @endforeach
        </div>

        <!-- Sort Select -->
        <div class="flex items-center gap-3 shrink-0 self-end md:self-auto">
            <span class="text-xs text-slate-500 font-medium">Sắp xếp:</span>
            <form action="{{ route('products.index') }}" method="GET" id="sortForm">
                @if(request('category')) <input type="hidden" name="category" value="{{ request('category') }}"> @endif
                @if(request('brand')) <input type="hidden" name="brand" value="{{ request('brand') }}"> @endif
                @if(request('keyword')) <input type="hidden" name="keyword" value="{{ request('keyword') }}"> @endif
                <select
                    name="sort"
                    onchange="document.getElementById('sortForm').submit()"
                    class="bg-white border border-slate-200 text-slate-700 text-xs rounded-xl px-3 py-2 font-medium focus:outline-hidden focus:border-blue-500 shadow-2xs"
                >
                    <option value="latest" {{ $sort === 'latest' ? 'selected' : '' }}>Mới nhất</option>
                    <option value="popular" {{ $sort === 'popular' ? 'selected' : '' }}>Bán chạy nhất</option>
                    <option value="price_asc" {{ $sort === 'price_asc' ? 'selected' : '' }}>Giá: Thấp đến Cao</option>
                    <option value="price_desc" {{ $sort === 'price_desc' ? 'selected' : '' }}>Giá: Cao đến Thấp</option>
                </select>
            </form>
        </div>
    </div>

    <!-- Active Filters Notification -->
    @if(request('keyword') || request('category') || request('brand'))
        <div class="flex items-center gap-2 text-xs text-slate-600 bg-blue-50/70 border border-blue-200/60 p-3 rounded-xl">
            <i class="fa-solid fa-filter text-blue-600"></i>
            <span>Đang lọc theo:</span>
            @if(request('keyword'))
                <span class="bg-white px-2.5 py-0.5 rounded-md font-semibold text-blue-700 border border-blue-200">Từ khóa: "{{ request('keyword') }}"</span>
            @endif
            @if($currentCategory)
                <span class="bg-white px-2.5 py-0.5 rounded-md font-semibold text-blue-700 border border-blue-200">Danh mục: {{ $currentCategory->name }}</span>
            @endif
            @if($currentBrand)
                <span class="bg-white px-2.5 py-0.5 rounded-md font-semibold text-blue-700 border border-blue-200">Hãng: {{ $currentBrand->name }}</span>
            @endif
            <a href="{{ route('products.index') }}" class="ml-auto text-rose-600 font-bold hover:underline">Xóa tất cả bộ lọc</a>
        </div>
    @endif

    <!-- Product Grid (4 columns) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @forelse($products as $product)
            @php
                $firstImg = $product->images->first()?->image_url ?? '/images/placeholder.svg';
                $discountPercent = $product->base_price > $product->sale_price 
                    ? round((($product->base_price - $product->sale_price) / $product->base_price) * 100) 
                    : 0;
                $firstVariant = $product->variants->first();
            @endphp
            <div class="tilt-card group bg-white rounded-3xl border border-slate-200/80 hover:border-slate-300 transition-all duration-300 flex flex-col overflow-hidden relative shadow-2xs" data-tilt>
                <!-- Badges -->
                <div class="absolute top-3.5 left-3.5 z-10 flex flex-col gap-1.5">
                    @if($discountPercent > 0)
                        <span class="bg-rose-50/95 text-rose-600 border border-rose-100 text-[10px] font-bold px-2.5 py-0.5 rounded-full shadow-2xs backdrop-blur-xs">
                            -{{ $discountPercent }}%
                        </span>
                    @endif
                    @if($product->total_sold > 30)
                        <span class="bg-amber-50/95 text-amber-700 border border-amber-100 text-[10px] font-bold px-2.5 py-0.5 rounded-full shadow-2xs backdrop-blur-xs">
                            <i class="fa-solid fa-fire mr-0.5"></i> Bán chạy
                        </span>
                    @endif
                </div>

                <!-- Brand tag -->
                <div class="absolute top-3.5 right-3.5 z-10">
                    <span class="text-[10px] font-bold uppercase tracking-wider bg-white/85 backdrop-blur-xs text-slate-600 px-2.5 py-0.5 rounded-full border border-slate-200/70">
                        {{ $product->brand?->name }}
                    </span>
                </div>

                <!-- Image container -->
                <a href="{{ route('products.show', $product->slug) }}" class="block relative aspect-[4/3] bg-[#F1F5F9] overflow-hidden">
                    <img
                        src="{{ $firstImg }}"
                        alt="{{ $product->name }}"
                        loading="lazy"
                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 ease-out"
                        onerror="this.src='/images/placeholder.svg'"
                    >
                </a>

                <!-- Product Body -->
                <div class="p-5 flex flex-col flex-grow space-y-3">
                    <!-- Category Name -->
                    <span class="text-[11px] font-semibold text-blue-600 uppercase tracking-wider">
                        {{ $product->category?->name }}
                    </span>

                    <!-- Product Name -->
                    <a href="{{ route('products.show', $product->slug) }}" class="text-sm font-bold text-slate-900 group-hover:text-blue-600 transition line-clamp-2 leading-snug">
                        {{ $product->name }}
                    </a>

                    <!-- Key Spec Pills -->
                    <div class="flex flex-wrap gap-1.5 text-[11px] text-slate-600">
                        @if($firstVariant && $firstVariant->ram)
                            <span class="bg-slate-100 px-2 py-0.5 rounded-md font-medium">{{ $firstVariant->ram }}</span>
                        @endif
                        @if($firstVariant && $firstVariant->storage)
                            <span class="bg-slate-100 px-2 py-0.5 rounded-md font-medium">{{ $firstVariant->storage }}</span>
                        @endif
                        @if($product->screen_size)
                            <span class="bg-slate-100 px-2 py-0.5 rounded-md font-medium">{{ $product->screen_size }}"</span>
                        @endif
                        @if($product->weight_grams)
                            <span class="bg-slate-100 px-2 py-0.5 rounded-md font-medium">{{ round($product->weight_grams / 1000, 2) }}kg</span>
                        @endif
                    </div>

                    <!-- Price Block -->
                    <div class="pt-2 mt-auto border-t border-slate-100">
                        <div class="flex items-baseline gap-2">
                            <span class="text-base font-black text-slate-900">
                                {{ number_format($product->sale_price, 0, ',', '.') }}₫
                            </span>
                            @if($product->base_price > $product->sale_price)
                                <span class="text-xs text-slate-400 line-through">
                                    {{ number_format($product->base_price, 0, ',', '.') }}₫
                                </span>
                            @endif
                        </div>
                        <div class="text-[11px] text-slate-400 mt-0.5 flex justify-between items-center">
                            <span>Bảo hành {{ $product->warranty_months }} tháng</span>
                            <span class="text-emerald-600 font-semibold">Còn {{ $product->variants->sum('stock_quantity') }} máy</span>
                        </div>
                    </div>

                    <!-- CTA Button -->
                    <a
                        href="{{ route('products.show', $product->slug) }}"
                        class="w-full text-center bg-blue-50 hover:bg-blue-600 text-blue-600 hover:text-white text-xs font-bold py-2.5 rounded-full transition duration-200 flex items-center justify-center gap-1.5"
                    >
                        <span>Xem chi tiết</span>
                        <i class="fa-solid fa-arrow-right text-[10px] group-hover:translate-x-0.5 transition-transform"></i>
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-full py-16 text-center space-y-4 bg-white rounded-3xl border border-slate-200">
                <div class="w-16 h-16 mx-auto rounded-full bg-slate-100 flex items-center justify-center text-slate-400 text-2xl">
                    <i class="fa-solid fa-box-open"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-700">Không tìm thấy sản phẩm phù hợp</h3>
                <p class="text-sm text-slate-500 max-w-md mx-auto">Vui lòng thử tìm kiếm với từ khóa khác hoặc xóa bớt các tiêu chí lọc danh mục / thương hiệu.</p>
                <a href="{{ route('products.index') }}" class="inline-block bg-blue-600 text-white text-xs font-bold px-6 py-2.5 rounded-full hover:bg-blue-700 transition">
                    Xóa tất cả bộ lọc
                </a>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="pt-6">
        {{ $products->links() }}
    </div>
</div>
@endsection
