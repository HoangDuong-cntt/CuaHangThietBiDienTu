@extends('layouts.client')

@section('title', 'Thông tin bảo hành - tinh.')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">
    <div class="page-heading">
        <div>
            <p class="eyebrow">TRA CỨU BẢO HÀNH</p>
            <h1>Đơn {{ $order->order_code }}</h1>
        </div>
        <a class="text-link" href="{{ route('warranty.form') }}">&larr; Tra cứu đơn khác</a>
    </div>
    <section class="card order-detail-card">
        <p>Thời hạn bảo hành được tính từ ngày giao hàng thành công.</p>
        @foreach ($order->items as $item)
            <div class="warranty-row">
                <div>
                    <strong>{{ $item->product_name }}</strong>
                    @if ($item->variant_info)
                        <p>{{ $item->variant_info }}</p>
                    @endif
                </div>
                <div class="warranty-date">
                    <span>Hết hạn bảo hành</span>
                    <strong>{{ $item->warranty_expires_at?->format('d/m/Y') ?? 'Chưa xác định' }}</strong>
                </div>
            </div>
        @endforeach
    </section>
</div>
@endsection
