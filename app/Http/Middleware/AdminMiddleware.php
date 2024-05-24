<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if the user is authenticated and if they have the admin role
        if ($request->user() && $request->user()->role !== 'admin') {
            // Redirect non-admin users away from the admin panel
            return redirect('/dashboard')->with('error', 'You do not have permission to access this page.');
        }
        return $next($request);
    }
}
