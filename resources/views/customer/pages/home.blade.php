@extends('layouts.app')
@section('title', isset($category) ? $category->name . ' - BazaarHub' : 'BazaarHub - Shop Everything')

@push('styles')
<style>
/* ── Hero ─────────────────────────────── */
.hero-section {
    background: linear-gradient(135deg, #FF6B35 0%, #FF8C42 40%, #FFA552 70%, #F7C59F 100%);
    min-height: 420px;
    position: relative;
    overflow: hidden;
}
.hero-section::before {
    content: '';
    position: absolute;
    width: 500px; height: 500px;
    background: rgba(255,255,255,0.06);
    border-radius: 50%;
    top: -150px; right: -100px;
}
.hero-section::after {
    content: '';
    position: absolute;
    width: 300px; height: 300px;
    background: rgba(255,255,255,0.05);
    border-radius: 50%;
    bottom: -100px; left: -50px;
}
.hero-badge {
    background: rgba(255,255,255,0.2);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255,255,255,0.3);
    color: white;
    padding: 6px 16px;
    border-radius: 20px;
    font-size: 13px;
    display: inline-block;
    margin-bottom: 16px;
}
.hero-img {
    border-radius: 20px;
    box-shadow: 0 25px 60px rgba(0,0,0,0.25);
    transform: rotate(2deg);
    transition: transform 0.3s;
    max-height: 320px;
    object-fit: cover;
    width: 100%;
}
.hero-img:hover { transform: rotate(0deg) scale(1.02); }

