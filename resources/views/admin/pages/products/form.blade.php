@extends('layouts.admin')
@section('title', isset($product) ? 'Edit Product' : 'Add Product')
@section('page-title', isset($product) ? 'Edit Product' : 'Add New Product')
@section('content')

<div class="row g-4">
<div class="col-lg-8">
<div class="content-card">
    <div class="card-head">
        <h5><i class="bi bi-box-seam me-2" style="color:#FF6B35;"></i>
            {{ isset($product) ? 'Edit Product' : 'New Product' }}
        </h5>
    </div>
    <div class="p-4">

        @if($errors->any())
        <div class="alert border-0 rounded-3 mb-4"
             style="background:#fdecea;color:#e74c3c;font-size:14px;">
            <ul class="mb-0">
                @foreach($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ isset($product)
                ? route('admin.products.update', $product->id)
                : route('admin.products.store') }}"
              method="POST" enctype="multipart/form-data">
            @csrf
            @if(isset($product)) @method('PUT') @endif

            <div class="row g-3">
                {{-- Vendor --}}
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Vendor *</label>
                    <select name="vendor_id" class="form-select" required>
                        <option value="">-- Select Vendor --</option>
                        @foreach($vendors as $v)
                        <option value="{{ $v->id }}"
                            {{ old('vendor_id', $product->vendor_id ?? '') == $v->id ? 'selected' : '' }}>
                            {{ $v->shop_name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                {{-- Category --}}
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Category *</label>
                    <select name="category_id" class="form-select" required>
                        <option value="">-- Select Category --</option>
                        @foreach($categories as $cat)
                        <option value="{{ $cat->id }}"
                            {{ old('category_id', $product->category_id ?? '') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->icon ?? '' }} {{ $cat->name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                {{-- Name --}}
                <div class="col-12">
                    <label class="form-label fw-semibold">Product Name *</label>
                    <input type="text" name="name" class="form-control"
                           value="{{ old('name', $product->name ?? '') }}"
                           placeholder="e.g. Fresh Organic Tomatoes" required>
                </div>

                {{-- Description --}}
                <div class="col-12">
                    <label class="form-label fw-semibold">Description</label>
                    <textarea name="description" class="form-control" rows="3"
                              placeholder="Product description...">{{ old('description', $product->description ?? '') }}</textarea>
                </div>

                {{-- Price --}}
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Regular Price (Rs.) *</label>
                    <input type="number" name="price" class="form-control"
                           value="{{ old('price', $product->price ?? '') }}"
                           placeholder="0" min="0" step="0.01" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Sale Price (Rs.)</label>
                    <input type="number" name="sale_price" class="form-control"
                           value="{{ old('sale_price', $product->sale_price ?? '') }}"
                           placeholder="Optional" min="0" step="0.01">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Stock *</label>
                    <input type="number" name="stock" class="form-control"
                           value="{{ old('stock', $product->stock ?? '') }}"
                           placeholder="0" min="0" required>
                </div>

                {{-- Unit & Status --}}
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Unit</label>
                    <select name="unit" class="form-select">
                        @foreach(['piece','kg','gram','liter','dozen','pair','bunch','box','meter'] as $unit)
                        <option value="{{ $unit }}"
                            {{ old('unit', $product->unit ?? 'piece') == $unit ? 'selected' : '' }}>
                            {{ ucfirst($unit) }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Status</label>
                    <select name="status" class="form-select">
                        <option value="active"
                            {{ old('status', $product->status ?? 'active') == 'active' ? 'selected' : '' }}>
                            ✅ Active
                        </option>
                        <option value="inactive"
                            {{ old('status', $product->status ?? '') == 'inactive' ? 'selected' : '' }}>
                            ⏸️ Inactive
                        </option>
                    </select>
                </div>

                {{-- Image Upload --}}
                <div class="col-12">
                    <label class="form-label fw-semibold">
                        Product Image {{ isset($product) ? '' : '*' }}
                    </label>
                    <div class="image-upload-area"
                         onclick="document.getElementById('thumbnail').click()">
                        <div id="uploadPlaceholder">
                            <i class="bi bi-cloud-arrow-up"
                               style="font-size:36px;color:#FF6B35;"></i>
                            <p class="fw-semibold mt-2 mb-1" style="color:#2C3E50;">
                                Click to upload image
                            </p>
                            <p class="text-muted small mb-0">PNG, JPG, WEBP — max 5MB</p>
                        </div>
                        <img id="imagePreview" src="" alt="Preview"
                             style="display:none;max-height:180px;
                                    border-radius:10px;object-fit:cover;">
                    </div>
                    <input type="file" name="thumbnail" id="thumbnail"
                           accept="image/*" style="display:none"
                           {{ isset($product) ? '' : 'required' }}>

                    @if(isset($product) && $product->thumbnail)
                    <div class="mt-3 d-flex align-items-center gap-3">
                        <img src="{{ $product->thumbnail_url }}"
                             style="width:65px;height:65px;object-fit:cover;border-radius:10px;">
                        <div>
                            <div class="fw-semibold" style="font-size:13px;">Current Image</div>
                            <div class="text-muted" style="font-size:12px;">
                                Upload new to replace
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <div class="d-flex gap-3 mt-4">
                <button type="submit" class="btn-primary-a px-5">
                    <i class="bi bi-{{ isset($product) ? 'check-lg' : 'plus-lg' }}"></i>
                    {{ isset($product) ? 'Update Product' : 'Add Product' }}
                </button>
                <a href="{{ route('admin.products.index') }}"
                   class="btn btn-light rounded-3 px-4 fw-semibold">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
</div>

{{-- Tips --}}
<div class="col-lg-4">
    <div class="content-card">
        <div class="card-head"><h5>💡 Admin Tips</h5></div>
        <div class="p-4">
            <div class="d-flex flex-column gap-3">
                @foreach([
                    ['🏪','Assign Vendor','Always assign products to the correct vendor.'],
                    ['🏷️','Set Category','Correct category improves search visibility.'],
                    ['💰','Sale Price','Leave blank if no discount is active.'],
                    ['📸','HD Image','Use square images (500×500px) for best results.'],
                    ['📦','Stock Count','Keep stock accurate to avoid overselling.'],
                ] as $tip)
                <div class="d-flex gap-3 align-items-start">
                    <div style="font-size:20px;flex-shrink:0;">{{ $tip[0] }}</div>
                    <div>
                        <div class="fw-semibold" style="font-size:13px;">{{ $tip[1] }}</div>
                        <div class="text-muted" style="font-size:12px;">{{ $tip[2] }}</div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
</div>

@endsection

@push('scripts')
<script>
document.getElementById('thumbnail').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = function(e) {
        const preview = document.getElementById('imagePreview');
        const placeholder = document.getElementById('uploadPlaceholder');
        preview.src = e.target.result;
        preview.style.display = 'block';
        placeholder.style.display = 'none';
    };
    reader.readAsDataURL(file);
});
</script>
@endpush
