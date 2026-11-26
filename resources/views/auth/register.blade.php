@extends('layouts.app', ['title' => 'Đăng ký tài khoản'])

@section('content')
<div class="min-h-[calc(100vh-7.5rem)] flex items-center justify-center p-4 sm:p-6 lg:p-8 bg-zinc-100/70 dark:bg-zinc-950">
    
    <!-- Main Auth Card Container -->
    <div class="w-full max-w-5xl rounded-lg border border-zinc-200/80 dark:border-zinc-800 bg-white dark:bg-zinc-900 shadow-xl overflow-hidden grid grid-cols-1 lg:grid-cols-12 min-h-[640px]">
        
        <!-- Left Showcase Column -->
        <div class="hidden lg:flex lg:col-span-5 relative bg-zinc-950 p-10 flex-col justify-between overflow-hidden text-white">
            <img src="https://images.unsplash.com/photo-1518770660439-4636190af475?auto=format&fit=crop&w=1200&q=80" 
                 alt="Hardware Chip Microprocessor" 
                 class="absolute inset-0 w-full h-full object-cover opacity-25 mix-blend-luminosity scale-105">
            <div class="absolute inset-0 bg-gradient-to-t from-zinc-950 via-zinc-950/80 to-zinc-950/40"></div>
            
            <div class="relative z-10">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded bg-white/10 backdrop-blur-md border border-white/15 text-[11px] font-mono tracking-wider text-zinc-200 uppercase">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                    <span>THÀNH VIÊN SMARTTECH VIP</span>
                </div>
            </div>

            <div class="relative z-10 space-y-4 my-auto">
                <div class="w-10 h-1 bg-emerald-500 rounded-full"></div>
                <h2 class="text-3xl font-extrabold tracking-tight text-white leading-tight">
                    Đăng Ký Nhanh. Ưu Đãi Bất Tận.
                </h2>
                <p class="text-zinc-400 text-xs leading-relaxed">
                    Tạo tài khoản để nhận mã giảm giá thành viên, lưu nhiều địa chỉ nhận hàng và trải nghiệm quy trình mua sắm phần cứng mượt mà nhất.
                </p>

                <!-- Value Props List -->
                <div class="pt-4 space-y-2 border-t border-white/10">
                    <div class="flex items-center gap-2.5 text-xs text-zinc-300">
                        <span class="w-5 h-5 rounded bg-emerald-500/20 text-emerald-400 flex items-center justify-center text-[10px] font-bold">✓</span>
                        <span>Mã giảm chào mừng <strong class="text-white font-mono">WELCOME50</strong> giảm 50.000đ.</span>
                    </div>
                    <div class="flex items-center gap-2.5 text-xs text-zinc-300">
                        <span class="w-5 h-5 rounded bg-emerald-500/20 text-emerald-400 flex items-center justify-center text-[10px] font-bold">✓</span>
                        <span>Theo dõi lịch sử đơn và lộ trình giao hàng trực tuyến.</span>
                    </div>
                    <div class="flex items-center gap-2.5 text-xs text-zinc-300">
                        <span class="w-5 h-5 rounded bg-emerald-500/20 text-emerald-400 flex items-center justify-center text-[10px] font-bold">✓</span>
                        <span>Tra cứu bảo hành nhanh bằng số điện thoại.</span>
                    </div>
                </div>
            </div>

            <div class="relative z-10 border-t border-white/10 pt-4 flex items-center justify-between text-[11px] text-zinc-500 font-mono">
                <span>SMARTTECH PLATFORM</span>
                <span>DATA PRIVACY ENCRYPTED</span>
            </div>
        </div>

        <!-- Right Form Column -->
        <div class="lg:col-span-7 p-6 sm:p-10 lg:p-12 flex flex-col justify-center bg-white dark:bg-zinc-900">
            <div class="max-w-md w-full mx-auto space-y-5">
                
                <div class="space-y-1">
                    <span class="text-[10px] font-mono font-bold tracking-widest uppercase text-emerald-600 dark:text-emerald-400">
                        KHỞI TẠO TÀI KHOẢN MỚI
                    </span>
                    <h1 class="text-2xl font-bold tracking-tight text-zinc-900 dark:text-white">
                        Đăng ký thành viên
                    </h1>
                    <p class="text-xs text-zinc-500">
                        Đã có tài khoản? 
                        <a href="{{ route('login') }}" class="font-semibold text-blue-600 dark:text-blue-400 hover:underline">
                            Đăng nhập ngay
                        </a>
                    </p>
                </div>

                <!-- Google OAuth -->
                <div>
                    <a href="{{ route('login.google') }}" class="w-full flex items-center justify-center gap-3 px-4 py-2.5 rounded-md border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 hover:bg-zinc-50 dark:hover:bg-zinc-750 text-xs font-semibold text-zinc-700 dark:text-zinc-200 shadow-sm transition-all hover:border-zinc-300">
                        <svg class="w-4 h-4" viewBox="0 0 24 24">
                            <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                            <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                            <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/>
                            <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/>
                        </svg>
                        <span>Đăng ký nhanh với Google</span>
                    </a>
                </div>

                <div class="relative flex items-center justify-center">
                    <div class="w-full border-t border-zinc-200 dark:border-zinc-800"></div>
                    <span class="absolute bg-white dark:bg-zinc-900 px-3 text-[10px] font-mono uppercase text-zinc-400">
                        HOẶC ĐIỀN THÔNG TIN
                    </span>
                </div>

                <form action="{{ route('register.post') }}" method="POST" class="space-y-3.5" x-data="{ showPass: false }">
                    @csrf

                    <!-- Name -->
                    <div class="space-y-1">
                        <label for="name" class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300">
                            Họ và tên <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" 
                               id="name" 
                               name="name" 
                               value="{{ old('name') }}" 
                               required 
                               placeholder="Nguyễn Văn A"
                               class="w-full px-3.5 py-2.5 rounded-md border text-xs bg-zinc-50 dark:bg-zinc-950/50 transition-all placeholder:text-zinc-400
                                      {{ $errors->has('name') ? 'border-rose-500 ring-1 ring-rose-500' : 'border-zinc-200 dark:border-zinc-750 focus:border-blue-600 focus:ring-1 focus:ring-blue-600' }} outline-none">
                        @error('name')
                            <p class="text-[11px] text-rose-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email & Phone in 2 cols -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div class="space-y-1">
                            <label for="email" class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300">
                                Email <span class="text-rose-500">*</span>
                            </label>
                            <input type="email" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email') }}" 
                                   required 
                                   placeholder="name@email.com"
                                   class="w-full px-3.5 py-2.5 rounded-md border text-xs bg-zinc-50 dark:bg-zinc-950/50 transition-all placeholder:text-zinc-400
                                          {{ $errors->has('email') ? 'border-rose-500 ring-1 ring-rose-500' : 'border-zinc-200 dark:border-zinc-750 focus:border-blue-600 focus:ring-1 focus:ring-blue-600' }} outline-none">
                            @error('email')
                                <p class="text-[11px] text-rose-600 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-1">
                            <label for="phone" class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300">
                                Số điện thoại <span class="text-rose-500">*</span>
                            </label>
                            <input type="tel" 
                                   id="phone" 
                                   name="phone" 
                                   value="{{ old('phone') }}" 
                                   required
                                   placeholder="0901234567"
                                   class="w-full px-3.5 py-2.5 rounded-md border text-xs bg-zinc-50 dark:bg-zinc-950/50 transition-all placeholder:text-zinc-400
                                          {{ $errors->has('phone') ? 'border-rose-500 ring-1 ring-rose-500' : 'border-zinc-200 dark:border-zinc-750 focus:border-blue-600 focus:ring-1 focus:ring-blue-600' }} outline-none">
                            @error('phone')
                                <p class="text-[11px] text-rose-600 font-medium">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Password Fields in 2 cols -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div class="space-y-1">
                            <label for="password" class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300">
                                Mật khẩu <span class="text-rose-500">*</span>
                            </label>
                            <input :type="showPass ? 'text' : 'password'" 
                                   id="password" 
                                   name="password" 
                                   required 
                                   placeholder="Tối thiểu 8 ký tự"
                                   class="w-full px-3.5 py-2.5 rounded-md border text-xs bg-zinc-50 dark:bg-zinc-950/50 transition-all placeholder:text-zinc-400
                                          {{ $errors->has('password') ? 'border-rose-500 ring-1 ring-rose-500' : 'border-zinc-200 dark:border-zinc-750 focus:border-blue-600 focus:ring-1 focus:ring-blue-600' }} outline-none">
                            @error('password')
                                <p class="text-[11px] text-rose-600 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-1">
                            <label for="password_confirmation" class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300">
                                Nhập lại mật khẩu <span class="text-rose-500">*</span>
                            </label>
                            <input :type="showPass ? 'text' : 'password'" 
                                   id="password_confirmation" 
                                   name="password_confirmation" 
                                   required 
                                   placeholder="Nhập lại"
                                   class="w-full px-3.5 py-2.5 rounded-md border border-zinc-200 dark:border-zinc-750 focus:border-blue-600 focus:ring-1 focus:ring-blue-600 text-xs bg-zinc-50 dark:bg-zinc-950/50 transition-all placeholder:text-zinc-400 outline-none">
                        </div>
                    </div>

                    <div class="flex items-center justify-between pt-1">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" @change="showPass = !showPass" class="w-4 h-4 rounded-sm border-zinc-300 text-blue-600 focus:ring-blue-500 dark:bg-zinc-950">
                            <span class="text-xs text-zinc-600 dark:text-zinc-400 select-none">Hiển thị mật khẩu</span>
                        </label>
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="w-full py-2.5 px-4 rounded-md bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold tracking-wide transition-all shadow-md shadow-blue-500/10 hover:shadow-blue-500/20 active:scale-[0.99]">
                            ĐĂNG KÝ TÀI KHOẢN NGAY
                        </button>
                    </div>
                </form>

            </div>
        </div>

    </div>
</div>
@endsection
