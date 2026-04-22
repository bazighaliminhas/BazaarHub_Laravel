@extends('layouts.admin')
@section('title','Customers')
@section('page-title','All Customers')
@section('content')
<div class="content-card">
    <div class="card-head">
        <h5><i class="bi bi-people me-2" style="color:#FF6B35;"></i>Customers ({{ $customers->total() }})</h5>
    </div>
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr><th>#</th><th>Customer</th><th>Email</th><th>Orders</th><th>Joined</th></tr>
            </thead>
            <tbody>
                @foreach($customers as $i => $c)
                <tr>
                    <td style="color:#bbb;">{{ $customers->firstItem()+$i }}</td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div style="width:36px;height:36px;background:#eef2ff;border-radius:10px;
                                        display:flex;align-items:center;justify-content:center;
                                        color:#5c6bc0;font-weight:700;font-size:14px;">
                                {{ strtoupper(substr($c->name,0,1)) }}
                            </div>
                            <span class="fw-semibold">{{ $c->name }}</span>
                        </div>
                    </td>
                    <td style="font-size:13px;color:#888;">{{ $c->email }}</td>
                    <td><span class="fw-bold" style="color:#FF6B35;">{{ $c->orders_count }}</span></td>
                    <td style="font-size:12px;color:#bbb;">{{ $c->created_at->format('d M Y') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="p-4">{{ $customers->links() }}</div>
</div>
@endsection
