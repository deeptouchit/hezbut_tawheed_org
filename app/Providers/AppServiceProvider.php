<?php

namespace App\Providers;

use App\Helpers\SettingsHelper;
use App\Models\Theme;
use App\Services\ThemeService;
use App\View\Composers\MenuComposer;
use Illuminate\Support\Facades\Auth; // ✅ Auth ফ্যাসেড যোগ করো
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(ThemeService::class, function ($app) {
            return new ThemeService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        view()->share('setting', new SettingsHelper());
        View::composer('*', MenuComposer::class);

        // Register UserObserver
        \App\Models\User::observe(\App\Observers\UserObserver::class);

        // ============================================
        // Auth ডাটা সব ভিউতে শেয়ার করো
        // ============================================
        $this->shareAuthData();

        // ============================================
        // থিম ভিউ নেমস্পেস ডিফাইন করুন
        // ============================================
        $this->loadThemeNamespace();
    }

    /**
     * সব ভিউতে Auth ডাটা শেয়ার করো
     */
    protected function shareAuthData(): void
    {
        // সব ভিউতে $isLoggedIn এবং $customer ভ্যারিয়েবল পাবে (অ্যাডমিন বাদে)
        view()->composer('*', function ($view) {
            if (str_starts_with($view->getName(), 'admin.')) {
                return;
            }

        });

    }

    /**
     * থিম ভিউ নেমস্পেস লোড করুন
     */
    protected function loadThemeNamespace(): void
    {
        $themeFolder = 'hezbut-tawheed';

        try {
            // প্রিভিউ মোড চেক করুন (সেশন থেকে)
            if (session()->has('preview_theme')) {
                $themeFolder = session('preview_theme');
            }
            // না হলে ডাটাবেস থেকে একটিভ থিম নিন
            else {
                $activeTheme = Theme::getActiveTheme();
                if ($activeTheme && $activeTheme->folder) {
                    $themeFolder = $activeTheme->folder;
                }
            }
        } catch (\Exception $e) {
            // ডাটাবেস কানেক্ট না থাকলে বা টেবিল না থাকলে ডিফল্ট ব্যবহার করুন
            $themeFolder = 'hezbut-tawheed';
        }

        // থিম পাথ নির্ধারণ
        $themePath = resource_path('views/themes/' . $themeFolder);

        // চেক করুন থিম ফোল্ডার আছে কিনা
        if (!is_dir($themePath)) {
            $themePath = resource_path('views/themes/hezbut-tawheed');
        }

        // ভিউ নেমস্পেস যোগ করুন
        View::addNamespace('theme', $themePath);

        // থিম ফোল্ডার নাম ভিউতে শেয়ার করুন
        view()->share('themeFolder', $themeFolder);
    }
}
