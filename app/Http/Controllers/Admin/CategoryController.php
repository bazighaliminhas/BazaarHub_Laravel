<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('products')->get();
        return view('admin.pages.categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:categories,name',
            'icon' => 'nullable|string|max:10',
        ]);

        Category::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'icon' => $request->icon,
        ]);

        return back()->with('success', '✅ Category added!');
    }

    public function destroy(Category $category)
    {
        // ✅ Step 1: Har product ke order_items delete karo
        foreach ($category->products as $product) {
            $product->orderItems()->delete();
        }

        // ✅ Step 2: Category ke saare products delete karo
        $category->products()->delete();

        // ✅ Step 3: Ab category delete karo
        $category->delete();

        return back()->with('success', '🗑️ Category deleted!');
    }
}
