<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class MenuHelper
{
    public const CACHE_KEY = 'menu_data';
    public const CACHE_TTL = 3600;

    /**
     * JSON ফাইল থেকে মেনু ডাটা লোড করুন
     */
    public static function getMenuData()
    {
        return Cache::remember(self::CACHE_KEY, self::CACHE_TTL, function () {
            return self::loadMenuFromFile();
        });
    }

    /**
     * JSON ফাইল থেকে মেনু লোড করুন
     */
    private static function loadMenuFromFile()
    {
        $path = resource_path('data/menu.json');

        if (!File::exists($path)) {
            return self::getDefaultMenu();
        }

        try {
            $content = File::get($path);
            $data = json_decode($content, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                Log::error('Menu JSON parse error: ' . json_last_error_msg());
                return self::getDefaultMenu();
            }

            // ✅ ফুটার সেকশন নিশ্চিত করুন
            $data = self::ensureFooterSections($data);

            return self::sortMenuItems($data);
        } catch (\Exception $e) {
            Log::error('Menu load error: ' . $e->getMessage());
            return self::getDefaultMenu();
        }
    }

    /**
     * ✅ ফুটার সেকশন নিশ্চিত করুন (JSON এ না থাকলে যোগ করবে)
     */
    private static function ensureFooterSections($data)
    {
        $footerSections = [
            'footer_quick_links',
            'footer_customer_service',
            'footer_about',
            'footer_contact'
        ];

        foreach ($footerSections as $section) {
            if (!isset($data[$section]) || !is_array($data[$section])) {
                $data[$section] = [];
            }
        }

        return $data;
    }

    /**
     * মেনু আইটেম সাজান
     */
    private static function sortMenuItems($data)
    {
        $sections = [
            'desktop_nav',
            'mobile_nav',
            'topbar_left',
            'topbar_right',
            'footer_quick_links',
            'footer_customer_service',
            'footer_about',
            'footer_contact'
        ];

        foreach ($sections as $section) {
            if (isset($data[$section]) && is_array($data[$section])) {
                usort($data[$section], function ($a, $b) {
                    $posA = $a['position'] ?? 999;
                    $posB = $b['position'] ?? 999;
                    return $posA - $posB;
                });
            }
        }

        return $data;
    }

    /**
     * ডিফল্ট মেনু (JSON না থাকলে)
     */
    private static function getDefaultMenu()
    {
        return [
            'topbar_left' => [],
            'topbar_right' => [],
            'desktop_nav' => [],
            'mobile_nav' => [],
            'footer_quick_links' => [],
            'footer_customer_service' => [],
            'footer_about' => [],
            'footer_contact' => []
        ];
    }

    /**
     * ✅ ফুটার কুইক লিংক পান
     */
    public static function getFooterQuickLinks()
    {
        $data = self::getMenuData();
        return $data['footer_quick_links'] ?? [];
    }

    /**
     * ✅ ফুটার কাস্টমার সার্ভিস লিংক পান
     */
    public static function getFooterCustomerService()
    {
        $data = self::getMenuData();
        return $data['footer_customer_service'] ?? [];
    }

    /**
     * ✅ ফুটার অ্যাবাউট লিংক পান
     */
    public static function getFooterAbout()
    {
        $data = self::getMenuData();
        return $data['footer_about'] ?? [];
    }

    /**
     * ✅ ফুটার কন্টাক্ট লিংক পান
     */
    public static function getFooterContact()
    {
        $data = self::getMenuData();
        return $data['footer_contact'] ?? [];
    }

    /**
     * মেনু আইটেমের URL তৈরি করুন
     */
    public static function getMenuUrl($item)
    {
        if (!isset($item['url'])) {
            return '#';
        }

        $type = $item['type'] ?? 'url';
        $url = $item['url'];

        try {
            if ($type === 'route') {
                $params = $item['params'] ?? [];
                return route($url, $params);
            }
            return url($url);
        } catch (\Exception $e) {
            Log::warning('Menu URL generation failed: ' . $e->getMessage());
            return '#';
        }
    }

    /**
     * মেনু আইটেম active কিনা চেক করুন (সাব-মেনুসহ)
     */
    public static function isActive($item)
    {
        // ১. সরাসরি আইটেমটি একটিভ কিনা চেক করুন
        if (self::isDirectActive($item)) {
            return true;
        }

        // ২. যদি সাব-মেনু (children) থাকে, তবে কোনো একটি চাইল্ড একটিভ কিনা চেক করুন
        if (!empty($item['children']) && is_array($item['children'])) {
            foreach ($item['children'] as $child) {
                if (self::isActive($child)) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * সরাসরি একটি মেনু আইটেম একটিভ কিনা চেক করুন
     */
    private static function isDirectActive($item)
    {
        if (!isset($item['url'])) {
            return false;
        }

        $type = $item['type'] ?? 'url';
        $url = $item['url'];

        // Get current route name
        $currentRoute = request()->route();
        $currentRouteName = $currentRoute ? $currentRoute->getName() : null;

        // Clean paths for comparison
        $itemUrl = self::getMenuUrl($item);
        $itemPath = trim(parse_url($itemUrl, PHP_URL_PATH), '/');
        $currentPath = trim(request()->getPathInfo(), '/');

        // 1. Precise Route Name matching first
        if ($type === 'route') {
            if ($currentRouteName === $url) {
                // If it has slug param, check slug as well
                if (isset($item['params']) && isset($item['params']['slug'])) {
                    $currentSlug = request()->route('slug');
                    return $currentSlug === $item['params']['slug'];
                }
                return true;
            }
        }

        // 2. Exact Path Match (highest priority)
        if ($currentPath === $itemPath) {
            return true;
        }

        // 3. Special Route-based Context Matching to prevent broad wildcard collision
        if ($currentRouteName !== null) {
            // If we are on a blog category page, only match if the item is that specific category
            if ($currentRouteName === 'blog.category') {
                return $currentPath === $itemPath;
            }

            // If we are on a blog tag page, only match if the item is that specific tag
            if ($currentRouteName === 'blog.tag') {
                return $currentPath === $itemPath;
            }

            // If we are on announcements or events page, only match if the item path matches
            if ($currentRouteName === 'announcements.index' || $currentRouteName === 'events.index') {
                return $currentPath === $itemPath;
            }

            // If we are on a blog details page, highlight the parent blog/articles menu
            if ($currentRouteName === 'blog.detail') {
                return $itemPath === 'articles' || $url === 'blog';
            }
        }

        // 4. Safe Wildcard Match (fallback) - do not match /articles/* if we are on sub-modules
        if ($itemPath !== '' && $itemPath !== 'articles' && (request()->is($itemPath) || request()->is($itemPath . '/*'))) {
            return true;
        }

        return false;
    }

    /**
     * গেট ন্যাভিগেশন মেনু উইথ সাবমেনু ট্রি
     */
    public static function getNavbarMenu($section = 'desktop_nav')
    {
        $data = self::getMenuData();
        $items = $data[$section] ?? [];

        // Build tree
        $itemsById = [];
        $tree = [];

        foreach ($items as $item) {
            $item['children'] = [];
            $itemsById[$item['id']] = $item;
        }

        foreach ($itemsById as $id => &$item) {
            if (!empty($item['parent_id']) && isset($itemsById[$item['parent_id']])) {
                $itemsById[$item['parent_id']]['children'][] = &$item;
            } else {
                $tree[] = &$item;
            }
        }

        // Sort root items by position
        usort($tree, function ($a, $b) {
            return ($a['position'] ?? 99) - ($b['position'] ?? 99);
        });

        // Sort children recursively
        foreach ($tree as &$rootItem) {
            if (!empty($rootItem['children'])) {
                usort($rootItem['children'], function ($a, $b) {
                    return ($a['position'] ?? 99) - ($b['position'] ?? 99);
                });
            }
        }

        return $tree;
    }

    /**
     * মেনু ক্যাশ রিফ্রেশ করুন
     */
    public static function refreshCache()
    {
        Cache::forget(self::CACHE_KEY);
        return self::getMenuData();
    }

    /**
     * মেনু ক্যাশ ক্লিয়ার করুন
     */
    public static function clearCache()
    {
        Cache::forget(self::CACHE_KEY);
    }
}
