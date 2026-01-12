<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    public function handle($request, \Closure $next, $permission)
    {
        if (!auth()->check() || !auth()->user()->can($permission)) {
            redirect()->back()->with('error', 'Unauthorized action');
        }
        return $next($request);
    }
}
