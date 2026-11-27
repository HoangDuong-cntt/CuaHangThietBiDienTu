@extends('layouts.client')

@section('title', 'Tra cứu đơn hàng - D&D.')

@section('content')
<div class="max-w-xl mx-auto px-4 py-12">
    <section class="tracking-card card">
        <p class="eyebrow">HỖ TRỢ ĐƠN HÀNG</p>
        <h1>Tra cứu tiến độ đơn hàng</h1>
        <p>Nhập mã đơn hàng và số điện thoại người nhận để xem trạng thái vận chuyển.</p>
        @if ($errors->any())
            <div class="form-errors" role="alert">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif
        <form class="tracking-form" action="{{ route('orders.track') }}" method="POST">
            @csrf
            <label class="field-label" for="order_code">Mã đơn hàng</label>
            <input id="order_code" name="order_code" value="{{ old('order_code') }}" placeholder="VD: ORD-2026-..." required>
            <label class="field-label" for="receiver_phone">Số điện thoại nhận hàng</label>
            <input id="receiver_phone" name="receiver_phone" value="{{ old('receiver_phone') }}" inputmode="tel" placeholder="VD: 0912345678" required>
            <button class="button button-primary button-full" type="submit">Tra cứu ngay</button>
        </form>
    </section>
</div>
@endsection
