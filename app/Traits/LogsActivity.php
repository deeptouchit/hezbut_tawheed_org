<?php

namespace App\Traits;

use App\Models\ActivityLog;

trait LogsActivity
{
    protected static function bootLogsActivity()
    {
        static::created(function ($model) {
            self::logActivity('create', $model);
        });

        static::updated(function ($model) {
            self::logActivity('update', $model);
        });

        static::deleted(function ($model) {
            self::logActivity('delete', $model);
        });
    }

    protected static function logActivity($action, $model)
    {
        // ✅ URL ছোট করা (শুধু গুরুত্বপূর্ণ প্যারামিটার)
        $url = self::shortenUrl(request()->fullUrl());

        ActivityLog::create([
            'user_id'     => (auth()->check() && auth()->user() instanceof \App\Models\User) ? auth()->id() : null,
            'action'      => $action,
            'module'      => class_basename($model),
            'description' => $action . ' ' . class_basename($model) . ': ' . ($model->name ?? $model->id),
            'ip_address'  => request()->ip(),
            'user_agent'  => request()->userAgent(),
            'url'         => $url,
            'method'      => request()->method(),
            'old_data'    => $action === 'update' ? $model->getOriginal() : null,
            'new_data'    => $action !== 'delete' ? $model->toArray() : null,
        ]);
    }

    /**
     * URL ছোট করার হেল্পার মেথড
     */
    protected static function shortenUrl(string $fullUrl): string
    {
        $path = parse_url($fullUrl, PHP_URL_PATH) ?? '/';
        parse_str(parse_url($fullUrl, PHP_URL_QUERY) ?? '', $query);

        // শুধুমাত্র গুরুত্বপূর্ণ প্যারামিটার রাখুন
        $importantKeys = ['slug', 'id', 'product_id', 'page', 'sort', 'category', 'brand', 'search', 'q'];
        $filteredQuery = array_intersect_key($query, array_flip($importantKeys));

        if (empty($filteredQuery)) {
            return $path;
        }

        return $path . '?' . http_build_query($filteredQuery);
    }
}
