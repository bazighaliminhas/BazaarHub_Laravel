<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Vendor Dashboard') — BazaarHub</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <style>
        * { font-family: 'Poppins', sans-serif; }
        :root {
            --primary: #FF6B35;
            --sidebar-bg: #1a1f2e;
            --sidebar-hover: #252b3b;
            --sidebar-active: #FF6B35;
            --sidebar-width: 260px;
        }

        body { background: #f4f6fb; overflow-x: hidden; }

        /* ── Sidebar ── */
        .sidebar {
            width: var(--sidebar-width);
            background: var(--sidebar-bg);
            min-height: 100vh;
            position: fixed;
            top: 0; left: 0;
            z-index: 1000;
            transition: all 0.3s;
            overflow-y: auto;
        }
        .sidebar-brand {
            padding: 24px 20px;
            border-bottom: 1px solid rgba(255,255,255,0.07);
        }
        .sidebar-brand h5 {
            color: white;
            font-weight: 700;
            margin: 0;
            font-size: 20px;
        }
        .sidebar-brand span { color: var(--primary); }
        .vendor-info {
            padding: 16px 20px;
            background: rgba(255,255,255,0.04);
            margin: 12px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .vendor-avatar {
            width: 42px; height: 42px;
            background: linear-gradient(135deg, #FF6B35, #FF8C42);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            color: white; font-weight: 700; font-size: 18px;
            flex-shrink: 0;
        }
        .vendor-name { color: white; font-weight: 600; font-size: 14px; margin: 0; }
        .vendor-role { color: rgba(255,255,255,0.45); font-size: 11px; }

        .sidebar-nav { padding: 8px 12px; }
        .nav-section-title {
            color: rgba(255,255,255,0.3);
            font-size: 10px;
            font-weight: 600;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            padding: 16px 8px 6px;
        }
        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 11px 14px;
            border-radius: 10px;
            color: rgba(255,255,255,0.6);
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.2s;
            margin-bottom: 2px;
        }
        .sidebar-link i { font-size: 18px; width: 22px; }
        .sidebar-link:hover {
            background: var(--sidebar-hover);
            color: white;
        }
        .sidebar-link.active {
            background: linear-gradient(135deg, #FF6B35, #FF8C42);
            color: white;
            box-shadow: 0 4px 15px rgba(255,107,53,0.35);
        }
        .sidebar-link .badge-count {
            margin-left: auto;
            background: rgba(255,255,255,0.15);
            color: white;
            font-size: 11px;
            padding: 2px 8px;
            border-radius: 20px;
        }
        .sidebar-link.active .badge-count {
            background: rgba(255,255,255,0.25);
        }

        /* ── Main Content ── */
        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            transition: all 0.3s;
        }

        /* ── Top Navbar ── */
        .top-navbar {
            background: white;
            padding: 14px 28px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.06);
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 999;
        }
        .page-title { font-size: 20px; font-weight: 700; color: #2C3E50; margin: 0; }
        .top-right { display: flex; align-items: center; gap: 16px; }
        .btn-visit-store {
            background: #fff3ee;
            color: #FF6B35;
            border: none;
            border-radius: 25px;
            padding: 8px 18px;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s;
        }
        .btn-visit-store:hover { background: #FF6B35; color: white; }

        /* ── Cards ── */
        .stat-card {
            background: white;
            border-radius: 18px;
            padding: 24px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.06);
            border: none;
            transition: transform 0.2s;
        }
        .stat-card:hover { transform: translateY(-3px); }
        .stat-icon {
            width: 52px; height: 52px;
            border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            font-size: 24px;
        }
        .stat-number { font-size: 30px; font-weight: 700; color: #2C3E50; }
        .stat-label  { font-size: 13px; color: #888; }

        /* ── Content Card ── */
        .content-card {
            background: white;
            border-radius: 18px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.06);
            border: none;
            overflow: hidden;
        }
        .content-card-header {
            padding: 20px 24px;
            border-bottom: 1px solid #f0f0f0;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .content-card-header h5 { margin: 0; font-weight: 700; color: #2C3E50; }
        .content-card-body { padding: 24px; }

        /* ── Table ── */
        .vendor-table { width: 100%; border-collapse: separate; border-spacing: 0; }
        .vendor-table th {
            background: #f8f9fa;
            padding: 12px 16px;
            font-size: 12px;
            font-weight: 600;
            color: #888;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 2px solid #f0f0f0;
        }
        .vendor-table td {
            padding: 14px 16px;
            border-bottom: 1px solid #f8f9fa;
            font-size: 14px;
            color: #2C3E50;
            vertical-align: middle;
        }
        .vendor-table tr:last-child td { border-bottom: none; }
        .vendor-table tr:hover td { background: #fafbff; }

        .product-thumb {
            width: 48px; height: 48px;
            border-radius: 10px;
            object-fit: cover;
        }

        /* ── Buttons ── */
        .btn-primary-v {
            background: linear-gradient(135deg, #FF6B35, #FF8C42);
            color: white; border: none;
            border-radius: 10px;
            padding: 10px 22px;
            font-weight: 600; font-size: 14px;
            transition: all 0.2s;
            text-decoration: none;
            display: inline-flex;
            align-items: center; gap: 8px;
        }
        .btn-primary-v:hover {
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(255,107,53,0.4);
        }
        .btn-edit {
            background: #e8f4fd; color: #2980b9;
            border: none; border-radius: 8px;
            padding: 6px 14px; font-size: 12px; font-weight: 600;
            text-decoration: none; transition: all 0.2s;
            display: inline-flex; align-items: center; gap: 5px;
        }
        .btn-edit:hover { background: #2980b9; color: white; }
        .btn-delete {
            background: #fdecea; color: #e74c3c;
            border: none; border-radius: 8px;
            padding: 6px 14px; font-size: 12px; font-weight: 600;
            cursor: pointer; transition: all 0.2s;
            display: inline-flex; align-items: center; gap: 5px;
        }
        .btn-delete:hover { background: #e74c3c; color: white; }

        /* ── Badge Status ── */
        .badge-active   { background:#e8f8f0; color:#27ae60; padding:4px 12px; border-radius:20px; font-size:12px; font-weight:600; }
        .badge-inactive { background:#fef9e7; color:#f39c12; padding:4px 12px; border-radius:20px; font-size:12px; font-weight:600; }

        /* ── Form Styles ── */
        .form-label { font-weight: 600; font-size: 14px; color: #2C3E50; }
        .form-control, .form-select {
            border: 2px solid #f0f0f0;
            border-radius: 10px;
            padding: 10px 14px;
            font-size: 14px;
            transition: border-color 0.2s;
        }
        .form-control:focus, .form-select:focus {
            border-color: #FF6B35;
            box-shadow: 0 0 0 3px rgba(255,107,53,0.1);
        }
        .image-upload-area {
            border: 2px dashed #ddd;
            border-radius: 14px;
            padding: 32px;
            text-align: center;
            cursor: pointer;
            transition: all 0.2s;
            background: #fafafa;
        }
        .image-upload-area:hover {
            border-color: #FF6B35;
            background: #fff3ee;
        }

        /* ── Mobile ── */
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.show { transform: translateX(0); }
            .main-content { margin-left: 0; }
        }
    </style>
    @stack('styles')
</head>
<body>

{{-- ══════════════════════════════
     SIDEBAR
══════════════════════════════ --}}
<div class="sidebar" id="sidebar">

    {{-- Brand --}}
    <div class="sidebar-brand">
        <h5>🛒 Bazaar<span>Hub</span></h5>
        <div style="color:rgba(255,255,255,0.35); font-size:11px; margin-top:2px;">Vendor Panel</div>
    </div>

    {{-- Vendor Info --}}
    <div class="vendor-info">
        <div class="vendor-avatar">
            {{ strtoupper(substr(auth('vendor')->user()->shop_name, 0, 1)) }}
        </div>
        <div>
            <p class="vendor-name">{{ auth('vendor')->user()->shop_name }}</p>
            <div class="vendor-role">✅ Active Vendor</div>
        </div>
    </div>

    {{-- Navigation --}}
    <nav class="sidebar-nav">
        <div class="nav-section-title">Main</div>

        <a href="{{ route('vendor.dashboard') }}"
           class="sidebar-link {{ request()->routeIs('vendor.dashboard') ? 'active' : '' }}">
            <i class="bi bi-grid-1x2-fill"></i> Dashboard
        </a>

        <div class="nav-section-title">Products</div>

        <a href="{{ route('vendor.products.index') }}"
           class="sidebar-link {{ request()->routeIs('vendor.products.*') ? 'active' : '' }}">
            <i class="bi bi-box-seam-fill"></i> My Products
            <span class="badge-count">
                {{ auth('vendor')->user()->products()->count() }}
            </span>
        </a>

        <a href="{{ route('vendor.products.create') }}"
           class="sidebar-link">
            <i class="bi bi-plus-circle-fill"></i> Add New Product
        </a>

        <div class="nav-section-title">Account</div>

        <a href="{{ route('home') }}" class="sidebar-link" target="_blank">
            <i class="bi bi-shop"></i> Visit Store
        </a>

        <form action="{{ route('vendor.logout') }}" method="POST" class="mt-2">
            @csrf
            <button type="submit" class="sidebar-link w-100 border-0 text-start"
                    style="background:transparent;">
                <i class="bi bi-box-arrow-left" style="color:#e74c3c;"></i>
                <span style="color:rgba(255,255,255,0.6);">Logout</span>
            </button>
        </form>
    </nav>
</div>

{{-- ══════════════════════════════
     MAIN CONTENT
══════════════════════════════ --}}
<div class="main-content">

    {{-- Top Navbar --}}
    <div class="top-navbar">
        <div class="d-flex align-items-center gap-3">
            <button class="btn btn-sm d-md-none" id="sidebarToggle"
                    style="background:#f4f6fb; border:none; border-radius:8px; padding:8px 12px;">
                <i class="bi bi-list fs-5"></i>
            </button>
            <h5 class="page-title">@yield('page-title', 'Dashboard')</h5>
        </div>
        <div class="top-right">
            <a href="{{ route('home') }}" class="btn-visit-store" target="_blank">
                <i class="bi bi-eye me-1"></i> View Store
            </a>
            <div class="dropdown">
                <button class="btn btn-sm dropdown-toggle d-flex align-items-center gap-2"
                        style="background:#f4f6fb; border:none; border-radius:10px; padding:8px 14px;"
                        data-bs-toggle="dropdown">
                    <div style="width:30px;height:30px;background:linear-gradient(135deg,#FF6B35,#FF8C42);
                                border-radius:50%;display:flex;align-items:center;justify-content:center;
                                color:white;font-weight:700;font-size:13px;">
                        {{ strtoupper(substr(auth('vendor')->user()->name, 0, 1)) }}
                    </div>
                    <span style="font-size:14px;font-weight:600;">{{ auth('vendor')->user()->name }}</span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-3">
                    <li><a class="dropdown-item" href="#"><i class="bi bi-person me-2"></i>Profile</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form action="{{ route('vendor.logout') }}" method="POST">
                            @csrf
                            <button class="dropdown-item text-danger">
                                <i class="bi bi-box-arrow-right me-2"></i>Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    {{-- Flash Messages --}}
    <div class="px-4 pt-3">
        @if(session('success'))
        <div class="alert border-0 rounded-3 d-flex align-items-center gap-2"
             style="background:#e8f8f0; color:#27ae60;">
            <i class="bi bi-check-circle-fill fs-5"></i>
            {{ session('success') }}
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
        @endif
        @if(session('error'))
        <div class="alert border-0 rounded-3 d-flex align-items-center gap-2"
             style="background:#fdecea; color:#e74c3c;">
            <i class="bi bi-exclamation-circle-fill fs-5"></i>
            {{ session('error') }}
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
        @endif
    </div>

    {{-- Page Content --}}
    <div class="p-4">
        @yield('content')
    </div>
</div>

<script>
    // Mobile sidebar toggle
    document.getElementById('sidebarToggle')?.addEventListener('click', function() {
        document.getElementById('sidebar').classList.toggle('show');
    });
</script>

@stack('scripts')
</body>
</html>
