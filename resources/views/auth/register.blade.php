@extends('layouts.client', ['title' => 'Đăng ký tài khoản - D&D.'])

@section('content')
<div class="min-h-[calc(100vh-14rem)] flex items-center justify-center py-10 px-4 sm:px-6 lg:px-8 bg-slate-100/60">
    
    <!-- Master Studio Split Container -->
    <div class="w-full max-w-5xl rounded-2xl border border-slate-200/90 bg-white shadow-2xl shadow-slate-900/8 overflow-hidden grid grid-cols-1 lg:grid-cols-12 min-h-[640px]">
        
        <!-- Left Column: Showcase (5 cols on lg) -->
        <div class="hidden lg:flex lg:col-span-5 relative bg-slate-950 p-10 flex-col justify-between overflow-hidden text-white">
            <img src="https://images.unsplash.com/photo-1518770660439-4636190af475?auto=format&fit=crop&w=1200&q=80" 
                 alt="D&D Silicon Hardware" 
                 class="absolute inset-0 w-full h-full object-cover opacity-20 mix-blend-luminosity scale-105">
            <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/85 to-slate-950/40"></div>
            
            <div class="relative z-10">
                <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-white/10 backdrop-blur-md border border-white/15 text-[11px] font-mono tracking-wider text-slate-200">
                    <span class="w-1.5 h-1.5 rounded-full bg-blue-400"></span>
                    <span>THÀNH VIÊN D&D VIP</span>
                </div>
            </div>

            <div class="relative z-10 space-y-4 my-auto">
                <div class="w-12 h-1 bg-emerald-500 rounded-full"></div>
                <h2 class="text-3xl font-extrabold tracking-tight text-white leading-tight">
                    Đăng Ký Nhanh.<br>Ưu Đãi Bất Tận.
                </h2>
                <p class="text-slate-300 text-xs leading-relaxed">
                    Khởi tạo tài khoản thành viên D&D để quản lý đơn hàng thông minh, nhận hỗ trợ từ trợ lý AI và hưởng đặc quyền tích điểm đổi quà.
                </p>

                <!-- Value Props List -->
                <div class="pt-4 space-y-2.5 border-t border-white/10">
                    <div class="flex items-center gap-2.5 text-xs text-slate-300">
                        <span class="w-5 h-5 rounded-md bg-emerald-500/20 text-emerald-400 flex items-center justify-center text-[10px] font-bold">✓</span>
                        <span>Mã chào mừng thành viên mới giảm đến 10%.</span>
                    </div>
                    <div class="flex items-center gap-2.5 text-xs text-slate-300">
                        <span class="w-5 h-5 rounded-md bg-emerald-500/20 text-emerald-400 flex items-center justify-center text-[10px] font-bold">✓</span>
                        <span>Theo dõi lộ trình giao hàng trực tuyến theo thời gian thực.</span>
                    </div>
                    <div class="flex items-center gap-2.5 text-xs text-slate-300">
                        <span class="w-5 h-5 rounded-md bg-emerald-500/20 text-emerald-400 flex items-center justify-center text-[10px] font-bold">✓</span>
                        <span>Tra cứu lịch sử bảo hành điện tử nhanh chóng bằng số điện thoại.</span>
                    </div>
                </div>
            </div>

            <div class="relative z-10 border-t border-white/10 pt-4 flex items-center justify-between text-[11px] text-slate-400 font-mono">
                <span>D&D ECOSYSTEM</span>
                <span>DATA PRIVACY ENCRYPTED</span>
            </div>
        </div>

        <!-- Right Column: Form (7 cols on lg) -->
        <div class="lg:col-span-7 p-8 sm:p-12 flex flex-col justify-center bg-white">
            <div class="max-w-md w-full mx-auto space-y-5">
                
                <!-- Title & Brand Header -->
                <div class="space-y-1.5">
                    <a href="{{ route('home') }}" class="inline-flex items-center gap-1 group mb-1">
                        <span class="text-2xl font-black tracking-tight text-slate-900 group-hover:text-blue-600 transition-colors">D&D<span class="text-blue-600 font-extrabold text-3xl leading-none">.</span></span>
                    </a>
                    <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-slate-900">
                        Đăng ký tài khoản
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-500">
                        Đã có tài khoản? 
                        <a href="{{ route('login') }}" class="font-bold text-blue-600 hover:text-blue-700 hover:underline">
                            Đăng nhập ngay
                        </a>
                    </p>
                </div>

                <!-- Flash Alert -->
                @if(session('error'))
                    <div class="p-3.5 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 text-xs flex items-start gap-2.5">
                        <i class="fa-solid fa-circle-exclamation text-rose-500 mt-0.5 shrink-0 text-sm"></i>
                        <div>{{ session('error') }}</div>
                    </div>
                @endif

                <!-- Google OAuth Button -->
                <div>
                    <a href="{{ route('login.google') }}" 
                       class="w-full flex items-center justify-center gap-3 px-4 py-3 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-xs sm:text-sm font-semibold text-slate-700 shadow-2xs hover:border-slate-300 transition-all active:scale-[0.99]">
                        <svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24">
                            <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                            <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                            <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/>
                            <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/>
                        </svg>
                        <span>Đăng ký nhanh với Google</span>
                    </a>
                </div>

                <div class="relative flex items-center justify-center">
                    <div class="w-full border-t border-slate-200"></div>
                    <span class="absolute bg-white px-3 text-[10px] font-mono uppercase tracking-wider text-slate-400">
                        HOẶC ĐIỀN THÔNG TIN
                    </span>
                </div>

                <form action="{{ route('register.post') }}" method="POST" class="space-y-3.5" x-data="{ showPass: false }">
                    @csrf

                    <!-- Name (Clean, NO overlapping left icon) -->
                    <div class="space-y-1.5">
                        <label for="name" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                            Họ và tên <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" 
                               id="name" 
                               name="name" 
                               value="{{ old('name') }}" 
                               required 
                               autofocus
                               placeholder="Nguyễn Văn A"
                               class="w-full px-4 py-2.5 sm:py-3 rounded-xl border text-sm bg-slate-50/50 hover:bg-white focus:bg-white transition-all placeholder:text-slate-400 outline-none font-medium
                                      {{ $errors->has('name') ? 'border-rose-400 focus:border-rose-600 focus:ring-4 focus:ring-rose-500/10' : 'border-slate-200 focus:border-blue-600 focus:ring-4 focus:ring-blue-500/10' }}">
                        @error('name')
                            <p class="text-xs text-rose-600 font-medium mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email & Phone (2 cols) -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                        <div class="space-y-1.5">
                            <label for="email" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                                Email <span class="text-rose-500">*</span>
                            </label>
                            <input type="email" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email') }}" 
                                   required 
                                   placeholder="name@email.com"
                                   class="w-full px-4 py-2.5 sm:py-3 rounded-xl border text-sm bg-slate-50/50 hover:bg-white focus:bg-white transition-all placeholder:text-slate-400 outline-none font-medium
                                          {{ $errors->has('email') ? 'border-rose-400 focus:border-rose-600 focus:ring-4 focus:ring-rose-500/10' : 'border-slate-200 focus:border-blue-600 focus:ring-4 focus:ring-blue-500/10' }}">
                            @error('email')
                                <p class="text-xs text-rose-600 font-medium mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-1.5">
                            <label for="phone" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                                Số điện thoại <span class="text-rose-500">*</span>
                            </label>
                            <input type="tel" 
                                   id="phone" 
                                   name="phone" 
                                   value="{{ old('phone') }}" 
                                   required 
                                   placeholder="0901234567"
                                   class="w-full px-4 py-2.5 sm:py-3 rounded-xl border text-sm bg-slate-50/50 hover:bg-white focus:bg-white transition-all placeholder:text-slate-400 outline-none font-medium
                                          {{ $errors->has('phone') ? 'border-rose-400 focus:border-rose-600 focus:ring-4 focus:ring-rose-500/10' : 'border-slate-200 focus:border-blue-600 focus:ring-4 focus:ring-blue-500/10' }}">
                            @error('phone')
                                <p class="text-xs text-rose-600 font-medium mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Password & Confirmation (2 cols) -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                        <div class="space-y-1.5">
                            <label for="password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                                Mật khẩu <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <input :type="showPass ? 'text' : 'password'" 
                                       id="password" 
                                       name="password" 
                                       required 
                                       placeholder="Tối thiểu 8 ký tự"
                                       class="w-full pl-4 pr-10 py-2.5 sm:py-3 rounded-xl border text-sm bg-slate-50/50 hover:bg-white focus:bg-white transition-all placeholder:text-slate-400 outline-none font-medium
                                              {{ $errors->has('password') ? 'border-rose-400 focus:border-rose-600 focus:ring-4 focus:ring-rose-500/10' : 'border-slate-200 focus:border-blue-600 focus:ring-4 focus:ring-blue-500/10' }}">
                                <button type="button" 
                                        @click="showPass = !showPass" 
                                        class="absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400 hover:text-slate-600 transition-colors cursor-pointer">
                                    <i :class="showPass ? 'fa-regular fa-eye-slash' : 'fa-regular fa-eye'" class="text-sm"></i>
                                </button>
                            </div>
                            @error('password')
                                <p class="text-xs text-rose-600 font-medium mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-1.5">
                            <label for="password_confirmation" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                                Nhập lại mật khẩu <span class="text-rose-500">*</span>
                            </label>
                            <input :type="showPass ? 'text' : 'password'" 
                                   id="password_confirmation" 
                                   name="password_confirmation" 
                                   required 
                                   placeholder="Khớp với mật khẩu"
                                   class="w-full px-4 py-2.5 sm:py-3 rounded-xl border border-slate-200 focus:border-blue-600 focus:ring-4 focus:ring-blue-500/10 text-sm bg-slate-50/50 hover:bg-white focus:bg-white transition-all placeholder:text-slate-400 outline-none font-medium">
                        </div>
                    </div>

                    <!-- Terms -->
                    <div class="pt-1 text-[11px] text-slate-500 leading-relaxed">
                        Bằng việc bấm Đăng ký, bạn đồng ý với 
                        <a href="#" class="font-semibold text-slate-700 hover:text-blue-600 hover:underline">Điều khoản dịch vụ</a> 
                        và 
                        <a href="#" class="font-semibold text-slate-700 hover:text-blue-600 hover:underline">Chính sách bảo mật</a> 
                        của <strong class="text-slate-900">D&D.</strong>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="w-full py-3.5 px-6 rounded-xl bg-slate-900 hover:bg-blue-600 text-white text-xs sm:text-sm font-bold uppercase tracking-wider transition-all shadow-lg shadow-slate-950/10 hover:shadow-blue-600/20 active:scale-[0.99] cursor-pointer mt-1">
                        Đăng ký tài khoản
                    </button>
                </form>

            </div>
        </div>

    </div>
</div>
@endsection
