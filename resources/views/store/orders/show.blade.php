@extends('layouts.client')

@section('title', 'Đơn hàng '.$order->order_code)

@section('content')
    <div class="page-heading">
        <div>
            <p class="eyebrow">ĐẶT HÀNG THÀNH CÔNG</p>
            <h1>Đơn hàng {{ $order->order_code }}</h1>
        </div>
        <a class="text-link" href="{{ route('orders.track.form') }}">Tra cứu đơn hàng</a>
    </div>

    <section class="card order-status-card">
        <div class="status-heading">
            <div>
                <span class="status-pill status-{{ $order->status }}">{{ str($order->status)->replace('_', ' ')->title() }}</span>
                <p>Đặt lúc {{ $order->created_at->format('d/m/Y H:i') }}</p>
            </div>
            <strong>{{ number_format($order->total_amount, 0, ',', '.') }} ₫</strong>
        </div>
        <ol class="timeline">
            @foreach ($order->statusHistories as $history)
                <li class="timeline-item">
                    <span class="timeline-dot"></span>
                    <div>
                        <strong>{{ str($history->status)->replace('_', ' ')->title() }}</strong>
                        <p>{{ $history->description }}</p>
                        <time>{{ $history->created_at->format('d/m/Y H:i') }}</time>
                    </div>
                </li>
            @endforeach
        </ol>
    </section>

    <section class="card order-detail-card">
        <h2>Sản phẩm đã đặt</h2>
        @foreach ($order->items as $item)
            <div class="summary-product">
                <span>{{ $item->product_name }} {{ $item->variant_info ? ' - '.$item->variant_info : '' }} × {{ $item->quantity }}</span>
                <strong>{{ number_format($item->total_price, 0, ',', '.') }} ₫</strong>
            </div>
        @endforeach
        <div class="summary-line"><span>Người nhận</span><span>{{ $order->receiver_name }} · {{ $order->receiver_phone }}</span></div>
        <div class="summary-line"><span>Địa chỉ</span><span>{{ $order->shipping_address }}</span></div>
        <div class="summary-line"><span>Đơn vị vận chuyển</span><span>{{ $order->shipping_method }} · {{ number_format($order->shipping_fee, 0, ',', '.') }} ₫</span></div>
        <div class="summary-line"><span>Thanh toán</span><span>{{ strtoupper($order->payment_method) }} · {{ $order->payment_status === 'paid' ? 'Đã thanh toán' : 'Chưa thanh toán' }}</span></div>
        @if ($order->payment_method === 'vnpay' && $order->payment_status !== 'paid' && $order->status === 'pending')
            <form action="{{ route('orders.payment.retry', $order->order_code) }}" method="POST">
                @csrf
                <button class="button button-primary" type="submit">Thử thanh toán VNPay lại</button>
            </form>
        @endif
    </section>
@endsection
