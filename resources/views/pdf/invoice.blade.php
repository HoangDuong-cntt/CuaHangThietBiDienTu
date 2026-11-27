<!DOCTYPE html>
<html lang="vi">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Hóa đơn {{ $order->order_code }}</title>
    <style>
        body {
            color: #172033;
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }

        h1 {
            margin-bottom: 4px;
            color: #1747b0;
            font-size: 24px;
        }

        .muted {
            color: #68758d;
        }

        .details {
            margin: 24px 0;
        }

        .details td {
            padding: 5px 16px 5px 0;
            vertical-align: top;
        }

        table.items {
            width: 100%;
            border-collapse: collapse;
        }

        .items th,
        .items td {
            padding: 10px 8px;
            border-bottom: 1px solid #dce2ed;
            text-align: left;
        }

        .items th:last-child,
        .items td:last-child {
            text-align: right;
        }

        .totals {
            width: 45%;
            margin: 16px 0 0 auto;
        }

        .totals td {
            padding: 6px;
            text-align: right;
        }

        .grand-total {
            color: #1747b0;
            font-size: 15px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <h1>HÓA ĐƠN BÁN HÀNG</h1>
    <p class="muted">SmartTech · Thiết bị công nghệ chính hãng</p>

    <table class="details">
        <tr><td><strong>Mã đơn hàng</strong></td><td>{{ $order->order_code }}</td></tr>
        <tr><td><strong>Ngày lập</strong></td><td>{{ $order->created_at->format('d/m/Y H:i') }}</td></tr>
        <tr><td><strong>Khách hàng</strong></td><td>{{ $order->receiver_name }}</td></tr>
        <tr><td><strong>Số điện thoại</strong></td><td>{{ $order->receiver_phone }}</td></tr>
        <tr><td><strong>Địa chỉ nhận</strong></td><td>{{ $order->shipping_address }}</td></tr>
        <tr><td><strong>Đơn vị vận chuyển</strong></td><td>{{ $order->shipping_method }}</td></tr>
        <tr><td><strong>Phương thức</strong></td><td>{{ strtoupper($order->payment_method) }}</td></tr>
    </table>

    <table class="items">
        <thead>
            <tr>
                <th>Sản phẩm</th>
                <th>Số lượng</th>
                <th>Đơn giá</th>
                <th>Thành tiền</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($order->items as $item)
                <tr>
                    <td>{{ $item->product_name }} {{ $item->variant_info ? ' - '.$item->variant_info : '' }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ number_format($item->unit_price, 0, ',', '.') }} ₫</td>
                    <td>{{ number_format($item->total_price, 0, ',', '.') }} ₫</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table class="totals">
        <tr><td>Tạm tính</td><td>{{ number_format($order->subtotal, 0, ',', '.') }} ₫</td></tr>
        <tr><td>Giảm giá</td><td>-{{ number_format($order->discount_amount, 0, ',', '.') }} ₫</td></tr>
        <tr><td>Phí vận chuyển</td><td>{{ number_format($order->shipping_fee, 0, ',', '.') }} ₫</td></tr>
        <tr class="grand-total"><td>Tổng thanh toán</td><td>{{ number_format($order->total_amount, 0, ',', '.') }} ₫</td></tr>
    </table>
</body>
</html>
