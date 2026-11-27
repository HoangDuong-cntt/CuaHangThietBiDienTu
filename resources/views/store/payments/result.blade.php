@extends('layouts.client')

@section('title', 'Kết quả thanh toán')

@section('content')
    <section class="tracking-card card">
        <p class="eyebrow">KẾT QUẢ THANH TOÁN</p>
        <h1>{{ $isPaid ? 'Thanh toán thành công' : 'Chưa ghi nhận thanh toán thành công' }}</h1>
        <p>{{ $message }}</p>
        @if ($order)
            <div class="summary-line">
                <span>Mã đơn hàng</span>
                <strong>{{ $order->order_code }}</strong>
            </div>
            <div class="summary-line">
                <span>Trạng thái thanh toán</span>
                <strong>{{ $order->payment_status === 'paid' ? 'Đã thanh toán' : 'Chưa thanh toán' }}</strong>
            </div>
            @auth
                @if (auth()->id() === $order->user_id)
                    <a class="button button-primary button-full" href="{{ route('orders.show', $order->order_code) }}">Xem đơn hàng</a>
                @endif
            @endauth
        @endif
        <a class="text-link" href="{{ route('orders.track.form') }}">Tra cứu đơn hàng</a>
    </section>
@endsection
