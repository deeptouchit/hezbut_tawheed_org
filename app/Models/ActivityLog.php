<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    use HasFactory;

    protected $table = 'activity_logs';

    protected $fillable = [
        'user_id', 'action', 'module', 'description', 'ip_address',
        'user_agent', 'url', 'method', 'old_data', 'new_data'
    ];

    protected $casts = [
        'old_data'   => 'array',
        'new_data'   => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user that performed the activity
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Log an activity - URL ছোট করে সংরক্ষণ
     */
    public static function log(string $action, string $module, ?string $description = null, $oldData = null, $newData = null): self
    {
        // ✅ URL ছোট করা - শুধু পাথ + গুরুত্বপূর্ণ প্যারামিটার
        $url = self::shortenUrl(request()->fullUrl());

        return self::create([
            'user_id'     => auth()->id(),
            'action'      => $action,
            'module'      => $module,
            'description' => $description,
            'ip_address'  => request()->ip(),
            'user_agent'  => request()->userAgent(),
            'url'         => $url,
            'method'      => request()->method(),
            'old_data'    => $oldData,
            'new_data'    => $newData,
        ]);
    }

    /**
     * URL ছোট করার হেল্পার মেথড
     */
    private static function shortenUrl(string $fullUrl): string
    {
        // 1. পাথ নিন
        $path = parse_url($fullUrl, PHP_URL_PATH) ?? '/';

        // 2. কুয়েরি প্যারামিটার নিন
        parse_str(parse_url($fullUrl, PHP_URL_QUERY) ?? '', $query);

        // 3. গুরুত্বপূর্ণ প্যারামিটার ফিল্টার করুন (প্রোডাক্ট আইডি, স্লাগ, পেজ ইত্যাদি)
        $importantKeys = ['slug', 'id', 'product_id', 'page', 'sort', 'category', 'brand', 'search', 'q'];
        $filteredQuery = array_intersect_key($query, array_flip($importantKeys));

        // 4. যদি কোন গুরুত্বপূর্ণ প্যারামিটার না থাকে, তাহলে খালি
        if (empty($filteredQuery)) {
            return $path;
        }

        // 5. শুধুমাত্র গুরুত্বপূর্ণ প্যারামিটার দিয়ে URL তৈরি করুন
        return $path . '?' . http_build_query($filteredQuery);
    }

    /**
     * Get action badge HTML
     */
    public function getActionBadgeAttribute(): string
    {
        $colors = [
            'create'  => 'success',
            'update'  => 'info',
            'delete'  => 'danger',
            'login'   => 'primary',
            'logout'  => 'secondary',
            'restore' => 'warning',
            'clear'   => 'dark',
            'export'  => 'success',
        ];

        $color = $colors[$this->action] ?? 'secondary';
        return '<span class="badge bg-' . $color . '" style="width:100px;">' . ucfirst($this->action) . '</span>';
    }

    /**
     * Get module badge HTML
     */
    public function getModuleBadgeAttribute(): string
    {
        $colors = [
            'user'       => 'primary',
            'role'       => 'info',
            'permission' => 'warning',
            'backup'     => 'success',
            'cache'      => 'dark',
            'email'      => 'danger',
            'sms'        => 'purple',
            'activity'   => 'secondary',
        ];

        $color = $colors[$this->module] ?? 'secondary';
        return '<span class="badge bg-' . $color . '" style="width:100px;">' . ucfirst($this->module) . '</span>';
    }

    /**
     * Scope for today's logs
     */
    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }

    /**
     * Scope for this week's logs
     */
    public function scopeThisWeek($query)
    {
        return $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
    }

    /**
     * Scope for this month's logs
     */
    public function scopeThisMonth($query)
    {
        return $query->whereMonth('created_at', now()->month);
    }

    /**
     * Scope for this year's logs
     */
    public function scopeThisYear($query)
    {
        return $query->whereYear('created_at', now()->year);
    }
}
