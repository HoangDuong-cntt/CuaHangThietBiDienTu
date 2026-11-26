@extends('layouts.client', ['title' => 'Quên mật khẩu - tinh.'])

@section('content')
<div class="py-12 sm:py-16 lg:py-20 bg-[#fafbfc] min-h-[calc(100vh-16rem)] flex items-center justify-center px-4 sm:px-6">
    <div class="w-full max-w-md">

        <!-- Main Card -->
        <div class="bg-white rounded-3xl border border-slate-200/80 p-8 sm:p-10 shadow-xl shadow-slate-900/5">
            
            <!-- Icon & Header -->
            <div class="text-center mb-8">
                <div class="w-14 h-14 rounded-2xl bg-blue-50 text-blue-600 mx-auto flex items-center justify-center text-2xl mb-4 shadow-2xs">
                    <i class="fa-solid fa-key"></i>
                </div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">
                    Quên mật khẩu?
                </h1>
                <p class="mt-2 text-xs sm:text-sm text-slate-500 leading-relaxed">
                    Nhập email đã đăng ký tài khoản. Hệ thống sẽ gửi cho bạn liên kết an toàn để đặt lại mật khẩu mới.
                </p>
            </div>

            <!-- Flash Session Alerts -->
            @if(session('success'))
                <div class="mb-6 p-4 rounded-2xl bg-emerald-50 border border-emerald-200/80 text-emerald-700 text-xs sm:text-sm flex items-start gap-3">
                    <i class="fa-solid fa-circle-check text-emerald-500 mt-0.5 shrink-0 text-base"></i>
                    <div>{{ session('success') }}</div>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 p-4 rounded-2xl bg-rose-50 border border-rose-200/80 text-rose-700 text-xs sm:text-sm flex items-start gap-3">
                    <i class="fa-solid fa-circle-exclamation text-rose-500 mt-0.5 shrink-0 text-base"></i>
                    <div>{{ session('error') }}</div>
                </div>
            @endif

            <!-- Form -->
            <form action="{{ route('password.email') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label for="email" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        Địa chỉ Email của bạn
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
                               placeholder="ten@email.com"
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

                <button type="submit" class="w-full py-3 px-4 rounded-2xl bg-slate-900 hover:bg-blue-600 text-white text-xs sm:text-sm font-bold tracking-wide transition-all shadow-md shadow-slate-950/10 hover:shadow-blue-600/20 active:scale-[0.99] cursor-pointer mt-2">
                    Gửi liên kết khôi phục
                </button>
            </form>

            <!-- Bottom Footnote -->
            <div class="mt-8 pt-6 border-t border-slate-100 text-center">
                <a href="{{ route('login') }}" class="text-xs sm:text-sm font-semibold text-slate-600 hover:text-blue-600 transition-colors inline-flex items-center gap-1.5">
                    <i class="fa-solid fa-arrow-left text-xs"></i>
                    <span>Quay lại đăng nhập</span>
                </a>
            </div>

        </div>

    </div>
</div>
@endsection
