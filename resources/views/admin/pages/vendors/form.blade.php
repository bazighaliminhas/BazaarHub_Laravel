@extends('layouts.admin')
@section('title', isset($vendor) ? 'Edit Vendor' : 'Add Vendor')
@section('page-title', isset($vendor) ? 'Edit Vendor' : 'Add New Vendor')
@section('content')
<div class="row justify-content-center">
<div class="col-lg-7">
<div class="content-card">
    <div class="card-head">
        <h5><i class="bi bi-shop me-2" style="color:#FF6B35;"></i>
            {{ isset($vendor) ? 'Edit Vendor Details' : 'New Vendor Account' }}
        </h5>
    </div>
    <div class="p-4">
        @if($errors->any())
        <div class="alert border-0 rounded-3 mb-4" style="background:#fdecea;color:#e74c3c;font-size:14px;">
            @foreach($errors->all() as $e)<div><i class="bi bi-x-circle me-1"></i>{{ $e }}</div>@endforeach
        </div>
        @endif

        <form action="{{ isset($vendor) ? route('admin.vendors.update',$vendor->id) : route('admin.vendors.store') }}"
              method="POST">
            @csrf
            @if(isset($vendor)) @method('PUT') @endif

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Shop Name *</label>
                    <input type="text" name="shop_name" class="form-control"
                           value="{{ old('shop_name',$vendor->shop_name??'') }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Owner Name *</label>
                    <input type="text" name="name" class="form-control"
                           value="{{ old('name',$vendor->name??'') }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Email *</label>
                    <input type="email" name="email" class="form-control"
                           value="{{ old('email',$vendor->email??'') }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Phone</label>
                    <input type="text" name="phone" class="form-control"
                           value="{{ old('phone',$vendor->phone??'') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">
                        Password {{ isset($vendor) ? '(leave blank to keep)' : '*' }}
                    </label>
                    <input type="password" name="password" class="form-control"
                           {{ isset($vendor) ? '' : 'required' }}>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Status *</label>
                    <select name="status" class="form-select" required>
                        @foreach(['active'=>'✅ Active','inactive'=>'⏸️ Inactive','banned'=>'🚫 Banned'] as $val=>$label)
                        <option value="{{ $val }}"
                            {{ old('status',$vendor->status??'active')===$val?'selected':'' }}>
                            {{ $label }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold">Description</label>
                    <textarea name="description" class="form-control" rows="3">{{ old('description',$vendor->description??'') }}</textarea>
                </div>
            </div>

            <div class="d-flex gap-3 mt-4">
                <button type="submit" class="btn-primary-a px-5">
                    <i class="bi bi-{{ isset($vendor)?'check-lg':'plus-lg' }}"></i>
                    {{ isset($vendor) ? 'Update Vendor' : 'Create Vendor' }}
                </button>
                <a href="{{ route('admin.vendors.index') }}" class="btn btn-light rounded-3 px-4 fw-semibold">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
</div>
</div>
@endsection
