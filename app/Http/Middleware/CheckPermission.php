<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Gate;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next, ...$permissions)
    {

        // dd($permissions);

        foreach ($permissions as $permission) {
            if (!Gate::allows($permission)) {
                // Handle unauthorized access here, such as redirecting or returning a response
                abort(403, 'Unauthorized');
            }
        }

        return $next($request);
    }


}
