@extends('layouts.client', ['title' => 'Đặt lại mật khẩu mới - tinh.'])

@section('content')
<div class="py-12 sm:py-16 lg:py-20 bg-[#fafbfc] min-h-[calc(100vh-16rem)] flex items-center justify-center px-4 sm:px-6">
    <div class="w-full max-w-md">

        <!-- Main Card -->
        <div class="bg-white rounded-3xl border border-slate-200/80 p-8 sm:p-10 shadow-xl shadow-slate-900/5">
            
            <!-- Icon & Header -->
            <div class="text-center mb-8">
                <div class="w-14 h-14 rounded-2xl bg-blue-50 text-blue-600 mx-auto flex items-center justify-center text-2xl mb-4 shadow-2xs">
                    <i class="fa-solid fa-shield-halved"></i>
                </div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">
                    Đặt lại mật khẩu
                </h1>
                <p class="mt-2 text-xs sm:text-sm text-slate-500 leading-relaxed">
                    Nhập mật khẩu mới an toàn cho tài khoản của bạn (tối thiểu 8 ký tự).
                </p>
            </div>

            <!-- Form -->
            <form action="{{ route('password.update') }}" method="POST" class="space-y-4" x-data="{ showPass: false }">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">

                <!-- Email (Readonly) -->
                <div>
                    <label for="email" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        Tài khoản Email
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400">
                            <i class="fa-regular fa-envelope text-sm"></i>
                        </span>
                        <input type="email" 
                               id="email" 
                               name="email" 
                               value="{{ $email ?? old('email') }}" 
                               required 
                               readonly
                               class="w-full pl-10 pr-4 py-2.5 sm:py-3 rounded-2xl border border-slate-200 bg-slate-100/70 text-slate-500 text-xs sm:text-sm outline-none cursor-not-allowed">
                    </div>
                    @error('email')
                        <p class="text-xs text-rose-600 font-medium mt-1.5 flex items-center gap-1.5">
                            <i class="fa-solid fa-circle-exclamation text-[11px]"></i>
                            <span>{{ $message }}</span>
                        </p>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        Mật khẩu mới
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400">
                            <i class="fa-solid fa-lock text-sm"></i>
                        </span>
                        <input :type="showPass ? 'text' : 'password'" 
                               id="password" 
                               name="password" 
                               required 
                               autofocus
                               placeholder="Tối thiểu 8 ký tự"
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

                <!-- Password Confirmation -->
                <div>
                    <label for="password_confirmation" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        Nhập lại mật khẩu mới
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400">
                            <i class="fa-solid fa-shield-check text-sm"></i>
                        </span>
                        <input :type="showPass ? 'text' : 'password'" 
                               id="password_confirmation" 
                               name="password_confirmation" 
                               required 
                               placeholder="Khớp với mật khẩu trên"
                               class="w-full pl-10 pr-4 py-2.5 sm:py-3 rounded-2xl border border-slate-200 focus:border-blue-600 focus:ring-4 focus:ring-blue-500/10 text-xs sm:text-sm bg-slate-50/50 hover:bg-white focus:bg-white transition-all placeholder:text-slate-400 outline-none">
                    </div>
                </div>

                <button type="submit" class="w-full py-3 px-4 rounded-2xl bg-slate-900 hover:bg-blue-600 text-white text-xs sm:text-sm font-bold tracking-wide transition-all shadow-md shadow-slate-950/10 hover:shadow-blue-600/20 active:scale-[0.99] cursor-pointer mt-2">
                    Cập nhật mật khẩu mới
                </button>
            </form>

        </div>

    </div>
</div>
@endsection
