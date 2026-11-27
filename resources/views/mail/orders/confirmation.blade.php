<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <title>Xác nhận đơn hàng {{ $order->order_code }}</title>
</head>
<body style="font-family: Arial, sans-serif; color: #172033; line-height: 1.6;">
    <h1>Đơn hàng đã được tiếp nhận</h1>
    <p>Xin chào {{ $order->receiver_name }},</p>
    <p>D&D. đã nhận đơn hàng <strong>{{ $order->order_code }}</strong>.</p>
    <table style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr>
                <th style="padding: 8px; border-bottom: 1px solid #ddd; text-align: left;">Sản phẩm</th>
                <th style="padding: 8px; border-bottom: 1px solid #ddd;">Số lượng</th>
                <th style="padding: 8px; border-bottom: 1px solid #ddd; text-align: right;">Thành tiền</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($order->items as $item)
                <tr>
                    <td style="padding: 8px; border-bottom: 1px solid #eee;">{{ $item->product_name }} {{ $item->variant_info ? ' - '.$item->variant_info : '' }}</td>
                    <td style="padding: 8px; border-bottom: 1px solid #eee; text-align: center;">{{ $item->quantity }}</td>
                    <td style="padding: 8px; border-bottom: 1px solid #eee; text-align: right;">{{ number_format($item->total_price, 0, ',', '.') }} ₫</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <p><strong>Tổng thanh toán: {{ number_format($order->total_amount, 0, ',', '.') }} ₫</strong></p>
    <p>Địa chỉ nhận hàng: {{ $order->shipping_address }}</p>
    <p>Hóa đơn PDF được đính kèm trong email này.</p>
    <p>Cảm ơn bạn đã mua sắm tại D&D.</p>
</body>
</html>
