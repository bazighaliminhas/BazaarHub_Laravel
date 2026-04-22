<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth('web')->check() || !auth('web')->user()->isAdmin()) {
            return redirect()->route('admin.login')
                ->with('error', 'Access denied. Admins only.');
        }
        return $next($request);
    }
}
