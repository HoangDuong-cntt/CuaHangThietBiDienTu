@extends('layouts.admin')

@section('title', 'Voucher & Giảm giá')

@section('content')
<div class="space-y-6" x-data="{ 
    modalOpen: false,
    isEdit: false,
    formAction: '{{ route('admin.coupons.store') }}',
    formData: {
        id: null,
        code: '',
        discount_type: 'fixed_amount',
        discount_value: '',
        min_order_amount: '0',
        max_discount_amount: '',
        usage_limit: '',
        usage_limit_per_user: '1',
        starts_at: '',
        expires_at: '',
        is_active: true
    },
    openCreate() {
        this.isEdit = false;
        this.formAction = '{{ route('admin.coupons.store') }}';
        this.formData = {
            id: null,
            code: '',
            discount_type: 'fixed_amount',
            discount_value: '',
            min_order_amount: '0',
            max_discount_amount: '',
            usage_limit: '',
            usage_limit_per_user: '1',
            starts_at: '{{ now()->format('Y-m-d\TH:i') }}',
            expires_at: '{{ now()->addMonth()->format('Y-m-d\TH:i') }}',
            is_active: true
        };
        this.modalOpen = true;
    },
    openEdit(coupon) {
        this.isEdit = true;
        this.formAction = '/admin/coupons/' + coupon.id;
        this.formData = {
            id: coupon.id,
            code: coupon.code,
            discount_type: coupon.discount_type,
            discount_value: coupon.discount_value,
            min_order_amount: coupon.min_order_amount || '0',
            max_discount_amount: coupon.max_discount_amount || '',
            usage_limit: coupon.usage_limit || '',
            usage_limit_per_user: coupon.usage_limit_per_user || '1',
            starts_at: coupon.starts_at ? coupon.starts_at.substring(0, 16) : '',
            expires_at: coupon.expires_at ? coupon.expires_at.substring(0, 16) : '',
            is_active: Boolean(coupon.is_active)
        };
        this.modalOpen = true;
    },
    formatMoney(val) {
        if (!val || isNaN(val)) return '0 đ';
        return new Intl.NumberFormat('vi-VN').format(val) + ' đ';
    }
}">

    <!-- Page Header & Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-2 border-b border-slate-200">
        <div>
            <h1 class="text-xl font-bold tracking-tight text-slate-900 flex items-center gap-2.5">
                <span>Quản trị Voucher &amp; Mã Khuyến Mãi</span>
                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-mono font-semibold bg-purple-50 text-purple-700 border border-purple-200">
                    COUPON ENGINE
                </span>
            </h1>
            <p class="text-xs text-slate-500 mt-0.5">
                Cấu hình các chương trình giảm giá kích cầu mua sắm và quản lý hạn ngạch ngân sách khuyến mãi.
            </p>
        </div>

        <div class="flex items-center gap-3">
            <button @click="openCreate()" type="button" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold shadow-xs transition-colors">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                <span>Tạo mã giảm giá mới</span>
            </button>
        </div>
    </div>

    <!-- KPI Metric Cards Grid (Clean Modern Light Aesthetic) -->
    <div class="grid grid-cols-2 md:grid-cols-5 gap-3.5">
        <div class="p-4 rounded-xl bg-white border border-slate-200/80 shadow-xs flex flex-col justify-between">
            <span class="text-[11px] font-medium text-slate-500">Tổng số mã</span>
            <div class="mt-2 flex items-baseline justify-between">
                <span class="text-2xl font-bold text-slate-900 font-mono">{{ $stats['total'] }}</span>
                <span class="text-xs text-slate-400">chiến dịch</span>
            </div>
        </div>

        <div class="p-4 rounded-xl bg-white border border-slate-200/80 shadow-xs flex flex-col justify-between">
            <span class="text-[11px] font-medium text-emerald-600 flex items-center gap-1.5">
                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                Đang hoạt động
            </span>
            <div class="mt-2 flex items-baseline justify-between">
                <span class="text-2xl font-bold text-emerald-600 font-mono">{{ $stats['active'] }}</span>
                <span class="text-xs text-emerald-600/70">khả dụng</span>
            </div>
        </div>

        <div class="p-4 rounded-xl bg-white border border-slate-200/80 shadow-xs flex flex-col justify-between">
            <span class="text-[11px] font-medium text-rose-600 flex items-center gap-1.5">
                <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                Đã hết hạn
            </span>
            <div class="mt-2 flex items-baseline justify-between">
                <span class="text-2xl font-bold text-rose-600 font-mono">{{ $stats['expired'] }}</span>
                <span class="text-xs text-rose-600/70">quá hạn</span>
            </div>
        </div>

        <div class="p-4 rounded-xl bg-white border border-slate-200/80 shadow-xs flex flex-col justify-between">
            <span class="text-[11px] font-medium text-amber-600 flex items-center gap-1.5">
                <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                Hết lượt dùng
            </span>
            <div class="mt-2 flex items-baseline justify-between">
                <span class="text-2xl font-bold text-amber-600 font-mono">{{ $stats['exhausted'] }}</span>
                <span class="text-xs text-amber-600/70">hết quota</span>
            </div>
        </div>

        <div class="p-4 rounded-xl bg-white border border-slate-200/80 shadow-xs flex flex-col justify-between col-span-2 md:col-span-1">
            <span class="text-[11px] font-medium text-blue-600 flex items-center gap-1.5">
                <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                Tổng lượt áp dụng
            </span>
            <div class="mt-2 flex items-baseline justify-between">
                <span class="text-2xl font-bold text-blue-600 font-mono">{{ number_format($stats['total_used']) }}</span>
                <span class="text-xs text-blue-600/70">lượt</span>
            </div>
        </div>
    </div>

    <!-- Filter & Search Toolbar -->
    <div class="p-3.5 rounded-xl bg-white border border-slate-200/80 shadow-xs flex flex-col md:flex-row items-center justify-between gap-3">
        <form method="GET" action="{{ route('admin.coupons.index') }}" class="flex-1 w-full flex flex-col sm:flex-row items-center gap-3">
            
            <!-- Search Query Input -->
            <div class="relative flex-1 w-full">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}" 
                       placeholder="Tìm kiếm theo mã Voucher (ví dụ: TECH10, NEWUSER50K)..." 
                       class="w-full pl-9 pr-3 py-2 rounded-lg bg-white border border-slate-300 text-slate-800 text-xs placeholder:text-slate-400 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 uppercase font-mono">
            </div>

            <!-- Discount Type Filter -->
            <div class="w-full sm:w-48">
                <select name="discount_type" onchange="this.form.submit()" class="w-full px-3 py-2 rounded-lg bg-white border border-slate-300 text-slate-700 text-xs focus:outline-none focus:border-blue-500">
                    <option value="">-- Tất cả hình thức --</option>
                    <option value="percentage" {{ request('discount_type') === 'percentage' ? 'selected' : '' }}>Giảm phần trăm (%)</option>
                    <option value="fixed_amount" {{ request('discount_type') === 'fixed_amount' ? 'selected' : '' }}>Giảm tiền mặt (VNĐ)</option>
                </select>
            </div>

            <!-- Status Filter -->
            <div class="w-full sm:w-48">
                <select name="status" onchange="this.form.submit()" class="w-full px-3 py-2 rounded-lg bg-white border border-slate-300 text-slate-700 text-xs focus:outline-none focus:border-blue-500">
                    <option value="">-- Tất cả trạng thái --</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>🟢 Đang hoạt động</option>
                    <option value="expired" {{ request('status') === 'expired' ? 'selected' : '' }}>🔴 Đã hết hạn</option>
                    <option value="exhausted" {{ request('status') === 'exhausted' ? 'selected' : '' }}>🟡 Hết lượt sử dụng</option>
                    <option value="disabled" {{ request('status') === 'disabled' ? 'selected' : '' }}>⚪ Đã tạm tắt</option>
                </select>
            </div>

            <!-- Submit Filter Button -->
            <button type="submit" class="w-full sm:w-auto px-4 py-2 rounded-lg bg-slate-800 hover:bg-slate-900 text-white text-xs font-semibold transition-colors shadow-2xs">
                Lọc dữ liệu
            </button>

            @if(request()->hasAny(['search', 'discount_type', 'status']))
                <a href="{{ route('admin.coupons.index') }}" class="text-xs text-slate-500 hover:text-slate-800 font-medium">
                    Đặt lại
                </a>
            @endif
        </form>

        <div class="text-xs text-slate-500 font-medium shrink-0">
            Hiển thị: <strong class="text-slate-900">{{ $coupons->total() }}</strong> mã
        </div>
    </div>

    <!-- Coupons Data Table (Clean White Card) -->
    <div class="rounded-xl border border-slate-200/80 bg-white overflow-hidden shadow-xs">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead class="bg-slate-50 border-b border-slate-200 text-slate-500 font-semibold uppercase text-[10px] tracking-wider">
                    <tr>
                        <th class="py-3 px-5">Mã Voucher</th>
                        <th class="py-3 px-4">Loại hình &amp; Mức giảm</th>
                        <th class="py-3 px-4">Điều kiện đơn hàng</th>
                        <th class="py-3 px-4">Tiến độ sử dụng</th>
                        <th class="py-3 px-4">Thời gian hiệu lực</th>
                        <th class="py-3 px-4 text-center">Trạng thái</th>
                        <th class="py-3 px-5 text-right">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($coupons as $coupon)
                        @php
                            $badge = $coupon->status_badge;
                            $percentUsed = $coupon->usage_limit ? min(100, round(($coupon->used_count / $coupon->usage_limit) * 100)) : 0;
                        @endphp
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            
                            <!-- Coupon Code Pill -->
                            <td class="py-3.5 px-5">
                                <span class="px-2.5 py-1 rounded-md text-xs font-mono font-bold tracking-wider bg-purple-50 text-purple-700 border border-purple-200 select-all">
                                    {{ $coupon->code }}
                                </span>
                            </td>

                            <!-- Discount Type & Value -->
                            <td class="py-3.5 px-4">
                                <div class="flex flex-col">
                                    @if($coupon->discount_type === 'percentage')
                                        <div class="text-slate-900 font-semibold text-xs">
                                            Giảm {{ (int)$coupon->discount_value }}%
                                        </div>
                                        @if($coupon->max_discount_amount)
                                            <span class="text-[11px] text-slate-500 font-mono">
                                                Trần tối đa: {{ number_format($coupon->max_discount_amount) }} đ
                                            </span>
                                        @endif
                                    @else
                                        <div class="text-emerald-600 font-bold font-mono text-xs">
                                            -{{ number_format($coupon->discount_value) }} đ
                                        </div>
                                        <span class="text-[10px] text-slate-400">Tiền mặt trực tiếp</span>
                                    @endif
                                </div>
                            </td>

                            <!-- Order Minimum Condition -->
                            <td class="py-3.5 px-4 text-[11px]">
                                <div class="flex flex-col">
                                    <span class="text-slate-700">
                                        Đơn từ: <strong class="text-slate-900">{{ number_format($coupon->min_order_amount) }} đ</strong>
                                    </span>
                                    <span class="text-slate-400 text-[10px]">
                                        Tối đa {{ $coupon->usage_limit_per_user }} lần / khách
                                    </span>
                                </div>
                            </td>

                            <!-- Usage Progress Bar -->
                            <td class="py-3.5 px-4">
                                <div class="flex flex-col gap-1 w-32">
                                    <div class="flex justify-between text-[11px] font-mono">
                                        <span class="text-slate-900 font-semibold">{{ $coupon->used_count }}</span>
                                        <span class="text-slate-400">/ {{ $coupon->usage_limit ?? '∞' }}</span>
                                    </div>
                                    @if($coupon->usage_limit)
                                        <div class="w-full bg-slate-100 rounded-full h-1.5 overflow-hidden">
                                            <div class="h-1.5 rounded-full {{ $percentUsed >= 100 ? 'bg-amber-500' : 'bg-blue-600' }}" 
                                                 style="width: {{ $percentUsed }}%"></div>
                                        </div>
                                    @else
                                        <div class="text-[10px] text-slate-400 italic">Không giới hạn</div>
                                    @endif
                                </div>
                            </td>

                            <!-- Dates / Validity Range -->
                            <td class="py-3.5 px-4 text-[11px]">
                                <div class="flex flex-col">
                                    @if($coupon->expires_at)
                                        <span class="{{ $coupon->isExpired() ? 'text-rose-600 font-bold' : 'text-slate-800' }}">
                                            HSD: {{ $coupon->expires_at->format('d/m/Y H:i') }}
                                        </span>
                                        <span class="text-[10px] text-slate-400">
                                            {{ $coupon->isExpired() ? 'Đã quá hạn' : 'Còn ' . $coupon->expires_at->diffForHumans(null, true) }}
                                        </span>
                                    @else
                                        <span class="text-emerald-600 font-semibold">Vô thời hạn</span>
                                    @endif
                                </div>
                            </td>

                            <!-- Status Badge -->
                            <td class="py-3.5 px-4 text-center">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium border {{ $badge['class'] }}">
                                    {{ $badge['label'] }}
                                </span>
                            </td>

                            <!-- Action Buttons -->
                            <td class="py-3.5 px-5 text-right">
                                <div class="flex items-center justify-end gap-1">
                                    
                                    <!-- Toggle Active / Disabled Switch Button -->
                                    <form action="{{ route('admin.coupons.toggle-status', $coupon->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" 
                                                title="{{ $coupon->is_active ? 'Tạm ngưng mã này' : 'Kích hoạt lại mã' }}"
                                                class="p-1.5 rounded-md hover:bg-slate-100 {{ $coupon->is_active ? 'text-emerald-600' : 'text-slate-400' }} transition-colors">
                                            @if($coupon->is_active)
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                </svg>
                                            @else
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/>
                                                </svg>
                                            @endif
                                        </button>
                                    </form>

                                    <!-- Edit Button -->
                                    <button @click="openEdit({{ $coupon->toJson() }})" 
                                            type="button" 
                                            title="Chỉnh sửa mã giảm giá"
                                            class="p-1.5 rounded-md hover:bg-slate-100 text-slate-500 hover:text-blue-600 transition-colors">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </button>

                                    <!-- Delete Button -->
                                    <form action="{{ route('admin.coupons.destroy', $coupon->id) }}" 
                                          method="POST" 
                                          onsubmit="return confirm('Bạn có chắc chắn muốn xóa vĩnh viễn mã giảm giá [{{ $coupon->code }}]?');" 
                                          class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                title="Xóa voucher"
                                                class="p-1.5 rounded-md hover:bg-rose-50 text-rose-600 transition-colors">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </form>

                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-slate-400">
                                <svg class="w-8 h-8 mx-auto mb-2 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                                </svg>
                                Không tìm thấy mã giảm giá nào phù hợp với bộ lọc.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($coupons->hasPages())
            <div class="p-4 border-t border-slate-100 bg-slate-50/50">
                {{ $coupons->links() }}
            </div>
        @endif
    </div>

    <!-- Modal Tạo Mới / Chỉnh Sửa Voucher Kèm Live Card Preview -->
    <div x-show="modalOpen" 
         x-cloak 
         class="fixed inset-0 z-50 overflow-y-auto"
         aria-labelledby="modal-title" role="dialog" aria-modal="true">
        
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-xs transition-opacity" 
             @click="modalOpen = false"></div>

        <div class="flex min-h-full items-center justify-center p-4">
            <div class="relative w-full max-w-4xl rounded-2xl bg-white border border-slate-200 shadow-2xl p-6 overflow-hidden" 
                 @click.stop>
                
                <!-- Modal Header -->
                <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                    <h3 class="text-base font-bold text-slate-900 flex items-center gap-2">
                        <span x-text="isEdit ? 'Chỉnh sửa mã giảm giá: ' + formData.code : 'Tạo mới Mã Giảm Giá (Voucher)'"></span>
                    </h3>
                    <button @click="modalOpen = false" class="text-slate-400 hover:text-slate-600 p-1 rounded-lg hover:bg-slate-100">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <!-- 2 Columns: Form on Left, Live Card Preview on Right -->
                <form :action="formAction" method="POST" class="mt-5">
                    @csrf
                    <template x-if="isEdit">
                        <input type="hidden" name="_method" value="PUT">
                    </template>

                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                        
                        <!-- Left Column: Form Fields (7 cols) -->
                        <div class="lg:col-span-7 space-y-3.5">
                            
                            <!-- Coupon Code -->
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">
                                    Mã giảm giá (Code) <span class="text-rose-500">*</span>
                                </label>
                                <input type="text" 
                                       name="code" 
                                       x-model="formData.code" 
                                       @input="formData.code = formData.code.toUpperCase().replace(/\s+/g, '')"
                                       required
                                       placeholder="Ví dụ: TECH10, NEWUSER50K..." 
                                       class="w-full px-3 py-2 rounded-lg bg-white border border-slate-300 text-slate-900 font-mono font-bold uppercase text-xs focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                                <span class="text-[10px] text-slate-400">Tự động viết hoa và loại bỏ khoảng trắng.</span>
                            </div>

                            <!-- Discount Type & Value -->
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">
                                        Hình thức giảm <span class="text-rose-500">*</span>
                                    </label>
                                    <select name="discount_type" 
                                            x-model="formData.discount_type" 
                                            class="w-full px-3 py-2 rounded-lg bg-white border border-slate-300 text-slate-800 text-xs focus:outline-none focus:border-blue-500">
                                        <option value="fixed_amount">Tiền mặt cố định (VNĐ)</option>
                                        <option value="percentage">Theo tỷ lệ phần trăm (%)</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">
                                        Mức giảm <span class="text-rose-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <input type="number" 
                                               name="discount_value" 
                                               x-model="formData.discount_value" 
                                               required
                                               min="1"
                                               :max="formData.discount_type === 'percentage' ? 100 : ''"
                                               placeholder="Nhập số..." 
                                               class="w-full px-3 py-2 pr-10 rounded-lg bg-white border border-slate-300 text-slate-900 font-mono text-xs focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                                        <span class="absolute inset-y-0 right-0 pr-3 flex items-center text-[11px] font-medium text-slate-400 pointer-events-none"
                                              x-text="formData.discount_type === 'percentage' ? '%' : 'VNĐ'"></span>
                                    </div>
                                </div>
                            </div>

                            <!-- Min Order & Max Discount (Cap) -->
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">
                                        Đơn tối thiểu (VNĐ)
                                    </label>
                                    <input type="number" 
                                           name="min_order_amount" 
                                           x-model="formData.min_order_amount" 
                                           min="0"
                                           placeholder="0" 
                                           class="w-full px-3 py-2 rounded-lg bg-white border border-slate-300 text-slate-900 font-mono text-xs focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                                </div>

                                <div x-show="formData.discount_type === 'percentage'">
                                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">
                                        Giảm tối đa (Trần tiền)
                                    </label>
                                    <input type="number" 
                                           name="max_discount_amount" 
                                           x-model="formData.max_discount_amount" 
                                           min="0"
                                           placeholder="Để trống = Không trần" 
                                           class="w-full px-3 py-2 rounded-lg bg-white border border-slate-300 text-slate-900 font-mono text-xs focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                                </div>
                            </div>

                            <!-- Usage Quotas: Global & Per User -->
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">
                                        Tổng lượt toàn hệ thống
                                    </label>
                                    <input type="number" 
                                           name="usage_limit" 
                                           x-model="formData.usage_limit" 
                                           min="1"
                                           placeholder="Để trống = Vô hạn" 
                                           class="w-full px-3 py-2 rounded-lg bg-white border border-slate-300 text-slate-900 font-mono text-xs focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">
                                        Lượt mỗi khách hàng <span class="text-rose-500">*</span>
                                    </label>
                                    <input type="number" 
                                           name="usage_limit_per_user" 
                                           x-model="formData.usage_limit_per_user" 
                                           required
                                           min="1"
                                           placeholder="1" 
                                           class="w-full px-3 py-2 rounded-lg bg-white border border-slate-300 text-slate-900 font-mono text-xs focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                                </div>
                            </div>

                            <!-- Time Window -->
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">
                                        Bắt đầu áp dụng
                                    </label>
                                    <input type="datetime-local" 
                                           name="starts_at" 
                                           x-model="formData.starts_at" 
                                           class="w-full px-3 py-2 rounded-lg bg-white border border-slate-300 text-slate-800 font-mono text-xs focus:outline-none focus:border-blue-500">
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">
                                        Kết thúc hiệu lực
                                    </label>
                                    <input type="datetime-local" 
                                           name="expires_at" 
                                           x-model="formData.expires_at" 
                                           class="w-full px-3 py-2 rounded-lg bg-white border border-slate-300 text-slate-800 font-mono text-xs focus:outline-none focus:border-blue-500">
                                </div>
                            </div>

                            <!-- Is Active Checkbox -->
                            <div class="flex items-center gap-2 pt-1">
                                <input type="checkbox" 
                                       id="is_active" 
                                       name="is_active" 
                                       value="1" 
                                       x-model="formData.is_active"
                                       class="rounded bg-white border-slate-300 text-blue-600 focus:ring-blue-500">
                                <label for="is_active" class="text-xs text-slate-700 select-none cursor-pointer font-medium">
                                    Kích hoạt hoạt động ngay sau khi lưu
                                </label>
                            </div>

                        </div>

                        <!-- Right Column: Live Interactive Voucher Ticket Preview (5 cols) -->
                        <div class="lg:col-span-5 flex flex-col justify-between p-5 rounded-xl bg-slate-50 border border-slate-200">
                            <div>
                                <span class="text-[11px] font-semibold text-slate-500 block mb-3 uppercase tracking-wider">
                                    Xem trước thẻ Voucher
                                </span>

                                <!-- Ticket Box -->
                                <div class="rounded-xl bg-gradient-to-br from-indigo-600 to-purple-700 text-white p-4 relative overflow-hidden shadow-md">
                                    <div class="absolute -right-6 -bottom-6 w-24 h-24 rounded-full bg-white/10 pointer-events-none"></div>

                                    <div class="flex items-center justify-between border-b border-white/20 pb-2.5 mb-3">
                                        <div class="flex items-center gap-2">
                                            <div class="w-6 h-6 rounded bg-white text-indigo-700 flex items-center justify-center font-bold text-[10px]">
                                                D&D
                                            </div>
                                            <span class="text-[10px] font-bold tracking-wider text-indigo-100 uppercase">SMARTTECH VOUCHER</span>
                                        </div>
                                        <span class="px-2 py-0.5 rounded text-[9px] font-bold bg-white/20 text-white"
                                              x-text="formData.discount_type === 'percentage' ? (formData.discount_value || '0') + '% OFF' : 'GIẢM TIỀN MẶT'">
                                        </span>
                                    </div>

                                    <!-- Code Big Title -->
                                    <div class="text-xl font-bold font-mono tracking-wider text-white mb-2"
                                         x-text="formData.code || 'MÃ VOUCHER'">
                                    </div>

                                    <!-- Benefit Description -->
                                    <div class="text-xs text-indigo-100 font-medium mb-3">
                                        <template x-if="formData.discount_type === 'percentage'">
                                            <span>
                                                Giảm <strong class="text-white font-bold" x-text="(formData.discount_value || '0') + '%'"></strong> 
                                                cho đơn hàng từ <strong class="text-white" x-text="formatMoney(formData.min_order_amount)"></strong>
                                                <template x-if="formData.max_discount_amount">
                                                    <span class="block text-[10px] text-indigo-200 mt-0.5">
                                                        (Trần giảm tối đa: <span x-text="formatMoney(formData.max_discount_amount)"></span>)
                                                    </span>
                                                </template>
                                            </span>
                                        </template>
                                        <template x-if="formData.discount_type === 'fixed_amount'">
                                            <span>
                                                Giảm ngay <strong class="text-emerald-300 font-bold" x-text="formatMoney(formData.discount_value)"></strong> 
                                                cho đơn từ <strong class="text-white" x-text="formatMoney(formData.min_order_amount)"></strong>
                                            </span>
                                        </template>
                                    </div>

                                    <!-- Footer info inside ticket -->
                                    <div class="pt-2 border-t border-white/20 flex items-center justify-between text-[10px] font-mono text-indigo-200">
                                        <span>Quota: <span class="text-white" x-text="formData.usage_limit || 'Vô hạn'"></span></span>
                                        <span x-text="formData.expires_at ? 'HSD: ' + formData.expires_at.replace('T', ' ') : 'Vô thời hạn'"></span>
                                    </div>
                                </div>

                                <p class="text-xs text-slate-500 mt-3 leading-relaxed">
                                    Thẻ này mô phỏng giao diện khách hàng sẽ nhìn thấy tại trang Thanh toán &amp; Giỏ hàng.
                                </p>
                            </div>

                            <!-- Modal Submit Actions -->
                            <div class="pt-4 border-t border-slate-200 flex items-center justify-end gap-3 mt-4">
                                <button @click="modalOpen = false" type="button" class="px-4 py-2 rounded-lg bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 text-xs font-semibold transition-colors">
                                    Hủy bỏ
                                </button>
                                <button type="submit" class="px-4 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold shadow-xs transition-colors">
                                    <span x-text="isEdit ? 'Lưu cập nhật voucher' : 'Tạo mới mã giảm giá'"></span>
                                </button>
                            </div>
                        </div>

                    </div>
                </form>

            </div>
        </div>
    </div>

</div>
@endsection
