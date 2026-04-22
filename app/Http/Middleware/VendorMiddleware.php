<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class VendorMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth('vendor')->check()) {
            return redirect()->route('vendor.login')
                ->with('error', 'Please login as a vendor.');
        }
        return $next($request);
    }
}
