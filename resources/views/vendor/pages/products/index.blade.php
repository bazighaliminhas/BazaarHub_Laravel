@extends('layouts.vendor')
@section('title', 'My Products')
@section('page-title', 'My Products')

@section('content')

<div class="content-card">
    <div class="content-card-header">
        <h5><i class="bi bi-box-seam me-2" style="color:#FF6B35;"></i>
            All Products
            <span class="badge rounded-pill ms-2 px-3"
                  style="background:#fff3ee; color:#FF6B35; font-size:12px;">
                {{ $products->total() }}
            </span>
        </h5>
        <a href="{{ route('vendor.products.create') }}" class="btn-primary-v">
            <i class="bi bi-plus-lg"></i> Add New Product
        </a>
    </div>

    <div class="content-card-body p-0">
        @if($products->isEmpty())
        <div class="text-center py-5">
            <div style="font-size:60px;">📦</div>
            <h6 class="text-muted mt-3">No products found.</h6>
            <a href="{{ route('vendor.products.create') }}" class="btn-primary-v mt-3 d-inline-flex">
                <i class="bi bi-plus-lg"></i> Add First Product
            </a>
        </div>
        @else
        <div class="table-responsive">
            <table class="vendor-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Product</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $i => $product)
                    <tr>
                        <td style="color:#bbb; font-size:13px;">
                            {{ $products->firstItem() + $i }}
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <img src="{{ $product->thumbnail_url }}"
                                     class="product-thumb" alt="{{ $product->name }}">
                                <div>
                                    <div class="fw-semibold">{{ Str::limit($product->name, 35) }}</div>
                                    <div style="font-size:11px; color:#bbb;">
                                        {{ Str::limit($product->description, 40) }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td>{{ $product->category->icon ?? '' }} {{ $product->category->name }}</td>
                        <td>
                            <div class="fw-bold" style="color:#FF6B35;">
                                Rs. {{ number_format($product->sale_price ?? $product->price, 0) }}
                            </div>
                            @if($product->sale_price)
                            <div style="text-decoration:line-through; color:#bbb; font-size:11px;">
                                Rs. {{ number_format($product->price, 0) }}
                            </div>
                            @endif
                        </td>
                        <td>{{ $product->stock }} {{ $product->unit }}</td>
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
                                      onsubmit="return confirm('Are you sure you want to delete this product?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-delete">
                                        <i class="bi bi-trash"></i> Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="p-4">
            {{ $products->links() }}
        </div>
        @endif
    </div>
</div>

@endsection
