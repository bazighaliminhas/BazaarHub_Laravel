<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Vendor;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['vendor','category'])->latest()->paginate(12);
        return view('admin.pages.products.index', compact('products'));
    }

    public function create()
    {
        $vendors    = Vendor::where('status','active')->get();
        $categories = Category::all();
        return view('admin.pages.products.form', compact('vendors','categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'vendor_id'   => 'required|exists:vendors,id',
            'category_id' => 'required|exists:categories,id',
            'name'        => 'required|string|max:200',
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|integer|min:0',
            'thumbnail'   => 'required|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        $path = $request->file('thumbnail')->store('products','public');

        Product::create([
            'vendor_id'   => $request->vendor_id,
            'category_id' => $request->category_id,
            'name'        => $request->name,
            'slug'        => $this->uniqueSlug($request->name),
            'description' => $request->description,
            'price'       => $request->price,
            'sale_price'  => $request->sale_price ?: null,
            'stock'       => $request->stock,
            'unit'        => $request->unit ?? 'piece',
            'thumbnail'   => $path,
            'status'      => $request->status ?? 'active',
        ]);

        return redirect()->route('admin.products.index')
            ->with('success', '🎉 Product added successfully!');
    }

    public function edit(Product $product)
    {
        $vendors    = Vendor::where('status','active')->get();
        $categories = Category::all();
        return view('admin.pages.products.form', compact('product','vendors','categories'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'vendor_id'   => 'required|exists:vendors,id',
            'category_id' => 'required|exists:categories,id',
            'name'        => 'required|string|max:200',
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|integer|min:0',
            'thumbnail'   => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        $data = [
            'vendor_id'   => $request->vendor_id,
            'category_id' => $request->category_id,
            'name'        => $request->name,
            'slug'        => $this->uniqueSlug($request->name, $product->id),
            'description' => $request->description,
            'price'       => $request->price,
            'sale_price'  => $request->sale_price ?: null,
            'stock'       => $request->stock,
            'unit'        => $request->unit ?? 'piece',
            'status'      => $request->status ?? 'active',
        ];

        if ($request->hasFile('thumbnail')) {
            if ($product->thumbnail && !str_starts_with($product->thumbnail,'http')) {
                \Storage::disk('public')->delete($product->thumbnail);
            }
            $data['thumbnail'] = $request->file('thumbnail')->store('products','public');
        }

        $product->update($data);

        return redirect()->route('admin.products.index')
            ->with('success', '✅ Product updated!');
    }

    public function destroy(Product $product)
    {
        // ✅ Pehle related order_items delete karo
        $product->orderItems()->delete();

        // ✅ Thumbnail delete karo
        if ($product->thumbnail && !str_starts_with($product->thumbnail,'http')) {
            \Storage::disk('public')->delete($product->thumbnail);
        }

        // ✅ Ab product delete karo
        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', '🗑️ Product deleted!');
    }

    private function uniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $slug = Str::slug($name); $count = 1;
        while (Product::where('slug',$slug)
            ->when($ignoreId, fn($q) => $q->where('id','!=',$ignoreId))
            ->exists()) {
            $slug = Str::slug($name).'-'.$count++;
        }
        return $slug;
    }
}
