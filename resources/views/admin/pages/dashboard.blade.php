@extends('layouts.admin')
@section('title','Admin Dashboard')
@section('page-title','Dashboard')

@section('content')

{{-- Welcome --}}
<div class="rounded-4 p-4 mb-4 text-white"
     style="background:linear-gradient(135deg,#0f1623,#1a2332);
            box-shadow:0 8px 30px rgba(0,0,0,0.2);">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <h4 class="fw-bold mb-1">
                Welcome back, {{ auth('web')->user()->name }}! 🔐
            </h4>
            <p class="mb-0 opacity-60" style="font-size:14px;">
                BazaarHub Admin Panel — Full platform overview
            </p>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <a href="{{ route('admin.vendors.create') }}" class="btn btn-warning rounded-pill px-3 fw-bold" style="font-size:13px;">
                <i class="bi bi-plus me-1"></i>Add Vendor
            </a>
            <a href="{{ route('admin.products.create') }}" class="btn btn-light rounded-pill px-3 fw-bold" style="font-size:13px;">
                <i class="bi bi-plus me-1"></i>Add Product
            </a>
        </div>
    </div>
</div>

{{-- Stats --}}
<div class="row g-4 mb-4">
    @foreach([
        ['label'=>'Total Customers','value'=>$totalCustomers, 'icon'=>'👥','bg'=>'#fff3ee','trend'=>'Registered users','class'=>'trend-up'],
        ['label'=>'Active Vendors',  'value'=>$activeVendors,  'icon'=>'🏪','bg'=>'#e8f8f0','trend'=>'Selling on platform','class'=>'trend-up'],
        ['label'=>'Total Products',  'value'=>$totalProducts,  'icon'=>'📦','bg'=>'#eef2ff','trend'=>'Listed products','class'=>'trend-info'],
        ['label'=>'Total Orders',    'value'=>$totalOrders,    'icon'=>'🛒','bg'=>'#fef9e7','trend'=>'All time orders','class'=>'trend-info'],
        ['label'=>'Total Vendors',   'value'=>$totalVendors,   'icon'=>'🏬','bg'=>'#fdecea','trend'=>'Registered vendors','class'=>'trend-info'],
        ['label'=>'Categories',      'value'=>$totalCategories,'icon'=>'🗂️','bg'=>'#f0f9f4','trend'=>'Product categories','class'=>'trend-up'],
    ] as $stat)
    <div class="col-6 col-md-4 col-xl-2">
        <div class="stat-card text-center">
            <div class="stat-icon mx-auto mb-2" style="background:{{ $stat['bg'] }}">
                {{ $stat['icon'] }}
            </div>
            <div class="stat-num">{{ $stat['value'] }}</div>
            <div class="stat-label">{{ $stat['label'] }}</div>
            <div class="stat-trend {{ $stat['class'] }}">{{ $stat['trend'] }}</div>
        </div>
    </div>
    @endforeach
</div>

<div class="row g-4">
    {{-- Recent Vendors --}}
    <div class="col-lg-6">
        <div class="content-card h-100">
            <div class="card-head">
                <h5><i class="bi bi-shop me-2" style="color:#FF6B35;"></i>Recent Vendors</h5>
                <a href="{{ route('admin.vendors.index') }}" class="btn-sm-view">View All</a>
            </div>
            <div class="table-responsive">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Vendor</th>
                            <th>Email</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentVendors as $vendor)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div style="width:34px;height:34px;background:linear-gradient(135deg,#FF6B35,#FF8C42);
                                                border-radius:10px;display:flex;align-items:center;justify-content:center;
                                                color:white;font-weight:700;font-size:14px;flex-shrink:0;">
                                        {{ strtoupper(substr($vendor->shop_name,0,1)) }}
                                    </div>
                                    <div>
                                        <div class="fw-semibold" style="font-size:13px;">{{ $vendor->shop_name }}</div>
                                        <div style="font-size:11px;color:#bbb;">{{ $vendor->name }}</div>
                                    </div>
                                </div>
                            </td>
                            <td style="font-size:12px;color:#888;">{{ $vendor->email }}</td>
                            <td>
                                @if($vendor->status==='active') <span class="b-active">Active</span>
                                @elseif($vendor->status==='banned') <span class="b-banned">Banned</span>
                                @else <span class="b-inactive">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.vendors.edit',$vendor->id) }}" class="btn-sm-edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Recent Customers --}}
    <div class="col-lg-6">
        <div class="content-card h-100">
            <div class="card-head">
                <h5><i class="bi bi-people me-2" style="color:#FF6B35;"></i>Recent Customers</h5>
                <a href="{{ route('admin.customers.index') }}" class="btn-sm-view">View All</a>
            </div>
            <div class="table-responsive">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Customer</th>
                            <th>Email</th>
                            <th>Joined</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentCustomers as $customer)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div style="width:34px;height:34px;background:#eef2ff;
                                                border-radius:10px;display:flex;align-items:center;justify-content:center;
                                                color:#5c6bc0;font-weight:700;font-size:14px;flex-shrink:0;">
                                        {{ strtoupper(substr($customer->name,0,1)) }}
                                    </div>
                                    <span class="fw-semibold" style="font-size:13px;">{{ $customer->name }}</span>
                                </div>
                            </td>
                            <td style="font-size:12px;color:#888;">{{ $customer->email }}</td>
                            <td style="font-size:12px;color:#bbb;">
                                {{ $customer->created_at->diffForHumans() }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Recent Products --}}
    <div class="col-12">
        <div class="content-card">
            <div class="card-head">
                <h5><i class="bi bi-box-seam me-2" style="color:#FF6B35;"></i>Recent Products</h5>
                <a href="{{ route('admin.products.index') }}" class="btn-sm-view">View All</a>
            </div>
            <div class="table-responsive">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Vendor</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentProducts as $product)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <img src="{{ $product->thumbnail_url }}"
                                         style="width:42px;height:42px;object-fit:cover;border-radius:10px;"
                                         alt="">
                                    <span class="fw-semibold">{{ Str::limit($product->name,30) }}</span>
                                </div>
                            </td>
                            <td style="font-size:12px;">{{ $product->vendor->shop_name }}</td>
                            <td style="font-size:12px;">{{ $product->category->icon ?? '' }} {{ $product->category->name }}</td>
                            <td class="fw-bold" style="color:#FF6B35;">
                                Rs. {{ number_format($product->sale_price ?? $product->price,0) }}
                            </td>
                            <td>{{ $product->stock }}</td>
                            <td>
                                @if($product->status==='active') <span class="b-active">Active</span>
                                @else <span class="b-inactive">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('admin.products.edit',$product->id) }}" class="btn-sm-edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('admin.products.destroy',$product->id) }}"
                                          method="POST"
                                          onsubmit="return confirm('Delete this product?')">
                                        @csrf @method('DELETE')
                                        <button class="btn-sm-del"><i class="bi bi-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection
