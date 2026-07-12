<?php

namespace App\Providers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to your application's "home" route.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/dashboard';

    /**
     * Custom home paths for different guards
     */
    public const ADMIN_HOME = '/admin/dashboard';


    /**
     * The controller namespace for the application.
     *
     * When present, controller route declarations will automatically be prefixed with this namespace.
     *
     * @var string|null
     */
    protected $namespace = 'App\\Http\\Controllers';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            // API Routes
            Route::prefix('api')
                ->middleware('api')
                ->namespace($this->namespace)
                ->group(base_path('routes/api.php'));

            // Web Routes (Main)
            Route::middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/web.php'));

            // Admin Routes
            Route::middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/admin.php'));


        });
    }

    /**
     * Configure the rate limiters for the application.
     */
    protected function configureRateLimiting(): void
    {
        // API Rate Limiting
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by(optional($request->user())->id ?: $request->ip());
        });

        // Global rate limiting for all routes
        RateLimiter::for('global', function (Request $request) {
            return Limit::perMinute(100)->by($request->ip());
        });

        // Login rate limiting (prevent brute force)
        RateLimiter::for('login', function (Request $request) {
            return Limit::perMinute(5)->by($request->ip())->response(function () {
                return response()->json([
                    'message' => 'অনেকবার চেষ্টা করেছেন। দয়া করে কিছুক্ষণ পর আবার চেষ্টা করুন।'
                ], 429);
            });
        });

        // Admin specific rate limiting (stricter)
        RateLimiter::for('admin', function (Request $request) {
            return Limit::perMinute(200)->by(optional($request->user())->id ?: $request->ip());
        });

        // API rate limiting for authenticated users
        RateLimiter::for('api_auth', function (Request $request) {
            return $request->user()
                ? Limit::perMinute(100)->by($request->user()->id)
                : Limit::perMinute(30)->by($request->ip());
        });



        // Product import/export rate limiting
        RateLimiter::for('import_export', function (Request $request) {
            return Limit::perMinute(5)->by($request->user()?->id ?: $request->ip());
        });
    }

    /**
     * Get the path that users should be redirected to based on their role.
     *
     * @param \Illuminate\Http\Request $request
     * @return string
     */
    public static function getHomePath(Request $request): string
    {
        if (!$request->user()) {
            return self::HOME;
        }

        $user = $request->user();

        return match($user->role) {
            'super_admin', 'admin', 'manager' => self::ADMIN_HOME,
            default => self::HOME,
        };
    }
}
