@extends('layouts.app')
@section('title', 'Order Successful - BazaarHub')

@push('styles')
<style>
    .success-wrapper {
        background: linear-gradient(180deg, #fff8f4 0%, #ffffff 100%);
        min-height: 100vh;
    }

    .success-card,
    .info-card,
    .items-card {
        background: #fff;
        border-radius: 22px;
        border: none;
        box-shadow: 0 10px 35px rgba(0, 0, 0, 0.07);
        overflow: hidden;
    }

    .card-header-gradient {
        background: linear-gradient(135deg, #FF6B35, #FF8C42);
        color: #fff;
        padding: 18px 24px;
    }

    .card-header-gradient h5 {
        margin: 0;
        font-weight: 700;
        font-size: 16px;
    }

    .success-hero {
        text-align: center;
        padding: 40px 30px;
        position: relative;
        overflow: hidden;
    }

    .success-hero::before {
        content: '';
        position: absolute;
        width: 220px;
        height: 220px;
        background: rgba(255, 107, 53, 0.08);
        border-radius: 50%;
        top: -70px;
        left: -50px;
    }

    .success-hero::after {
        content: '';
        position: absolute;
        width: 180px;
        height: 180px;
        background: rgba(39, 174, 96, 0.08);
        border-radius: 50%;
        bottom: -70px;
        right: -40px;
    }

    .success-icon {
        width: 92px;
        height: 92px;
        margin: 0 auto 20px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 42px;
        color: #fff;
        background: linear-gradient(135deg, #27ae60, #2ecc71);
        box-shadow: 0 12px 30px rgba(39, 174, 96, 0.35);
        position: relative;
        z-index: 1;
    }

    .success-title {
        font-size: 32px;
        font-weight: 800;
        color: #2C3E50;
        margin-bottom: 10px;
        position: relative;
        z-index: 1;
    }

    .success-subtitle {
        color: #7f8c8d;
        font-size: 15px;
        margin-bottom: 22px;
        position: relative;
        z-index: 1;
        line-height: 1.7;
    }

    .order-badge {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        background: #fff3ee;
        color: #FF6B35;
        border: 1px solid #ffd7c8;
        border-radius: 999px;
        padding: 10px 18px;
        font-weight: 700;
        font-size: 14px;
        position: relative;
        z-index: 1;
        flex-wrap: wrap;
        justify-content: center;
    }

    .status-pill {
        display: inline-block;
        margin-top: 14px;
        padding: 8px 16px;
        border-radius: 999px;
        background: #edf9f1;
        color: #27ae60;
        font-weight: 700;
        font-size: 13px;
        position: relative;
        z-index: 1;
    }

    .detail-list {
        padding: 22px 24px;
    }

    .detail-row {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 15px;
        padding: 12px 0;
        border-bottom: 1px solid #f2f2f2;
        font-size: 14px;
    }

    .detail-row:last-child {
        border-bottom: none;
    }

    .detail-label {
        color: #888;
        font-weight: 500;
    }

    .detail-value {
        color: #2C3E50;
        font-weight: 700;
        text-align: right;
    }

    .amount-highlight {
        color: #FF6B35;
        font-size: 22px;
        font-weight: 800;
    }

    .ordered-item {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 16px 0;
        border-bottom: 1px solid #f4f4f4;
    }

    .ordered-item:last-child {
        border-bottom: none;
    }

    .ordered-item img {
        width: 64px;
        height: 64px;
        object-fit: cover;
        border-radius: 14px;
        flex-shrink: 0;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }

    .ordered-item-name {
        font-weight: 700;
        color: #2C3E50;
        font-size: 14px;
        margin-bottom: 4px;
    }

    .ordered-item-meta {
        font-size: 13px;
        color: #888;
    }

    .ordered-item-price {
        margin-left: auto;
        font-weight: 800;
        color: #FF6B35;
        font-size: 15px;
        white-space: nowrap;
    }

    .action-buttons .btn {
        border-radius: 14px;
        padding: 13px 20px;
        font-weight: 700;
    }

    .btn-primary-success {
        background: linear-gradient(135deg, #FF6B35, #FF8C42);
        color: #fff;
        border: none;
        box-shadow: 0 10px 25px rgba(255, 107, 53, 0.3);
    }

    .btn-primary-success:hover {
        color: #fff;
        transform: translateY(-2px);
    }

    .btn-outline-soft {
        background: #fff;
        color: #2C3E50;
        border: 1.5px solid #e5e7eb;
    }

    .btn-outline-soft:hover {
        border-color: #FF6B35;
        color: #FF6B35;
        background: #fff8f4;
    }

    .secure-note {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 14px 16px;
        border-radius: 14px;
        background: #edf9f1;
        color: #27ae60;
        font-size: 13px;
        font-weight: 600;
    }

    .steps-bar {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0;
        margin-bottom: 32px;
    }

    .step {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 6px;
        flex: 1;
        position: relative;
    }

    .step::after {
        content: '';
        position: absolute;
        top: 18px;
        left: 60%;
        width: 80%;
        height: 2px;
        background: #e8e8e8;
        z-index: 0;
    }

    .step:last-child::after {
        display: none;
    }

    .step-circle {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 14px;
        z-index: 1;
        background: #f0f0f0;
        color: #bbb;
    }

    .step.done .step-circle {
        background: #27ae60;
        color: white;
        box-shadow: 0 4px 15px rgba(39, 174, 96, 0.35);
    }

    .step-label {
        font-size: 12px;
        font-weight: 600;
        color: #27ae60;
    }

    @media (max-width: 991px) {
        .info-card[style] {
            position: static !important;
            top: auto !important;
        }
    }

    @media (max-width: 768px) {
        .success-title {
            font-size: 26px;
        }

        .detail-row {
            flex-direction: column;
            align-items: flex-start;
        }

        .detail-value {
            text-align: left;
        }

        .action-buttons {
            display: grid !important;
            gap: 10px;
        }

        .ordered-item {
            align-items: flex-start;
            flex-direction: column;
        }

        .ordered-item-price {
            margin-left: 0;
        }

        .steps-bar {
            gap: 10px;
        }

        .step::after {
            display: none;
        }
    }
</style>
@endpush

@section('content')
<div class="success-wrapper py-5">
    <div class="container">

        {{-- Steps --}}
        <div class="steps-bar">
            <div class="step done">
                <div class="step-circle"><i class="bi bi-cart-check"></i></div>
                <div class="step-label">Cart</div>
            </div>
            <div class="step done">
                <div class="step-circle">2</div>
                <div class="step-label">Checkout</div>
            </div>
            <div class="step done">
                <div class="step-circle">3</div>
                <div class="step-label">Payment</div>
            </div>
            <div class="step done">
                <div class="step-circle"><i class="bi bi-check-lg"></i></div>
                <div class="step-label">Done</div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-7">

                {{-- Success Hero --}}
                <div class="success-card mb-4">
                    <div class="success-hero">
                        <div class="success-icon">
                            <i class="bi bi-check-lg"></i>
                        </div>

                        <h1 class="success-title">Payment Successful!</h1>
                        <p class="success-subtitle">
                            Thank you for shopping at <strong>BazaarHub</strong>.
                            Your order has been placed successfully and is now being processed.
                        </p>

                        <div class="order-badge">
                            <i class="bi bi-receipt-cutoff"></i>
                            <span>Order Number: {{ $order->order_number }}</span>
                        </div>

                        <div class="status-pill">
                            <i class="bi bi-patch-check-fill me-1"></i>
                            Payment Confirmed
                        </div>
                    </div>
                </div>

                {{-- Delivery Information --}}
                <div class="info-card mb-4">
                    <div class="card-header-gradient">
                        <h5><i class="bi bi-truck me-2"></i>Delivery Information</h5>
                    </div>

                    <div class="detail-list">
                        <div class="detail-row">
                            <span class="detail-label">Customer Name</span>
                            <span class="detail-value">{{ $order->full_name }}</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Phone Number</span>
                            <span class="detail-value">{{ $order->phone }}</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Delivery Address</span>
                            <span class="detail-value">{{ $order->address }}, {{ $order->city }}</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Payment Method</span>
                            <span class="detail-value text-uppercase">{{ $order->payment_method }}</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Transaction ID</span>
                            <span class="detail-value">{{ $order->tran_auth_id }}</span>
                        </div>
                    </div>
                </div>

                {{-- Ordered Items --}}
                <div class="items-card">
                    <div class="card-header-gradient">
                        <h5><i class="bi bi-bag-check me-2"></i>Ordered Items</h5>
                    </div>

                    <div class="p-4">
                        @foreach($order->items as $item)
                            <div class="ordered-item">
                                <img src="{{ $item->product && $item->product->thumbnail ? asset('storage/' . $item->product->thumbnail) : 'https://via.placeholder.com/64' }}"
                                     alt="{{ $item->product_name }}">

                                <div class="flex-grow-1">
                                    <div class="ordered-item-name">{{ $item->product_name }}</div>
                                    <div class="ordered-item-meta">
                                        Qty: {{ $item->quantity }} × Rs. {{ number_format($item->price, 0) }}
                                    </div>
                                </div>

                                <div class="ordered-item-price">
                                    Rs. {{ number_format($item->subtotal, 0) }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="info-card" style="position:sticky; top:90px;">
                    <div class="card-header-gradient">
                        <h5><i class="bi bi-receipt me-2"></i>Order Summary</h5>
                    </div>

                    <div class="p-4">
                        <div class="detail-row">
                            <span class="detail-label">Order Status</span>
                            <span class="detail-value text-capitalize">{{ $order->status }}</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Payment Status</span>
                            <span class="detail-value text-success text-capitalize">{{ $order->payment_status }}</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Subtotal</span>
                            <span class="detail-value">Rs. {{ number_format($order->subtotal, 0) }}</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Shipping</span>
                            <span class="detail-value">Rs. {{ number_format($order->shipping, 0) }}</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Total Paid</span>
                            <span class="detail-value amount-highlight">
                                Rs. {{ number_format($order->total, 0) }}
                            </span>
                        </div>

                        <div class="secure-note mt-3">
                            <i class="bi bi-shield-check fs-5"></i>
                            <span>Your payment has been securely processed via KuickPay.</span>
                        </div>

                        <div class="action-buttons d-flex flex-column mt-4 gap-2">
                            <a href="{{ route('home') }}" class="btn btn-primary-success">
                                <i class="bi bi-house-door me-2"></i>Continue Shopping
                            </a>

                            <a href="{{ route('customer.cart') }}" class="btn btn-outline-soft">
                                <i class="bi bi-cart3 me-2"></i>Back to Cart
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
