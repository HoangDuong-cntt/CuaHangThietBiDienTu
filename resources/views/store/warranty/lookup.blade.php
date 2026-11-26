@extends('layouts.client')

@section('title', 'Tra cứu bảo hành - tinh.')

@section('content')
<div class="max-w-xl mx-auto px-4 py-12">
    <section class="tracking-card card">
        <p class="eyebrow">HỖ TRỢ SẢN PHẨM</p>
        <h1>Tra cứu bảo hành</h1>
        <p>Nhập mã đơn hàng và số điện thoại người nhận. Không cần đăng nhập.</p>
        @if ($errors->any())
            <div class="form-errors" role="alert">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif
        <form class="tracking-form" action="{{ route('warranty.lookup') }}" method="POST">
            @csrf
            <label class="field-label" for="order_code">Mã đơn hàng</label>
            <input id="order_code" name="order_code" value="{{ old('order_code') }}" placeholder="VD: ORD-2026-..." required>
            <label class="field-label" for="receiver_phone">Số điện thoại nhận hàng</label>
            <input id="receiver_phone" name="receiver_phone" value="{{ old('receiver_phone') }}" inputmode="tel" placeholder="VD: 0912345678" required>
            <button class="button button-primary button-full" type="submit">Tra cứu bảo hành</button>
        </form>
    </section>
</div>
@endsection
