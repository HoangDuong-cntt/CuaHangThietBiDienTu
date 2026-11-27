@extends('layouts.client', ['title' => 'Đăng nhập tài khoản - D&D.'])

@section('content')
<div class="min-h-[calc(100vh-16rem)] flex items-center justify-center py-10 px-4 sm:px-6">
    <div class="w-full max-w-[420px]">

        <!-- Compact Card -->
        <div class="bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-8 shadow-sm">
            
            <!-- Brand & Title -->
            <div class="text-center mb-6">
                <a href="{{ route('home') }}" class="inline-flex items-center gap-1 group mb-2">
                    <span class="text-2xl font-black tracking-tight text-slate-900 group-hover:text-blue-600 transition-colors">D&D<span class="text-blue-600 font-extrabold text-3xl leading-none">.</span></span>
                </a>
                <h1 class="text-xl font-bold tracking-tight text-slate-900">
                    Đăng nhập tài khoản
                </h1>
                <p class="text-xs text-slate-500 mt-1">
                    Nhập email và mật khẩu để tiếp tục mua sắm
                </p>
            </div>

            <!-- Flash Session Alerts -->
            @if(session('error'))
                <div class="mb-4 p-3 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 text-xs flex items-start gap-2">
                    <i class="fa-solid fa-circle-exclamation text-rose-500 mt-0.5 shrink-0"></i>
                    <div>{{ session('error') }}</div>
                </div>
            @endif

            @if(session('success'))
                <div class="mb-4 p-3 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs flex items-start gap-2">
                    <i class="fa-solid fa-circle-check text-emerald-500 mt-0.5 shrink-0"></i>
                    <div>{{ session('success') }}</div>
                </div>
            @endif

            <!-- Google OAuth Button -->
            <div class="mb-4">
                <a href="{{ route('login.google') }}" 
                   class="w-full flex items-center justify-center gap-2.5 px-4 py-2.5 rounded-full border border-slate-200 bg-white hover:bg-slate-50 text-xs font-semibold text-slate-700 shadow-2xs hover:border-slate-300 transition-all active:scale-[0.99]">
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
            <div class="relative flex items-center justify-center mb-4">
                <div class="w-full border-t border-slate-200"></div>
                <span class="absolute bg-white px-2.5 text-[10px] font-medium uppercase tracking-wider text-slate-400">
                    Hoặc dùng Email
                </span>
            </div>

            <!-- Form -->
            <form action="{{ route('login.post') }}" method="POST" class="space-y-3.5">
                @csrf

                <!-- Email Field -->
                <div>
                    <label for="email" class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1">
                        Địa chỉ Email
                    </label>
                    <input type="email" 
                           id="email" 
                           name="email" 
                           value="{{ old('email') }}" 
                           required 
                           autofocus
                           autocomplete="email"
                           placeholder="name@email.com"
                           class="w-full px-3.5 py-2.5 rounded-xl border text-xs sm:text-sm bg-white hover:border-slate-300 focus:border-blue-600 focus:ring-2 focus:ring-blue-500/10 outline-none transition-all placeholder:text-slate-400
                                  {{ $errors->has('email') ? 'border-rose-400 focus:border-rose-600 focus:ring-2 focus:ring-rose-500/10' : 'border-slate-200' }}">
                    @error('email')
                        <p class="text-[11px] text-rose-600 font-medium mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password Field (Native type="password" - ALWAYS MASKED) -->
                <div>
                    <div class="flex items-center justify-between mb-1">
                        <label for="password" class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider">
                            Mật khẩu
                        </label>
                        <a href="{{ route('password.request') }}" class="text-[11px] font-medium text-blue-600 hover:text-blue-700 hover:underline">
                            Quên mật khẩu?
                        </a>
                    </div>
                    <div class="relative">
                        <input type="password" 
                               id="password" 
                               name="password" 
                               required 
                               autocomplete="current-password"
                               placeholder="••••••••"
                               class="w-full pl-3.5 pr-10 py-2.5 rounded-xl border text-xs sm:text-sm bg-white hover:border-slate-300 focus:border-blue-600 focus:ring-2 focus:ring-blue-500/10 outline-none transition-all placeholder:text-slate-400
                                      {{ $errors->has('password') ? 'border-rose-400 focus:border-rose-600 focus:ring-2 focus:ring-rose-500/10' : 'border-slate-200' }}">
                        <button type="button" 
                                onclick="togglePasswordVisibility('password', this)" 
                                class="absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400 hover:text-slate-600 transition-colors cursor-pointer"
                                title="Ẩn/Hiện mật khẩu">
                            <i class="fa-regular fa-eye text-xs"></i>
                        </button>
                    </div>
                    @error('password')
                        <p class="text-[11px] text-rose-600 font-medium mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Remember Me -->
                <div class="flex items-center justify-between pt-0.5">
                    <label class="flex items-center gap-2 cursor-pointer select-none">
                        <input type="checkbox" name="remember" class="w-3.5 h-3.5 rounded border-slate-300 text-blue-600 focus:ring-blue-500 cursor-pointer">
                        <span class="text-xs text-slate-600">Ghi nhớ đăng nhập</span>
                    </label>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="w-full py-3 px-5 rounded-full bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold tracking-wide transition-all shadow-md shadow-blue-600/20 active:scale-[0.99] cursor-pointer mt-1">
                    Đăng nhập tài khoản
                </button>
            </form>

            <!-- Bottom Footnote -->
            <div class="mt-6 pt-4 border-t border-slate-100 text-center">
                <p class="text-xs text-slate-500">
                    Chưa có tài khoản? 
                    <a href="{{ route('register') }}" class="font-bold text-blue-600 hover:text-blue-700 hover:underline">
                        Đăng ký ngay
                    </a>
                </p>
            </div>

        </div>

    </div>
</div>

<script>
function togglePasswordVisibility(inputId, btn) {
    const input = document.getElementById(inputId);
    if (!input) return;
    const isPassword = input.type === 'password';
    input.type = isPassword ? 'text' : 'password';
    const icon = btn.querySelector('i');
    if (icon) {
        icon.className = isPassword ? 'fa-regular fa-eye-slash text-xs' : 'fa-regular fa-eye text-xs';
    }
}
</script>
@endsection
