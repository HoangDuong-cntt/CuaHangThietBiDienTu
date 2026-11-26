@extends('layouts.app', ['title' => 'Xác thực địa chỉ Email'])

@section('content')
<div class="min-h-[calc(100vh-12rem)] flex items-center justify-center p-6">
    <div class="w-full max-w-md space-y-6 p-8 rounded-lg border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 shadow-xl text-center">
        
        <div class="w-12 h-12 rounded-full bg-blue-500/10 text-blue-600 dark:text-blue-400 mx-auto flex items-center justify-center text-2xl">
            ✉️
        </div>

        <div class="space-y-2">
            <span class="text-[10px] font-mono tracking-widest uppercase text-zinc-500">BẢO MẬT TÀI KHOẢN</span>
            <h1 class="text-xl font-bold tracking-tight text-zinc-900 dark:text-white">
                Xác thực địa chỉ Email
            </h1>
            <p class="text-xs text-zinc-500 leading-relaxed">
                Cảm ơn bạn đã đăng ký! Hệ thống đã gửi một liên kết xác thực đến hộp thư 
                <strong class="text-zinc-900 dark:text-white font-mono">{{ Auth::user()?->email }}</strong>. 
                Vui lòng kiểm tra hộp thư đến (hoặc thư rác/spam) và bấm vào liên kết để kích hoạt tài khoản.
            </p>
        </div>

        @if (session('status') == 'verification-link-sent')
            <div class="p-3 rounded-md border border-emerald-500/30 bg-emerald-500/10 text-emerald-700 dark:text-emerald-400 text-xs font-medium">
                ✓ Một liên kết xác thực mới vừa được gửi lại vào email của bạn!
            </div>
        @endif

        <div class="flex flex-col gap-3 pt-2">
            <form action="{{ route('verification.send') }}" method="POST">
                @csrf
                <button type="submit" class="w-full py-2.5 px-4 rounded-md bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold transition-all shadow-sm">
                    Gửi lại email xác thực
                </button>
            </form>

            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full py-2 px-4 text-xs font-semibold text-zinc-500 hover:text-zinc-900 dark:hover:text-white transition-colors">
                    Đăng xuất tài khoản
                </button>
            </form>
        </div>

    </div>
</div>
@endsection
