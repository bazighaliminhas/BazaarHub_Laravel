<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'BazaarHub - Shop Everything')</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <style>
        * { font-family: 'Poppins', sans-serif; }

        :root {
            --primary: #FF6B35;
            --secondary: #2C3E50;
            --accent: #F7C59F;
            --success: #27AE60;
            --light-bg: #FFF8F5;
        }

        body { background: var(--light-bg); }

        .navbar-brand span { color: var(--primary); font-weight: 700; }

        .btn-primary-custom {
            background: var(--primary);
            border: none; color: white;
            border-radius: 25px; padding: 8px 24px;
            font-weight: 500; transition: all 0.3s;
        }
        .btn-primary-custom:hover {
            background: #e55a25;
            transform: translateY(-1px);
            box-shadow: 0 4px 15px rgba(255,107,53,0.4);
            color: white;
        }

        .cart-badge {
            background: var(--primary); color: white;
            border-radius: 50%; font-size: 10px; padding: 2px 6px;
        }

        .navbar {
            background: white !important;
            box-shadow: 0 2px 15px rgba(0,0,0,0.08);
        }

        .category-pill {
            background: white; border: 2px solid #eee;
            border-radius: 25px; padding: 6px 18px;
            font-size: 14px; cursor: pointer;
            transition: all 0.2s; text-decoration: none;
            color: var(--secondary); white-space: nowrap;
        }
        .category-pill:hover,
        .category-pill.active {
            border-color: var(--primary);
            color: var(--primary);
            background: #fff3ee;
        }

        .product-card {
            border: none; border-radius: 15px;
            overflow: hidden; transition: all 0.3s;
            box-shadow: 0 2px 10px rgba(0,0,0,0.06);
        }
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.12);
        }
        .product-card img {
            height: 220px; object-fit: cover; width: 100%;
        }

        .price-tag  { color: var(--primary); font-weight: 700; font-size: 18px; }
        .sale-price { text-decoration: line-through; color: #999; font-size: 13px; }

        footer {
            background: var(--secondary);
            color: white; padding: 40px 0 0;
        }

        /* Admin navbar badge */
        .admin-nav-btn {
            background: linear-gradient(135deg, #0f1623, #1a2332);
            color: white; border: none;
            border-radius: 20px; padding: 7px 16px;
            font-size: 12px; font-weight: 600;
            text-decoration: none; transition: all 0.2s;
            display: inline-flex; align-items: center; gap: 6px;
        }
        .admin-nav-btn:hover {
            background: linear-gradient(135deg, #FF6B35, #FF8C42);
            color: white;
            box-shadow: 0 4px 15px rgba(255,107,53,0.4);
        }
    </style>

    @stack('styles')
</head>

<body>

{{-- ══════════════════════════════════════
     NAVBAR
══════════════════════════════════════ --}}
<nav class="navbar navbar-expand-lg sticky-top">
    <div class="container">

        {{-- Brand --}}
        <a class="navbar-brand fs-4 fw-bold" href="{{ route('home') }}">
            🛒 Bazaar<span>Hub</span>
        </a>

        <button class="navbar-toggler" type="button"
                data-bs-toggle="collapse" data-bs-target="#navbarMain">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarMain">

            {{-- Search Bar --}}
            <form class="d-flex mx-auto w-50" action="#" method="GET">
                <div class="input-group">
                    <input type="text" name="q"
                           class="form-control rounded-start-pill border-end-0"
                           placeholder="Search products, vendors..."
                           style="border-color:#FF6B35;">
                    <button class="btn btn-primary-custom rounded-end-pill" type="submit">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </form>

            {{-- Right Side --}}
            <ul class="navbar-nav ms-auto align-items-center gap-2">

                @auth('web')
                    {{-- ── Logged in as ADMIN ── --}}
                    @if(auth('web')->user()->isAdmin())
                        <li class="nav-item">
                            <a href="{{ route('admin.dashboard') }}" class="admin-nav-btn">
                                🔐 Admin Panel
                            </a>
                        </li>
                        <li class="nav-item">
                            <form action="{{ route('admin.logout') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit"
                                        class="btn btn-sm btn-outline-danger rounded-pill px-3"
                                        style="font-size:13px;">
                                    <i class="bi bi-box-arrow-right me-1"></i>Logout
                                </button>
                            </form>
                        </li>

                    {{-- ── Logged in as CUSTOMER ── --}}
                    @else
                        <li class="nav-item">
                            <a href="{{ route('customer.cart') }}" class="nav-link position-relative">
                                <i class="bi bi-cart3 fs-5"></i>
                                <span class="cart-badge position-absolute top-0 start-100 translate-middle"
                                      id="cart-count">
                                    {{ auth('web')->user()->cart()->sum('quantity') }}
                                </span>
                            </a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center gap-1"
                               href="#" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle fs-5"></i>
                                {{ auth('web')->user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-3 mt-2">
                                <li>
                                    <a class="dropdown-item" href="{{ route('customer.cart') }}">
                                        <i class="bi bi-cart3 me-2" style="color:#FF6B35;"></i>My Cart
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="#">
                                        <i class="bi bi-bag me-2" style="color:#FF6B35;"></i>My Orders
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('customer.logout') }}" method="POST">
                                        @csrf
                                        <button class="dropdown-item text-danger">
                                            <i class="bi bi-box-arrow-right me-2"></i>Logout
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endif

                @else
                    {{-- ── Guest ── --}}
                    <li class="nav-item">
                        <a href="{{ route('customer.login') }}"
                           class="nav-link fw-semibold"
                           style="color:#FF6B35;">
                            <i class="bi bi-person me-1"></i>Login
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('customer.register') }}" class="btn btn-primary-custom">
                            Register
                        </a>
                    </li>
                @endauth

            </ul>
        </div>
    </div>
</nav>

{{-- ══════════════════════════════════════
     CATEGORY NAV BAR
══════════════════════════════════════ --}}
<div class="bg-white border-bottom py-2">
    <div class="container">
        <div class="d-flex gap-2 overflow-auto pb-1" style="scrollbar-width:none;">
            <a href="{{ route('home') }}"
               class="category-pill {{ request()->routeIs('home') ? 'active' : '' }}">
                🏠 All
            </a>
            @foreach(\App\Models\Category::all() as $cat)
            <a href="{{ route('category', $cat->slug) }}"
               class="category-pill {{ request()->segment(2) == $cat->slug ? 'active' : '' }}">
                {{ $cat->icon }} {{ $cat->name }}
            </a>
            @endforeach
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════
     FLASH MESSAGES
══════════════════════════════════════ --}}
<div class="container mt-3">
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show rounded-3" role="alert">
        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif
    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show rounded-3" role="alert">
        <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif
