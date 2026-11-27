@extends('layouts.client')

@section('title', 'Thanh toán - D&D.')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="page-heading">
        <div>
            <p class="eyebrow">HOÀN TẤT ĐẶT HÀNG</p>
            <h1>Thông tin thanh toán</h1>
        </div>
        <a class="text-link" href="{{ route('cart.index') }}">&larr; Quay lại giỏ hàng</a>
    </div>

    @if ($errors->any())
        <div class="form-errors" role="alert">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    @if (session('status'))
        <p class="flash-message" role="status">{{ session('status') }}</p>
    @endif

    @if (session('coupon_warning'))
        <p class="form-errors" role="alert">{{ session('coupon_warning') }}</p>
    @endif

    <div class="checkout-layout">
        <form class="card checkout-form" action="{{ route('checkout.store') }}" method="POST">
            @csrf
            <section class="form-section">
                <h2>Địa chỉ nhận hàng</h2>
                @if ($addresses->isNotEmpty())
                    <label class="field-label" for="address_id">Chọn từ sổ địa chỉ</label>
                    <select id="address_id" name="address_id">
                        <option value="">Nhập địa chỉ khác</option>
                        @foreach ($addresses as $address)
                            <option value="{{ $address->id }}" @selected(old('address_id', $address->is_default ? $address->id : null) == $address->id)>
                                {{ $address->receiver_name }} - {{ $address->receiver_phone }}: {{ $address->street_address }}, {{ $address->ward }}, {{ $address->district }}, {{ $address->province }}
                            </option>
                        @endforeach
                    </select>
                @endif

                <div class="address-fields" data-address-fields>
                    <label class="field-label" for="receiver_name">Người nhận</label>
                    <input id="receiver_name" name="receiver_name" value="{{ old('receiver_name', auth()->user()->name) }}" autocomplete="name">
                    <label class="field-label" for="receiver_phone">Số điện thoại</label>
                    <input id="receiver_phone" name="receiver_phone" value="{{ old('receiver_phone', auth()->user()->phone) }}" autocomplete="tel">
                    <label class="field-label" for="street_address">Địa chỉ</label>
                    <input id="street_address" name="street_address" value="{{ old('street_address') }}" autocomplete="street-address">
                    <div class="field-grid">
                        <div>
                            <label class="field-label" for="ward">Phường / xã</label>
                            <input id="ward" name="ward" value="{{ old('ward') }}">
                        </div>
                        <div>
                            <label class="field-label" for="district">Quận / huyện</label>
                            <input id="district" name="district" value="{{ old('district') }}">
                        </div>
                    </div>
                    <label class="field-label" for="province">Tỉnh / thành phố</label>
                    <input id="province" name="province" value="{{ old('province') }}">
                </div>
            </section>

            <section class="form-section">
                <h2>Đơn vị vận chuyển</h2>
                <label class="field-label" for="shipping_method">Chọn dịch vụ giao hàng</label>
                <select id="shipping_method" name="shipping_method" data-shipping-method required>
                    @foreach ($shippingMethods as $methodKey => $method)
                        <option
                            value="{{ $methodKey }}"
                            data-label="{{ $method['label'] }}"
                            data-fee="{{ $method['fee'] }}"
                            @selected($selectedShippingMethod === $methodKey)
                        >
                            {{ $method['label'] }} · {{ number_format($method['fee'], 0, ',', '.') }} ₫
                        </option>
                    @endforeach
                </select>
            </section>

            <section class="form-section">
                <h2>Phương thức thanh toán</h2>
                <label class="payment-option">
                    <input type="radio" name="payment_method" value="cod" checked>
                    <span><strong>Thanh toán khi nhận hàng (COD)</strong><small>Thanh toán trực tiếp cho đơn vị vận chuyển.</small></span>
                </label>
                <label class="payment-option">
                    <input type="radio" name="payment_method" value="vnpay" @disabled(! $vnpayEnabled)>
                    <span>
                        <strong>Thanh toán trực tuyến VNPay</strong>
                        <small>{{ $vnpayEnabled ? 'Thanh toán qua VNPay Sandbox.' : 'Cần cấu hình VNPAY_TMN_CODE và VNPAY_HASH_SECRET.' }}</small>
                    </span>
                </label>
            </section>

            <section class="form-section">
                <label class="field-label" for="note">Ghi chú đơn hàng</label>
                <textarea id="note" name="note" rows="3">{{ old('note') }}</textarea>
            </section>

            <button class="button button-primary button-full" type="submit">Đặt hàng</button>
        </form>

        <aside class="order-summary card">
            <h2>Đơn hàng</h2>
            @foreach ($items as $item)
                <div class="summary-product">
                    <span>{{ $item['variant']->product->name }} × {{ $item['quantity'] }}</span>
                    <strong>{{ number_format($item['lineTotal'], 0, ',', '.') }} ₫</strong>
                </div>
            @endforeach
            <div class="summary-line"><span>Tạm tính</span><strong>{{ number_format($subtotal, 0, ',', '.') }} ₫</strong></div>
            <div class="summary-line">
                <span>Giao hàng · <span data-shipping-method-label>{{ $shippingMethods[$selectedShippingMethod]['label'] }}</span></span>
                <strong data-shipping-fee>{{ number_format($shippingFee, 0, ',', '.') }} ₫</strong>
            </div>
            @if ($coupon)
                <div class="summary-line">
                    <span>Giảm giá · {{ $coupon->code }}</span>
                    <strong>-{{ number_format($coupon->discountAmount, 0, ',', '.') }} ₫</strong>
                </div>
            @endif
            <form class="coupon-form" action="{{ route('checkout.coupon.apply') }}" method="POST">
                @csrf
                <label class="field-label" for="coupon_code">Mã voucher ưu đãi</label>
                <div class="coupon-form-row">
                    <input
                        id="coupon_code"
                        name="coupon_code"
                        value="{{ old('coupon_code', $coupon?->code ?? '') }}"
                        autocomplete="off"
                        placeholder="Nhập mã voucher"
                        required
                    >
                    <button class="button button-secondary" type="submit">Áp dụng</button>
                </div>
                @if ($coupon)
                    <p class="coupon-applied" role="status">{{ $coupon->message }}</p>
                @endif
            </form>
            <div class="summary-total">
                <span>Tổng thanh toán</span>
                <strong
                    data-checkout-total
                    data-subtotal="{{ (int) round($subtotal) }}"
                    data-discount="{{ (int) round($discountAmount) }}"
                >
                    {{ number_format($subtotal - $discountAmount + $shippingFee, 0, ',', '.') }} ₫
                </strong>
            </div>
        </aside>
    </div>
</div>
@endsection
