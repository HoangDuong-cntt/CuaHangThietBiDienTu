@extends('layouts.client')

@section('title', 'Giỏ hàng - D.D.')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="page-heading">
        <div>
            <p class="eyebrow">MUA SẮM</p>
            <h1>Giỏ hàng của bạn</h1>
        </div>
        <a class="text-link" href="{{ route('shop.index') }}">&larr; Tiếp tục mua sắm</a>
    </div>

    @if ($errors->any())
        <div class="form-errors" role="alert">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    @if ($items->isEmpty())
        <section class="empty-state card">
            <span class="empty-icon" aria-hidden="true">🛒</span>
            <h2>Giỏ hàng đang trống</h2>
            <p>Thêm sản phẩm bạn yêu thích để tiếp tục đặt hàng.</p>
            <a class="button button-primary" href="{{ route('shop.index') }}">Khám phá sản phẩm</a>
        </section>
    @else
        <div class="cart-layout">
            <section class="cart-list card" aria-label="Sản phẩm trong giỏ">
                @foreach ($items as $item)
                    @php
                        $variant = $item['variant'];
                        $unitPrice = (float) $variant->currentPrice();
                    @endphp
                    <article
                        class="cart-row"
                        data-cart-row
                        data-variant-id="{{ $variant->id }}"
                        data-quantity="{{ $item['quantity'] }}"
                    >
                        @if($variant->product->images->first()?->image_url)
                            <div class="w-16 h-16 rounded-xl bg-[#f8fafc] border border-slate-200/80 p-1 flex items-center justify-center shrink-0 overflow-hidden">
                                <img src="{{ $variant->product->images->first()->image_url }}" alt="{{ $variant->product->name }}" class="w-full h-full object-contain">
                            </div>
                        @else
                            <div class="product-placeholder" aria-hidden="true">{{ mb_substr($variant->product->name, 0, 1) }}</div>
                        @endif
                        <div class="cart-product">
                            <h2>
                                <a href="{{ route('products.show', $variant->product->slug) }}" class="hover:text-blue-600 transition-colors">
                                    {{ $variant->product->name }}
                                </a>
                            </h2>
                            <p>{{ collect([$variant->color_name, $variant->ram, $variant->storage])->filter()->join(' / ') ?: $variant->sku }}</p>
                            <strong>{{ number_format($unitPrice, 0, ',', '.') }} ₫</strong>
                            @if ($item['quantity'] > $variant->stock_quantity)
                                <span class="stock-warning">Chỉ còn {{ $variant->stock_quantity }} sản phẩm trong kho.</span>
                            @endif
                        </div>
                        <form class="cart-quantity-form" action="{{ route('cart.items.update', $variant->id) }}" method="POST" data-ajax-cart data-cart-action="update">
                            @csrf
                            @method('PATCH')
                            <label class="sr-only" for="quantity-{{ $variant->id }}">Số lượng</label>
                            <input id="quantity-{{ $variant->id }}" name="quantity" type="number" min="1" max="99" value="{{ $item['quantity'] }}">
                            <button class="button button-secondary" type="submit">Cập nhật</button>
                        </form>
                        <div class="cart-row-total">
                            <strong class="cart-line-total">{{ number_format($item['lineTotal'], 0, ',', '.') }} ₫</strong>
                            <form action="{{ route('cart.items.destroy', $variant->id) }}" method="POST" data-ajax-cart data-cart-action="remove">
                                @csrf
                                @method('DELETE')
                                <button class="button-link" type="submit">Xóa</button>
                            </form>
                        </div>
                    </article>
                @endforeach
                <div class="ajax-message" data-cart-message role="status" hidden></div>
            </section>

            <aside class="order-summary card">
                <h2>Tóm tắt đơn hàng</h2>
                <div class="summary-line"><span>Sản phẩm</span><span data-cart-count-total>{{ $items->sum('quantity') }}</span></div>
                <div class="summary-line"><span>Tạm tính</span><strong data-cart-subtotal>{{ number_format($subtotal, 0, ',', '.') }} ₫</strong></div>
                <div class="summary-line"><span>Phí vận chuyển</span><span>Tính ở bước thanh toán</span></div>
                <div class="summary-total"><span>Tổng tạm tính</span><strong data-cart-total>{{ number_format($subtotal, 0, ',', '.') }} ₫</strong></div>
                @auth
                    <a class="button button-primary button-full" href="{{ route('checkout.create') }}">Tiến hành đặt hàng</a>
                @else
                    @if (Route::has('login'))
                        <a class="button button-primary button-full" href="{{ route('login') }}">Đăng nhập để thanh toán</a>
                    @else
                        <p class="muted-text">Đăng nhập sẽ được bật khi hoàn tất phân hệ xác thực.</p>
                    @endif
                @endauth
                <p class="secure-note">Thông tin đơn hàng được bảo vệ an toàn.</p>
            </aside>
        </div>
    @endif
</div>
@endsection
