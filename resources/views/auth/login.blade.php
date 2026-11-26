@extends('layouts.client', ['title' => 'Đăng nhập tài khoản - tinh.'])

@section('content')
<div class="py-12 sm:py-16 lg:py-20 bg-[#fafbfc] min-h-[calc(100vh-16rem)] flex items-center justify-center px-4 sm:px-6">
    <div class="w-full max-w-md">

        <!-- Main Card -->
        <div class="bg-white rounded-3xl border border-slate-200/80 p-8 sm:p-10 shadow-xl shadow-slate-900/5">
            
            <!-- Brand Badge & Title -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center gap-1 mb-3">
                    <span class="text-3xl font-black tracking-tight text-slate-900">tinh<span class="text-blue-600 font-extrabold text-4xl leading-none">.</span></span>
                </div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">
                    Đăng nhập tài khoản
                </h1>
                <p class="mt-2 text-xs sm:text-sm text-slate-500">
                    Chào mừng bạn quay lại! Khám phá công nghệ tinh chọn.
                </p>
            </div>

            <!-- Flash Session Alerts -->
            @if(session('error'))
                <div class="mb-6 p-4 rounded-2xl bg-rose-50 border border-rose-200/80 text-rose-700 text-xs sm:text-sm flex items-start gap-3">
                    <i class="fa-solid fa-circle-exclamation text-rose-500 mt-0.5 shrink-0 text-base"></i>
                    <div>{{ session('error') }}</div>
                </div>
            @endif

            @if(session('success'))
                <div class="mb-6 p-4 rounded-2xl bg-emerald-50 border border-emerald-200/80 text-emerald-700 text-xs sm:text-sm flex items-start gap-3">
                    <i class="fa-solid fa-circle-check text-emerald-500 mt-0.5 shrink-0 text-base"></i>
                    <div>{{ session('success') }}</div>
                </div>
            @endif

            @if(session('status'))
                <div class="mb-6 p-4 rounded-2xl bg-blue-50 border border-blue-200/80 text-blue-700 text-xs sm:text-sm flex items-start gap-3">
                    <i class="fa-solid fa-circle-info text-blue-500 mt-0.5 shrink-0 text-base"></i>
                    <div>{{ session('status') }}</div>
                </div>
            @endif

            <!-- Google OAuth Quick Login -->
            <div class="mb-6">
                <a href="{{ route('login.google') }}" 
                   class="w-full flex items-center justify-center gap-3 px-4 py-3 rounded-2xl border border-slate-200 bg-white hover:bg-slate-50 text-xs sm:text-sm font-semibold text-slate-700 shadow-2xs hover:border-slate-300 transition-all active:scale-[0.99]">
                    <svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24">
                        <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                        <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                        <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/>
                        <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/>
                    </svg>
                    <span>Tiếp tục với Google</span>
                </a>
            </div>

            <!-- Divider -->
            <div class="relative flex items-center justify-center mb-6">
                <div class="w-full border-t border-slate-200"></div>
                <span class="absolute bg-white px-3 text-[11px] font-medium uppercase tracking-wider text-slate-400">
                    Hoặc dùng Email
                </span>
            </div>

            <!-- Form -->
            <form action="{{ route('login.post') }}" method="POST" class="space-y-4" x-data="{ showPass: false }">
                @csrf

                <!-- Email Input -->
                <div>
                    <label for="email" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        Địa chỉ Email
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400">
                            <i class="fa-regular fa-envelope text-sm"></i>
                        </span>
                        <input type="email" 
                               id="email" 
                               name="email" 
                               value="{{ old('email') }}" 
                               required 
                               autofocus
                               autocomplete="email"
                               placeholder="tenban@email.com"
                               class="w-full pl-10 pr-4 py-2.5 sm:py-3 rounded-2xl border text-xs sm:text-sm bg-slate-50/50 hover:bg-white focus:bg-white transition-all placeholder:text-slate-400 outline-none
                                      {{ $errors->has('email') ? 'border-rose-400 focus:border-rose-600 focus:ring-4 focus:ring-rose-500/10' : 'border-slate-200 focus:border-blue-600 focus:ring-4 focus:ring-blue-500/10' }}">
                    </div>
                    @error('email')
                        <p class="text-xs text-rose-600 font-medium mt-1.5 flex items-center gap-1.5">
                            <i class="fa-solid fa-circle-exclamation text-[11px]"></i>
                            <span>{{ $message }}</span>
                        </p>
                    @enderror
                </div>

                <!-- Password Input -->
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label for="password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                            Mật khẩu
                        </label>
                        <a href="{{ route('password.request') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-700 hover:underline">
                            Quên mật khẩu?
                        </a>
                    </div>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400">
                            <i class="fa-solid fa-lock text-sm"></i>
                        </span>
                        <input :type="showPass ? 'text' : 'password'" 
                               id="password" 
                               name="password" 
                               required 
                               autocomplete="current-password"
                               placeholder="••••••••"
                               class="w-full pl-10 pr-10 py-2.5 sm:py-3 rounded-2xl border text-xs sm:text-sm bg-slate-50/50 hover:bg-white focus:bg-white transition-all placeholder:text-slate-400 outline-none
                                      {{ $errors->has('password') ? 'border-rose-400 focus:border-rose-600 focus:ring-4 focus:ring-rose-500/10' : 'border-slate-200 focus:border-blue-600 focus:ring-4 focus:ring-blue-500/10' }}">
                        <button type="button" 
                                @click="showPass = !showPass" 
                                class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-slate-400 hover:text-slate-600 transition-colors cursor-pointer">
                            <i :class="showPass ? 'fa-regular fa-eye-slash' : 'fa-regular fa-eye'" class="text-sm"></i>
                        </button>
                    </div>
                    @error('password')
                        <p class="text-xs text-rose-600 font-medium mt-1.5 flex items-center gap-1.5">
                            <i class="fa-solid fa-circle-exclamation text-[11px]"></i>
                            <span>{{ $message }}</span>
                        </p>
                    @enderror
                </div>

                <!-- Remember Me -->
                <div class="flex items-center justify-between pt-1">
                    <label class="flex items-center gap-2.5 cursor-pointer select-none">
                        <input type="checkbox" name="remember" class="w-4 h-4 rounded-md border-slate-300 text-blue-600 focus:ring-blue-500 cursor-pointer">
                        <span class="text-xs sm:text-sm text-slate-600">Ghi nhớ đăng nhập</span>
                    </label>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="w-full py-3 px-4 rounded-2xl bg-slate-900 hover:bg-blue-600 text-white text-xs sm:text-sm font-bold tracking-wide transition-all shadow-md shadow-slate-950/10 hover:shadow-blue-600/20 active:scale-[0.99] cursor-pointer mt-2">
                    Đăng nhập tài khoản
                </button>
            </form>

            <!-- Bottom Footnote -->
            <div class="mt-8 pt-6 border-t border-slate-100 text-center">
                <p class="text-xs sm:text-sm text-slate-500">
                    Chưa có tài khoản? 
                    <a href="{{ route('register') }}" class="font-bold text-blue-600 hover:text-blue-700 hover:underline">
                        Đăng ký tài khoản
                    </a>
                </p>
            </div>

        </div>

        <!-- Trust Features Strip -->
        <div class="mt-8 grid grid-cols-3 gap-3 text-center text-[11px] text-slate-500">
            <div class="flex items-center justify-center gap-1.5">
                <i class="fa-solid fa-shield-halved text-emerald-500"></i>
                <span>SSL 256-bit</span>
            </div>
            <div class="flex items-center justify-center gap-1.5">
                <i class="fa-solid fa-arrows-rotate text-blue-500"></i>
                <span>Đổi mới 30 ngày</span>
            </div>
            <div class="flex items-center justify-center gap-1.5">
                <i class="fa-solid fa-medal text-amber-500"></i>
                <span>100% Chính hãng</span>
            </div>
        </div>

    </div>
</div>
@endsection
