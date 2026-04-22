<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CustomerMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth('web')->check()) {
            return redirect()->route('customer.login')
                ->with('error', 'Please login to continue.');
        }
        return $next($request);
    }
}
