<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // If user is not authenticated, redirect to login.
        if (! auth()->check()) {
            return redirect()->guest(route('login'));
        }

        // If authenticated but role mismatch, forbid.
        if (auth()->user()->role !== $role) {
            abort(403);
        }

        return $next($request);
    }
}
