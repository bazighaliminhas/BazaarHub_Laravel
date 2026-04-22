<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = User::where('role','customer')
            ->withCount('orders')
            ->latest()->paginate(15);
        return view('admin.pages.customers.index', compact('customers'));
    }
}
