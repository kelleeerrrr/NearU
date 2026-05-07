<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && Auth::user()->user_type === 'admin') {
            return $next($request);
        }

        // If user is not admin, redirect with error
        if (Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Access denied. Admin privileges required.');
        }

        // If not authenticated, redirect to login
        return redirect()->route('login');
    }
}
