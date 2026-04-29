<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class UserActivityMiddleware
{
    public function handle($request, Closure $next)
    {
        // Update the user's last activity timestamp in the session
        if (Auth::check()) {
            $request->session()->put('last_activity', now());
        }

        return $next($request);
    }
}
