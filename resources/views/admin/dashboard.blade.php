@extends('layouts.admin')

@section('title', 'Dashboard Báo cáo')

@section('content')
<div class="space-y-6">

    <!-- Page Header & Quick Controls -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-2 border-b border-slate-200">
        <div>
            <h1 class="text-xl font-bold tracking-tight text-slate-900 flex items-center gap-2.5">
                <span>Trung tâm Điều hành &amp; Giám sát</span>
                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-mono font-semibold bg-blue-50 text-blue-700 border border-blue-200">
                    LIVE SYSTEM
                </span>
            </h1>
            <p class="text-xs text-slate-500 mt-0.5">
                Tổng quan kiến trúc phân quyền động, giám sát người dùng và bảo mật hệ thống.
            </p>
        </div>

        <div class="flex items-center gap-2.5">
            @if(auth()->user()->hasPermission('manage_roles'))
                <a href="{{ route('admin.roles.index') }}" class="inline-flex items-center gap-2 px-3.5 py-2 rounded-lg bg-white hover:bg-slate-50 text-slate-700 border border-slate-300 text-xs font-semibold transition-colors shadow-2xs">
                    <svg class="w-4 h-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                    </svg>
                    <span>Ma trận Phân quyền</span>
                </a>
            @endif

            @if(auth()->user()->hasPermission('manage_users'))
                <a href="{{ route('admin.staff.index') }}" class="inline-flex items-center gap-2 px-3.5 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold shadow-xs transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    <span>Quản lý Nhân sự</span>
                </a>
            @endif
        </div>
    </div>

    <!-- Metric Cards Grid (Clean Modern White Cards) -->
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3.5">
        <!-- Customers Metric -->
        <div class="p-4 rounded-xl bg-white border border-slate-200/80 shadow-xs">
            <div class="flex items-center justify-between text-slate-500 mb-2">
                <span class="text-[11px] font-medium text-slate-500">Khách hàng</span>
                <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
            </div>
            <div class="text-2xl font-bold font-mono text-slate-900 tracking-tight">
                {{ number_format($stats['total_customers']) }}
            </div>
            <div class="text-[11px] text-emerald-600 font-medium mt-1 flex items-center gap-1">
                <span>&bull;</span> Đã đăng ký
            </div>
        </div>

        @if(auth()->user()->hasPermission('manage_users'))
            <!-- Staff Metric -->
            <div class="p-4 rounded-xl bg-white border border-slate-200/80 shadow-xs">
                <div class="flex items-center justify-between text-slate-500 mb-2">
                    <span class="text-[11px] font-medium text-slate-500">Nhân sự</span>
                    <svg class="w-4 h-4 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <div class="text-2xl font-bold font-mono text-slate-900 tracking-tight">
                    {{ number_format($stats['total_staff']) }}
                </div>
                <div class="text-[11px] text-blue-600 font-medium mt-1 flex items-center gap-1">
                    <span>&bull;</span> Nhân viên
                </div>
            </div>
        @endif

        @if(auth()->user()->hasPermission('manage_roles'))
            <!-- Roles Metric -->
            <div class="p-4 rounded-xl bg-white border border-slate-200/80 shadow-xs">
                <div class="flex items-center justify-between text-slate-500 mb-2">
                    <span class="text-[11px] font-medium text-slate-500">Vai trò (Roles)</span>
                    <svg class="w-4 h-4 text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
                <div class="text-2xl font-bold font-mono text-slate-900 tracking-tight">
                    {{ number_format($stats['total_roles']) }}
                </div>
                <div class="text-[11px] text-purple-600 font-medium mt-1 flex items-center gap-1">
                    <span>&bull;</span> Nhóm vai trò
                </div>
            </div>

            <!-- Permissions Metric -->
            <div class="p-4 rounded-xl bg-white border border-slate-200/80 shadow-xs">
                <div class="flex items-center justify-between text-slate-500 mb-2">
                    <span class="text-[11px] font-medium text-slate-500">Quyền hạn</span>
                    <svg class="w-4 h-4 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                    </svg>
                </div>
                <div class="text-2xl font-bold font-mono text-slate-900 tracking-tight">
                    {{ number_format($stats['total_permissions']) }}
                </div>
                <div class="text-[11px] text-amber-600 font-medium mt-1 flex items-center gap-1">
                    <span>&bull;</span> Phân hệ RBAC
                </div>
            </div>
        @endif

        @if(auth()->user()->hasPermission('manage_coupons'))
            <!-- Coupons Metric -->
            <div class="p-4 rounded-xl bg-white border border-slate-200/80 shadow-xs">
                <div class="flex items-center justify-between text-slate-500 mb-2">
                    <span class="text-[11px] font-medium text-slate-500">Voucher</span>
                    <svg class="w-4 h-4 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                    </svg>
                </div>
                <div class="text-2xl font-bold font-mono text-slate-900 tracking-tight">
                    {{ number_format($stats['active_coupons']) }}
                </div>
                <div class="text-[11px] text-emerald-600 font-medium mt-1 flex items-center gap-1">
                    <span>&bull;</span> Đang hoạt động
                </div>
            </div>
        @endif

        @if(auth()->user()->hasPermission('manage_reviews'))
            <!-- Reviews Metric -->
            <div class="p-4 rounded-xl bg-white border border-slate-200/80 shadow-xs">
                <div class="flex items-center justify-between text-slate-500 mb-2">
                    <span class="text-[11px] font-medium text-slate-500">Đánh giá</span>
                    <svg class="w-4 h-4 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                    </svg>
                </div>
                <div class="text-2xl font-bold font-mono text-slate-900 tracking-tight">
                    {{ number_format($stats['total_reviews']) }}
                </div>
                <div class="text-[11px] text-yellow-600 font-medium mt-1 flex items-center gap-1">
                    <span>&bull;</span> Phản hồi
                </div>
            </div>
        @endif
    </div>

    <!-- Main Workspace Split: System Telemetry & Role Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        @if(auth()->user()->hasPermission('manage_users'))
            <!-- Left 2 Cols: Recent Staff Table for Authorized Admin/HR -->
            <div class="lg:col-span-2 rounded-xl border border-slate-200/80 bg-white overflow-hidden shadow-xs">
                <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
                    <div class="flex items-center gap-2.5">
                        <div class="w-2 h-2 rounded-full bg-blue-600"></div>
                        <h2 class="text-xs font-bold uppercase tracking-wider text-slate-800">Nhân sự quản trị gần đây</h2>
                    </div>
                    <a href="{{ route('admin.staff.index') }}" class="text-xs font-medium text-blue-600 hover:text-blue-700">
                        Xem tất cả &rarr;
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-slate-50 border-b border-slate-100 text-slate-500 font-semibold text-[10px] uppercase">
                            <tr>
                                <th class="py-3 px-4">Nhân sự</th>
                                <th class="py-3 px-4">Số điện thoại</th>
                                <th class="py-3 px-4">Vai trò (Role)</th>
                                <th class="py-3 px-4 text-center">Trạng thái</th>
                                <th class="py-3 px-4 text-right">Khởi tạo</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700">
                            @forelse($recentStaff as $staff)
                                <tr class="hover:bg-slate-50/70 transition-colors">
                                    <td class="py-3 px-4 flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-slate-100 border border-slate-200 flex items-center justify-center font-bold text-xs text-slate-700 uppercase shrink-0">
                                            {{ substr($staff->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="font-semibold text-slate-900 flex items-center gap-1.5">
                                                <span>{{ $staff->name }}</span>
                                                @if($staff->role === 'admin')
                                                    <span class="px-1.5 py-0.2 rounded text-[9px] font-mono font-bold bg-rose-50 text-rose-700 border border-rose-200">ROOT</span>
                                                @endif
                                            </div>
                                            <div class="text-[11px] text-slate-400">{{ $staff->email }}</div>
                                        </div>
                                    </td>
                                    <td class="py-3 px-4 font-mono text-slate-600">
                                        {{ $staff->phone ?? '---' }}
                                    </td>
                                    <td class="py-3 px-4">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-medium bg-slate-100 text-slate-700 border border-slate-200">
                                            {{ $staff->roleModel->display_name ?? ($staff->role === 'admin' ? 'Quản trị viên' : 'Nhân viên') }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-4 text-center">
                                        @if($staff->is_active)
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-medium bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Active
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-medium bg-rose-50 text-rose-700 border border-rose-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> Locked
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-4 text-right font-mono text-[11px] text-slate-400">
                                        {{ $staff->created_at->format('d/m/Y') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-8 text-center text-slate-400 text-xs">
                                        Chưa có nhân sự nào trong danh sách.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @else
            <!-- Dedicated Workspace Info for Specialized Personnel -->
            <div class="lg:col-span-2 rounded-xl border border-slate-200/80 bg-white p-6 space-y-5 shadow-xs">
                <div class="flex items-center gap-3 pb-4 border-b border-slate-100">
                    <div class="w-10 h-10 rounded-xl bg-blue-50 border border-blue-200 text-blue-600 flex items-center justify-center font-bold text-sm">
                        ST
                    </div>
                    <div>
                        <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wider">
                            Không gian làm việc: {{ auth()->user()->roleModel->display_name ?? 'Nhân sự vận hành' }}
                        </h2>
                        <p class="text-xs text-slate-500 mt-0.5">
                            Tài khoản phân quyền theo chuyên môn. Bạn chỉ truy cập được các chức năng được cấp phép.
                        </p>
                    </div>
                </div>

                <div class="space-y-3">
                    <div class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                        Các module nghiệp vụ được phân công:
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
                        @if(auth()->user()->hasPermission('manage_orders') || auth()->user()->hasPermission('view_inventory'))
                            <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200 space-y-1">
                                <div class="font-bold text-emerald-700 flex items-center gap-2">
                                    <span>📦</span> Đơn hàng &amp; Quản lý Kho
                                </div>
                                <p class="text-[11px] text-slate-500">
                                    Theo dõi danh sách xuất/nhập, kiểm soát hàng tồn kho và phê duyệt vận chuyển.
                                </p>
                            </div>
                        @endif

                        @if(auth()->user()->hasPermission('manage_products'))
                            <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200 space-y-1">
                                <div class="font-bold text-blue-700 flex items-center gap-2">
                                    <span>🛍️</span> Sản phẩm &amp; Danh mục 3D
                                </div>
                                <p class="text-[11px] text-slate-500">
                                    Cập nhật số lượng sản phẩm, giá bán, biến thể và cấu hình trình diễn 3D.
                                </p>
                            </div>
                        @endif

                        @if(auth()->user()->hasPermission('manage_coupons'))
                            <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200 space-y-1">
                                <div class="font-bold text-purple-700 flex items-center gap-2">
                                    <span>🎟️</span> Khuyến mãi &amp; Voucher
                                </div>
                                <p class="text-[11px] text-slate-500">
                                    Quản lý mã giảm giá, giới hạn ngân sách voucher và chương trình ưu đãi.
                                </p>
                            </div>
                        @endif

                        @if(auth()->user()->hasPermission('manage_reviews') || auth()->user()->hasPermission('manage_banners'))
                            <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200 space-y-1">
                                <div class="font-bold text-yellow-700 flex items-center gap-2">
                                    <span>⭐</span> Đánh giá &amp; Banners
                                </div>
                                <p class="text-[11px] text-slate-500">
                                    Kiểm duyệt phản hồi của khách hàng và điều chỉnh slider banner trang chủ.
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endif

        <!-- Right 1 Col: Security & Specs -->
        <div class="space-y-4">

            <!-- Security Policy Summary -->
            <div class="rounded-xl border border-slate-200/80 bg-white p-5 shadow-xs">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-800">Chính sách Bảo mật</span>
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                        ENFORCED
                    </span>
                </div>

                <div class="mt-3.5 space-y-3 text-xs">
                    <div class="flex items-start justify-between">
                        <div>
                            <div class="font-semibold text-slate-800">Xác thực 2 lớp / Signed URL</div>
                            <div class="text-[11px] text-slate-400">Email Verification Sha256</div>
                        </div>
                        <span class="text-[10px] font-bold text-emerald-600 font-mono">ACTIVE</span>
                    </div>

                    <div class="flex items-start justify-between">
                        <div>
                            <div class="font-semibold text-slate-800">Dynamic RBAC Gate</div>
                            <div class="text-[11px] text-slate-400">Middleware Protection</div>
                        </div>
                        <span class="text-[10px] font-bold text-blue-600 font-mono">STRICT</span>
                    </div>

                    <div class="flex items-start justify-between">
                        <div>
                            <div class="font-semibold text-slate-800">Rate Limiting Auth</div>
                            <div class="text-[11px] text-slate-400">5 lần thử / phút (IP base)</div>
                        </div>
                        <span class="text-[10px] font-bold text-emerald-600 font-mono">ACTIVE</span>
                    </div>

                    <div class="flex items-start justify-between">
                        <div>
                            <div class="font-semibold text-slate-800">Mã hóa Mật khẩu</div>
                            <div class="text-[11px] text-slate-400">Bcrypt Cost 12 with Salt</div>
                        </div>
                        <span class="text-[10px] font-bold text-emerald-600 font-mono">OK</span>
                    </div>
                </div>
            </div>

            <!-- Technology Stack Specs -->
            <div class="rounded-xl border border-slate-200/80 bg-white p-5 shadow-xs">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-800">Thông số Hệ thống</span>
                    <span class="text-[10px] font-mono text-slate-400">PHP {{ PHP_VERSION }}</span>
                </div>

                <div class="mt-3 divide-y divide-slate-100 font-mono text-[11px]">
                    <div class="py-2 flex items-center justify-between">
                        <span class="text-slate-500">LARAVEL ENGINE</span>
                        <span class="text-slate-900 font-semibold">{{ app()->version() }}</span>
                    </div>
                    <div class="py-2 flex items-center justify-between">
                        <span class="text-slate-500">DATABASE</span>
                        <span class="text-slate-900 font-semibold">MySQL 8.0</span>
                    </div>
                    <div class="py-2 flex items-center justify-between">
                        <span class="text-slate-500">CACHE / SESSION</span>
                        <span class="text-slate-900 font-semibold">Redis In-Memory</span>
                    </div>
                    <div class="py-2 flex items-center justify-between">
                        <span class="text-slate-500">MAIL SYSTEM</span>
                        <span class="text-blue-600 font-semibold">Mailpit (8025)</span>
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>
@endsection
