<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index()
    {
        $products = auth('vendor')->user()
            ->products()
            ->with('category')
            ->latest()
            ->paginate(10);

        return view('vendor.pages.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('vendor.pages.products.form', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:200',
            'category_id' => 'required|exists:categories,id',
            'price'       => 'required|numeric|min:0',
            'sale_price'  => 'nullable|numeric|min:0|lt:price',
            'stock'       => 'required|integer|min:0',
            'thumbnail'   => 'required|image|mimes:jpg,jpeg,png,webp|max:5120',
            'status'      => 'required|in:active,inactive',
        ]);

        $thumbnailPath = $request->file('thumbnail')
            ->store('products', 'public');

        Product::create([
            'vendor_id'   => auth('vendor')->id(),
            'category_id' => $request->category_id,
            'name'        => $request->name,
            'slug'        => $this->uniqueSlug($request->name),
            'description' => $request->description,
            'price'       => $request->price,
            'sale_price'  => $request->sale_price ?: null,
            'stock'       => $request->stock,
            'unit'        => $request->unit,
            'thumbnail'   => $thumbnailPath,
            'status'      => $request->status,
        ]);

        return redirect()->route('vendor.products.index')
            ->with('success', '🎉 Product added successfully!');
    }

    public function edit(Product $product)
    {
        if ($product->vendor_id !== auth('vendor')->id()) {
            abort(403, 'Unauthorized.');
        }

        $categories = Category::all();
        return view('vendor.pages.products.form', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        if ($product->vendor_id !== auth('vendor')->id()) {
            abort(403, 'Unauthorized.');
        }

        $request->validate([
            'name'        => 'required|string|max:200',
            'category_id' => 'required|exists:categories,id',
            'price'       => 'required|numeric|min:0',
            'sale_price'  => 'nullable|numeric|min:0',
            'stock'       => 'required|integer|min:0',
            'thumbnail'   => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'status'      => 'required|in:active,inactive',
        ]);

        $data = [
            'category_id' => $request->category_id,
            'name'        => $request->name,
            'slug'        => $this->uniqueSlug($request->name, $product->id),
            'description' => $request->description,
            'price'       => $request->price,
            'sale_price'  => $request->sale_price ?: null,
            'stock'       => $request->stock,
            'unit'        => $request->unit,
            'status'      => $request->status,
        ];

        if ($request->hasFile('thumbnail')) {
            if ($product->thumbnail && !str_starts_with($product->thumbnail, 'http')) {
                \Storage::disk('public')->delete($product->thumbnail);
            }
            $data['thumbnail'] = $request->file('thumbnail')->store('products', 'public');
        }

        $product->update($data);

        return redirect()->route('vendor.products.index')
            ->with('success', '✅ Product updated successfully!');
    }

    public function destroy(Product $product)
    {
        if ($product->vendor_id !== auth('vendor')->id()) {
            abort(403, 'Unauthorized.');
        }

        // ✅ Step 1: Pehle related order_items delete karo
        $product->orderItems()->delete();

        // ✅ Step 2: Thumbnail delete karo
        if ($product->thumbnail && !str_starts_with($product->thumbnail, 'http')) {
            \Storage::disk('public')->delete($product->thumbnail);
        }

        // ✅ Step 3: Ab product delete karo
        $product->delete();

        return redirect()->route('vendor.products.index')
            ->with('success', '🗑️ Product deleted successfully!');
    }

    private function uniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $slug = Str::slug($name);
        $count = 1;
        while (
            Product::where('slug', $slug)
                ->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = Str::slug($name) . '-' . $count++;
        }
        return $slug;
    }
}
