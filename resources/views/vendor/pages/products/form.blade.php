@extends('layouts.vendor')
@section('title', isset($product) ? 'Edit Product' : 'Add New Product')
@section('page-title', isset($product) ? 'Edit Product' : 'Add New Product')

@section('content')

<div class="row g-4">

    {{-- Main Form --}}
    <div class="col-lg-8">
        <div class="content-card">
            <div class="content-card-header">
                <h5>
                    <i class="bi bi-{{ isset($product) ? 'pencil' : 'plus-circle' }} me-2"
                       style="color:#FF6B35;"></i>
                    {{ isset($product) ? 'Edit Product Details' : 'Product Information' }}
                </h5>
            </div>
            <div class="content-card-body">

                @if($errors->any())
                <div class="alert border-0 rounded-3 mb-4"
                     style="background:#fdecea; color:#e74c3c; font-size:14px;">
                    <i class="bi bi-exclamation-circle me-2"></i>
                    <strong>Please fix these errors:</strong>
                    <ul class="mb-0 mt-2">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <form action="{{ isset($product)
                    ? route('vendor.products.update', $product->id)
                    : route('vendor.products.store') }}"
                      method="POST" enctype="multipart/form-data">
                    @csrf
                    @if(isset($product)) @method('PUT') @endif

                    {{-- Product Name --}}
                    <div class="mb-4">
                        <label class="form-label">Product Name <span class="text-danger">*</span></label>
                        <input type="text" name="name"
                               class="form-control"
                               value="{{ old('name', $product->name ?? '') }}"
                               placeholder="e.g. Fresh Organic Tomatoes"
                               required>
                    </div>

                    {{-- Category --}}
                    <div class="mb-4">
                        <label class="form-label">Category <span class="text-danger">*</span></label>
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

                    {{-- Description --}}
                    <div class="mb-4">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="4"
                                  placeholder="Describe your product...">{{ old('description', $product->description ?? '') }}</textarea>
                    </div>

                    {{-- Price Row --}}
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <label class="form-label">Regular Price (Rs.) <span class="text-danger">*</span></label>
                            <input type="number" name="price" class="form-control"
                                   value="{{ old('price', $product->price ?? '') }}"
                                   placeholder="0" min="0" step="0.01" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Sale Price (Rs.)</label>
                            <input type="number" name="sale_price" class="form-control"
                                   value="{{ old('sale_price', $product->sale_price ?? '') }}"
                                   placeholder="Leave empty if no sale" min="0" step="0.01">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Stock Quantity <span class="text-danger">*</span></label>
                            <input type="number" name="stock" class="form-control"
                                   value="{{ old('stock', $product->stock ?? '') }}"
                                   placeholder="0" min="0" required>
                        </div>
                    </div>

                    {{-- Unit & Status --}}
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label">Unit</label>
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
                            <label class="form-label">Status</label>
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
                    </div>

                    {{-- Thumbnail Upload (COMPULSORY) --}}
                    <div class="mb-4">
                        <label class="form-label">
                            Main Product Image <span class="text-danger">*</span>
                            <span style="font-size:12px; color:#888;">(HD quality recommended)</span>
                        </label>

                        <div class="image-upload-area" id="uploadArea"
                             onclick="document.getElementById('thumbnail').click()">
                            <div id="uploadPlaceholder">
                                <i class="bi bi-cloud-arrow-up"
                                   style="font-size:40px; color:#FF6B35;"></i>
                                <p class="fw-semibold mt-2 mb-1" style="color:#2C3E50;">
                                    Click to upload main image
                                </p>
                                <p class="text-muted small mb-0">
                                    PNG, JPG, WEBP up to 5MB — HD quality preferred
                                </p>
                            </div>
                            <img id="imagePreview" src="" alt="Preview"
                                 style="display:none; max-height:200px;
                                        border-radius:10px; object-fit:cover;">
                        </div>
                        <input type="file" name="thumbnail" id="thumbnail"
                               accept="image/*" style="display:none"
                               {{ isset($product) ? '' : 'required' }}>

                        {{-- Show existing image on edit --}}
                        @if(isset($product) && $product->thumbnail)
                        <div class="mt-3 d-flex align-items-center gap-3">
                            <img src="{{ $product->thumbnail_url }}"
                                 style="width:70px;height:70px;object-fit:cover;border-radius:10px;"
                                 alt="Current">
                            <div>
                                <div class="fw-semibold" style="font-size:13px;">Current Image</div>
                                <div class="text-muted" style="font-size:12px;">
                                    Upload new image to replace
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>

                    {{-- Submit --}}
                    <div class="d-flex gap-3">
                        <button type="submit" class="btn-primary-v px-5">
                            <i class="bi bi-{{ isset($product) ? 'check-lg' : 'plus-lg' }}"></i>
                            {{ isset($product) ? 'Update Product' : 'Add Product' }}
                        </button>
                        <a href="{{ route('vendor.products.index') }}"
                           class="btn btn-light rounded-3 px-4 fw-semibold">
                            Cancel
                        </a>
                    </div>

                </form>
            </div>
        </div>
    </div>

    {{-- Tips Sidebar --}}
    <div class="col-lg-4">
        <div class="content-card">
            <div class="content-card-header">
                <h5>💡 Tips for Great Listings</h5>
            </div>
            <div class="content-card-body">
                <div class="d-flex flex-column gap-3">
                    @foreach([
                        ['icon'=>'📸','title'=>'HD Images','desc'=>'Use clear, bright photos on white or clean backgrounds.'],
                        ['icon'=>'✍️','title'=>'Clear Title','desc'=>'Be specific: "Fresh Organic Tomatoes 1kg" not just "Tomatoes".'],
                        ['icon'=>'💰','title'=>'Competitive Price','desc'=>'Check similar products and price competitively.'],
                        ['icon'=>'📦','title'=>'Accurate Stock','desc'=>'Keep stock updated to avoid order issues.'],
                        ['icon'=>'🏷️','title'=>'Use Sale Price','desc'=>'Sale badges attract more customers to click.'],
                    ] as $tip)
                    <div class="d-flex gap-3 align-items-start">
                        <div style="font-size:22px; flex-shrink:0;">{{ $tip['icon'] }}</div>
                        <div>
                            <div class="fw-semibold" style="font-size:13px;">{{ $tip['title'] }}</div>
                            <div class="text-muted" style="font-size:12px;">{{ $tip['desc'] }}</div>
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
// Image preview on select
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
