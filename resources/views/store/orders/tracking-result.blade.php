@extends('layouts.client')

@section('title', 'Theo dõi '.$order->order_code.' - D&D.')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">
    <div class="page-heading">
        <div>
            <p class="eyebrow">TRA CỨU ĐƠN HÀNG</p>
            <h1>{{ $order->order_code }}</h1>
        </div>
        <a class="text-link" href="{{ route('orders.track.form') }}">&larr; Tra cứu đơn khác</a>
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
        <h2>Sản phẩm</h2>
        @foreach ($order->items as $item)
            <div class="summary-product">
                <span>{{ $item->product_name }} {{ $item->variant_info ? ' - '.$item->variant_info : '' }} × {{ $item->quantity }}</span>
                <strong>{{ number_format($item->total_price, 0, ',', '.') }} ₫</strong>
            </div>
        @endforeach
    </section>
</div>
@endsection
