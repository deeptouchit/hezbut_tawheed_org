<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class XssProtection
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $input = $request->all();

        if ($input) {
            array_walk_recursive($input, function (&$input) {
                $input = strip_tags($input);
            });

            $request->merge($input);
        }

        return $next($request);
    }
}
