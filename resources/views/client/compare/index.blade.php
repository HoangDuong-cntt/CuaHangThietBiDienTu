@extends('layouts.client')

@section('title', 'So Sánh Thông Số Đối Chiếu Thiết Bị - D&D.')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <!-- BREADCRUMB & HEADER -->
    <div class="mb-8">
        <nav class="flex items-center gap-2 text-xs text-slate-400 mb-3">
            <a href="{{ route('home') }}" class="hover:text-blue-600 transition-colors">Trang chủ</a>
            <i class="fa-solid fa-chevron-right text-[10px]"></i>
            <span class="text-slate-700 font-semibold">So sánh thiết bị</span>
        </nav>

        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white rounded-3xl p-6 border border-slate-200/80 shadow-xs">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="w-8 h-8 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-sm shadow-xs border border-indigo-100">
                        <i class="fa-solid fa-scale-balanced"></i>
                    </span>
                    <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight">Bảng So Sánh Đối Chiếu Thông Số</h1>
                </div>
                <p class="text-xs text-slate-500">
                    Đối chiếu trực quan từ 2 đến 3 sản phẩm. Thuật toán tự động đánh dấu 
                    <span class="inline-flex items-center gap-1 font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-md border border-emerald-200 text-[11px]">
                        <i class="fa-solid fa-crown text-[10px] text-amber-500"></i> Thuộc tính vượt trội
                    </span>
                </p>
            </div>

            <!-- Quick Action button -->
            <div class="flex items-center gap-2">
                @if(count($products) < 3)
                <button 
                    type="button"
                    onclick="openCompareModal()"
                    class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-full shadow-xs transition-all flex items-center gap-1.5 cursor-pointer"
                >
                    <i class="fa-solid fa-plus text-xs"></i>
                    <span>Thêm thiết bị so sánh</span>
                </button>
                @endif
                <a href="{{ route('shop.index') }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-full transition-colors">
                    Xem Cửa hàng
                </a>
            </div>
        </div>
    </div>

    @if(empty($products))
        <div class="bg-white rounded-3xl p-12 text-center border border-slate-200 shadow-xs max-w-2xl mx-auto my-8">
            <div class="w-20 h-20 rounded-3xl bg-blue-50 text-blue-600 flex items-center justify-center text-3xl mx-auto mb-5 shadow-xs border border-blue-100">
                <i class="fa-solid fa-scale-unbalanced-flip"></i>
            </div>
            <h3 class="text-lg font-extrabold text-slate-800 mb-2">Bảng so sánh đang trống</h3>
            <p class="text-xs text-slate-500 max-w-md mx-auto mb-6 leading-relaxed">
                Bạn đã xóa toàn bộ thiết bị khỏi bảng so sánh. Hãy bấm chọn thêm máy trực tiếp hoặc khôi phục hai thiết bị mẫu để tiếp tục đối chiếu cấu hình.
            </p>
            <div class="flex flex-wrap items-center justify-center gap-3">
                <button 
                    type="button" 
                    onclick="openCompareModal()" 
                    class="px-6 py-2.5 rounded-full bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold transition-all shadow-xs flex items-center gap-2 cursor-pointer"
                >
                    <i class="fa-solid fa-plus text-xs"></i>
                    <span>Thêm thiết bị so sánh</span>
                </button>
                <a href="{{ route('shop.index') }}" class="px-6 py-2.5 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition-colors inline-flex items-center gap-2">
                    <i class="fa-solid fa-store text-xs text-slate-400"></i>
                    <span>Xem Cửa hàng</span>
                </a>
                <a href="{{ route('compare.index') }}" class="px-6 py-2.5 rounded-full bg-blue-50 hover:bg-blue-100 text-blue-700 text-xs font-bold transition-colors inline-flex items-center gap-2">
                    <i class="fa-solid fa-rotate-left text-xs text-blue-500"></i>
                    <span>So sánh mẫu mặc định</span>
                </a>
            </div>
        </div>
    @else
        @if(!empty($mismatchedRemoved))
        <div class="mb-5 p-4 rounded-2xl bg-amber-50 border border-amber-200 text-amber-800 text-xs flex items-center gap-3 shadow-xs">
            <i class="fa-solid fa-circle-exclamation text-base text-amber-500 shrink-0"></i>
            <div>
                <strong>Ràng buộc cùng danh mục:</strong> Bảng so sánh chỉ hỗ trợ đối chiếu các thiết bị cùng nhóm danh mục 
                (<strong>{{ $rootCategory['name'] ?? 'cùng loại' }}</strong>). 
                Các sản phẩm thuộc nhóm danh mục khác đã được tự động loại bỏ để các tiêu chí kỹ thuật đối chiếu chính xác.
            </div>
        </div>
        @endif

        <!-- COMPARISON TABLE CONTAINER -->
        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full border-collapse text-left text-xs min-w-[700px]">

                    <!-- TABLE HEADER: PRODUCT CARDS -->
                    <thead>
                        <tr class="border-b border-slate-200 bg-slate-50/50">
                            <th class="p-4 sm:p-6 w-1/4 align-top border-r border-slate-200/80">
                                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block mb-1">Đang so sánh</span>
                                <div class="text-base font-extrabold text-slate-900 mb-2">{{ count($products) }} / 3 thiết bị</div>
                                <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-blue-50 text-blue-700 text-[11px] font-bold border border-blue-200/60 mb-2">
                                    <i class="fa-solid fa-layer-group text-[10px]"></i>
                                    <span>{{ $rootCategory['name'] ?? 'Thiết bị' }}</span>
                                </div>
                                <p class="text-[11px] text-slate-400">Ràng buộc cùng danh mục để đối chiếu cấu hình chuẩn xác.</p>
                            </th>

                            @foreach($products as $product)
                            <th class="p-4 sm:p-6 w-1/4 align-top border-r border-slate-200/80 last:border-r-0 relative group">
                                <!-- Remove product button (Luôn cho phép xóa kể cả máy cuối cùng) -->
                                <button 
                                    type="button"
                                    onclick="removeCompareProduct({{ $product['id'] }})"
                                    class="absolute top-4 right-4 w-7 h-7 rounded-full bg-slate-100 hover:bg-red-50 text-slate-400 hover:text-red-500 flex items-center justify-center transition-colors text-xs cursor-pointer"
                                    title="Xóa máy này khỏi so sánh"
                                >
                                    <i class="fa-solid fa-xmark"></i>
                                </button>

                                <!-- Product Info -->
                                <a href="{{ route('products.show', $product['slug']) }}" class="block group/link">
                                    <div class="w-32 h-24 mx-auto rounded-2xl bg-[#F1F5F9] flex items-center justify-center mb-3 overflow-hidden shadow-2xs group-hover/link:ring-2 group-hover/link:ring-blue-500/30 transition-all">
                                        <img src="{{ $product['image_url'] }}" alt="{{ $product['name'] }}" class="w-full h-full object-cover group-hover/link:scale-105 transition-transform" onerror="this.src='/images/placeholder.svg'">
                                    </div>
                                    <div class="text-[10px] font-extrabold text-blue-600 uppercase tracking-wider mb-1">{{ $product['brand'] }}</div>
                                    <h3 class="font-bold text-slate-900 text-sm leading-snug line-clamp-2 mb-2 group-hover/link:text-blue-600 transition-colors">{{ $product['name'] }}</h3>
                                </a>
                                
                                <div class="mb-3">
                                    <div class="text-base font-black text-slate-900">
                                        {{ number_format($product['current_price'], 0, ',', '.') }} đ
                                    </div>
                                    @if($product['is_on_sale'])
                                        <div class="text-[10px] text-slate-400 line-through">
                                            {{ number_format($product['base_price'], 0, ',', '.') }} đ
                                        </div>
                                    @endif
                                </div>

                                <button 
                                    type="button" 
                                    onclick="window.SmartTechAI && window.SmartTechAI.setProductContext({{ $product['id'] }}); window.SmartTechAI && window.SmartTechAI.openChat();"
                                    class="w-full py-2 px-4 rounded-full bg-[#7C3AED] hover:bg-[#6D28D9] text-white font-bold text-xs shadow-xs transition-all flex items-center justify-center gap-1.5 cursor-pointer"
                                >
                                    <i class="fa-solid fa-sparkles text-[11px]"></i>
                                    <span>Hỏi AI về máy này</span>
                                </button>
                            </th>
                            @endforeach

                            <!-- Slots thêm sản phẩm còn thiếu (để bảng luôn đủ 3 sản phẩm) -->
                            @for($i = count($products) + 1; $i <= 3; $i++)
                            <th class="p-4 sm:p-6 w-1/4 align-top border-r border-slate-200/80 last:border-r-0 border-dashed bg-slate-50/30 text-center">
                                <div onclick="openCompareModal()" class="h-full min-h-[260px] flex flex-col items-center justify-center p-6 rounded-2xl border-2 border-dashed border-slate-200 hover:border-blue-400 hover:bg-blue-50/30 transition-all group cursor-pointer">
                                    <div class="w-14 h-14 rounded-2xl bg-blue-50 group-hover:bg-blue-600 text-blue-600 group-hover:text-white flex items-center justify-center text-xl mb-3 transition-colors shadow-xs">
                                        <i class="fa-solid fa-plus"></i>
                                    </div>
                                    <h4 class="text-sm font-bold text-slate-800 mb-1">Thêm thiết bị thứ {{ $i }}</h4>
                                    <p class="text-xs text-slate-400 mb-4">Chọn thêm máy để so khớp</p>

                                    <div class="w-full max-w-[200px]">
                                        <div class="relative">
                                            <input 
                                                type="text" 
                                                placeholder="Gõ tên máy..." 
                                                readonly
                                                class="w-full py-2 pl-3 pr-8 text-xs bg-white border border-slate-200 rounded-xl text-slate-600 placeholder-slate-400 cursor-pointer shadow-xs pointer-events-none"
                                            >
                                            <div class="absolute right-3 top-2.5 text-slate-400 text-xs">
                                                <i class="fa-solid fa-magnifying-glass"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </th>
                            @endfor
                        </tr>
                    </thead>

                    <!-- TABLE BODY: SPECS COMPARISON -->
                    <tbody class="divide-y divide-slate-100">

                        <!-- SECTION 1: CÁC THÔNG SỐ CỐT LÕI -->
                        <tr class="bg-slate-100/70 font-bold text-slate-800">
                            <td colspan="4" class="py-2.5 px-6 uppercase tracking-wider text-[11px] text-blue-700">
                                <i class="fa-solid fa-star text-amber-500 mr-1.5"></i>Thông số cốt lõi quan trọng
                            </td>
                        </tr>

                        @foreach($coreSpecs as $spec)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="p-4 font-semibold text-slate-600 border-r border-slate-100">
                                {{ $spec['name'] }}
                            </td>
                            @foreach($products as $product)
                                @php
                                    $val = $spec['values'][$product['id']] ?? null;
                                    $isBest = $val['is_best'] ?? false;
                                @endphp
                                <td class="p-4 border-r border-slate-100 last:border-r-0 {{ $isBest ? 'bg-emerald-50/80 font-bold text-emerald-900' : 'text-slate-800' }}">
                                    <div class="flex items-center justify-between gap-2">
                                        <span>{{ $val['text'] ?? '-' }}</span>
                                        @if($isBest)
                                            <span class="inline-flex items-center gap-1 text-[10px] font-black uppercase tracking-wider px-2 py-0.5 rounded-full bg-emerald-200/80 text-emerald-800 shrink-0">
                                                <i class="fa-solid fa-crown text-[9px] text-amber-600"></i> Tốt nhất
                                            </span>
                                        @endif
                                    </div>
                                </td>
                            @endforeach
                            @for($i = count($products) + 1; $i <= 3; $i++)
                                <td class="p-4 border-r border-slate-100 last:border-r-0 bg-slate-50/20 text-slate-300 text-center">-</td>
                            @endfor
                        </tr>
                        @endforeach

                        <!-- SECTION 2: CHI TIẾT THEO TỪNG NHÓM THUỘC TÍNH (CPU, RAM, MÀN HÌNH...) -->
                        @foreach($specGroups as $group)
                        <tr class="bg-slate-100/70 font-bold text-slate-800">
                            <td colspan="4" class="py-2.5 px-6 uppercase tracking-wider text-[11px] text-indigo-700">
                                <i class="fa-solid fa-microchip text-indigo-500 mr-1.5"></i>{{ $group['group_name'] }}
                            </td>
                        </tr>

                        @foreach($group['specs'] as $spec)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="p-4 font-semibold text-slate-600 border-r border-slate-100">
                                {{ $spec['name'] }}
                                @if($spec['unit'])
                                    <span class="text-[10px] text-slate-400 font-normal">({{ $spec['unit'] }})</span>
                                @endif
                            </td>
                            @foreach($products as $product)
                                @php
                                    $val = $spec['values'][$product['id']] ?? null;
                                    $isBest = $val['is_best'] ?? false;
                                @endphp
                                <td class="p-4 border-r border-slate-100 last:border-r-0 {{ $isBest ? 'bg-emerald-50/80 font-bold text-emerald-900' : 'text-slate-800' }}">
                                    <div class="flex items-center justify-between gap-2">
                                        <span>{{ $val['text'] ?? '-' }}</span>
                                        @if($isBest)
                                            <span class="inline-flex items-center gap-1 text-[10px] font-black uppercase tracking-wider px-2 py-0.5 rounded-full bg-emerald-200/80 text-emerald-800 shrink-0">
                                                <i class="fa-solid fa-check text-[9px] text-emerald-600"></i> Tốt hơn
                                            </span>
                                        @endif
                                    </div>
                                </td>
                            @endforeach
                            @for($i = count($products) + 1; $i <= 3; $i++)
                                <td class="p-4 border-r border-slate-100 last:border-r-0 bg-slate-50/20 text-slate-300 text-center">-</td>
                            @endfor
                        </tr>
                        @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>

