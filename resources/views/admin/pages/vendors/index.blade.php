@extends('layouts.admin')
@section('title','Vendors')
@section('page-title','Manage Vendors')
@section('content')
<div class="content-card">
    <div class="card-head">
        <h5><i class="bi bi-shop me-2" style="color:#FF6B35;"></i>All Vendors ({{ $vendors->total() }})</h5>
        <a href="{{ route('admin.vendors.create') }}" class="btn-primary-a">
            <i class="bi bi-plus-lg"></i> Add Vendor
        </a>
    </div>
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr><th>#</th><th>Vendor</th><th>Email</th><th>Phone</th><th>Products</th><th>Status</th><th>Actions</th></tr>
            </thead>
            <tbody>
                @foreach($vendors as $i => $vendor)
                <tr>
                    <td style="color:#bbb;">{{ $vendors->firstItem()+$i }}</td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div style="width:38px;height:38px;background:linear-gradient(135deg,#FF6B35,#FF8C42);
                                        border-radius:10px;display:flex;align-items:center;justify-content:center;
                                        color:white;font-weight:700;font-size:15px;flex-shrink:0;">
                                {{ strtoupper(substr($vendor->shop_name,0,1)) }}
                            </div>
                            <div>
                                <div class="fw-semibold">{{ $vendor->shop_name }}</div>
                                <div style="font-size:11px;color:#bbb;">{{ $vendor->name }}</div>
                            </div>
                        </div>
                    </td>
                    <td style="font-size:13px;color:#888;">{{ $vendor->email }}</td>
                    <td style="font-size:13px;">{{ $vendor->phone ?? '—' }}</td>
                    <td><span class="fw-bold" style="color:#FF6B35;">{{ $vendor->products_count }}</span></td>
                    <td>
                        @if($vendor->status==='active') <span class="b-active">● Active</span>
                        @elseif($vendor->status==='banned') <span class="b-banned">● Banned</span>
                        @else <span class="b-inactive">● Inactive</span>
                        @endif
                    </td>
                    <td>
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.vendors.edit',$vendor->id) }}" class="btn-sm-edit">
                                <i class="bi bi-pencil"></i> Edit
                            </a>
                            <form action="{{ route('admin.vendors.destroy',$vendor->id) }}"
                                  method="POST" onsubmit="return confirm('Delete vendor and all their products?')">
                                @csrf @method('DELETE')
                                <button class="btn-sm-del"><i class="bi bi-trash"></i> Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="p-4">{{ $vendors->links() }}</div>
</div>
@endsection
