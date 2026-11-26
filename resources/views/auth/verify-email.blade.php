@extends('layouts.client', ['title' => 'Xác thực địa chỉ Email - D&D.'])

@section('content')
<div class="min-h-[calc(100vh-14rem)] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 bg-slate-100/60">
    <div class="w-full max-w-md">

        <!-- Card Container -->
        <div class="bg-white rounded-2xl border border-slate-200/90 p-8 sm:p-10 shadow-xl shadow-slate-900/5 text-center">
            
            <a href="{{ route('home') }}" class="inline-flex items-center gap-1 group mb-5">
                <span class="text-2xl font-black tracking-tight text-slate-900 group-hover:text-blue-600 transition-colors">D&D<span class="text-blue-600 font-extrabold text-3xl leading-none">.</span></span>
            </a>

            <!-- Icon -->
            <div class="w-16 h-16 rounded-2xl bg-blue-50 text-blue-600 mx-auto flex items-center justify-center text-3xl mb-5 shadow-2xs">
                <i class="fa-regular fa-envelope-open"></i>
            </div>

            <h1 class="text-2xl font-bold tracking-tight text-slate-900 mb-2">
                Xác thực địa chỉ Email
            </h1>
            <p class="text-xs sm:text-sm text-slate-500 leading-relaxed mb-6">
                Cảm ơn bạn đã đăng ký tài khoản! Hệ thống đã gửi một liên kết kích hoạt đến hộp thư:
                <br>
                <strong class="font-bold text-slate-900 font-mono mt-1 inline-block">{{ Auth::user()?->email }}</strong>
            </p>

            @if (session('status') == 'verification-link-sent')
                <div class="mb-6 p-3.5 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs flex items-start gap-2.5 text-left">
                    <i class="fa-solid fa-circle-check text-emerald-500 mt-0.5 shrink-0 text-sm"></i>
                    <div>Một liên kết xác thực mới vừa được gửi lại đến hộp thư của bạn!</div>
                </div>
            @endif

            <div class="space-y-3">
                <form action="{{ route('verification.send') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full py-3.5 px-6 rounded-xl bg-slate-900 hover:bg-blue-600 text-white text-xs sm:text-sm font-bold uppercase tracking-wider transition-all shadow-md shadow-slate-950/10 hover:shadow-blue-600/20 active:scale-[0.99] cursor-pointer">
                        Gửi lại email xác thực
                    </button>
                </form>

                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full py-2.5 px-4 text-xs font-semibold text-slate-500 hover:text-rose-600 transition-colors cursor-pointer">
                        Đăng xuất tài khoản
                    </button>
                </form>
            </div>

        </div>

    </div>
</div>
@endsection
