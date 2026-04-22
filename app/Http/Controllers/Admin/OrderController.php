<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['user'])->latest()->paginate(15);
        return view('admin.pages.orders.index', compact('orders'));
    }
}
