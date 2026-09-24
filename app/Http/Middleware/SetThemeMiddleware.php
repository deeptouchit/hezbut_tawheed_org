<?php

namespace App\Http\Middleware;

use App\Models\Theme;
use Closure;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\View;

class SetThemeMiddleware
{
    public function handle($request, Closure $next)
    {
        $themeFolder = null;

        // ১. প্রথমে চেক করুন প্রিভিউ মোড একটিভ কিনা
        if (session()->has('preview_mode') && session()->has('preview_theme')) {
            $themeFolder = session('preview_theme');

            // প্রিভিউ ব্যানার দেখানোর জন্য শেয়ারড ভ্যারিয়েবল
            view()->share('isPreviewMode', true);
            view()->share('previewThemeName', session('preview_theme'));
        }
        // ২. না হলে ডাটাবেস থেকে একটিভ থিম নিন
        else {
            $activeTheme = Theme::getActiveTheme();
            $themeFolder = $activeTheme ? $activeTheme->folder : 'default';

            view()->share('isPreviewMode', false);
            view()->share('previewThemeName', null);
        }

        // থিম ভিউ পাথ সেট করুন
        $themePath = resource_path('views/themes/' . $themeFolder);

        // যদি থিম ফোল্ডার না থাকে, ডিফল্ট ব্যবহার করুন
        if (!is_dir($themePath)) {
            $themePath = resource_path('views/themes/default');
        }

        // ভিউ নেমস্পেস যোগ করুন
        View::addNamespace('theme', $themePath);

        // থিম ফোল্ডারটি ভিউতে শেয়ার করুন
        view()->share('themeFolder', $themeFolder);

        return $next($request);
    }
}
