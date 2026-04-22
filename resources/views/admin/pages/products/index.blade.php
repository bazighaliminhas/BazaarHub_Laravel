@extends('layouts.admin')
@section('title','All Products')
@section('page-title','All Products')
@section('content')

<div class="content-card">
    <div class="card-head">
        <h5><i class="bi bi-box-seam me-2" style="color:#FF6B35;"></i>
            All Products
            <span class="badge rounded-pill ms-2 px-3"
                  style="background:#fff3ee;color:#FF6B35;font-size:12px;">
                {{ $products->total() }}
            </span>
        </h5>
        <a href="{{ route('admin.products.create') }}" class="btn-primary-a">
            <i class="bi bi-plus-lg"></i> Add Product
        </a>
    </div>

    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>#</th>
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
                @foreach($products as $i => $product)
                <tr>
                    <td style="color:#bbb;">{{ $products->firstItem() + $i }}</td>
                    <td>
                        <div class="d-flex align-items-center gap-3">
                            <img src="{{ $product->thumbnail_url }}"
                                 style="width:44px;height:44px;object-fit:cover;border-radius:10px;"
                                 alt="{{ $product->name }}">
                            <div>
                                <div class="fw-semibold" style="font-size:13px;">
                                    {{ Str::limit($product->name, 32) }}
                                </div>
                                <div style="font-size:11px;color:#bbb;">
                                    {{ Str::limit($product->description, 35) }}
                                </div>
                            </div>
                        </div>
                    </td>
                    <td style="font-size:13px;">{{ $product->vendor->shop_name }}</td>
                    <td style="font-size:13px;">
                        {{ $product->category->icon ?? '' }} {{ $product->category->name }}
                    </td>
                    <td>
                        <div class="fw-bold" style="color:#FF6B35;">
                            Rs. {{ number_format($product->sale_price ?? $product->price, 0) }}
                        </div>
                        @if($product->sale_price)
                        <div style="text-decoration:line-through;color:#bbb;font-size:11px;">
                            Rs. {{ number_format($product->price, 0) }}
                        </div>
                        @endif
                    </td>
                    <td>
                        <span class="fw-semibold">{{ $product->stock }}</span>
                        <span style="font-size:11px;color:#bbb;"> {{ $product->unit }}</span>
                    </td>
                    <td>
                        @if($product->status === 'active')
                            <span class="b-active">● Active</span>
                        @else
                            <span class="b-inactive">● Inactive</span>
                        @endif
                    </td>
                    <td>
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.products.edit', $product->id) }}"
                               class="btn-sm-edit">
                                <i class="bi bi-pencil"></i> Edit
                            </a>
                            <form action="{{ route('admin.products.destroy', $product->id) }}"
                                  method="POST"
                                  onsubmit="return confirm('Delete this product?')">
                                @csrf @method('DELETE')
                                <button class="btn-sm-del">
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
    <div class="p-4">{{ $products->links() }}</div>
</div>
@endsection
