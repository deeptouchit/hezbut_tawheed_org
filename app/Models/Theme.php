<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Theme extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'folder',
        'version',
        'author',
        'description',
        'preview_image',
        'screenshot',
        'is_active',
        'is_core',
        'status',
        'settings',
        'requires',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'is_active' => 'boolean',
        'is_core' => 'boolean',
        'settings' => 'array',
        'requires' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * The "booted" method of the model.
     */
    protected static function booted()
    {
        // যখন থিম সেভ হবে (আপডেট/ক্রিয়েট)
        static::saved(function ($theme) {
            Cache::forget('active_theme');
            Cache::forget('all_themes');
        });

        // যখন থিম ডিলিট হবে
        static::deleted(function ($theme) {
            Cache::forget('active_theme');
            Cache::forget('all_themes');

            // কোর থিম ডিলিট হলে এক্সেপশন দিতে হবে (কন্ট্রোলারে চেক করবেন)
        });
    }

    /**
     * Scope: শুধুমাত্র একটিভ থিম
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope: শুধুমাত্র ইনস্টল করা থিম
     */
    public function scopeInstalled($query)
    {
        return $query->where('status', 'installed');
    }

    /**
     * Scope: শুধুমাত্র কোর থিম
     */
    public function scopeCore($query)
    {
        return $query->where('is_core', true);
    }

    /**
     * Scope: শুধুমাত্র নন-কোর থিম (ইউজার আপলোড করা)
     */
    public function scopeNonCore($query)
    {
        return $query->where('is_core', false);
    }

    /**
     * থিম একটিভেট করার মেথড
     */
    public function activate()
    {
        // আগের একটিভ থিম ডিঅ্যাকটিভেট করুন
        static::where('is_active', true)->update(['is_active' => false]);

        // বর্তমান থিম একটিভেট করুন
        $this->update([
            'is_active' => true,
            'status' => 'activated'
        ]);

        // ক্যাশ পরিষ্কার করুন
        Cache::forget('active_theme');

        return true;
    }

    /**
     * থিম ডিঅ্যাকটিভেট করার মেথড
     */
    public function deactivate()
    {
        // কোর থিম চেক (কন্ট্রোলারে করবেন, এখানে শুধু আপডেট)
        $this->update([
            'is_active' => false,
            'status' => 'deactivated'
        ]);

        Cache::forget('active_theme');

        return true;
    }

    /**
     * একটিভ থিম পাওয়ার হেল퍼 মেথড
     */
    public static function getActiveTheme()
    {
        return Cache::remember('active_theme', 86400, function () {
            return static::where('is_active', true)->first();
        });
    }

    /**
     * সব ইনস্টল করা থিম পাওয়ার হেল퍼 মেথড
     */
    public static function getAllThemes()
    {
        return Cache::remember('all_themes', 3600, function () {
            return static::orderBy('is_core', 'desc')
                        ->orderBy('name', 'asc')
                        ->get();
        });
    }

    /**
     * থিমের ফোল্ডার পাথ রিটার্ন করে
     */
    public function getViewPath()
    {
        return resource_path("views/themes/{$this->folder}");
    }

    /**
     * থিমের পাবলিক অ্যাসেট পাথ
     */
    public function getAssetPath()
    {
        return asset("themes/{$this->folder}");
    }

    /**
     * থিমের প্রিভিউ ইমেজ ইউআরএল
     */
    public function getPreviewUrlAttribute()
    {
        if ($this->preview_image) {
            return asset($this->preview_image);
        }
        return asset("themes/{$this->folder}/screenshot.png");
    }

    /**
     * চেক করে থিমটি ডিলিট করা যাবে কিনা
     */
    public function isDeletable()
    {
        return !$this->is_core && $this->folder !== 'default';
    }

    /**
     * চেক করে থিমটি ডিঅ্যাকটিভ করা যাবে কিনা
     */
    public function isDeactivatable()
    {
        // যদি একমাত্র একটিভ থিম হয় এবং অন্য কোন থিম না থাকে, তাহাবে না
        if ($this->is_active && static::count() <= 1) {
            return false;
        }
        return true;
    }
}
