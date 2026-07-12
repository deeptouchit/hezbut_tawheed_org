<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class RouteHelper
{
    private const CACHE_KEY = 'frontend_routes';
    private const CACHE_TTL = 86400; // 24 hours

    /**
     * সব ফ্রন্টএন্ড GET রাউট লিস্ট
     */
    public static function getFrontendRoutes()
    {
        return Cache::remember(self::CACHE_KEY, self::CACHE_TTL, function () {
            return self::generateRouteList();
        });
    }

    /**
     * রাউট লিস্ট জেনারেট করুন
     */
    private static function generateRouteList()
    {
        $routes = [];
        $allRoutes = Route::getRoutes();

        foreach ($allRoutes as $route) {
            // ১. শুধু GET মেথড
            if (!in_array('GET', $route->methods())) {
                continue;
            }

            $uri = $route->uri();
            $name = $route->getName();

            // ২. অ্যাডমিন রাউট বাদ
            if (str_starts_with($uri, 'admin/') || str_starts_with($uri, 'admin')) {
                continue;
            }

            // ৩. API রাউট বাদ
            if (str_starts_with($uri, 'api/') || str_starts_with($uri, 'api')) {
                continue;
            }

            // ৪. লারাভেল ডিবাগ রাউট বাদ
            if (in_array($uri, ['_debugbar', '_ignition', 'telescope', 'horizon'])) {
                continue;
            }

            // ৫. ডায়নামিক প্যারামিটারযুক্ত রাউট বাদ (যেমন: {slug}, {id})
            if (str_contains($uri, '{')) {
                // কিন্তু ক্যাটাগরি/ব্র্যান্ডের মতো গুরুত্বপূর্ণ রাউট রাখতে পারেন
                // if (!str_contains($uri, 'category') && !str_contains($uri, 'brand')) {
                //     continue;
                // }
                continue;
            }

            // ৬. রাউট লেবেল তৈরি
            $label = self::generateLabel($name, $uri);

            $routes[] = [
                'name' => $name,
                'uri' => '/' . $uri,
                'label' => $label,
                'route' => $name,
            ];
        }

        // ডুপ্লিকেট বাদ
        $routes = self::removeDuplicates($routes);

        // সর্ট করুন
        usort($routes, function ($a, $b) {
            return strcmp($a['label'], $b['label']);
        });

        return $routes;
    }

    /**
     * রাউট লেবেল জেনারেট করুন
     */
    private static function generateLabel($name, $uri)
    {
        // রাউট নাম থেকে লেবেল
        if ($name) {
            $label = str_replace('.', ' ', $name);
            $label = str_replace('index', '', $label);
            $label = str_replace('show', 'বিস্তারিত', $label);
            $label = ucwords($label);
            return $label;
        }

        // URI থেকে লেবেল
        $label = str_replace('/', ' ', $uri);
        $label = str_replace('-', ' ', $label);
        $label = ucwords($label);
        return $label;
    }

    /**
     * ডুপ্লিকেট রাউট বাদ দিন
     */
    private static function removeDuplicates($routes)
    {
        $unique = [];
        $seen = [];

        foreach ($routes as $route) {
            $key = $route['uri'];
            if (!in_array($key, $seen)) {
                $seen[] = $key;
                $unique[] = $route;
            }
        }

        return $unique;
    }

    /**
     * ফ্রন্টএন্ড রাউট ক্যাশ রিফ্রেশ
     */
    public static function refreshCache()
    {
        Cache::forget(self::CACHE_KEY);
        return self::getFrontendRoutes();
    }

    /**
     * রাউট ক্যাশ ক্লিয়ার
     */
    public static function clearCache()
    {
        Cache::forget(self::CACHE_KEY);
    }

    /**
     * ড্রপডাউনের জন্য ফরম্যাটেড লিস্ট
     */
    public static function getDropdownOptions()
    {
        $routes = self::getFrontendRoutes();
        $options = [];

        foreach ($routes as $route) {
            $options[] = [
                'value' => $route['route'],
                'label' => $route['label'],
                'uri' => $route['uri'],
            ];
        }

        return $options;
    }

    /**
     * রাউট ইউআরএল বের করুন
     */
    public static function getRouteUrl($routeName)
    {
        try {
            return route($routeName);
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * রাউট আছে কিনা চেক করুন
     */
    public static function routeExists($routeName)
    {
        try {
            return Route::has($routeName);
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * রাউট লিস্টে সার্চ করুন
     */
    public static function searchRoutes($searchTerm)
    {
        $routes = self::getFrontendRoutes();
        return array_filter($routes, function ($route) use ($searchTerm) {
            return stripos($route['label'], $searchTerm) !== false ||
                   stripos($route['uri'], $searchTerm) !== false ||
                   stripos($route['name'], $searchTerm) !== false;
        });
    }
}
