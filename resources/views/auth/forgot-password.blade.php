@extends('layouts.app', ['title' => 'Khôi phục mật khẩu'])

@section('content')
<div class="min-h-[calc(100vh-12rem)] flex items-center justify-center p-6">
    <div class="w-full max-w-md space-y-6 p-8 rounded-md border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-950 shadow-sm">
        
        <div class="space-y-1">
            <span class="text-[10px] font-mono tracking-widest uppercase text-zinc-500">BẢO MẬT TÀI KHOẢN</span>
            <h2 class="text-xl font-bold tracking-tight text-zinc-900 dark:text-white">Quên mật khẩu?</h2>
            <p class="text-xs text-zinc-500">
                Nhập email đã đăng ký. Hệ thống sẽ gửi cho bạn một liên kết an toàn để đặt lại mật khẩu mới.
            </p>
        </div>

        <form action="{{ route('password.email') }}" method="POST" class="space-y-4">
            @csrf

            <div class="space-y-1.5">
                <label for="email" class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300">
                    Địa chỉ Email
                </label>
                <input type="email" 
                       id="email" 
                       name="email" 
                       value="{{ old('email') }}" 
                       required 
                       autofocus
                       placeholder="name@company.com"
                       class="w-full px-3.5 py-2.5 rounded-md border text-xs bg-transparent transition-colors placeholder:text-zinc-400
                              {{ $errors->has('email') ? 'border-rose-500' : 'border-zinc-300 dark:border-zinc-800 focus:border-zinc-900 dark:focus:border-white focus:ring-1 focus:ring-zinc-900 dark:focus:ring-white' }} outline-none">
                @error('email')
                    <p class="text-[11px] text-rose-600 dark:text-rose-400 font-medium mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="w-full py-2.5 px-4 rounded-md bg-zinc-900 hover:bg-zinc-800 text-white dark:bg-white dark:text-zinc-950 dark:hover:bg-zinc-200 text-xs font-semibold tracking-wide transition-colors">
                Gửi liên kết khôi phục
            </button>
        </form>

        <div class="border-t border-zinc-100 dark:border-zinc-800 pt-4 text-center">
            <a href="{{ route('login') }}" class="text-xs font-semibold text-zinc-600 hover:text-zinc-950 dark:text-zinc-400 dark:hover:text-white transition-colors">
                &larr; Quay lại trang đăng nhập
            </a>
        </div>
    </div>
</div>
@endsection
