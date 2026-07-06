<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class BlogCategory extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'blog_categories';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'image',
        'meta_title',
        'meta_description',
        'status',
        'sort_order',
    ];

    protected $casts = [
        'status' => 'boolean',
        'sort_order' => 'integer',
    ];

    // =============================================
    // Relationships
    // =============================================

    /**
     * এই ক্যাটাগরির অধীনে থাকা ব্লগ পোস্টসমূহ
     */
    public function blogs()
    {
        return $this->hasMany(Blog::class, 'category_id');
    }

    /**
     * এই ক্যাটাগরির অধীনে থাকা সক্রিয় ব্লগ পোস্টসমূহ
     */
    public function activeBlogs()
    {
        return $this->blogs()->where('status', true)->where('published_at', '<=', now());
    }

    /**
     * এই ক্যাটাগরির অধীনে থাকা প্রকাশিত ব্লগ পোস্টসমূহ
     */
    public function publishedBlogs()
    {
        return $this->blogs()->published();
    }

    // =============================================
    // Accessors
    // =============================================

    /**
     * ইমেজ URL এক্সেসর
     */
    public function getImageUrlAttribute()
    {
        if (!$this->image) {
            return 'https://placehold.co/400x300/006A4E/FFFFFF?text=Category';
        }

        if (filter_var($this->image, FILTER_VALIDATE_URL)) {
            return $this->image;
        }

        if (file_exists(public_path($this->image))) {
            return asset($this->image);
        }

        return 'https://placehold.co/400x300/006A4E/FFFFFF?text=Category';
    }

    /**
     * ক্যাটাগরি URL এক্সেসর
     */
    public function getUrlAttribute()
    {
        return route('blog.category', $this->slug);
    }

    /**
     * ব্লগ কাউন্ট এক্সেসর
     */
    public function getBlogsCountAttribute()
    {
        return $this->activeBlogs()->count();
    }

    /**
     * ফরম্যাটেড তৈরি তারিখ
     */
    public function getFormattedCreatedAtAttribute()
    {
        return $this->created_at->format('F j, Y');
    }

    /**
     * ফরম্যাটেড আপডেট তারিখ
     */
    public function getFormattedUpdatedAtAttribute()
    {
        return $this->updated_at->format('F j, Y');
    }

    /**
     * বাংলা মাসের নাম
     */
    public function getBanglaMonthAttribute()
    {
        $banglaMonths = [
            'January' => 'জানুয়ারি', 'February' => 'ফেব্রুয়ারি', 'March' => 'মার্চ',
            'April' => 'এপ্রিল', 'May' => 'মে', 'June' => 'জুন',
            'July' => 'জুলাই', 'August' => 'আগস্ট', 'September' => 'সেপ্টেম্বর',
            'October' => 'অক্টোবর', 'November' => 'নভেম্বর', 'December' => 'ডিসেম্বর'
        ];

        $englishMonth = $this->created_at->format('F');
        return $banglaMonths[$englishMonth] ?? $englishMonth;
    }

    // =============================================
    // Scopes
    // =============================================

    /**
     * শুধুমাত্র সক্রিয় ক্যাটাগরি
     */
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    /**
     * সর্ট অর্ডার অনুযায়ী সাজানো
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order', 'asc')->orderBy('name', 'asc');
    }

    /**
     * নাম অনুযায়ী সাজানো
     */
    public function scopeOrderByName($query, $direction = 'asc')
    {
        return $query->orderBy('name', $direction);
    }

    /**
     * ব্লগ কাউন্ট অনুযায়ী সাজানো
     */
    public function scopeOrderByBlogCount($query, $direction = 'desc')
    {
        return $query->withCount('activeBlogs')->orderBy('active_blogs_count', $direction);
    }

    /**
     * সার্চ স্কোপ
     */
    public function scopeSearch($query, $search)
    {
        return $query->where('name', 'LIKE', "%{$search}%")
            ->orWhere('description', 'LIKE', "%{$search}%");
    }

    /**
     * আইডি দ্বারা ফিল্টার
     */
    public function scopeWhereId($query, $id)
    {
        return $query->where('id', $id);
    }

    /**
     * স্লাগ দ্বারা ফিল্টার
     */
    public function scopeWhereSlug($query, $slug)
    {
        return $query->where('slug', $slug);
    }

    // =============================================
    // Methods
    // =============================================

    /**
     * ক্যাটাগরি সক্রিয় কিনা
     */
    public function isActive()
    {
        return $this->status;
    }

    /**
     * ক্যাটাগরি সক্রিয় করুন
     */
    public function activate()
    {
        $this->update(['status' => true]);
    }

    /**
     * ক্যাটাগরি নিষ্ক্রিয় করুন
     */
    public function deactivate()
    {
        $this->update(['status' => false]);
    }

    /**
     * ব্লগ পোস্ট আছে কিনা
     */
    public function hasBlogs()
    {
        return $this->activeBlogs()->exists();
    }

    /**
     * সাম্প্রতিক ব্লগ পোস্ট
     */
    public function getRecentBlogs($limit = 5)
    {
        return $this->activeBlogs()->recent()->limit($limit)->get();
    }

    /**
     * জনপ্রিয় ব্লগ পোস্ট
     */
    public function getPopularBlogs($limit = 5)
    {
        return $this->activeBlogs()->popular()->limit($limit)->get();
    }

    /**
     * ক্যাটাগরি ট্রি (যদি সাবক্যাটাগরি থাকে)
     * (ভবিষ্যতে ব্যবহারের জন্য)
     */
    public function parent()
    {
        return $this->belongsTo(BlogCategory::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(BlogCategory::class, 'parent_id');
    }

    /**
     * ড্রপডাউনের জন্য ক্যাটাগরি লিস্ট
     */
    public static function getDropdownList()
    {
        return self::active()->ordered()->pluck('name', 'id');
    }

    // =============================================
    // Boot Method
    // =============================================

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
            if (empty($category->sort_order)) {
                $category->sort_order = 0;
            }
        });

        static::updating(function ($category) {
            if ($category->isDirty('name') && empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });

        // ক্যাটাগরি ডিলিট করার সময় আচরণ
        static::deleting(function ($category) {
            // সফট ডিলিট হলে ব্লগগুলোর ক্যাটাগরি ID null করে দেওয়া
            if ($category->isForceDeleting()) {
                $category->blogs()->update(['category_id' => null]);
            }
        });
    }
}
