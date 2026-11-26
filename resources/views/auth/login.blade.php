@extends('layouts.app', ['title' => 'Đăng nhập tài khoản'])

@section('content')
<div class="min-h-[calc(100vh-7.5rem)] flex items-center justify-center p-4 sm:p-6 lg:p-8 bg-zinc-100/70 dark:bg-zinc-950">
    
    <!-- Main Auth Card Container (Crisp 1px border, high-end studio feel) -->
    <div class="w-full max-w-5xl rounded-lg border border-zinc-200/80 dark:border-zinc-800 bg-white dark:bg-zinc-900 shadow-xl overflow-hidden grid grid-cols-1 lg:grid-cols-12 min-h-[580px]">
        
        <!-- Left Showcase Column (5 cols on lg) -->
        <div class="hidden lg:flex lg:col-span-5 relative bg-zinc-950 p-10 flex-col justify-between overflow-hidden text-white">
            <!-- Background Image with Dark Vignette Overlay -->
            <img src="https://images.unsplash.com/photo-1550745165-9bc0b252726f?auto=format&fit=crop&w=1200&q=80" 
                 alt="Hardware Tech Lab" 
                 class="absolute inset-0 w-full h-full object-cover opacity-25 mix-blend-luminosity scale-105">
            <div class="absolute inset-0 bg-gradient-to-t from-zinc-950 via-zinc-950/80 to-zinc-950/40"></div>
            
            <!-- Top Tag -->
            <div class="relative z-10">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded bg-white/10 backdrop-blur-md border border-white/15 text-[11px] font-mono tracking-wider text-zinc-200 uppercase">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span>HARDWARE PORTAL 2026</span>
                </div>
            </div>

            <!-- Middle Text & Accents -->
            <div class="relative z-10 space-y-4 my-auto">
                <div class="w-10 h-1 bg-blue-500 rounded-full"></div>
                <h2 class="text-3xl font-extrabold tracking-tight text-white leading-tight">
                    Công Nghệ Đỉnh Cao. Trải Nghiệm Tương Lai.
                </h2>
                <p class="text-zinc-400 text-xs leading-relaxed">
                    Đăng nhập để theo dõi lịch sử đơn hàng, sử dụng mã giảm giá và trải nghiệm xem mô hình 3D xoay 360 độ độc quyền tại SmartTech.
                </p>

                <!-- Mini Stat Badges -->
                <div class="pt-4 grid grid-cols-2 gap-3 border-t border-white/10">
                    <div class="p-2.5 rounded bg-white/5 border border-white/10 backdrop-blur-sm">
                        <p class="text-[10px] font-mono text-zinc-400 uppercase">Bảo hành</p>
                        <p class="text-xs font-bold text-white mt-0.5">100% Chính Hãng</p>
                    </div>
                    <div class="p-2.5 rounded bg-white/5 border border-white/10 backdrop-blur-sm">
                        <p class="text-[10px] font-mono text-zinc-400 uppercase">Đổi trả</p>
                        <p class="text-xs font-bold text-white mt-0.5">30 Ngày 1 Đổi 1</p>
                    </div>
                </div>
            </div>

            <!-- Bottom Footnote -->
            <div class="relative z-10 border-t border-white/10 pt-4 flex items-center justify-between text-[11px] text-zinc-500 font-mono">
                <span>SMARTTECH ECOSYSTEM</span>
                <span>SECURE SSL 256-BIT</span>
            </div>
        </div>

        <!-- Right Form Column (7 cols on lg) -->
        <div class="lg:col-span-7 p-6 sm:p-10 lg:p-12 flex flex-col justify-center bg-white dark:bg-zinc-900">
            <div class="max-w-md w-full mx-auto space-y-6">
                
                <!-- Title Header -->
                <div class="space-y-1">
                    <span class="text-[10px] font-mono font-bold tracking-widest uppercase text-blue-600 dark:text-blue-400">
                        CỔNG XÁC THỰC THÀNH VIÊN
                    </span>
                    <h1 class="text-2xl font-bold tracking-tight text-zinc-900 dark:text-white">
                        Đăng nhập tài khoản
                    </h1>
                    <p class="text-xs text-zinc-500">
                        Chưa có tài khoản? 
                        <a href="{{ route('register') }}" class="font-semibold text-blue-600 dark:text-blue-400 hover:underline">
                            Đăng ký miễn phí tại đây
                        </a>
                    </p>
                </div>

                <!-- Google OAuth Button -->
                <div>
                    <a href="{{ route('login.google') }}" class="w-full flex items-center justify-center gap-3 px-4 py-2.5 rounded-md border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 hover:bg-zinc-50 dark:hover:bg-zinc-750 text-xs font-semibold text-zinc-700 dark:text-zinc-200 shadow-sm transition-all hover:border-zinc-300">
                        <svg class="w-4 h-4" viewBox="0 0 24 24">
                            <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                            <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                            <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/>
                            <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/>
                        </svg>
                        <span>Đăng nhập nhanh bằng Google</span>
                    </a>
                </div>

                <!-- Divider -->
                <div class="relative flex items-center justify-center">
                    <div class="w-full border-t border-zinc-200 dark:border-zinc-800"></div>
                    <span class="absolute bg-white dark:bg-zinc-900 px-3 text-[10px] font-mono uppercase text-zinc-400">
                        HOẶC EMAIL &amp; MẬT KHẨU
                    </span>
                </div>

                <!-- Form -->
                <form action="{{ route('login.post') }}" method="POST" class="space-y-4" x-data="{ showPass: false }">
                    @csrf

                    <!-- Email Field -->
                    <div class="space-y-1.5">
                        <label for="email" class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300">
                            Địa chỉ Email
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-zinc-400">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.206"/>
                                </svg>
                            </span>
                            <input type="email" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email') }}" 
                                   required 
                                   autofocus
                                   placeholder="ten@smarttech.vn"
                                   class="w-full pl-9 pr-3.5 py-2.5 rounded-md border text-xs bg-zinc-50 dark:bg-zinc-950/50 transition-all placeholder:text-zinc-400
                                          {{ $errors->has('email') ? 'border-rose-500 ring-1 ring-rose-500' : 'border-zinc-200 dark:border-zinc-750 focus:border-blue-600 focus:ring-1 focus:ring-blue-600' }} outline-none">
                        </div>
                        @error('email')
                            <p class="text-[11px] text-rose-600 dark:text-rose-400 font-medium mt-1 flex items-center gap-1">
                                <span>⚠️</span> {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Password Field -->
                    <div class="space-y-1.5">
                        <div class="flex items-center justify-between">
                            <label for="password" class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300">
                                Mật khẩu
                            </label>
                            <a href="{{ route('password.request') }}" class="text-[11px] font-medium text-blue-600 dark:text-blue-400 hover:underline">
                                Quên mật khẩu?
                            </a>
                        </div>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-zinc-400">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                            </span>
                            <input :type="showPass ? 'text' : 'password'" 
                                   id="password" 
                                   name="password" 
                                   required 
                                   placeholder="••••••••"
                                   class="w-full pl-9 pr-10 py-2.5 rounded-md border text-xs bg-zinc-50 dark:bg-zinc-950/50 transition-all placeholder:text-zinc-400
                                          {{ $errors->has('password') ? 'border-rose-500 ring-1 ring-rose-500' : 'border-zinc-200 dark:border-zinc-750 focus:border-blue-600 focus:ring-1 focus:ring-blue-600' }} outline-none">
                            <button type="button" 
                                    @click="showPass = !showPass" 
                                    class="absolute inset-y-0 right-0 flex items-center pr-3 text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-300">
                                <svg x-show="!showPass" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                <svg x-show="showPass" x-cloak class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/>
                                </svg>
                            </button>
                        </div>
                        @error('password')
                            <p class="text-[11px] text-rose-600 dark:text-rose-400 font-medium mt-1 flex items-center gap-1">
                                <span>⚠️</span> {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center justify-between pt-0.5">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="remember" class="w-4 h-4 rounded-sm border-zinc-300 text-blue-600 focus:ring-blue-500 dark:bg-zinc-950">
                            <span class="text-xs text-zinc-600 dark:text-zinc-400 select-none">Ghi nhớ đăng nhập</span>
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="w-full py-2.5 px-4 rounded-md bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold tracking-wide transition-all shadow-md shadow-blue-500/10 hover:shadow-blue-500/20 active:scale-[0.99]">
                        ĐĂNG NHẬP VÀO HỆ THỐNG
                    </button>
                </form>

            </div>
        </div>

    </div>
</div>
@endsection
