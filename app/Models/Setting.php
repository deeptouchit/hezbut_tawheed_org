<?php
// app/Models/Setting.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $table = 'settings';

    protected $fillable = [
        'key',
        'value',
        'type',
        'group',
        'label',
        'options',
        'sort_order',
        'is_encrypted',
        'validation_rules',
        'placeholder',
        'help_text',
        'is_active'
    ];

    protected $casts = [
        'options'          => 'array',
        'validation_rules' => 'array',
        'is_encrypted'     => 'boolean',
        'is_active'        => 'boolean',
        'sort_order'       => 'integer'
    ];

    // Boot method for caching
    protected static function boot()
    {
        parent::boot();

        static::saved(function () {
            Cache::forget('site_settings');
            Cache::forget('site_settings_group');
        });

        static::deleted(function () {
            Cache::forget('site_settings');
            Cache::forget('site_settings_group');
        });
    }

    // Get all settings with caching
    public static function getAllSettings()
    {
        return Cache::remember('site_settings', 3600, function () {
            return self::where('is_active', true)
                ->orderBy('group')
                ->orderBy('sort_order')
                ->get()
                ->pluck('value', 'key')
                ->toArray();
        });
    }

    // Get settings by group
    public static function getSettingsByGroup($group)
    {
        return Cache::remember("site_settings_group_{$group}", 3600, function () use ($group) {
            return self::where('group', $group)
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->get()
                ->pluck('value', 'key')
                ->toArray();
        });
    }

    // Get single setting value
    public static function get($key, $default = null, $raw = false)
    {
        $settings = self::getAllSettings();
        $value = $settings[$key] ?? $default;

        if (!$raw && $value && is_string($value)) {
            $ext = pathinfo($value, PATHINFO_EXTENSION);
            if (in_array(strtolower($ext), ['png', 'jpg', 'jpeg', 'gif', 'ico', 'svg', 'webp', 'apk', 'ipa', 'pdf'])) {
                if (!str_starts_with($value, 'uploads/settings/') && !str_starts_with($value, 'http://') && !str_starts_with($value, 'https://')) {
                    $value = 'uploads/settings/' . $value;
                }
            }
        }

        return $value;
    }

    // Set single setting value
    public static function set($key, $value)
    {
        $setting = self::where('key', $key)->first();
        if ($setting) {
            $setting->value = $value;
            $setting->save();
        } else {
            self::create([
                'key'   => $key,
                'value' => $value,
                'label' => $key,
                'type'  => 'text'
            ]);
        }
        return true;
    }
}
