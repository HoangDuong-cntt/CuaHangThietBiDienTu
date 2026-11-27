@extends('layouts.client')

@section('title', 'D&D. - Thiết bị công nghệ chính hãng')

@section('content')
    <section class="home-hero">
        <div class="home-copy">
            <p class="eyebrow">SMARTTECH E-COMMERCE</p>
            <h1>Công nghệ tốt hơn, trải nghiệm mua sắm dễ dàng hơn.</h1>
            <p>Khám phá thiết bị công nghệ chính hãng, theo dõi đơn hàng và quản lý bảo hành ngay trên một nền tảng.</p>
            <div class="page-actions">
                <a class="button button-primary" href="{{ route('cart.index') }}">Mở giỏ hàng</a>
                <a class="button button-secondary" href="{{ route('orders.track.form') }}">Tra cứu đơn hàng</a>
            </div>
        </div>
        <div class="home-card">
            <span class="home-card-icon" aria-hidden="true">ST</span>
            <strong>Mua sắm an tâm</strong>
            <span>Đơn hàng · Thanh toán · Bảo hành</span>
        </div>
    </section>

    <section class="home-features">
        <a class="feature-card card" href="{{ route('cart.index') }}">
            <span class="feature-number">01</span>
            <h2>Giỏ hàng</h2>
            <p>Thêm sản phẩm, cập nhật số lượng và xem tạm tính tức thời.</p>
        </a>
        <a class="feature-card card" href="{{ route('orders.track.form') }}">
            <span class="feature-number">02</span>
            <h2>Theo dõi đơn</h2>
            <p>Tra cứu tiến độ bằng mã đơn hàng và số điện thoại.</p>
        </a>
        <a class="feature-card card" href="{{ route('warranty.form') }}">
            <span class="feature-number">03</span>
            <h2>Bảo hành</h2>
            <p>Kiểm tra thời hạn bảo hành không cần đăng nhập.</p>
        </a>
    </section>
@endsection
