<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Category;

class DashboardController extends Controller
{
    public function index()
    {
        $vendor         = auth('vendor')->user();
        $totalProducts  = $vendor->products()->count();
        $activeProducts = $vendor->products()->where('status', 'active')->count();
        $totalStock     = $vendor->products()->sum('stock');
        $categoryCount  = $vendor->products()->distinct('category_id')->count('category_id');
        $recentProducts = $vendor->products()->with('category')->latest()->take(5)->get();

        return view('vendor.pages.dashboard', compact(
            'totalProducts', 'activeProducts',
            'totalStock', 'categoryCount', 'recentProducts'
        ));
    }
}
