<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{

    public function handle($request, Closure $next, $role)
        {
    if (! auth()->user() || ! auth()->user()->hasRole($role)) {
           return $next($request);
        // abort(403, 'Unauthorized action.');
        }

    return $next($request);
}

}
