@extends('layouts.client', ['title' => 'Quên mật khẩu - D&D.'])

@section('content')
<div class="min-h-[calc(100vh-16rem)] flex items-center justify-center py-10 px-4 sm:px-6">
    <div class="w-full max-w-[420px]">

        <!-- Compact Card -->
        <div class="bg-white rounded-2xl border border-slate-200/90 p-6 sm:p-8 shadow-sm">
            
            <!-- Brand & Icon -->
            <div class="text-center mb-6">
                <a href="{{ route('home') }}" class="inline-flex items-center gap-1 group mb-2">
                    <span class="text-2xl font-black tracking-tight text-slate-900 group-hover:text-blue-600 transition-colors">D&D<span class="text-blue-600 font-extrabold text-3xl leading-none">.</span></span>
                </a>
                <h1 class="text-xl font-bold tracking-tight text-slate-900">
                    Quên mật khẩu?
                </h1>
                <p class="text-xs text-slate-500 mt-1 leading-relaxed">
                    Nhập email đã đăng ký để nhận liên kết khôi phục mật khẩu.
                </p>
            </div>

            <!-- Flash Session Alerts -->
            @if(session('success'))
                <div class="mb-4 p-3 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs flex items-start gap-2">
                    <i class="fa-solid fa-circle-check text-emerald-500 mt-0.5 shrink-0"></i>
                    <div>{{ session('success') }}</div>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 p-3 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 text-xs flex items-start gap-2">
                    <i class="fa-solid fa-circle-exclamation text-rose-500 mt-0.5 shrink-0"></i>
                    <div>{{ session('error') }}</div>
                </div>
            @endif

            <!-- Form -->
            <form action="{{ route('password.email') }}" method="POST" class="space-y-3.5">
                @csrf

                <div>
                    <label for="email" class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1">
                        Địa chỉ Email của bạn
                    </label>
                    <input type="email" 
                           id="email" 
                           name="email" 
                           value="{{ old('email') }}" 
                           required 
                           autofocus
                           placeholder="name@email.com"
                           class="w-full px-3.5 py-2.5 rounded-xl border text-xs sm:text-sm bg-white hover:border-slate-300 focus:border-blue-600 focus:ring-2 focus:ring-blue-500/10 outline-none transition-all placeholder:text-slate-400
                                  {{ $errors->has('email') ? 'border-rose-400 focus:border-rose-600 focus:ring-2 focus:ring-rose-500/10' : 'border-slate-200' }}">
                    @error('email')
                        <p class="text-[11px] text-rose-600 font-medium mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="w-full py-2.5 px-4 rounded-xl bg-slate-900 hover:bg-blue-600 text-white text-xs font-bold uppercase tracking-wider transition-all shadow-sm active:scale-[0.99] cursor-pointer mt-1">
                    Gửi liên kết khôi phục
                </button>
            </form>

            <!-- Bottom Footnote -->
            <div class="mt-6 pt-4 border-t border-slate-100 text-center">
                <a href="{{ route('login') }}" class="text-xs font-semibold text-slate-600 hover:text-blue-600 transition-colors inline-flex items-center gap-1.5">
                    <i class="fa-solid fa-arrow-left text-xs"></i>
                    <span>Quay lại trang đăng nhập</span>
                </a>
            </div>

        </div>

    </div>
</div>
@endsection
