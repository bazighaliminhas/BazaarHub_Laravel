<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Dashboard') — BazaarHub</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <style>
        * { font-family: 'Poppins', sans-serif; }
        :root {
            --primary: #FF6B35;
            --admin-sidebar: #0f1623;
            --sidebar-width: 265px;
        }
        body { background: #f0f2f8; overflow-x: hidden; }

        /* ── Sidebar ── */
        .sidebar {
            width: var(--sidebar-width);
            background: var(--admin-sidebar);
            height: 100vh;              /* ✅ Fixed height = full screen */
            position: fixed;
            top: 0; left: 0;
            z-index: 1000;
            overflow-y: scroll !important;  /* ✅ FORCE scroll */
            overflow-x: hidden;
            transition: transform 0.3s;
            display: flex;
            flex-direction: column;
        }

        /* ✅ Scrollbar style — thin & dark */
        .sidebar::-webkit-scrollbar { width: 4px; }
        .sidebar::-webkit-scrollbar-track { background: transparent; }
        .sidebar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 10px; }
        .sidebar::-webkit-scrollbar-thumb:hover { background: rgba(255,255,255,0.2); }

        .sidebar-brand {
            padding: 22px 20px 16px;
            border-bottom: 1px solid rgba(255,255,255,0.06);
            flex-shrink: 0;
        }
        .sidebar-brand h5 { color: white; font-weight: 700; font-size: 20px; margin: 0; }
        .sidebar-brand span { color: #FF6B35; }
        .admin-badge {
            background: linear-gradient(135deg, #FF6B35, #FF8C42);
            color: white; font-size: 10px; font-weight: 700;
            padding: 3px 10px; border-radius: 20px;
            letter-spacing: 1px; text-transform: uppercase;
            display: inline-block; margin-top: 4px;
        }
        .admin-profile {
            margin: 12px; padding: 14px;
            background: rgba(255,255,255,0.04);
            border-radius: 12px;
            display: flex; align-items: center; gap: 12px;
            flex-shrink: 0;
        }
        .admin-avatar {
            width: 44px; height: 44px;
            background: linear-gradient(135deg, #FF6B35, #FF8C42);
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            color: white; font-weight: 700; font-size: 18px; flex-shrink: 0;
        }
        .admin-name  { color: white; font-weight: 600; font-size: 14px; margin: 0; }
        .admin-role  { color: rgba(255,255,255,0.4); font-size: 11px; }

        /* ✅ Nav takes remaining space and pushes logout to bottom */
        .sidebar-nav {
            padding: 6px 12px 0;
            display: flex;
            flex-direction: column;
            flex: 1;
        }

        /* ✅ Logout always sticks to bottom */
        .sidebar-nav .logout-wrapper {
            margin-top: auto;
            padding: 12px 0 20px;
            border-top: 1px solid rgba(255,255,255,0.06);
        }

        .nav-section {
            color: rgba(255,255,255,0.25);
            font-size: 10px; font-weight: 700;
            letter-spacing: 1.5px; text-transform: uppercase;
            padding: 14px 8px 5px;
        }
        .sidebar-link {
            display: flex; align-items: center; gap: 12px;
            padding: 11px 14px; border-radius: 10px;
            color: rgba(255,255,255,0.55);
            text-decoration: none; font-size: 14px; font-weight: 500;
            transition: all 0.2s; margin-bottom: 2px;
        }
        .sidebar-link i { font-size: 17px; width: 22px; flex-shrink: 0; }
        .sidebar-link:hover { background: rgba(255,255,255,0.06); color: white; }
        .sidebar-link.active {
            background: linear-gradient(135deg, #FF6B35, #FF8C42);
            color: white;
            box-shadow: 0 4px 15px rgba(255,107,53,0.3);
        }
        .sidebar-link .nav-count {
            margin-left: auto; background: rgba(255,255,255,0.12);
            color: rgba(255,255,255,0.7); font-size: 11px;
            padding: 2px 8px; border-radius: 20px;
        }
        .sidebar-link.active .nav-count { background: rgba(255,255,255,0.25); color: white; }

        /* ✅ Logout button */
        .logout-btn {
            display: flex; align-items: center; gap: 12px;
            padding: 11px 14px; border-radius: 10px;
            color: rgba(255,255,255,0.55);
            font-size: 14px; font-weight: 500;
            transition: all 0.2s;
            background: transparent; border: none;
            width: 100%; text-align: left; cursor: pointer;
        }
        .logout-btn:hover { background: rgba(231,76,60,0.15); color: #e74c3c; }
        .logout-btn i { font-size: 17px; width: 22px; flex-shrink: 0; color: #e74c3c; }

        /* ── Main ── */
        .main-content { margin-left: var(--sidebar-width); min-height: 100vh; }
        .top-bar {
            background: white; padding: 14px 28px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.05);
            display: flex; align-items: center; justify-content: space-between;
            position: sticky; top: 0; z-index: 999;
        }
        .page-title { font-size: 20px; font-weight: 700; color: #1a1f2e; margin: 0; }

        /* ── Stat Cards ── */
        .stat-card {
            background: white; border-radius: 18px;
            padding: 22px; border: none;
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
            transition: transform 0.2s;
        }
        .stat-card:hover { transform: translateY(-3px); }
        .stat-icon {
            width: 50px; height: 50px; border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            font-size: 22px;
        }
        .stat-num   { font-size: 28px; font-weight: 700; color: #1a1f2e; }
        .stat-label { font-size: 13px; color: #888; }
        .stat-trend { font-size: 12px; margin-top: 6px; }
        .trend-up   { color: #27ae60; }
        .trend-info { color: #3498db; }

        /* ── Content Card ── */
        .content-card {
            background: white; border-radius: 18px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
            border: none; overflow: hidden;
        }
        .card-head {
            padding: 18px 22px;
            border-bottom: 1px solid #f5f5f5;
            display: flex; align-items: center; justify-content: space-between;
        }
        .card-head h5 { margin: 0; font-weight: 700; font-size: 16px; color: #1a1f2e; }

        /* ── Table ── */
        .admin-table { width: 100%; border-collapse: separate; border-spacing: 0; }
        .admin-table th {
            background: #f8f9fa; padding: 12px 16px;
            font-size: 11px; font-weight: 700; color: #999;
            text-transform: uppercase; letter-spacing: 0.5px;
            border-bottom: 2px solid #f0f0f0;
        }
        .admin-table td {
            padding: 13px 16px; border-bottom: 1px solid #f8f9fa;
            font-size: 13px; color: #2C3E50; vertical-align: middle;
        }
        .admin-table tr:last-child td { border-bottom: none; }
        .admin-table tr:hover td { background: #fafbff; }

        /* ── Buttons ── */
        .btn-primary-a {
            background: linear-gradient(135deg, #FF6B35, #FF8C42);
            color: white; border: none; border-radius: 10px;
            padding: 9px 20px; font-weight: 600; font-size: 13px;
            text-decoration: none; display: inline-flex;
            align-items: center; gap: 7px; transition: all 0.2s;
        }
        .btn-primary-a:hover {
            color: white; transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(255,107,53,0.4);
        }
        .btn-sm-edit {
            background: #e8f4fd; color: #2980b9; border: none;
            border-radius: 8px; padding: 5px 12px; font-size: 12px;
            font-weight: 600; text-decoration: none; transition: all 0.2s;
            display: inline-flex; align-items: center; gap: 4px;
        }
        .btn-sm-edit:hover { background: #2980b9; color: white; }
        .btn-sm-del {
            background: #fdecea; color: #e74c3c; border: none;
            border-radius: 8px; padding: 5px 12px; font-size: 12px;
            font-weight: 600; cursor: pointer; transition: all 0.2s;
            display: inline-flex; align-items: center; gap: 4px;
        }
        .btn-sm-del:hover { background: #e74c3c; color: white; }
        .btn-sm-view {
            background: #f0f9f4; color: #27ae60; border: none;
            border-radius: 8px; padding: 5px 12px; font-size: 12px;
            font-weight: 600; text-decoration: none; transition: all 0.2s;
            display: inline-flex; align-items: center; gap: 4px;
        }
        .btn-sm-view:hover { background: #27ae60; color: white; }

        /* ── Badges ── */
        .b-active   { background:#e8f8f0; color:#27ae60; padding:4px 12px; border-radius:20px; font-size:11px; font-weight:600; }
        .b-inactive { background:#fef9e7; color:#f39c12; padding:4px 12px; border-radius:20px; font-size:11px; font-weight:600; }
        .b-banned   { background:#fdecea; color:#e74c3c; padding:4px 12px; border-radius:20px; font-size:11px; font-weight:600; }
        .b-pending  { background:#eef2ff; color:#5c6bc0; padding:4px 12px; border-radius:20px; font-size:11px; font-weight:600; }

        /* ── Form ── */
        .form-control, .form-select {
            border: 2px solid #f0f0f0; border-radius: 10px;
            padding: 10px 14px; font-size: 14px; transition: all 0.2s;
        }
        .form-control:focus, .form-select:focus {
            border-color: #FF6B35;
            box-shadow: 0 0 0 3px rgba(255,107,53,0.1);
        }
        .image-upload-area {
            border: 2px dashed #ddd; border-radius: 14px;
            padding: 30px; text-align: center; cursor: pointer;
            transition: all 0.2s; background: #fafafa;
        }
        .image-upload-area:hover { border-color: #FF6B35; background: #fff3ee; }

        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.show { transform: translateX(0); }
            .main-content { margin-left: 0; }
        }
    </style>
    @stack('styles')
</head>
<body>

{{-- ══ SIDEBAR ══ --}}
<div class="sidebar" id="sidebar">

    {{-- Brand --}}
    <div class="sidebar-brand">
        <h5>🛒 Bazaar<span>Hub</span></h5>
        <div class="admin-badge">Super Admin</div>
    </div>

    {{-- Profile --}}
    <div class="admin-profile">
        <div class="admin-avatar">
            {{ strtoupper(substr(auth('web')->user()->name, 0, 1)) }}
        </div>
        <div>
            <p class="admin-name">{{ auth('web')->user()->name }}</p>
            <div class="admin-role">🔐 Full Access</div>
        </div>
    </div>

    {{-- Nav Links --}}
    <nav class="sidebar-nav">

        {{-- OVERVIEW --}}
        <div class="nav-section">Overview</div>
        <a href="{{ route('admin.dashboard') }}"
           class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="bi bi-grid-1x2-fill"></i> Dashboard
        </a>

        {{-- MANAGEMENT --}}
        <div class="nav-section">Management</div>
        <a href="{{ route('admin.vendors.index') }}"
           class="sidebar-link {{ request()->routeIs('admin.vendors.*') ? 'active' : '' }}">
            <i class="bi bi-shop-window"></i> Vendors
            <span class="nav-count">{{ \App\Models\Vendor::count() }}</span>
        </a>
        <a href="{{ route('admin.products.index') }}"
           class="sidebar-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
            <i class="bi bi-box-seam-fill"></i> All Products
            <span class="nav-count">{{ \App\Models\Product::count() }}</span>
        </a>
        <a href="{{ route('admin.customers.index') }}"
           class="sidebar-link {{ request()->routeIs('admin.customers.*') ? 'active' : '' }}">
            <i class="bi bi-people-fill"></i> Customers
            <span class="nav-count">{{ \App\Models\User::where('role','customer')->count() }}</span>
        </a>
        <a href="{{ route('admin.orders.index') }}"
           class="sidebar-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
            <i class="bi bi-bag-check-fill"></i> Orders
            <span class="nav-count">{{ \App\Models\Order::count() }}</span>
        </a>
        <a href="{{ route('admin.categories.index') }}"
           class="sidebar-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
            <i class="bi bi-tags-fill"></i> Categories
            <span class="nav-count">{{ \App\Models\Category::count() }}</span>
        </a>

        {{-- ATTENDANCE --}}
        <div class="nav-section">Attendance</div>
        <a href="{{ route('admin.attendance.index') }}"
           class="sidebar-link {{ request()->routeIs('admin.attendance.index') ? 'active' : '' }}">
            <i class="bi bi-fingerprint"></i> Attendance
            <span class="nav-count">{{ \App\Models\Attendance::whereDate('punch_time', today())->count() }}</span>
        </a>
        <a href="{{ route('admin.attendance.report') }}"
           class="sidebar-link {{ request()->routeIs('admin.attendance.report') ? 'active' : '' }}">
            <i class="bi bi-bar-chart-line-fill"></i> Reports
        </a>

        {{-- ✅ SYSTEM + LOGOUT — always at bottom --}}
        <div class="logout-wrapper">
            <div class="nav-section" style="padding-top:0;">System</div>
            <a href="{{ route('home') }}" class="sidebar-link" target="_blank">
                <i class="bi bi-globe"></i> View Website
            </a>
            <form action="{{ route('admin.logout') }}" method="POST" class="mt-1">
                @csrf
                <button type="submit" class="logout-btn">
                    <i class="bi bi-box-arrow-left"></i>
                    <span>Logout</span>
                </button>
            </form>
        </div>

    </nav>
</div>

{{-- ══ MAIN ══ --}}
<div class="main-content">
    <div class="top-bar">
        <div class="d-flex align-items-center gap-3">
            <button class="btn btn-sm d-md-none" id="sidebarToggle"
                    style="background:#f0f2f8; border:none; border-radius:8px; padding:8px 12px;">
                <i class="bi bi-list fs-5"></i>
            </button>
            <h5 class="page-title">@yield('page-title', 'Dashboard')</h5>
        </div>
        <div class="d-flex align-items-center gap-3">
            <a href="{{ route('home') }}" target="_blank"
               style="background:#fff3ee; color:#FF6B35; border:none; border-radius:25px;
                      padding:8px 18px; font-size:13px; font-weight:600; text-decoration:none;">
                <i class="bi bi-eye me-1"></i> View Store
            </a>
            <div class="d-flex align-items-center gap-2"
                 style="background:#f0f2f8; border-radius:12px; padding:8px 14px;">
                <div style="width:30px;height:30px;background:linear-gradient(135deg,#FF6B35,#FF8C42);
                            border-radius:8px;display:flex;align-items:center;justify-content:center;
                            color:white;font-weight:700;font-size:13px;">
                    {{ strtoupper(substr(auth('web')->user()->name, 0, 1)) }}
                </div>
                <span style="font-size:13px;font-weight:600;color:#1a1f2e;">
                    {{ auth('web')->user()->name }}
                </span>
            </div>
        </div>
    </div>

    {{-- Flash Messages --}}
    <div class="px-4 pt-3">
        @if(session('success'))
        <div class="alert border-0 rounded-3 d-flex align-items-center gap-2 mb-0"
             style="background:#e8f8f0; color:#27ae60; font-size:14px;">
            <i class="bi bi-check-circle-fill fs-5"></i> {{ session('success') }}
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
        @endif
        @if(session('error'))
        <div class="alert border-0 rounded-3 d-flex align-items-center gap-2 mb-0"
             style="background:#fdecea; color:#e74c3c; font-size:14px;">
            <i class="bi bi-exclamation-circle-fill fs-5"></i> {{ session('error') }}
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
        @endif
    </div>

    <div class="p-4">
        @yield('content')
    </div>
</div>

<script>
    document.getElementById('sidebarToggle')?.addEventListener('click', function() {
        document.getElementById('sidebar').classList.toggle('show');
    });
</script>
@stack('scripts')
</body>
</html>