</div>

{{-- ══════════════════════════════════════
     MAIN CONTENT
══════════════════════════════════════ --}}
<main>
    @yield('content')
</main>

{{-- ══════════════════════════════════════
     FOOTER
══════════════════════════════════════ --}}
<footer class="mt-5">
    <div class="container">
        <div class="row pb-4">
            <div class="col-md-4 mb-4">
                <h5 class="fw-bold">🛒 BazaarHub</h5>
                <p class="text-white-50">
                    Your one-stop marketplace for everything — fresh produce,
                    fashion, electronics and more.
                </p>
            </div>
            <div class="col-md-2 mb-4">
                <h6 class="fw-bold">Shop</h6>
                <ul class="list-unstyled text-white-50">
                    <li><a href="{{ route('home') }}" class="text-white-50 text-decoration-none">All Products</a></li>
                    <li><a href="{{ route('category','vegetables') }}" class="text-white-50 text-decoration-none">Vegetables</a></li>
                    <li><a href="{{ route('category','electronics') }}" class="text-white-50 text-decoration-none">Electronics</a></li>
                </ul>
            </div>
            <div class="col-md-2 mb-4">
                <h6 class="fw-bold">Account</h6>
                <ul class="list-unstyled text-white-50">
                    <li><a href="{{ route('customer.register') }}" class="text-white-50 text-decoration-none">Register</a></li>
                    <li><a href="{{ route('customer.login') }}" class="text-white-50 text-decoration-none">Login</a></li>
                    <li><a href="{{ route('vendor.login') }}" class="text-white-50 text-decoration-none">Vendor Login</a></li>
                </ul>
            </div>
            <div class="col-md-4 mb-4">
                <h6 class="fw-bold">Contact</h6>
                <p class="text-white-50">
                    <i class="bi bi-envelope me-2"></i>support@bazaarhub.pk
                </p>
                <p class="text-white-50">
                    <i class="bi bi-telephone me-2"></i>+92 300 0000000
                </p>
            </div>
        </div>

        {{-- Footer Bottom Bar --}}
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 py-3"
             style="border-top:1px solid rgba(255,255,255,0.08);">
            <span style="color:rgba(255,255,255,0.4); font-size:13px;">
                © {{ date('Y') }} BazaarHub. All rights reserved.
            </span>
            <div class="d-flex gap-3 align-items-center">
                <a href="{{ route('vendor.login') }}"
                   style="color:rgba(255,255,255,0.35);font-size:12px;text-decoration:none;">
                    🏪 Vendor Login
                </a>
                <a href="{{ route('admin.login') }}"
                   style="color:rgba(255,255,255,0.25);font-size:12px;text-decoration:none;">
                    🔐 Admin
                </a>
            </div>
        </div>
    </div>
</footer>

@stack('scripts')
</body>
</html>
