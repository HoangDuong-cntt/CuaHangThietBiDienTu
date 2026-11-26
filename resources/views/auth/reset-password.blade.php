@extends('layouts.client', ['title' => 'Đặt lại mật khẩu mới - D&D.'])

@section('content')
<div class="min-h-[calc(100vh-14rem)] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 bg-slate-100/60">
    <div class="w-full max-w-md">

        <!-- Card Container -->
        <div class="bg-white rounded-2xl border border-slate-200/90 p-8 sm:p-10 shadow-xl shadow-slate-900/5">
            
            <!-- Brand & Header -->
            <div class="text-center mb-8">
                <a href="{{ route('home') }}" class="inline-flex items-center gap-1 group mb-4">
                    <span class="text-2xl font-black tracking-tight text-slate-900 group-hover:text-blue-600 transition-colors">D&D<span class="text-blue-600 font-extrabold text-3xl leading-none">.</span></span>
                </a>
                <div class="w-14 h-14 rounded-2xl bg-blue-50 text-blue-600 mx-auto flex items-center justify-center text-2xl mb-3 shadow-2xs">
                    <i class="fa-solid fa-shield-halved"></i>
                </div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">
                    Đặt lại mật khẩu
                </h1>
                <p class="mt-2 text-xs sm:text-sm text-slate-500 leading-relaxed">
                    Nhập mật khẩu mới an toàn cho tài khoản D&D của bạn (tối thiểu 8 ký tự).
                </p>
            </div>

            <!-- Form -->
            <form action="{{ route('password.update') }}" method="POST" class="space-y-4" x-data="{ showPass: false }">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">

                <!-- Email (Readonly) -->
                <div class="space-y-1.5">
                    <label for="email" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                        Tài khoản Email
                    </label>
                    <input type="email" 
                           id="email" 
                           name="email" 
                           value="{{ $email ?? old('email') }}" 
                           required 
                           readonly
                           class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-100/70 text-slate-500 text-sm outline-none cursor-not-allowed font-medium">
                    @error('email')
                        <p class="text-xs text-rose-600 font-medium mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div class="space-y-1.5">
                    <label for="password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                        Mật khẩu mới
                    </label>
                    <div class="relative">
                        <input :type="showPass ? 'text' : 'password'" 
                               id="password" 
                               name="password" 
                               required 
                               autofocus
                               placeholder="Tối thiểu 8 ký tự"
                               class="w-full pl-4 pr-11 py-3 rounded-xl border text-sm bg-slate-50/50 hover:bg-white focus:bg-white transition-all placeholder:text-slate-400 outline-none font-medium
                                      {{ $errors->has('password') ? 'border-rose-400 focus:border-rose-600 focus:ring-4 focus:ring-rose-500/10' : 'border-slate-200 focus:border-blue-600 focus:ring-4 focus:ring-blue-500/10' }}">
                        <button type="button" 
                                @click="showPass = !showPass" 
                                class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-slate-400 hover:text-slate-600 transition-colors cursor-pointer">
                            <i :class="showPass ? 'fa-regular fa-eye-slash' : 'fa-regular fa-eye'" class="text-sm"></i>
                        </button>
                    </div>
                    @error('password')
                        <p class="text-xs text-rose-600 font-medium mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password Confirmation -->
                <div class="space-y-1.5">
                    <label for="password_confirmation" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                        Nhập lại mật khẩu mới
                    </label>
                    <input :type="showPass ? 'text' : 'password'" 
                           id="password_confirmation" 
                           name="password_confirmation" 
                           required 
                           placeholder="Khớp với mật khẩu trên"
                           class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-blue-600 focus:ring-4 focus:ring-blue-500/10 text-sm bg-slate-50/50 hover:bg-white focus:bg-white transition-all placeholder:text-slate-400 outline-none font-medium">
                </div>

                <button type="submit" class="w-full py-3.5 px-6 rounded-xl bg-slate-900 hover:bg-blue-600 text-white text-xs sm:text-sm font-bold uppercase tracking-wider transition-all shadow-md shadow-slate-950/10 hover:shadow-blue-600/20 active:scale-[0.99] cursor-pointer mt-2">
                    Cập nhật mật khẩu mới
                </button>
            </form>

        </div>

    </div>
</div>
@endsection
