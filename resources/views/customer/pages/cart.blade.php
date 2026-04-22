@extends('layouts.app')
@section('title', 'My Cart - BazaarHub')

@section('content')
<div class="container py-5">
    <h3 class="fw-bold mb-4"><i class="bi bi-cart3 me-2" style="color:#FF6B35;"></i>My Cart</h3>

    @if($cartItems->isEmpty())
        <div class="text-center py-5">
            <div class="fs-1 mb-3">🛒</div>
            <h5 class="text-muted">Your cart is empty!</h5>
            <a href="{{ route('home') }}" class="btn btn-primary-custom mt-3">Start Shopping</a>
        </div>
    @else
        <div class="row g-4">
            <div class="col-lg-8">
                @foreach($cartItems as $item)
                <div class="card border-0 shadow-sm rounded-4 mb-3">
                    <div class="card-body d-flex align-items-center gap-3">
                        <img src="{{ $item->product->thumbnail ? asset('storage/'.$item->product->thumbnail) : 'https://via.placeholder.com/80' }}"
                             style="width:80px;height:80px;object-fit:cover;border-radius:10px;" alt="">
                        <div class="flex-grow-1">
                            <h6 class="fw-semibold mb-1">{{ $item->product->name }}</h6>
                            <small class="text-muted">By {{ $item->product->vendor->shop_name }}</small>
                            <div class="price-tag mt-1">
                                Rs. {{ number_format($item->product->sale_price ?? $item->product->price, 0) }}
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <button class="btn btn-sm btn-outline-secondary rounded-circle qty-btn"
                                    data-action="minus" data-id="{{ $item->product_id }}">−</button>
                            <span class="fw-bold qty-display" id="qty-{{ $item->product_id }}">{{ $item->quantity }}</span>
                            <button class="btn btn-sm btn-outline-secondary rounded-circle qty-btn"
                                    data-action="plus" data-id="{{ $item->product_id }}">+</button>
                        </div>
                        <button class="btn btn-sm btn-outline-danger rounded-circle remove-item"
                                data-id="{{ $item->product_id }}">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Order Summary --}}
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 p-4">
                    <h5 class="fw-bold mb-4">Order Summary</h5>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Subtotal</span>
                        <span class="fw-semibold">Rs. {{ number_format($subtotal, 0) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Shipping</span>
                        <span class="fw-semibold">Rs. {{ number_format($shipping, 0) }}</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between mb-4">
                        <span class="fw-bold fs-5">Total</span>
                        <span class="fw-bold fs-5" style="color:#FF6B35;">Rs. {{ number_format($total, 0) }}</span>
                    </div>

                    {{-- ✅ FIXED: Now links to checkout page --}}
                    <a href="{{ route('customer.checkout') }}"
                       class="btn btn-primary-custom w-100 py-2 text-decoration-none text-center">
                        <i class="bi bi-credit-card me-2"></i>Proceed to Checkout
                    </a>

                    <a href="{{ route('home') }}" class="btn btn-outline-secondary w-100 mt-2 rounded-pill">
                        Continue Shopping
                    </a>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

// Remove item
document.querySelectorAll('.remove-item').forEach(btn => {
    btn.addEventListener('click', function() {
        const productId = this.getAttribute('data-id');
        fetch('{{ route("customer.cart.remove") }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
            body: JSON.stringify({ product_id: productId })
        }).then(() => location.reload());
    });
});

// Qty buttons
document.querySelectorAll('.qty-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const productId = this.getAttribute('data-id');
        const action    = this.getAttribute('data-action');
        const qtyEl     = document.getElementById('qty-' + productId);
        let qty         = parseInt(qtyEl.textContent);

        if (action === 'plus') qty++;
        else if (action === 'minus' && qty > 1) qty--;
        else return;

        qtyEl.textContent = qty;

        fetch('{{ route("customer.cart.update") }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
            body: JSON.stringify({ product_id: productId, quantity: qty })
        }).then(() => location.reload());
    });
});
</script>
@endpush
