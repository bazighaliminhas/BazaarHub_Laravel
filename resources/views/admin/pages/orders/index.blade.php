@extends('layouts.admin')
@section('title','Orders')
@section('page-title','All Orders')
@section('content')
<div class="content-card">
    <div class="card-head">
        <h5><i class="bi bi-bag-check me-2" style="color:#FF6B35;"></i>
            Orders ({{ $orders->total() }})
        </h5>
    </div>

    @if($orders->isEmpty())
    <div class="text-center py-5">
        <div style="font-size:60px;">🛒</div>
        <h6 class="text-muted mt-3">No orders placed yet.</h6>
    </div>
    @else
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Order #</th>
                    <th>Customer</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                @php
                    $colors = [
                        'pending'    => ['bg'=>'#fef9e7','color'=>'#f39c12'],
                        'processing' => ['bg'=>'#eef2ff','color'=>'#5c6bc0'],
                        'shipped'    => ['bg'=>'#e8f4fd','color'=>'#2980b9'],
                        'delivered'  => ['bg'=>'#e8f8f0','color'=>'#27ae60'],
                        'cancelled'  => ['bg'=>'#fdecea','color'=>'#e74c3c'],
                    ];
                    $c = $colors[$order->status] ?? ['bg'=>'#f8f9fa','color'=>'#888'];
                @endphp
                <tr>
                    <td class="fw-bold" style="color:#FF6B35;">
                        #{{ $order->order_number ?? $order->id }}
                    </td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div style="width:34px;height:34px;background:#eef2ff;border-radius:10px;
                                        display:flex;align-items:center;justify-content:center;
                                        color:#5c6bc0;font-weight:700;font-size:13px;flex-shrink:0;">
                                {{ strtoupper(substr($order->user->name ?? 'G', 0, 1)) }}
                            </div>
                            <span class="fw-semibold" style="font-size:13px;">
                                {{ $order->user->name ?? 'Guest' }}
                            </span>
                        </div>
                    </td>
                    <td class="fw-bold" style="color:#FF6B35;">
                        Rs. {{ number_format($order->total, 0) }}
                    </td>
                    <td>
                        <span style="background:{{ $c['bg'] }};color:{{ $c['color'] }};
                                     padding:4px 12px;border-radius:20px;
                                     font-size:11px;font-weight:600;">
                            ● {{ ucfirst($order->status) }}
                        </span>
                    </td>
                    <td style="font-size:12px;color:#bbb;">
                        {{ $order->created_at->format('d M Y, h:i A') }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="p-4">{{ $orders->links() }}</div>
    @endif
</div>
@endsection
