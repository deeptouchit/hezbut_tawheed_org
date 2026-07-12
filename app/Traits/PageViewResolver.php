<?php

namespace App\Traits;

use Illuminate\Support\Facades\Cache;

trait PageViewResolver
{
    /**
     * Resolve and return the view for the page.
     *
     * @param string $slug
     * @param \App\Models\Page $page
     * @param array $extraData
     * @return \Illuminate\Contracts\View\View
     */
    protected function resolvePageView($slug, $page, $extraData = [])
    {
        $themeView = "theme::pages.{$slug}";
        
        if ($this->cacheViewExists($themeView)) {
            return view($themeView, array_merge(['page' => $page], $extraData));
        }
        
        $fallbackView = $this->getFallbackView($slug);
        return view($fallbackView, array_merge(['page' => $page], $extraData));
    }
    
    /**
     * Get fallback view for specific slugs.
     *
     * @param string $slug
     * @return string
     */
    protected function getFallbackView($slug)
    {
        $fallbacks = [
            'publications' => 'theme::pages.books.index',
        ];
        
        return $fallbacks[$slug] ?? 'theme::pages.page';
    }
    
    /**
     * Cache filesystem checks for view file existence.
     *
     * @param string $view
     * @return bool
     */
    protected function cacheViewExists($view)
    {
        $cacheKey = 'view_exists_' . str_replace(['::', '.'], '_', $view);
        return Cache::remember($cacheKey, 3600, function () use ($view) {
            return view()->exists($view);
        });
    }
}
