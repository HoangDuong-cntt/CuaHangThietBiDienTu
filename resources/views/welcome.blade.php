@extends('layouts.app', ['title' => 'Trang chủ'])

@section('content')
<div class="relative overflow-hidden bg-zinc-950 text-white border-b border-zinc-800">
    <!-- Grid Blueprint Background -->
    <div class="absolute inset-0 bg-[linear-gradient(to_right,#27272a_1px,transparent_1px),linear-gradient(to_bottom,#27272a_1px,transparent_1px)] bg-[size:4rem_4rem] [mask-image:radial-gradient(ellipse_60%_50%_at_50%_0%,#000_70%,transparent_100%)] opacity-30"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-28 flex flex-col items-center text-center">
        <!-- Tech Tag -->
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded bg-zinc-900 border border-zinc-800 text-xs font-mono text-zinc-300 mb-6">
            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
            <span>SMARTTECH E-COMMERCE &bull; LARAVEL 13 &bull; PHP 8.5</span>
        </div>

        <h1 class="text-4xl sm:text-6xl font-extrabold tracking-tight max-w-3xl leading-tight">
            Hệ Thống Mua Bán Thiết Bị Điện Tử &amp; Trợ Lý AI
        </h1>

        <p class="mt-4 text-sm sm:text-base text-zinc-400 max-w-2xl leading-relaxed">
            Nền tảng thương mại điện tử chuyên biệt thiết bị công nghệ (Laptop, Smartphone) tích hợp xem 3D tương tác 360 độ, quy đổi trọng lượng thực tế và tư vấn cấu hình AI.
        </p>

        <!-- CTA Buttons (Sharp Minimal) -->
        <div class="mt-8 flex flex-wrap items-center justify-center gap-3">
            @guest
                <a href="{{ route('login') }}" class="px-6 py-3 rounded-md bg-white text-zinc-950 font-bold text-xs uppercase tracking-wider hover:bg-zinc-200 transition-colors shadow-sm">
                    Đăng nhập tài khoản &rarr;
                </a>
                <a href="{{ route('register') }}" class="px-6 py-3 rounded-md border border-zinc-800 bg-zinc-900 text-zinc-200 font-semibold text-xs uppercase tracking-wider hover:bg-zinc-800 hover:text-white transition-colors">
                    Đăng ký thành viên
                </a>
            @else
                <a href="{{ route('profile.edit') }}" class="px-6 py-3 rounded-md bg-white text-zinc-950 font-bold text-xs uppercase tracking-wider hover:bg-zinc-200 transition-colors shadow-sm">
                    Quản lý Hồ sơ &amp; Sổ địa chỉ &rarr;
                </a>
            @endguest
        </div>

        <!-- Features Row -->
        <div class="mt-16 w-full max-w-4xl pt-8 border-t border-zinc-800/80">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-left">
                <div class="p-4 rounded-md border border-zinc-800 bg-zinc-900/60 space-y-1">
                    <span class="text-blue-400 text-lg">🛡️</span>
                    <h3 class="text-xs font-bold text-white">Bảo Mật Đa Tầng</h3>
                    <p class="text-[11px] text-zinc-400">Xác thực Google OAuth2, mã hóa mật khẩu Bcrypt và phân quyền động RBAC.</p>
                </div>
                <div class="p-4 rounded-md border border-zinc-800 bg-zinc-900/60 space-y-1">
                    <span class="text-emerald-400 text-lg">⚡</span>
                    <h3 class="text-xs font-bold text-white">Voucher &amp; Đánh Giá</h3>
                    <p class="text-[11px] text-zinc-400">Động cơ mã giảm giá linh hoạt, chấm sao và đánh giá thực tế đã mua hàng.</p>
                </div>
                <div class="p-4 rounded-md border border-zinc-800 bg-zinc-900/60 space-y-1">
                    <span class="text-purple-400 text-lg">📊</span>
                    <h3 class="text-xs font-bold text-white">Dashboard Quản Trị</h3>
                    <p class="text-[11px] text-zinc-400">Biểu đồ Chart.js thống kê doanh thu, phân bổ đơn hàng và xuất báo cáo Excel.</p>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
