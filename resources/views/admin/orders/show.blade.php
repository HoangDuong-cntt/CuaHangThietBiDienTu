@extends('layouts.admin')

@section('title', 'Đơn '.$order->order_code)
@section('page-heading', 'Chi tiết đơn hàng')

@section('admin-navigation')
    <a href="{{ route('admin.orders.index') }}">Đơn hàng</a>
    <a href="{{ route('admin.inventory.low-stock') }}">Cảnh báo tồn kho</a>
@endsection

@section('admin-content')
    @if (session('status'))
        <div class="flash-message" role="status">{{ session('status') }}</div>
    @endif
    @if ($errors->any())
        <div class="form-errors" role="alert">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <div class="page-heading">
        <div>
            <p class="eyebrow">ĐƠN {{ $order->order_code }}</p>
            <h1>{{ $order->user->name }}</h1>
        </div>
        <div class="page-actions">
            <a class="button button-secondary" href="{{ route('admin.orders.invoice', $order->order_code) }}">Tải hóa đơn PDF</a>
            <a class="button button-secondary" href="{{ route('admin.orders.index') }}">Quay lại danh sách</a>
        </div>
    </div>

    <div class="admin-order-grid">
        <section class="card admin-panel">
            <h2>Sản phẩm</h2>
            @foreach ($order->items as $item)
                <div class="summary-product">
                    <span>{{ $item->product_name }} {{ $item->variant_info ? ' - '.$item->variant_info : '' }} × {{ $item->quantity }}</span>
                    <strong>{{ number_format($item->total_price, 0, ',', '.') }} ₫</strong>
                </div>
            @endforeach
            <div class="summary-line"><span>Tạm tính</span><strong>{{ number_format($order->subtotal, 0, ',', '.') }} ₫</strong></div>
            <div class="summary-line"><span>Vận chuyển</span><strong>{{ number_format($order->shipping_fee, 0, ',', '.') }} ₫</strong></div>
            <div class="summary-total"><span>Tổng cộng</span><strong>{{ number_format($order->total_amount, 0, ',', '.') }} ₫</strong></div>
        </section>

        <section class="card admin-panel">
            <h2>Nhận hàng & thanh toán</h2>
            <p><strong>{{ $order->receiver_name }}</strong> · {{ $order->receiver_phone }}</p>
            <p>{{ $order->shipping_address }}</p>
            <p>{{ $order->shipping_method }} · {{ number_format($order->shipping_fee, 0, ',', '.') }} ₫</p>
            <p>{{ strtoupper($order->payment_method) }} · {{ $order->payment_status }}</p>
            @if ($order->note)
                <p>Ghi chú: {{ $order->note }}</p>
            @endif
        </section>
    </div>

    <section class="card admin-panel status-management">
        <div>
            <h2>Trạng thái đơn hàng</h2>
            <span class="status-pill status-{{ $order->status }}">{{ str($order->status)->replace('_', ' ')->title() }}</span>
        </div>
        @if ($nextStatuses !== [])
            <form action="{{ route('admin.orders.status.update', $order->order_code) }}" method="POST" class="status-form">
                @csrf
                @method('PATCH')
                <label class="field-label" for="next-status">Chuyển trạng thái</label>
                <select id="next-status" name="status">
                    @foreach ($nextStatuses as $status)
                        <option value="{{ $status }}">{{ str($status)->replace('_', ' ')->title() }}</option>
                    @endforeach
                </select>
                <label class="field-label" for="cancel_reason">Lý do hủy (nếu hủy đơn)</label>
                <input id="cancel_reason" name="cancel_reason" maxlength="255">
                <button class="button button-primary" type="submit">Cập nhật trạng thái</button>
            </form>
        @endif
        <ol class="timeline">
            @foreach ($order->statusHistories as $history)
                <li class="timeline-item">
                    <span class="timeline-dot"></span>
                    <div>
                        <strong>{{ str($history->status)->replace('_', ' ')->title() }}</strong>
                        <p>{{ $history->description }} {{ $history->changedBy ? ' · '.$history->changedBy->name : '' }}</p>
                        <time>{{ $history->created_at->format('d/m/Y H:i') }}</time>
                    </div>
                </li>
            @endforeach
        </ol>
    </section>
@endsection
