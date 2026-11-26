@extends('layouts.client', ['title' => 'Xác thực địa chỉ Email - tinh.'])

@section('content')
<div class="py-12 sm:py-16 lg:py-20 bg-[#fafbfc] min-h-[calc(100vh-16rem)] flex items-center justify-center px-4 sm:px-6">
    <div class="w-full max-w-md">

        <!-- Main Card -->
        <div class="bg-white rounded-3xl border border-slate-200/80 p-8 sm:p-10 shadow-xl shadow-slate-900/5 text-center">
            
            <!-- Icon -->
            <div class="w-16 h-16 rounded-3xl bg-blue-50 text-blue-600 mx-auto flex items-center justify-center text-3xl mb-5 shadow-2xs">
                <i class="fa-regular fa-envelope-open"></i>
            </div>

            <h1 class="text-2xl font-bold tracking-tight text-slate-900 mb-2">
                Xác thực địa chỉ Email
            </h1>
            <p class="text-xs sm:text-sm text-slate-500 leading-relaxed mb-6">
                Cảm ơn bạn đã đăng ký tài khoản! Chúng tôi đã gửi một liên kết kích hoạt đến hộp thư:
                <br>
                <strong class="font-bold text-slate-900 font-mono mt-1 inline-block">{{ Auth::user()?->email }}</strong>
            </p>

            @if (session('status') == 'verification-link-sent')
                <div class="mb-6 p-4 rounded-2xl bg-emerald-50 border border-emerald-200/80 text-emerald-700 text-xs sm:text-sm flex items-start gap-3 text-left">
                    <i class="fa-solid fa-circle-check text-emerald-500 mt-0.5 shrink-0 text-base"></i>
                    <div>Một liên kết xác thực mới vừa được gửi lại đến hộp thư của bạn!</div>
                </div>
            @endif

            <div class="space-y-3">
                <form action="{{ route('verification.send') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full py-3 px-4 rounded-2xl bg-slate-900 hover:bg-blue-600 text-white text-xs sm:text-sm font-bold tracking-wide transition-all shadow-md shadow-slate-950/10 hover:shadow-blue-600/20 active:scale-[0.99] cursor-pointer">
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
