<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\RedirectIfAuthenticated as Middleware;

class RedirectIfAuthenticated extends Middleware
{
    /**
     * Handle an incoming request.
     */
    public function handle($request, callable $next, ...$guards)
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (\Illuminate\Support\Facades\Auth::guard($guard)->check()) {
                return redirect(RouteServiceProvider::HOME ?? '/dashboard');
            }
        }

        return $next($request);
    }
}
