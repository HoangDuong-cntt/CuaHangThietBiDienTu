<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <title>{{ $statusLabel }} đơn hàng {{ $order->order_code }}</title>
</head>
<body style="font-family: Arial, sans-serif; color: #172033; line-height: 1.6;">
    <h1>{{ $statusLabel }} đơn hàng</h1>
    <p>Xin chào {{ $order->receiver_name }},</p>
    <p>Đơn hàng <strong>{{ $order->order_code }}</strong> của bạn đã được cập nhật: <strong>{{ $statusLabel }}</strong>.</p>

    @if ($order->status === 'cancelled')
        <p>Lý do hủy: {{ $order->cancel_reason }}</p>
    @endif

    <p><strong>Tổng giá trị đơn hàng: {{ number_format($order->total_amount, 0, ',', '.') }} ₫</strong></p>
    <p>Địa chỉ nhận hàng: {{ $order->shipping_address }}</p>
    <p>Nếu cần hỗ trợ, vui lòng liên hệ cửa hàng.</p>
</body>
</html>
