<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user()) {
            return redirect()->guest(route('admin.login'))
                ->with('warning', 'Please sign in with administrator credentials to access this area.');
        }

        if (! $request->user()->isAdmin()) {
            abort(403, 'Administrator privileges are required to access this resource.');
        }

        return $next($request);
    }
}
