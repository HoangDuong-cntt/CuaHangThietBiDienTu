@extends('layouts.admin')

@section('content')
<div class="space-y-6">

    <!-- Page Header & Quick Controls -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-2 border-b border-zinc-850">
        <div>
            <h1 class="text-xl font-bold tracking-tight text-white flex items-center gap-2.5">
                <span>Trung tâm Điều hành &amp; Giám sát</span>
                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-mono font-semibold bg-blue-500/10 text-blue-400 border border-blue-500/20">
                    LIVE SYSTEM
                </span>
            </h1>
            <p class="text-xs text-zinc-400 mt-1 font-mono">
                Tổng quan kiến trúc phân quyền động, giám sát người dùng và bảo mật hệ thống.
            </p>
        </div>

        <div class="flex items-center gap-2.5">
            @if(auth()->user()->hasPermission('manage_roles'))
                <a href="{{ route('admin.roles.index') }}" class="inline-flex items-center gap-2 px-3 py-2 rounded-md bg-zinc-900 hover:bg-zinc-800 text-zinc-200 border border-zinc-800 text-xs font-semibold transition-colors">
                    <svg class="w-4 h-4 text-zinc-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                    </svg>
                    <span>Ma trận Phân quyền</span>
                </a>
            @endif

            @if(auth()->user()->hasPermission('manage_users'))
                <a href="{{ route('admin.staff.index') }}" class="inline-flex items-center gap-2 px-3.5 py-2 rounded-md bg-blue-600 hover:bg-blue-500 text-white text-xs font-semibold shadow-sm transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    <span>Quản lý Nhân sự</span>
                </a>
            @endif
        </div>
    </div>

    <!-- Telemetry Metric Cards Grid (Sharp Tech Industrial Aesthetic) -->
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
        <!-- Customers Metric -->
        <div class="p-4 rounded-md bg-zinc-900/60 border border-zinc-850 hover:border-zinc-700 transition-colors">
            <div class="flex items-center justify-between text-zinc-500 mb-2">
                <span class="text-[10px] font-mono uppercase tracking-wider font-semibold">KHÁCH HÀNG</span>
                <svg class="w-3.5 h-3.5 text-zinc-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
            </div>
            <div class="text-2xl font-bold font-mono text-white tracking-tight">
                {{ number_format($stats['total_customers']) }}
            </div>
            <div class="text-[10px] font-mono text-emerald-400 mt-1 flex items-center gap-1">
                <span>&bull;</span> Khách đã đăng ký
            </div>
        </div>

        @if(auth()->user()->hasPermission('manage_users'))
            <!-- Staff Metric -->
            <div class="p-4 rounded-md bg-zinc-900/60 border border-zinc-850 hover:border-zinc-700 transition-colors">
                <div class="flex items-center justify-between text-zinc-500 mb-2">
                    <span class="text-[10px] font-mono uppercase tracking-wider font-semibold">NHÂN SỰ VẬN HÀNH</span>
                    <svg class="w-3.5 h-3.5 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <div class="text-2xl font-bold font-mono text-white tracking-tight">
                    {{ number_format($stats['total_staff']) }}
                </div>
                <div class="text-[10px] font-mono text-blue-400 mt-1 flex items-center gap-1">
                    <span>&bull;</span> Nhân viên phụ trách
                </div>
            </div>
        @endif

        @if(auth()->user()->hasPermission('manage_roles'))
            <!-- Roles Metric -->
            <div class="p-4 rounded-md bg-zinc-900/60 border border-zinc-850 hover:border-zinc-700 transition-colors">
                <div class="flex items-center justify-between text-zinc-500 mb-2">
                    <span class="text-[10px] font-mono uppercase tracking-wider font-semibold">VAI TRÒ (ROLES)</span>
                    <svg class="w-3.5 h-3.5 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
                <div class="text-2xl font-bold font-mono text-white tracking-tight">
                    {{ number_format($stats['total_roles']) }}
                </div>
                <div class="text-[10px] font-mono text-purple-400 mt-1 flex items-center gap-1">
                    <span>&bull;</span> Nhóm định danh
                </div>
            </div>

            <!-- Permissions Metric -->
            <div class="p-4 rounded-md bg-zinc-900/60 border border-zinc-850 hover:border-zinc-700 transition-colors">
                <div class="flex items-center justify-between text-zinc-500 mb-2">
                    <span class="text-[10px] font-mono uppercase tracking-wider font-semibold">QUYỀN HẠN (RBAC)</span>
                    <svg class="w-3.5 h-3.5 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                    </svg>
                </div>
                <div class="text-2xl font-bold font-mono text-white tracking-tight">
                    {{ number_format($stats['total_permissions']) }}
                </div>
                <div class="text-[10px] font-mono text-amber-400 mt-1 flex items-center gap-1">
                    <span>&bull;</span> 6 phân hệ chính
                </div>
            </div>
        @endif

        @if(auth()->user()->hasPermission('manage_coupons'))
            <!-- Coupons Metric -->
            <div class="p-4 rounded-md bg-zinc-900/60 border border-zinc-850 hover:border-zinc-700 transition-colors">
                <div class="flex items-center justify-between text-zinc-500 mb-2">
                    <span class="text-[10px] font-mono uppercase tracking-wider font-semibold">VOUCHER KHẢ DỤNG</span>
                    <svg class="w-3.5 h-3.5 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                    </svg>
                </div>
                <div class="text-2xl font-bold font-mono text-white tracking-tight">
                    {{ number_format($stats['active_coupons']) }}
                </div>
                <div class="text-[10px] font-mono text-zinc-400 mt-1 flex items-center gap-1">
                    <span>&bull;</span> Đang hoạt động
                </div>
            </div>
        @endif

        @if(auth()->user()->hasPermission('manage_reviews'))
            <!-- Reviews Metric -->
            <div class="p-4 rounded-md bg-zinc-900/60 border border-zinc-850 hover:border-zinc-700 transition-colors">
                <div class="flex items-center justify-between text-zinc-500 mb-2">
                    <span class="text-[10px] font-mono uppercase tracking-wider font-semibold">ĐÁNH GIÁ SẢN PHẨM</span>
                    <svg class="w-3.5 h-3.5 text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                    </svg>
                </div>
                <div class="text-2xl font-bold font-mono text-white tracking-tight">
                    {{ number_format($stats['total_reviews']) }}
                </div>
                <div class="text-[10px] font-mono text-zinc-400 mt-1 flex items-center gap-1">
                    <span>&bull;</span> Phản hồi thực tế
                </div>
            </div>
        @endif
    </div>

    <!-- Main Workspace Split: System Telemetry & Role Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        @if(auth()->user()->hasPermission('manage_users'))
            <!-- Left 2 Cols: Recent Staff Table for Authorized Admin/HR -->
            <div class="lg:col-span-2 rounded-md border border-zinc-850 bg-zinc-950 overflow-hidden">
                <div class="px-5 py-4 border-b border-zinc-850 flex items-center justify-between">
                    <div class="flex items-center gap-2.5">
                        <div class="w-2 h-2 rounded-full bg-blue-500"></div>
                        <h2 class="text-xs font-bold uppercase tracking-wider text-white">Nhân sự quản trị gần đây</h2>
                    </div>
                    <a href="{{ route('admin.staff.index') }}" class="text-[11px] font-mono text-blue-400 hover:text-blue-300">
                        Xem tất cả &rarr;
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-zinc-900/60 border-b border-zinc-850 text-zinc-400 font-mono text-[10px] uppercase">
                            <tr>
                                <th class="py-3 px-4">Nhân sự</th>
                                <th class="py-3 px-4">Số điện thoại</th>
                                <th class="py-3 px-4">Vai trò (Role)</th>
                                <th class="py-3 px-4 text-center">Trạng thái</th>
                                <th class="py-3 px-4 text-right">Khởi tạo</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-850/60 text-zinc-300">
                            @forelse($recentStaff as $staff)
                                <tr class="hover:bg-zinc-900/40 transition-colors">
                                    <td class="py-3.5 px-4 flex items-center gap-3">
                                        <div class="w-8 h-8 rounded bg-zinc-800 border border-zinc-700 flex items-center justify-center font-bold text-xs text-white uppercase shrink-0">
                                            {{ substr($staff->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="font-medium text-white flex items-center gap-1.5">
                                                <span>{{ $staff->name }}</span>
                                                @if($staff->role === 'admin')
                                                    <span class="px-1.5 py-0.2 rounded text-[9px] font-mono font-semibold bg-rose-500/10 text-rose-400 border border-rose-500/20">ROOT</span>
                                                @endif
                                            </div>
                                            <div class="text-[11px] font-mono text-zinc-400">{{ $staff->email }}</div>
                                        </div>
                                    </td>
                                    <td class="py-3.5 px-4 font-mono text-zinc-400">
                                        {{ $staff->phone ?? '---' }}
                                    </td>
                                    <td class="py-3.5 px-4">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-mono font-medium bg-zinc-900 border border-zinc-750 text-zinc-200">
                                            {{ $staff->roleModel->display_name ?? ($staff->role === 'admin' ? 'Quản trị viên' : 'Nhân viên') }}
                                        </span>
                                    </td>
                                    <td class="py-3.5 px-4 text-center">
                                        @if($staff->is_active)
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-mono font-medium bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span> Active
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-mono font-medium bg-rose-500/10 text-rose-400 border border-rose-500/20">
                                                <span class="w-1.5 h-1.5 rounded-full bg-rose-400"></span> Locked
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-3.5 px-4 text-right font-mono text-[11px] text-zinc-400">
                                        {{ $staff->created_at->format('d/m/Y') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-8 text-center text-zinc-500 font-mono text-xs">
                                        Chưa có nhân sự nào trong danh sách.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @else
            <!-- Left 2 Cols: Dedicated Operational Role Workspace for Specialized Personnel (e.g. Warehouse) -->
            <div class="lg:col-span-2 rounded-md border border-zinc-850 bg-zinc-950 p-6 space-y-5">
                <div class="flex items-center gap-3 pb-4 border-b border-zinc-850">
                    <div class="w-10 h-10 rounded bg-blue-600/10 border border-blue-500/20 text-blue-400 flex items-center justify-center font-bold text-sm">
                        ST
                    </div>
                    <div>
                        <h2 class="text-sm font-bold text-white uppercase tracking-wider">
                            Không gian làm việc: {{ auth()->user()->roleModel->display_name ?? 'Nhân sự vận hành' }}
                        </h2>
                        <p class="text-xs text-zinc-400 font-mono mt-0.5">
                            Tài khoản phân quyền theo chuyên môn. Bạn chỉ truy cập được các chức năng được cấp phép.
                        </p>
                    </div>
                </div>

                <div class="space-y-3">
                    <div class="text-xs font-semibold uppercase tracking-wider text-zinc-400 font-mono">
                        Các module nghiệp vụ được phân công phụ trách:
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 font-mono text-xs">
                        @if(auth()->user()->hasPermission('manage_orders') || auth()->user()->hasPermission('view_inventory'))
                            <div class="p-3.5 rounded-md bg-zinc-900/60 border border-zinc-800 space-y-1">
                                <div class="font-bold text-emerald-400 flex items-center gap-2">
                                    <span>📦</span> Đơn hàng &amp; Quản lý Kho
                                </div>
                                <p class="text-[11px] text-zinc-400 font-sans">
                                    Theo dõi danh sách xuất/nhập, kiểm soát hàng tồn kho và phê duyệt vận chuyển.
                                </p>
                            </div>
                        @endif

                        @if(auth()->user()->hasPermission('manage_products'))
                            <div class="p-3.5 rounded-md bg-zinc-900/60 border border-zinc-800 space-y-1">
                                <div class="font-bold text-blue-400 flex items-center gap-2">
                                    <span>🛍️</span> Sản phẩm &amp; Danh mục 3D
                                </div>
                                <p class="text-[11px] text-zinc-400 font-sans">
                                    Cập nhật số lượng sản phẩm, giá bán, biến thể và cấu hình trình diễn 3D.
                                </p>
                            </div>
                        @endif

                        @if(auth()->user()->hasPermission('manage_coupons'))
                            <div class="p-3.5 rounded-md bg-zinc-900/60 border border-zinc-800 space-y-1">
                                <div class="font-bold text-purple-400 flex items-center gap-2">
                                    <span>🎟️</span> Khuyến mãi &amp; Voucher
                                </div>
                                <p class="text-[11px] text-zinc-400 font-sans">
                                    Quản lý mã giảm giá, giới hạn ngân sách voucher và chương trình ưu đãi.
                                </p>
                            </div>
                        @endif

                        @if(auth()->user()->hasPermission('manage_reviews') || auth()->user()->hasPermission('manage_banners'))
                            <div class="p-3.5 rounded-md bg-zinc-900/60 border border-zinc-800 space-y-1">
                                <div class="font-bold text-yellow-400 flex items-center gap-2">
                                    <span>⭐</span> Đánh giá &amp; Banners
                                </div>
                                <p class="text-[11px] text-zinc-400 font-sans">
                                    Kiểm duyệt phản hồi của khách hàng và điều chỉnh slider banner trang chủ.
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endif

        <!-- Right 1 Col: Security Telemetry & Stack Specs -->
        <div class="space-y-6">

            <!-- Security Policy Summary -->
            <div class="rounded-md border border-zinc-850 bg-zinc-950 p-5">
                <div class="flex items-center justify-between pb-3 border-b border-zinc-850">
                    <span class="text-xs font-bold uppercase tracking-wider text-white">Chính sách Bảo mật</span>
                    <span class="px-1.5 py-0.5 rounded text-[9px] font-mono bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 font-bold">
                        ENFORCED
                    </span>
                </div>

                <div class="mt-4 space-y-3.5 text-xs">
                    <div class="flex items-start justify-between">
                        <div>
                            <div class="font-medium text-zinc-200">Xác thực 2 lớp / Signed URL</div>
                            <div class="text-[11px] text-zinc-400">Email Verification với Sha256</div>
                        </div>
                        <span class="font-mono text-[10px] text-emerald-400 font-bold">ACTIVE</span>
                    </div>

                    <div class="flex items-start justify-between">
                        <div>
                            <div class="font-medium text-zinc-200">Dynamic RBAC Gate</div>
                            <div class="text-[11px] text-zinc-400">Kiểm tra quyền hạn qua Middleware</div>
                        </div>
                        <span class="font-mono text-[10px] text-blue-400 font-bold">STRICT</span>
                    </div>

                    <div class="flex items-start justify-between">
                        <div>
                            <div class="font-medium text-zinc-200">Rate Limiting Auth</div>
                            <div class="text-[11px] text-zinc-400">Throttle 5 lần thử / 1 phút (IP base)</div>
                        </div>
                        <span class="font-mono text-[10px] text-emerald-400 font-bold">ACTIVE</span>
                    </div>

                    <div class="flex items-start justify-between">
                        <div>
                            <div class="font-medium text-zinc-200">Mã hóa Mật khẩu</div>
                            <div class="text-[11px] text-zinc-400">Bcrypt Cost 12 with Salt</div>
                        </div>
                        <span class="font-mono text-[10px] text-emerald-400 font-bold">OK</span>
                    </div>
                </div>
            </div>

            <!-- Technology Stack Specs -->
            <div class="rounded-md border border-zinc-850 bg-zinc-950 p-5">
                <div class="flex items-center justify-between pb-3 border-b border-zinc-850">
                    <span class="text-xs font-bold uppercase tracking-wider text-white">Thông số Hạ tầng</span>
                    <span class="text-[10px] font-mono text-zinc-400">LOCAL DOCKER</span>
                </div>

                <div class="mt-3 divide-y divide-zinc-850/60 font-mono text-[11px]">
                    <div class="py-2 flex items-center justify-between">
                        <span class="text-zinc-400">LARAVEL ENGINE</span>
                        <span class="text-zinc-200 font-semibold">{{ app()->version() }}</span>
                    </div>
                    <div class="py-2 flex items-center justify-between">
                        <span class="text-zinc-400">PHP RUNTIME</span>
                        <span class="text-zinc-200 font-semibold">{{ PHP_VERSION }}</span>
                    </div>
                    <div class="py-2 flex items-center justify-between">
                        <span class="text-zinc-400">DATABASE</span>
                        <span class="text-zinc-200 font-semibold">MySQL 8.0 (Sail)</span>
                    </div>
                    <div class="py-2 flex items-center justify-between">
                        <span class="text-zinc-400">CACHE / SESSION</span>
                        <span class="text-zinc-200 font-semibold">Redis In-Memory</span>
                    </div>
                    <div class="py-2 flex items-center justify-between">
                        <span class="text-zinc-400">MAIL SYSTEM</span>
                        <span class="text-blue-400 font-semibold">Mailpit (8025)</span>
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>
@endsection
