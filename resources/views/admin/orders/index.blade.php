@extends('layouts.admin')

@section('title', 'Quản lý đơn hàng')
@section('page-heading', 'Quản lý đơn hàng')

@section('admin-navigation')
    <a href="{{ route('admin.orders.index') }}">Đơn hàng</a>
    <a href="{{ route('admin.inventory.low-stock') }}">Cảnh báo tồn kho</a>
@endsection

@section('admin-content')
    <div class="page-heading">
        <div>
            <p class="eyebrow">VẬN HÀNH</p>
            <h1>Đơn hàng</h1>
        </div>
        <form action="{{ route('admin.orders.index') }}" method="GET" class="filter-form">
            <label class="sr-only" for="status">Lọc trạng thái</label>
            <select id="status" name="status" onchange="this.form.submit()">
                <option value="">Tất cả trạng thái</option>
                @foreach (['pending', 'confirmed', 'shipping', 'delivered', 'completed', 'cancelled'] as $status)
                    <option value="{{ $status }}" @selected(request('status') === $status)>{{ str($status)->replace('_', ' ')->title() }}</option>
                @endforeach
            </select>
        </form>
    </div>
    <div class="table-wrap card">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Mã đơn</th>
                    <th>Khách hàng</th>
                    <th>Ngày đặt</th>
                    <th>Thanh toán</th>
                    <th>Trạng thái</th>
                    <th>Tổng tiền</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($orders as $order)
                    <tr>
                        <td><strong>{{ $order->order_code }}</strong></td>
                        <td>{{ $order->user->name }}<small>{{ $order->receiver_phone }}</small></td>
                        <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                        <td>{{ strtoupper($order->payment_method) }} / {{ $order->payment_status }}</td>
                        <td><span class="status-pill status-{{ $order->status }}">{{ str($order->status)->replace('_', ' ')->title() }}</span></td>
                        <td>{{ number_format($order->total_amount, 0, ',', '.') }} ₫</td>
                        <td><a class="text-link" href="{{ route('admin.orders.show', $order->order_code) }}">Chi tiết</a></td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="table-empty">Chưa có đơn hàng.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="pagination-wrap">{{ $orders->links() }}</div>
@endsection
