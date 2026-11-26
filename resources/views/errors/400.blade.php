@extends('layouts.client')

@section('title', '400 - Yêu cầu không hợp lệ')

@section('content')
<div class="min-h-[60vh] flex items-center justify-center py-16 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full text-center bg-white rounded-3xl p-8 sm:p-10 border border-slate-200/80 shadow-sm">
        <div class="w-20 h-20 rounded-3xl bg-amber-50 text-amber-500 flex items-center justify-center text-3xl mx-auto mb-6 shadow-xs border border-amber-100">
            <i class="fa-solid fa-shield-halved"></i>
        </div>

        <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-black bg-amber-100 text-amber-800 uppercase tracking-wider mb-3">
            Lỗi 400 • Yêu cầu không hợp lệ
        </div>

        <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight mb-2">
            Ràng Buộc Bảo Mật Dữ Liệu
        </h1>

        <p class="text-xs text-slate-500 leading-relaxed mb-6">
            {{ $exception->getMessage() ?: 'Các tham số truy vấn trên thanh địa chỉ không vượt qua được kiểm tra bảo mật và tính hợp lệ.' }}
        </p>

        <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
            <a href="{{ route('shop.index') }}" class="w-full sm:w-auto px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-xs transition-all flex items-center justify-center gap-2">
                <i class="fa-solid fa-store text-xs"></i>
                <span>Đặt Lại Bộ Lọc</span>
            </a>
            <a href="{{ route('home') }}" class="w-full sm:w-auto px-5 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition-colors flex items-center justify-center gap-2">
                <i class="fa-solid fa-house text-xs text-slate-400"></i>
                <span>Trang Chủ</span>
            </a>
        </div>
    </div>
</div>
@endsection
