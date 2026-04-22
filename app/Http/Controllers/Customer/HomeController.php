<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        $products = Product::with(['vendor', 'category'])
            ->where('status', 'active')
            ->latest()
            ->paginate(12);

        return view('customer.pages.home', compact('products'));
    }

    public function category($slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();
        $products = Product::with(['vendor', 'category'])
            ->where('category_id', $category->id)
            ->where('status', 'active')
            ->latest()
            ->paginate(12);

        return view('customer.pages.home', compact('products', 'category'));
    }

    public function show($slug)
    {
        $product = Product::with(['vendor', 'category', 'images'])
            ->where('slug', $slug)
            ->firstOrFail();

        return view('customer.pages.product', compact('product'));
    }
}
