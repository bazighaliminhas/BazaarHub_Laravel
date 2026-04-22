<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VendorAuthController extends Controller
{
    public function showLogin()
    {
        return view('vendor.auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::guard('vendor')->attempt([
            'email'    => $request->email,
            'password' => $request->password,
            'status'   => 'active'
        ], $request->remember)) {
            return redirect()->route('vendor.dashboard');
        }

        return back()->withErrors(['email' => 'Invalid credentials or account inactive.']);
    }

    public function logout()
    {
        Auth::guard('vendor')->logout();
        return redirect()->route('vendor.login');
    }
}