/* ── Stats Bar ────────────────────────── */
.stats-bar {
    background: white;
    border-radius: 20px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.08);
    margin-top: -40px;
    position: relative;
    z-index: 10;
    padding: 24px 32px;
}
.stat-item { text-align: center; }
.stat-number { font-size: 28px; font-weight: 700; color: #FF6B35; }
.stat-label  { font-size: 13px; color: #888; }

/* ── Category Cards ───────────────────── */
.cat-card {
    background: white;
    border-radius: 18px;
    padding: 20px 12px;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s cubic-bezier(0.34,1.56,0.64,1);
    box-shadow: 0 4px 15px rgba(0,0,0,0.06);
    text-decoration: none;
    display: block;
    border: 2px solid transparent;
}
.cat-card:hover {
    transform: translateY(-8px) scale(1.03);
    border-color: #FF6B35;
    box-shadow: 0 15px 35px rgba(255,107,53,0.2);
}
.cat-card .cat-icon {
    font-size: 36px;
    margin-bottom: 8px;
    display: block;
    transition: transform 0.3s;
}
.cat-card:hover .cat-icon { transform: scale(1.2); }
.cat-card .cat-name {
    font-size: 13px;
    font-weight: 600;
    color: #2C3E50;
}

/* ── Section Title ────────────────────── */
.section-title {
    font-size: 26px;
    font-weight: 700;
    color: #2C3E50;
    position: relative;
    padding-bottom: 12px;
}
.section-title::after {
    content: '';
    position: absolute;
    bottom: 0; left: 0;
    width: 50px; height: 3px;
    background: #FF6B35;
    border-radius: 2px;
}

/* ── Product Card ─────────────────────── */
.product-card {
    border: none;
    border-radius: 18px;
    overflow: hidden;
    transition: all 0.3s;
    box-shadow: 0 4px 15px rgba(0,0,0,0.07);
    background: white;
}
.product-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 45px rgba(0,0,0,0.13);
}
.product-card .card-img-wrap {
    position: relative;
    overflow: hidden;
    height: 220px;
}
.product-card .card-img-wrap img {
    width: 100%; height: 100%;
    object-fit: cover;
    transition: transform 0.4s;
}
.product-card:hover .card-img-wrap img { transform: scale(1.08); }
.product-card .overlay-btn {
    position: absolute;
    bottom: -50px;
    left: 0; right: 0;
    text-align: center;
    transition: bottom 0.3s;
    padding: 0 12px;
}
.product-card:hover .overlay-btn { bottom: 12px; }

.badge-sale {
    position: absolute; top: 12px; left: 12px;
    background: #FF6B35; color: white;
    padding: 4px 10px; border-radius: 20px;
    font-size: 11px; font-weight: 600;
    z-index: 2;
}
.badge-vendor {
    position: absolute; top: 12px; right: 12px;
    background: rgba(44,62,80,0.85);
    backdrop-filter: blur(5px);
    color: white;
    padding: 4px 10px; border-radius: 20px;
    font-size: 10px; font-weight: 500;
    z-index: 2;
    max-width: 120px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.price-tag  { color: #FF6B35; font-weight: 700; font-size: 17px; }
.sale-price { text-decoration: line-through; color: #bbb; font-size: 13px; }

/* ── Add to Cart Btn ──────────────────── */
.btn-cart {
    background: linear-gradient(135deg, #FF6B35, #FF8C42);
    color: white;
    border: none;
    border-radius: 25px;
    padding: 8px 18px;
    font-size: 13px;
    font-weight: 600;
    width: 100%;
    transition: all 0.3s;
    box-shadow: 0 4px 15px rgba(255,107,53,0.35);
}
.btn-cart:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(255,107,53,0.5);
    color: white;
}
.btn-login-buy {
    background: #f8f9fa;
    color: #666;
    border: 1.5px dashed #ddd;
    border-radius: 25px;
    padding: 8px 18px;
    font-size: 13px;
    font-weight: 500;
    width: 100%;
    transition: all 0.2s;
    text-decoration: none;
    display: block;
    text-align: center;
}
.btn-login-buy:hover { border-color: #FF6B35; color: #FF6B35; background: #fff3ee; }

/* ── Toast ────────────────────────────── */
#cartToast {
    background: linear-gradient(135deg, #FF6B35, #FF8C42);
    border-radius: 14px;
    min-width: 280px;
}

/* ── Promo Banner ─────────────────────── */
.promo-banner {
    background: linear-gradient(135deg, #2C3E50, #34495E);
    border-radius: 20px;
    padding: 32px;
    color: white;
    position: relative;
    overflow: hidden;
}
.promo-banner::before {
    content: '🎉';
    position: absolute;
    font-size: 120px;
    right: 20px; top: -10px;
    opacity: 0.15;
}
</style>
@endpush

@section('content')

{{-- ═══════════════════════════════════════
     HERO SECTION
════════════════════════════════════════ --}}
@if(!isset($category))
<div class="hero-section d-flex align-items-center">
    <div class="container py-5 position-relative" style="z-index:2;">
        <div class="row align-items-center">
            <div class="col-lg-6 text-white mb-4 mb-lg-0">
                <div class="hero-badge">
                    ✨ Pakistan's #1 Multi-Vendor Marketplace
                </div>
                <h1 class="display-4 fw-bold mb-3 lh-sm">
                    Shop Everything<br>
                    <span style="color:#2C3E50; text-shadow: 2px 2px 0 rgba(255,255,255,0.3);">
                        You Love 🛒
                    </span>
                </h1>
                <p class="fs-5 mb-4" style="opacity:0.85; line-height:1.7;">
                    Fresh vegetables, trendy clothes, electronics & more —<br>
                    all from <strong>trusted local vendors</strong>, delivered to your door.
                </p>
                <div class="d-flex gap-3 flex-wrap">
                    <a href="#products" class="btn btn-dark btn-lg rounded-pill px-4 shadow">
                        <i class="bi bi-bag-heart me-2"></i>Shop Now
                    </a>
                    <a href="{{ route('vendor.login') }}"
                       class="btn btn-outline-light btn-lg rounded-pill px-4">
                        <i class="bi bi-shop me-2"></i>Become a Vendor
                    </a>
                </div>
            </div>
            <div class="col-lg-6 text-center d-none d-lg-block">
                <img src="https://images.unsplash.com/photo-1607082348824-0a96f2a4b9da?w=600&q=85"
                     alt="Shopping" class="hero-img">
            </div>
        </div>
    </div>
</div>

{{-- ── Stats Bar ── --}}
<div class="container">
    <div class="stats-bar">
        <div class="row text-center g-3">
            <div class="col-6 col-md-3">
                <div class="stat-number">{{ \App\Models\Product::count() }}+</div>
                <div class="stat-label">Products</div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-number">{{ \App\Models\Vendor::count() }}+</div>
                <div class="stat-label">Trusted Vendors</div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-number">{{ \App\Models\User::where('role','customer')->count() }}+</div>
                <div class="stat-label">Happy Customers</div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-number">{{ \App\Models\Category::count() }}+</div>
                <div class="stat-label">Categories</div>
            </div>
        </div>
    </div>
</div>

{{-- ── Category Cards ── --}}
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="section-title mb-0">Shop by Category</h4>
    </div>
    <div class="row g-3">
        @foreach(\App\Models\Category::all() as $cat)
        <div class="col-4 col-md-3 col-lg-2 col-xl-1-5">
            <a href="{{ route('category', $cat->slug) }}" class="cat-card">
                <span class="cat-icon">{{ $cat->icon ?? '🛍️' }}</span>
                <div class="cat-name">{{ $cat->name }}</div>
            </a>
        </div>
        @endforeach
    </div>
</div>
@endif

{{-- ═══════════════════════════════════════
     PRODUCTS SECTION
════════════════════════════════════════ --}}
<div class="container pb-5" id="products">

    {{-- Section Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="section-title mb-0">
            @if(isset($category))
                {{ $category->icon ?? '🛍️' }} {{ $category->name }}
            @else
                🔥 All Products
            @endif
        </h4>
        <span class="badge rounded-pill px-3 py-2"
              style="background:#fff3ee; color:#FF6B35; font-size:13px;">
            {{ $products->total() }} products
        </span>
    </div>

    @if($products->isEmpty())
    <div class="text-center py-5">
        <div style="font-size:80px;">😕</div>
        <h5 class="text-muted mt-3">No products in this category yet.</h5>
        <a href="{{ route('home') }}" class="btn btn-cart mt-3 px-5 d-inline-block" style="width:auto;">
            Browse All Products
        </a>
    </div>
    @else

    <div class="row g-4">
        @foreach($products as $product)
        <div class="col-6 col-md-4 col-lg-3">
            <div class="card product-card h-100">

                {{-- Image --}}
                <div class="card-img-wrap">
                    <a href="{{ route('product.show', $product->slug) }}">
                        <img src="{{ $product->thumbnail_url }}" alt="{{ $product->name }}"
                             loading="lazy">
                    </a>

                    @if($product->sale_price)
                        <span class="badge-sale">
                            {{ round((($product->price - $product->sale_price) / $product->price) * 100) }}% OFF
                        </span>
                    @endif

                    <span class="badge-vendor">🏪 {{ $product->vendor->shop_name }}</span>

                    {{-- Hover Add to Cart --}}
                    <div class="overlay-btn">
                        @auth('web')
                            @if(!auth('web')->user()->isAdmin())
                            <button class="btn-cart add-to-cart shadow"
                                    data-product-id="{{ $product->id }}"
                                    data-product-name="{{ $product->name }}">
                                <i class="bi bi-cart-plus me-1"></i> Quick Add
                            </button>
                            @endif
                        @else
                            <a href="{{ route('customer.login') }}" class="btn-login-buy shadow">
                                <i class="bi bi-lock me-1"></i> Login to Buy
                            </a>
                        @endauth
                    </div>
                </div>

                <div class="card-body d-flex flex-column p-3">
                    <span class="small mb-1" style="color:#FF6B35; font-weight:500;">
                        {{ $product->category->icon ?? '' }} {{ $product->category->name }}
                    </span>

                    <h6 class="fw-semibold mb-2" style="font-size:14px; line-height:1.4;">
                        <a href="{{ route('product.show', $product->slug) }}"
                           class="text-decoration-none text-dark">
                            {{ Str::limit($product->name, 38) }}
                        </a>
                    </h6>

                    <div class="mt-auto">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            @if($product->sale_price)
                                <span class="price-tag">Rs. {{ number_format($product->sale_price, 0) }}</span>
                                <span class="sale-price">Rs. {{ number_format($product->price, 0) }}</span>
                            @else
                                <span class="price-tag">Rs. {{ number_format($product->price, 0) }}</span>
                            @endif
                        </div>

                        @auth('web')
                            @if(!auth('web')->user()->isAdmin())
                            <button class="btn-cart add-to-cart"
                                    data-product-id="{{ $product->id }}"
                                    data-product-name="{{ $product->name }}">
                                <i class="bi bi-cart-plus me-1"></i> Add to Cart
                            </button>
                            @endif
                        @else
                            <a href="{{ route('customer.login') }}" class="btn-login-buy">
                                <i class="bi bi-lock me-1"></i> Login to Buy
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Pagination --}}
    <div class="d-flex justify-content-center mt-5">
        {{ $products->links() }}
    </div>
    @endif
</div>

{{-- ── Promo Banner ── --}}
@if(!isset($category))
<div class="container pb-5">
    <div class="promo-banner">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h3 class="fw-bold mb-2">🚀 Want to Sell on BazaarHub?</h3>
                <p class="mb-0 opacity-75">
                    Join hundreds of vendors and reach thousands of customers across Pakistan.
                    Setup your store in minutes!
                </p>
            </div>
            <div class="col-md-4 text-md-end mt-3 mt-md-0">
                <a href="{{ route('vendor.login') }}"
                   class="btn btn-warning btn-lg rounded-pill px-4 fw-bold">
                    Start Selling Today →
                </a>
            </div>
        </div>
    </div>
</div>
@endif

{{-- Toast --}}
<div class="position-fixed bottom-0 end-0 p-3" style="z-index:9999;">
    <div id="cartToast" class="toast align-items-center text-white border-0" role="alert">
        <div class="d-flex">
            <div class="toast-body fw-semibold fs-6">
                <i class="bi bi-check-circle-fill me-2"></i>
                <span id="toastMessage">Added to cart!</span>
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto"
                    data-bs-dismiss="toast"></button>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.querySelectorAll('.add-to-cart').forEach(function(btn) {
    btn.addEventListener('click', function() {
        const productId   = this.getAttribute('data-product-id');
        const productName = this.getAttribute('data-product-name');
        const button      = this;
        const original    = button.innerHTML;

        button.disabled   = true;
        button.innerHTML  = '<span class="spinner-border spinner-border-sm me-1"></span> Adding...';

        fetch('{{ route("customer.cart.add") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ product_id: productId, quantity: 1 })
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                const cc = document.getElementById('cart-count');
                if (cc) cc.textContent = data.cart_count;

                document.getElementById('toastMessage').textContent = '✅ ' + productName + ' added!';
                new bootstrap.Toast(document.getElementById('cartToast')).show();

                button.innerHTML = '<i class="bi bi-check-lg me-1"></i> Added!';
                button.style.background = 'linear-gradient(135deg,#27AE60,#2ECC71)';

                setTimeout(() => {
                    button.disabled  = false;
                    button.innerHTML = original;
                    button.style.background = '';
                }, 2000);
            }
        })
        .catch(() => {
            button.disabled  = false;
            button.innerHTML = original;
        });
    });
});
</script>
@endpush
