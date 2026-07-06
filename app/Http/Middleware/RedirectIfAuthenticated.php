<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            // Check for admin guard or web guard with admin role
            if (($guard === 'admin' && Auth::guard($guard)->check()) ||
                ($guard === 'web' && Auth::guard($guard)->check() && Auth::user()?->hasRole(['super_admin', 'admin', 'manager']))) {

                return redirect()->intended(RouteServiceProvider::ADMIN_HOME);
            }




            // Default check
            if (Auth::guard($guard)->check()) {
                $user = Auth::guard($guard)->user();

                if ($user && $user->hasRole(['super_admin', 'admin', 'manager'])) {
                    return redirect()->intended(RouteServiceProvider::ADMIN_HOME);
                }

                return redirect()->intended(RouteServiceProvider::HOME);
            }
        }

        return $next($request);
    }
}
