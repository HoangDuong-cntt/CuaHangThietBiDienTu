@extends('layouts.admin')

@section('title', 'Cảnh báo tồn kho')
@section('page-heading', 'Cảnh báo tồn kho')

@section('admin-navigation')
    <a href="{{ route('admin.orders.index') }}">Đơn hàng</a>
    <a href="{{ route('admin.inventory.low-stock') }}">Cảnh báo tồn kho</a>
@endsection

@section('admin-content')
    <div class="page-heading">
        <div>
            <p class="eyebrow">VẬN HÀNH KHO</p>
            <h1>Sắp hết hàng</h1>
        </div>
    </div>
    <div class="table-wrap card">
        <table class="data-table">
            <thead>
                <tr><th>Sản phẩm</th><th>SKU</th><th>Tồn kho</th><th>Ngưỡng cảnh báo</th></tr>
            </thead>
            <tbody>
                @forelse ($variants as $variant)
                    <tr>
                        <td>{{ $variant->product->name }} {{ $variant->color_name ? '· '.$variant->color_name : '' }}</td>
                        <td>{{ $variant->sku }}</td>
                        <td><strong class="stock-critical">{{ $variant->stock_quantity }}</strong></td>
                        <td>{{ $variant->low_stock_threshold }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="table-empty">Không có biến thể nào dưới ngưỡng tồn kho.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="pagination-wrap">{{ $variants->links() }}</div>
@endsection
