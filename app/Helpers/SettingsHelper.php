<?php

namespace App\Helpers;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class SettingsHelper
{
    /**
     * গ্রুপ আইকন ম্যাপিং
     */
    protected static $groupIcons = [
        'general'     => 'fa-building',
        'logo'        => 'fa-image',
        'social'      => 'fa-share-alt',
        'seo'         => 'fa-search',
        'currency'    => 'fa-money-bill-wave',
        'date'        => 'fa-calendar-alt',
        'invoice'     => 'fa-file-invoice',
        'email'       => 'fa-envelope',
        'shipping'    => 'fa-truck',
        'payment'     => 'fa-credit-card',
        'maintenance' => 'fa-tools',
        'cookie'      => 'fa-cookie-bite',
        'checkout'    => 'fa-shopping-cart',
        'otp'         => 'fa-key',
        'security'    => 'fa-shield-alt',
        'inventory'   => 'fa-boxes',
        'tax_vat'     => 'fa-percent',
        'about'       => 'fa-info-circle',
        'custom'      => 'fa-cog'
    ];

    /**
     * গ্রুপের আইকন পাওয়া
     */
    public static function getGroupIcon($group)
    {
        return self::$groupIcons[$group] ?? 'fa-cog';
    }

    /**
     * সব গ্রুপ আইকন পাওয়া
     */
    public static function getAllGroupIcons()
    {
        return self::$groupIcons;
    }

    /**
     * সেটিংস ভ্যালু পাওয়া
     */
    public static function getSetting($key, $default = null, $raw = false)
    {
        try {
            $settings = Cache::remember('global_settings_map', 3600, function () {
                return Setting::pluck('value', 'key')->toArray();
            });

            $value = $settings[$key] ?? $default;

            // Ensure value contains the uploads/settings/ prefix if it is a file/image (with extension)
            if (!$raw && $value && is_string($value)) {
                $ext = pathinfo($value, PATHINFO_EXTENSION);
                if (in_array(strtolower($ext), ['png', 'jpg', 'jpeg', 'gif', 'ico', 'svg', 'webp', 'apk', 'ipa', 'pdf'])) {
                    if (!str_starts_with($value, 'uploads/settings/') && !str_starts_with($value, 'http://') && !str_starts_with($value, 'https://')) {
                        $value = 'uploads/settings/' . $value;
                    }
                }
            }

            return $value;
        } catch (\Throwable $e) {
            Log::error("Failed to get setting: {$key}", ['error' => $e->getMessage()]);
            return $default;
        }
    }

    /**
     * সেটিংস ভ্যালু পাওয়া (শর্টকাট মেথড)
     */
    public function get($key, $default = null)
    {
        return self::getSetting($key, $default);
    }

    /**
     * গ্রুপ আইকন পাওয়া (নন-স্ট্যাটিক)
     */
    public function groupIcon($group)
    {
        return self::getGroupIcon($group);
    }

    /**
     * গ্রুপ অনুযায়ী সেটিংস পাওয়া
     */
    public static function getGroup($group)
    {
        try {
            return Cache::remember("settings_group_{$group}", 3600, function () use ($group) {
                return Setting::where('group', $group)
                    ->where('is_active', true)
                    ->orderBy('sort_order')
                    ->get()
                    ->mapWithKeys(function ($setting) {
                        $value = $setting->value;
                        if ($value && is_string($value)) {
                            $ext = pathinfo($value, PATHINFO_EXTENSION);
                            if (in_array(strtolower($ext), ['png', 'jpg', 'jpeg', 'gif', 'ico', 'svg', 'webp', 'apk', 'ipa', 'pdf'])) {
                                if (!str_starts_with($value, 'uploads/settings/') && !str_starts_with($value, 'http://') && !str_starts_with($value, 'https://')) {
                                    $value = 'uploads/settings/' . $value;
                                }
                            }
                        }
                        return [$setting->key => $value];
                    })
                    ->toArray();
            });
        } catch (\Throwable $e) {
            Log::error("Failed to get setting group: {$group}", ['error' => $e->getMessage()]);
            return [];
        }
    }

    /**
     * ক্যাশ পরিষ্কার করা
     */
    public static function clearCache()
    {
        Cache::forget('global_settings_map');

        $groups = Setting::select('group')->distinct()->pluck('group');
        foreach ($groups as $group) {
            Cache::forget("settings_group_{$group}");
        }
    }
}
