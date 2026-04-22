@extends('layouts.customer')

@section('content')
<div class="container py-5 text-center">
    <div class="card shadow border-0 p-5">
        <h1 class="text-success">Payment Successful!</h1>
        <p>Order Number: <strong>{{ $order->order_number }}</strong></p>
        <p>Thank you for shopping at BazaarHub.</p>
        <a href="{{ route('home') }}" class="btn btn-primary">Return Home</a>
    </div>
</div>
@endsection