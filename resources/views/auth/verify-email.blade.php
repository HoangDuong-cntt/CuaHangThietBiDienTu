@extends('layouts.client', ['title' => 'Xác thực địa chỉ Email - D&D.'])

@section('content')
<div class="min-h-[calc(100vh-16rem)] flex items-center justify-center py-10 px-4 sm:px-6">
    <div class="w-full max-w-[420px]">

        <!-- Compact Card -->
        <div class="bg-white rounded-2xl border border-slate-200/90 p-6 sm:p-8 shadow-sm text-center">
            
            <a href="{{ route('home') }}" class="inline-flex items-center gap-1 group mb-4">
                <span class="text-2xl font-black tracking-tight text-slate-900 group-hover:text-blue-600 transition-colors">D&D<span class="text-blue-600 font-extrabold text-3xl leading-none">.</span></span>
            </a>

            <!-- Icon -->
            <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 mx-auto flex items-center justify-center text-xl mb-3 shadow-2xs">
                <i class="fa-regular fa-envelope-open"></i>
            </div>

            <h1 class="text-xl font-bold tracking-tight text-slate-900 mb-1">
                Xác thực địa chỉ Email
            </h1>
            <p class="text-xs text-slate-500 leading-relaxed mb-4">
                Hệ thống đã gửi liên kết kích hoạt đến:
                <br>
                <strong class="font-bold text-slate-900 font-mono mt-1 inline-block">{{ Auth::user()?->email }}</strong>
            </p>

            @if (session('status') == 'verification-link-sent')
                <div class="mb-4 p-3 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs text-left flex items-start gap-2">
                    <i class="fa-solid fa-circle-check text-emerald-500 mt-0.5 shrink-0"></i>
                    <div>Một liên kết mới đã được gửi lại vào email của bạn!</div>
                </div>
            @endif

            <div class="space-y-2.5">
                <form action="{{ route('verification.send') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full py-2.5 px-4 rounded-xl bg-slate-900 hover:bg-blue-600 text-white text-xs font-bold uppercase tracking-wider transition-all shadow-sm active:scale-[0.99] cursor-pointer">
                        Gửi lại email xác thực
                    </button>
                </form>

                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full py-2 px-4 text-xs font-semibold text-slate-500 hover:text-rose-600 transition-colors cursor-pointer">
                        Đăng xuất tài khoản
                    </button>
                </form>
            </div>

        </div>

    </div>
</div>
@endsection
