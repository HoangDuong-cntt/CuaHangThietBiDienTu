@extends('layouts.client', ['title' => 'Quên mật khẩu - D&D.'])

@section('content')
<div class="min-h-[calc(100vh-14rem)] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 bg-slate-100/60">
    <div class="w-full max-w-md">

        <!-- Card Container -->
        <div class="bg-white rounded-2xl border border-slate-200/90 p-8 sm:p-10 shadow-xl shadow-slate-900/5">
            
            <!-- Brand & Icon -->
            <div class="text-center mb-8">
                <a href="{{ route('home') }}" class="inline-flex items-center gap-1 group mb-4">
                    <span class="text-2xl font-black tracking-tight text-slate-900 group-hover:text-blue-600 transition-colors">D&D<span class="text-blue-600 font-extrabold text-3xl leading-none">.</span></span>
                </a>
                <div class="w-14 h-14 rounded-2xl bg-blue-50 text-blue-600 mx-auto flex items-center justify-center text-2xl mb-3 shadow-2xs">
                    <i class="fa-solid fa-key"></i>
                </div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">
                    Quên mật khẩu?
                </h1>
                <p class="mt-2 text-xs sm:text-sm text-slate-500 leading-relaxed">
                    Nhập email đã đăng ký. Hệ thống sẽ gửi cho bạn liên kết an toàn để đặt lại mật khẩu mới.
                </p>
            </div>

            <!-- Flash Session Alerts -->
            @if(session('success'))
                <div class="mb-6 p-3.5 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs flex items-start gap-2.5">
                    <i class="fa-solid fa-circle-check text-emerald-500 mt-0.5 shrink-0 text-sm"></i>
                    <div>{{ session('success') }}</div>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 p-3.5 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 text-xs flex items-start gap-2.5">
                    <i class="fa-solid fa-circle-exclamation text-rose-500 mt-0.5 shrink-0 text-sm"></i>
                    <div>{{ session('error') }}</div>
                </div>
            @endif

            <!-- Form -->
            <form action="{{ route('password.email') }}" method="POST" class="space-y-4">
                @csrf

                <div class="space-y-1.5">
                    <label for="email" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                        Địa chỉ Email của bạn
                    </label>
                    <input type="email" 
                           id="email" 
                           name="email" 
                           value="{{ old('email') }}" 
                           required 
                           autofocus
                           placeholder="name@email.com"
                           class="w-full px-4 py-3 rounded-xl border text-sm bg-slate-50/50 hover:bg-white focus:bg-white transition-all placeholder:text-slate-400 outline-none font-medium
                                  {{ $errors->has('email') ? 'border-rose-400 focus:border-rose-600 focus:ring-4 focus:ring-rose-500/10' : 'border-slate-200 focus:border-blue-600 focus:ring-4 focus:ring-blue-500/10' }}">
                    @error('email')
                        <p class="text-xs text-rose-600 font-medium mt-1 flex items-center gap-1.5">
                            <i class="fa-solid fa-circle-exclamation text-[11px]"></i>
                            <span>{{ $message }}</span>
                        </p>
                    @enderror
                </div>

                <button type="submit" class="w-full py-3.5 px-6 rounded-xl bg-slate-900 hover:bg-blue-600 text-white text-xs sm:text-sm font-bold uppercase tracking-wider transition-all shadow-md shadow-slate-950/10 hover:shadow-blue-600/20 active:scale-[0.99] cursor-pointer mt-2">
                    Gửi liên kết khôi phục
                </button>
            </form>

            <!-- Bottom Footnote -->
            <div class="mt-8 pt-6 border-t border-slate-100 text-center">
                <a href="{{ route('login') }}" class="text-xs sm:text-sm font-semibold text-slate-600 hover:text-blue-600 transition-colors inline-flex items-center gap-1.5">
                    <i class="fa-solid fa-arrow-left text-xs"></i>
                    <span>Quay lại trang đăng nhập</span>
                </a>
            </div>

        </div>

    </div>
</div>
@endsection
