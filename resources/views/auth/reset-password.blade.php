@extends('layouts.app', ['title' => 'Đặt lại mật khẩu mới'])

@section('content')
<div class="min-h-[calc(100vh-12rem)] flex items-center justify-center p-6">
    <div class="w-full max-w-md space-y-6 p-8 rounded-md border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-950 shadow-sm">
        
        <div class="space-y-1">
            <span class="text-[10px] font-mono tracking-widest uppercase text-zinc-500">XÁC MINH DANH TÍNH</span>
            <h2 class="text-xl font-bold tracking-tight text-zinc-900 dark:text-white">Đặt lại mật khẩu</h2>
            <p class="text-xs text-zinc-500">
                Nhập mật khẩu mới an toàn cho tài khoản của bạn (tối thiểu 8 ký tự).
            </p>
        </div>

        <form action="{{ route('password.update') }}" method="POST" class="space-y-4">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            <div class="space-y-1.5">
                <label for="email" class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300">
                    Địa chỉ Email
                </label>
                <input type="email" 
                       id="email" 
                       name="email" 
                       value="{{ $email ?? old('email') }}" 
                       required 
                       readonly
                       class="w-full px-3.5 py-2.5 rounded-md border border-zinc-200 dark:border-zinc-800 bg-zinc-100 dark:bg-zinc-900 text-xs text-zinc-500 outline-none">
                @error('email')
                    <p class="text-[11px] text-rose-600 dark:text-rose-400 font-medium mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="space-y-1.5">
                <label for="password" class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300">
                    Mật khẩu mới
                </label>
                <input type="password" 
                       id="password" 
                       name="password" 
                       required 
                       autofocus
                       placeholder="••••••••"
                       class="w-full px-3.5 py-2.5 rounded-md border text-xs bg-transparent transition-colors placeholder:text-zinc-400
                              {{ $errors->has('password') ? 'border-rose-500' : 'border-zinc-300 dark:border-zinc-800 focus:border-zinc-900 dark:focus:border-white focus:ring-1 focus:ring-zinc-900 dark:focus:ring-white' }} outline-none">
                @error('password')
                    <p class="text-[11px] text-rose-600 dark:text-rose-400 font-medium mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="space-y-1.5">
                <label for="password_confirmation" class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300">
                    Nhập lại mật khẩu mới
                </label>
                <input type="password" 
                       id="password_confirmation" 
                       name="password_confirmation" 
                       required 
                       placeholder="••••••••"
                       class="w-full px-3.5 py-2.5 rounded-md border border-zinc-300 dark:border-zinc-800 focus:border-zinc-900 dark:focus:border-white focus:ring-1 focus:ring-zinc-900 dark:focus:ring-white text-xs bg-transparent transition-colors placeholder:text-zinc-400 outline-none">
            </div>

            <button type="submit" class="w-full py-2.5 px-4 rounded-md bg-zinc-900 hover:bg-zinc-800 text-white dark:bg-white dark:text-zinc-950 dark:hover:bg-zinc-200 text-xs font-semibold tracking-wide transition-colors">
                Cập nhật mật khẩu mới
            </button>
        </form>
    </div>
</div>
@endsection
