<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

use App\Models\Visitor;
use Illuminate\Support\Str;

class TrackVisitorMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // We only track GET requests and non-ajax requests
        if ($request->method() !== 'GET' || $request->ajax()) {
            return $response;
        }

        // Avoid tracking admin panel routes (route name prefix or path prefix)
        $route = $request->route();
        $routeName = $route ? $route->getName() : '';
        if (Str::startsWith($request->path(), 'admin') || $request->is('admin*') || Str::startsWith($routeName, 'admin.')) {
            return $response;
        }

        // Avoid tracking logged-in administrators (super_admin, admin, manager)
        if (auth()->check()) {
            $user = auth()->user();
            if (in_array($user->role ?? '', ['super_admin', 'admin', 'manager'])) {
                return $response;
            }
            if (method_exists($user, 'hasRole')) {
                foreach (['super_admin', 'admin', 'manager'] as $role) {
                    if ($user->hasRole($role)) {
                        return $response;
                    }
                }
            }
        }

        $userAgent = $request->userAgent() ?? '';

        // Exclude common bots
        $bots = ['googlebot', 'bingbot', 'slurp', 'duckduckbot', 'baiduspider', 'yandexbot', 'crawler', 'spider', 'curl', 'wget'];
        foreach ($bots as $bot) {
            if (stripos($userAgent, $bot) !== false) {
                return $response;
            }
        }

        // Parse Browser
        $browser = 'Unknown';
        if (stripos($userAgent, 'firefox') !== false) {
            $browser = 'Firefox';
        } elseif (stripos($userAgent, 'chrome') !== false) {
            if (stripos($userAgent, 'edg') !== false) {
                $browser = 'Edge';
            } elseif (stripos($userAgent, 'opr') !== false || stripos($userAgent, 'opera') !== false) {
                $browser = 'Opera';
            } else {
                $browser = 'Chrome';
            }
        } elseif (stripos($userAgent, 'safari') !== false) {
            $browser = 'Safari';
        } elseif (stripos($userAgent, 'msie') !== false || stripos($userAgent, 'trident') !== false) {
            $browser = 'Internet Explorer';
        }

        // Parse Platform
        $platform = 'Unknown';
        if (stripos($userAgent, 'windows') !== false) {
            $platform = 'Windows';
        } elseif (stripos($userAgent, 'android') !== false) {
            $platform = 'Android';
        } elseif (stripos($userAgent, 'iphone') !== false || stripos($userAgent, 'ipad') !== false) {
            $platform = 'iOS';
        } elseif (stripos($userAgent, 'macintosh') !== false || stripos($userAgent, 'mac os') !== false) {
            $platform = 'macOS';
        } elseif (stripos($userAgent, 'linux') !== false) {
            $platform = 'Linux';
        }

        // Parse Device
        $device = 'Desktop';
        if (stripos($userAgent, 'mobile') !== false || stripos($userAgent, 'android') !== false || stripos($userAgent, 'iphone') !== false) {
            $device = 'Mobile';
            if (stripos($userAgent, 'ipad') !== false || (stripos($userAgent, 'android') !== false && stripos($userAgent, 'mobile') === false)) {
                $device = 'Tablet';
            }
        }

        try {
            Visitor::create([
                'ip_address' => $request->ip(),
                'user_agent' => substr($userAgent, 0, 500),
                'device'     => $device,
                'browser'    => $browser,
                'platform'   => $platform,
                'url'        => Str::limit($request->fullUrl(), 250),
                'referrer'   => Str::limit($request->header('referer'), 250),
                'user_id'    => auth()->check() ? auth()->id() : null,
            ]);
        } catch (\Exception $e) {
            // Ignore database issues silently to avoid interrupting web requests
        }

        return $response;
    }
}
