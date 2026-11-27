@extends('layouts.client', ['title' => 'Đặt lại mật khẩu mới - D&D.'])

@section('content')
<div class="min-h-[calc(100vh-16rem)] flex items-center justify-center py-10 px-4 sm:px-6">
    <div class="w-full max-w-[420px]">

        <!-- Compact Card -->
        <div class="bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-8 shadow-sm">
            
            <!-- Brand & Header -->
            <div class="text-center mb-6">
                <a href="{{ route('home') }}" class="inline-flex items-center gap-1 group mb-2">
                    <span class="text-2xl font-black tracking-tight text-slate-900 group-hover:text-blue-600 transition-colors">D&D<span class="text-blue-600 font-extrabold text-3xl leading-none">.</span></span>
                </a>
                <h1 class="text-xl font-bold tracking-tight text-slate-900">
                    Đặt lại mật khẩu
                </h1>
                <p class="mt-1 text-xs text-slate-500 leading-relaxed">
                    Nhập mật khẩu mới an toàn cho tài khoản D&D của bạn (tối thiểu 8 ký tự).
                </p>
            </div>

            <!-- Form -->
            <form action="{{ route('password.update') }}" method="POST" class="space-y-3.5">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">

                <!-- Email (Readonly) -->
                <div>
                    <label for="email" class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1">
                        Tài khoản Email
                    </label>
                    <input type="email" 
                           id="email" 
                           name="email" 
                           value="{{ $email ?? old('email') }}" 
                           required 
                           readonly
                           class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-slate-500 text-xs sm:text-sm outline-none cursor-not-allowed">
                    @error('email')
                        <p class="text-[11px] text-rose-600 font-medium mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password (Native type="password" - ALWAYS MASKED) -->
                <div>
                    <label for="password" class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1">
                        Mật khẩu mới
                    </label>
                    <div class="relative">
                        <input type="password" 
                               id="password" 
                               name="password" 
                               required 
                               autofocus
                               placeholder="Tối thiểu 8 ký tự"
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

                <!-- Password Confirmation -->
                <div>
                    <label for="password_confirmation" class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1">
                        Nhập lại mật khẩu mới
                    </label>
                    <div class="relative">
                        <input type="password" 
                               id="password_confirmation" 
                               name="password_confirmation" 
                               required 
                               placeholder="Khớp với mật khẩu trên"
                               class="w-full pl-3.5 pr-10 py-2.5 rounded-xl border border-slate-200 focus:border-blue-600 focus:ring-2 focus:ring-blue-500/10 text-xs sm:text-sm bg-white hover:border-slate-300 outline-none transition-all placeholder:text-slate-400">
                        <button type="button" 
                                onclick="togglePasswordVisibility('password_confirmation', this)" 
                                class="absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400 hover:text-slate-600 transition-colors cursor-pointer"
                                title="Ẩn/Hiện mật khẩu">
                            <i class="fa-regular fa-eye text-xs"></i>
                        </button>
                    </div>
                </div>

                <button type="submit" class="w-full py-3 px-5 rounded-full bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold tracking-wide transition-all shadow-md shadow-blue-600/20 active:scale-[0.99] cursor-pointer mt-1">
                    Cập nhật mật khẩu mới
                </button>
            </form>

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
