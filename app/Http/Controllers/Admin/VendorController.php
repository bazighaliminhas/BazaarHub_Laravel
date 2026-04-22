<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class VendorController extends Controller
{
    public function index()
    {
        $vendors = Vendor::withCount('products')->latest()->paginate(10);
        return view('admin.pages.vendors.index', compact('vendors'));
    }

    public function create()
    {
        return view('admin.pages.vendors.form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'shop_name' => 'required|string|max:150',
            'name'      => 'required|string|max:100',
            'email'     => 'required|email|unique:vendors,email',
            'password'  => 'required|min:6',
            'phone'     => 'nullable|string|max:20',
            'status'    => 'required|in:active,inactive,banned',
        ]);

        Vendor::create([
            'shop_name'   => $request->shop_name,
            'name'        => $request->name,
            'email'       => $request->email,
            'password'    => Hash::make($request->password),
            'phone'       => $request->phone,
            'description' => $request->description,
            'status'      => $request->status,
        ]);

        return redirect()->route('admin.vendors.index')
            ->with('success', '✅ Vendor created successfully!');
    }

    public function edit(Vendor $vendor)
    {
        return view('admin.pages.vendors.form', compact('vendor'));
    }

    public function update(Request $request, Vendor $vendor)
    {
        $request->validate([
            'shop_name' => 'required|string|max:150',
            'name'      => 'required|string|max:100',
            'email'     => 'required|email|unique:vendors,email,' . $vendor->id,
            'status'    => 'required|in:active,inactive,banned',
        ]);

        $data = [
            'shop_name'   => $request->shop_name,
            'name'        => $request->name,
            'email'       => $request->email,
            'phone'       => $request->phone,
            'description' => $request->description,
            'status'      => $request->status,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $vendor->update($data);

        return redirect()->route('admin.vendors.index')
            ->with('success', '✅ Vendor updated successfully!');
    }

    public function destroy(Vendor $vendor)
    {
        $vendor->products()->delete();
        $vendor->delete();
        return redirect()->route('admin.vendors.index')
            ->with('success', '🗑️ Vendor deleted successfully!');
    }
}
