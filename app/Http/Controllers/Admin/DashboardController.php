<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Vendor;
use App\Models\Product;
use App\Models\Order;
use App\Models\Category;

class DashboardController extends Controller
{
    public function index()
    {
        $totalCustomers  = User::where('role', 'customer')->count();
        $totalVendors    = Vendor::count();
        $totalProducts   = Product::count();
        $totalOrders     = Order::count();
        $totalCategories = Category::count();
        $activeVendors   = Vendor::where('status', 'active')->count();

        $recentVendors   = Vendor::latest()->take(5)->get();
        $recentProducts  = Product::with(['vendor','category'])->latest()->take(6)->get();
        $recentCustomers = User::where('role','customer')->latest()->take(5)->get();

        return view('admin.pages.dashboard', compact(
            'totalCustomers','totalVendors','totalProducts',
            'totalOrders','totalCategories','activeVendors',
            'recentVendors','recentProducts','recentCustomers'
        ));
    }
}
