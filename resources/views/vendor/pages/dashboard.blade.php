@extends('layouts.vendor')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')

{{-- Welcome Banner --}}
<div class="rounded-4 p-4 mb-4 text-white"
     style="background: linear-gradient(135deg, #FF6B35, #FF8C42);
            box-shadow: 0 8px 30px rgba(255,107,53,0.3);">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <h4 class="fw-bold mb-1">
                Good {{ date('H') < 12 ? 'Morning' : (date('H') < 17 ? 'Afternoon' : 'Evening') }},
                {{ auth('vendor')->user()->name }}! 👋
            </h4>
            <p class="mb-0 opacity-75">
                Here's what's happening with <strong>{{ auth('vendor')->user()->shop_name }}</strong> today.
            </p>
        </div>
        <a href="{{ route('vendor.products.create') }}" class="btn btn-light fw-bold rounded-pill px-4">
            <i class="bi bi-plus-lg me-2"></i>Add Product
        </a>
    </div>
</div>

{{-- Stats --}}
<div class="row g-4 mb-4">
    <div class="col-6 col-xl-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label mb-2">Total Products</div>
                    <div class="stat-number">{{ $totalProducts }}</div>
                </div>
                <div class="stat-icon" style="background:#fff3ee;">📦</div>
            </div>
            <div class="mt-2" style="font-size:12px; color:#27ae60;">
                <i class="bi bi-arrow-up-short"></i> Active in store
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label mb-2">Active Products</div>
                    <div class="stat-number">{{ $activeProducts }}</div>
                </div>
                <div class="stat-icon" style="background:#e8f8f0;">✅</div>
            </div>
            <div class="mt-2" style="font-size:12px; color:#27ae60;">
                <i class="bi bi-eye"></i> Visible to customers
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label mb-2">Total Stock</div>
                    <div class="stat-number">{{ $totalStock }}</div>
                </div>
                <div class="stat-icon" style="background:#eef2ff;">🏪</div>
            </div>
            <div class="mt-2" style="font-size:12px; color:#888;">
                <i class="bi bi-box"></i> Units available
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label mb-2">Categories</div>
                    <div class="stat-number">{{ $categoryCount }}</div>
                </div>
                <div class="stat-icon" style="background:#fef9e7;">🗂️</div>
            </div>
            <div class="mt-2" style="font-size:12px; color:#888;">
                <i class="bi bi-tag"></i> Product categories
            </div>
        </div>
    </div>
</div>

{{-- Recent Products --}}
<div class="content-card">
    <div class="content-card-header">
        <h5><i class="bi bi-box-seam me-2" style="color:#FF6B35;"></i>Recent Products</h5>
        <a href="{{ route('vendor.products.create') }}" class="btn-primary-v">
            <i class="bi bi-plus-lg"></i> Add Product
        </a>
    </div>
    <div class="content-card-body p-0">
        @if($recentProducts->isEmpty())
        <div class="text-center py-5">
            <div style="font-size:60px;">📦</div>
            <h6 class="text-muted mt-3">No products yet. Add your first product!</h6>
            <a href="{{ route('vendor.products.create') }}" class="btn-primary-v mt-3 d-inline-flex">
                <i class="bi bi-plus-lg"></i> Add First Product
            </a>
        </div>
        @else
        <div class="table-responsive">
            <table class="vendor-table">
                <thead>
                    <tr>
                        <th>Product</th>
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
                                     class="product-thumb" alt="{{ $product->name }}">
                                <div>
                                    <div class="fw-semibold">{{ Str::limit($product->name, 30) }}</div>
                                    <div style="font-size:12px; color:#888;">
                                        Rs. {{ number_format($product->price, 0) }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span style="font-size:13px;">
                                {{ $product->category->icon ?? '' }} {{ $product->category->name }}
                            </span>
                        </td>
                        <td>
                            @if($product->sale_price)
                                <div class="fw-bold" style="color:#FF6B35;">
                                    Rs. {{ number_format($product->sale_price, 0) }}
                                </div>
                                <div style="text-decoration:line-through; color:#bbb; font-size:12px;">
                                    Rs. {{ number_format($product->price, 0) }}
                                </div>
                            @else
                                <div class="fw-bold" style="color:#FF6B35;">
                                    Rs. {{ number_format($product->price, 0) }}
                                </div>
                            @endif
                        </td>
                        <td>
                            <span class="fw-semibold">{{ $product->stock }}</span>
                            <span style="font-size:12px; color:#888;"> {{ $product->unit }}</span>
                        </td>
                        <td>
                            @if($product->status === 'active')
                                <span class="badge-active">● Active</span>
                            @else
                                <span class="badge-inactive">● Inactive</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex gap-2">
                                <a href="{{ route('vendor.products.edit', $product->id) }}"
                                   class="btn-edit">
                                    <i class="bi bi-pencil"></i> Edit
                                </a>
                                <form action="{{ route('vendor.products.destroy', $product->id) }}"
                                      method="POST"
                                      onsubmit="return confirm('Delete this product?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-delete">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
</div>

@endsection
