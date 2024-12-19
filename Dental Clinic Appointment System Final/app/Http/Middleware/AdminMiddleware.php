<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Check if the user is authenticated and is an admin (is_admin == 1)
        if (Auth::check() && Auth::user()->is_admin == 1) {
            return $next($request);
        }

        // If not an admin, redirect to user page
        return redirect()->route('user');
    }
}