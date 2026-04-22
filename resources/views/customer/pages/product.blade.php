@extends('layouts.app')
@section('title', $product->name . ' - BazaarHub')

@section('content')
<div class="container py-5">
    <div class="row g-5">
        <div class="col-md-5">
            <img src="{{ $product->thumbnail ? asset('storage/'.$product->thumbnail) : 'https://via.placeholder.com/500' }}"
                 class="img-fluid rounded-4 shadow" alt="{{ $product->name }}">
        </div>
        <div class="col-md-7">
            <span class="badge rounded-pill mb-2" style="background:#FF6B35;">
                {{ $product->category->name }}
            </span>
            <h2 class="fw-bold">{{ $product->name }}</h2>
            <p class="text-muted">By <strong>{{ $product->vendor->shop_name }}</strong></p>
            <div class="d-flex align-items-center gap-3 mb-3">
                @if($product->sale_price)
                    <span class="price-tag fs-3">Rs. {{ number_format($product->sale_price, 0) }}</span>
                    <span class="sale-price fs-5">Rs. {{ number_format($product->price, 0) }}</span>
                @else
                    <span class="price-tag fs-3">Rs. {{ number_format($product->price, 0) }}</span>
                @endif
            </div>
            <p>{{ $product->description }}</p>
            <p class="text-muted">Stock: <strong>{{ $product->stock }} {{ $product->unit }}(s)</strong></p>

            @auth('web')
                @if(!auth('web')->user()->isAdmin())
                <button class="btn btn-primary-custom px-5 py-2 add-to-cart"
                        data-product-id="{{ $product->id }}"
                        data-product-name="{{ $product->name }}">
                    <i class="bi bi-cart-plus me-2"></i>Add to Cart
                </button>
                @endif
            @else
                <a href="{{ route('customer.login') }}" class="btn btn-primary-custom px-5 py-2">
                    Login to Buy
                </a>
            @endauth
        </div>
    </div>
</div>
@endsection