<!-- ==================== SEARCH & SELECT PRODUCT MODAL ==================== -->
<div id="compare-modal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-xl w-full shadow-2xl border border-slate-200 overflow-hidden transform transition-all">
        <!-- Modal Header -->
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <div class="flex items-center gap-2.5">
                <div class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-sm shadow-xs border border-blue-100">
                    <i class="fa-solid fa-scale-balanced"></i>
                </div>
                <div>
                    <h3 class="font-bold text-sm text-slate-900">Chọn thiết bị để so sánh</h3>
                    <p class="text-xs text-slate-500">
                        @if(!empty($rootCategory['name']))
                            Chỉ tìm trong nhóm: <span class="font-bold text-blue-600">{{ $rootCategory['name'] }}</span> (Đang có {{ count($products) }}/3 máy)
                        @else
                            Tìm kiếm và chọn thiết bị để bắt đầu so sánh
                        @endif
                    </p>
                </div>
            </div>
            <button type="button" onclick="closeCompareModal()" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center text-sm transition-colors cursor-pointer">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <!-- Modal Search Input -->
        <div class="p-5 border-b border-slate-100 bg-slate-50/50">
            <div class="relative">
                <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-3 text-slate-400 text-sm"></i>
                <input 
                    type="text" 
                    id="modal-search-input"
                    placeholder="Gõ tên máy hoặc hãng để tìm {{ !empty($rootCategory['name']) ? 'trong nhóm ' . $rootCategory['name'] : 'sản phẩm' }}..." 
                    class="w-full pl-10 pr-4 py-2.5 text-xs bg-white rounded-xl border border-slate-200 focus:outline-hidden focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10 shadow-2xs"
                    autocomplete="off"
                >
            </div>
            @php
                $catName = mb_strtolower($rootCategory['name'] ?? '');
                if (str_contains($catName, 'thoại') || str_contains($catName, 'phone')) {
                    $chips = ['iPhone 16', 'Galaxy S24', 'Xiaomi', 'Ultra', 'Plus'];
                } elseif (str_contains($catName, 'bảng') || str_contains($catName, 'tablet') || str_contains($catName, 'ipad')) {
                    $chips = ['iPad Pro', 'iPad Air', 'Galaxy Tab', 'Xiaomi Pad'];
                } elseif (str_contains($catName, 'âm thanh') || str_contains($catName, 'tai nghe') || str_contains($catName, 'loa')) {
                    $chips = ['Sony WH', 'AirPods Max', 'Marshall', 'JBL'];
                } elseif (str_contains($catName, 'phụ kiện') || str_contains($catName, 'chuột') || str_contains($catName, 'phím') || str_contains($catName, 'gear')) {
                    $chips = ['Logitech', 'Keychron', 'Razer', 'Chuột'];
                } else {
                    $chips = ['MacBook', 'Dell XPS', 'Asus ROG', 'ThinkPad', 'Legion'];
                }
            @endphp
            <!-- Quick Keyword Chips -->
            <div class="flex flex-wrap items-center gap-1.5 mt-2.5">
                <span class="text-[10px] text-slate-400 font-semibold mr-1">Gợi ý:</span>
                @foreach($chips as $chip)
                    <button type="button" onclick="quickSearchModal('{{ $chip }}')" class="px-2.5 py-0.5 rounded-md bg-white border border-slate-200 text-[11px] font-medium text-slate-600 hover:text-blue-600 hover:border-blue-300 transition-colors shadow-2xs cursor-pointer">
                        {{ $chip }}
                    </button>
                @endforeach
            </div>
        </div>

        <!-- Modal Results Container -->
        <div id="modal-search-results" class="max-h-80 overflow-y-auto divide-y divide-slate-100 p-2">
            <div class="p-8 text-center text-xs text-slate-400">
                <i class="fa-solid fa-spinner fa-spin text-lg mb-2 block text-blue-500"></i>
                Đang tải danh sách thiết bị...
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
(function() {
    // Danh sách ID sản phẩm hiện tại từ server
    let currentIds = "{{ $currentIds ?? '' }}".split(',').map(s => parseInt(s.trim(), 10)).filter(n => !isNaN(n) && n > 0);
    const baseProductId = {{ $products[0]['id'] ?? 0 }};
    const currentRootCatId = {{ $rootCategory['id'] ?? 0 }};
    const currentRootCatName = "{{ $rootCategory['name'] ?? '' }}";

    // Đồng bộ vào localStorage để khi qua Shop bấm So Sánh không bị mất
    localStorage.setItem('smarttech_compare_ids', JSON.stringify(currentIds));
    if (currentRootCatId) {
        localStorage.setItem('smarttech_compare_root_id', currentRootCatId);
        localStorage.setItem('smarttech_compare_root_name', currentRootCatName);
    }

    window.openCompareModal = function() {
        document.getElementById('compare-modal').classList.remove('hidden');
        const input = document.getElementById('modal-search-input');
        input.value = '';
        input.focus();
        searchModalProducts(''); // Tự động load danh sách gợi ý cùng nhóm danh mục
    };

    window.closeCompareModal = function() {
        document.getElementById('compare-modal').classList.add('hidden');
    };

    window.quickSearchModal = function(q) {
        document.getElementById('modal-search-input').value = q;
        searchModalProducts(q);
    };

    const searchInput = document.getElementById('modal-search-input');
    let timer = null;
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const q = this.value.trim();
            clearTimeout(timer);
            timer = setTimeout(() => {
                searchModalProducts(q);
            }, 250);
        });
    }

    async function searchModalProducts(q) {
        const resultsEl = document.getElementById('modal-search-results');
        resultsEl.innerHTML = '<div class="p-8 text-center text-xs text-slate-400"><i class="fa-solid fa-spinner fa-spin text-lg mb-2 block text-blue-500"></i>Đang tìm thiết bị cùng nhóm danh mục...</div>';

        try {
            const url = baseProductId > 0 
                ? `/api/products/compare-search?q=${encodeURIComponent(q)}&base_id=${baseProductId}` 
                : `/api/products/compare-search?q=${encodeURIComponent(q)}`;
            const res = await fetch(url);
            const data = await res.json();

            if (!data.results || data.results.length === 0) {
                resultsEl.innerHTML = `
                    <div class="p-8 text-center text-xs text-slate-400">
                        <i class="fa-solid fa-face-frown text-xl text-slate-300 mb-1.5 block"></i>
                        Không tìm thấy thiết bị nào khớp trong nhóm "<strong>${escapeHtml(currentRootCatName || 'danh mục này')}</strong>".
                        <div class="text-[11px] text-slate-400 mt-1">Hệ thống chỉ cho phép đối chiếu các thiết bị cùng loại.</div>
                    </div>
                `;
                return;
            }

            resultsEl.innerHTML = data.results.map(item => {
                const isAlreadyAdded = currentIds.includes(item.id);
                return `
                    <div class="p-3 hover:bg-slate-50 flex items-center justify-between gap-3 rounded-2xl transition-colors">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="w-12 h-12 rounded-xl bg-slate-100 p-1 flex items-center justify-center shrink-0 border border-slate-200">
                                <img src="${item.image_url}" class="max-h-full max-w-full object-contain" onerror="this.src='/images/placeholder.svg'">
                            </div>
                            <div class="min-w-0">
                                <div class="text-[10px] font-bold text-blue-600 uppercase tracking-wider">${escapeHtml(item.brand || '')}</div>
                                <h4 class="text-xs font-bold text-slate-800 truncate">${escapeHtml(item.name)}</h4>
                                <div class="text-xs font-extrabold text-red-600 mt-0.5">${item.price}</div>
                            </div>
                        </div>

                        ${isAlreadyAdded ? `
                            <span class="px-3 py-1.5 rounded-xl bg-slate-100 text-slate-400 text-xs font-semibold shrink-0 cursor-not-allowed">
                                <i class="fa-solid fa-check mr-1"></i>Đã có
                            </span>
                        ` : `
                            <button 
                                type="button" 
                                onclick="addCompareProduct(${item.id})"
                                class="px-3 py-1.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold shrink-0 transition-colors flex items-center gap-1 shadow-xs"
                            >
                                <i class="fa-solid fa-plus text-[10px]"></i>
                                <span>Chọn so sánh</span>
                            </button>
                        `}
                    </div>
                `;
            }).join('');
        } catch (e) {
            resultsEl.innerHTML = '<div class="p-6 text-center text-xs text-red-500">Lỗi khi tải danh sách sản phẩm.</div>';
        }
    }

    // HÀM THÊM SẢN PHẨM SO SÁNH (ĐẢM BẢO GIỮ NGUYÊN CÁC SẢN PHẨM CŨ)
    window.addCompareProduct = function(newId) {
        newId = parseInt(newId, 10);
        if (isNaN(newId)) return;

        if (!currentIds.includes(newId)) {
            if (currentIds.length >= 3) {
                alert('Bảng so sánh đã đủ 3 thiết bị. Vui lòng xóa bớt 1 máy trước khi thêm máy mới.');
                return;
            }
            currentIds.push(newId);
        }

        // Cập nhật localStorage
        localStorage.setItem('smarttech_compare_ids', JSON.stringify(currentIds));

        // Điều hướng tới URL mới giữ nguyên thứ tự
        window.location.href = `/compare?ids=${currentIds.join(',')}`;
    };

    // HÀM XÓA SẢN PHẨM KHỎI SO SÁNH
    window.removeCompareProduct = function(removeId) {
        removeId = parseInt(removeId, 10);
        currentIds = currentIds.filter(id => id !== removeId);

        localStorage.setItem('smarttech_compare_ids', JSON.stringify(currentIds));

        if (currentIds.length === 0) {
            window.location.href = `/compare?ids=`;
        } else {
            window.location.href = `/compare?ids=${currentIds.join(',')}`;
        }
    };

    // Đóng modal khi click ra ngoài
    const modal = document.getElementById('compare-modal');
    if (modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                closeCompareModal();
            }
        });
    }

    function escapeHtml(str) {
        return (str || '').replace(/[&<>"']/g, function(m) {
            return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' }[m];
        });
    }
})();
</script>
@endpush
@endsection
