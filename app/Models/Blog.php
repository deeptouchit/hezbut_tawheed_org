<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Blog extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'blogs';

    protected $fillable = [
        'title',
        'slug',
        'short_description',
        'content',
        'featured_image',
        'author_id',
        'category_id',
        'tags',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'views',
        'status',
        'published_at',
        'sort_order',
        'is_gallery',
        'gallery_order',
    ];

    protected $casts = [
        'tags'         => 'array',
        'status'       => 'boolean',
        'published_at' => 'datetime',
        'views'        => 'integer',
        'sort_order'   => 'integer',
        'is_gallery'   => 'boolean',
        'gallery_order'=> 'integer',
    ];

      // =============================================
      // Relationships
      // =============================================

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function category()
    {
        return $this->belongsTo(BlogCategory::class, 'category_id');
    }

    public function comments()
    {
        return $this->hasMany(BlogComment::class)->where('is_approved', true)->whereNull('parent_id');
    }

    public function allComments()
    {
        return $this->hasMany(BlogComment::class);
    }

    public function approvedComments()
    {
        return $this->hasMany(BlogComment::class)->where('is_approved', true);
    }

    public function pendingComments()
    {
        return $this->hasMany(BlogComment::class)->where('is_approved', false);
    }

    public function replies()
    {
        return $this->hasMany(BlogComment::class)->whereNotNull('parent_id');
    }

      // =============================================
      // Accessors
      // =============================================
      /**
     * Compatibility accessor to map image_url to featured_image_url
     */
    public function getImageUrlAttribute()
    {
        return $this->featured_image_url;
    }

    /**
     * ফিচার্ড ইমেজ URL এক্সেসর (ডিফল্ট ইমেজ সহ)
     */
    public function getFeaturedImageUrlAttribute()
    {
        // ✅ ডিফল্ট ইমেজ
        $defaultImage = 'https://placehold.co/800x600/006A4E/FFFFFF?text=Hezbut+Tawheed';

        // যদি featured_image খালি থাকে
        if (empty($this->featured_image)) {
            return $defaultImage;
        }

          // যদি URL হয়
        if (filter_var($this->featured_image, FILTER_VALIDATE_URL)) {
            return $this->featured_image;
        }

          // ✅ যদি ফাইল থাকে
        $fullPath = public_path($this->featured_image);
        if (file_exists($fullPath)) {
            return asset($this->featured_image);
        }

          // কোনোটাই না হলে ডিফল্ট
        return $defaultImage;
    }

      /**
     * ইমেজ আছে কিনা চেক (ভিউতে ব্যবহারের জন্য)
     */
    public function hasFeaturedImage()
    {
        if (empty($this->featured_image)) {
            return false;
        }

        if (filter_var($this->featured_image, FILTER_VALIDATE_URL)) {
            return true;
        }

        $fullPath = public_path($this->featured_image);
        return file_exists($fullPath);
    }
    public function getFormattedDateAttribute()
    {
        $date = $this->published_at ?? $this->created_at;
        return $date ? $date->format('F j, Y'): '';
    }



    public function getFormattedDateBanglaAttribute()
    {
        $date = $this->published_at ?? $this->created_at;

        if (!$date) {
            return '';
        }

        $banglaMonths = [
            'January' => 'জানুয়ারি', 'February' => 'ফেব্রুয়ারি', 'March'     => 'মার্চ',
            'April'   => 'এপ্রিল',    'May'      => 'মে',          'June'      => 'জুন',
            'July'    => 'জুলাই',     'August'   => 'আগস্ট',       'September' => 'সেপ্টেম্বর',
            'October' => 'অক্টোবর',   'November' => 'নভেম্বর',     'December'  => 'ডিসেম্বর'
        ];

        $banglaNumbers = ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯'];

        $englishMonth = $date->format('F');
        $banglaMonth  = $banglaMonths[$englishMonth] ?? $englishMonth;

        $day  = str_replace(range(0, 9), $banglaNumbers, $date->format('d'));
        $year = str_replace(range(0, 9), $banglaNumbers, $date->format('Y'));

        return $banglaMonth . ' ' . $day . ', ' . $year;
    }

    public function getExcerptAttribute()
    {
        return Str::limit(strip_tags($this->content), 120);
    }

    public function getReadingTimeAttribute()
    {
        $wordCount     = str_word_count(strip_tags($this->content));
        $minutes       = ceil($wordCount / 200);
        $banglaNumbers = ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯'];
        $minutesBangla = str_replace(range(0, 9), $banglaNumbers, (string)$minutes);
        return $minutesBangla . ' মিনিট পড়া';
    }

    public function getTagListAttribute()
    {
        if (is_array($this->tags)) {
            return implode(', ', $this->tags);
        }
        return $this->tags;
    }

    public function getTagsArrayAttribute()
    {
        if (is_array($this->tags)) {
            return $this->tags;
        }
        if (is_string($this->tags)) {
            return explode(',', $this->tags);
        }
        return [];
    }

    public function getUrlAttribute()
    {
        return route('blog.detail', $this->slug);
    }

    public function getShareUrlAttribute()
    {
        return urlencode(route('blog.detail', $this->slug));
    }

    public function getShareTextAttribute()
    {
        return urlencode($this->title);
    }

      // =============================================
      // Scopes
      // =============================================

    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    public function scopePublished($query)
    {
        return $query->where('status', true)
            ->where('published_at', '<=', now());
    }

    public function scopePopular($query)
    {
        return $query->orderBy('views', 'desc');
    }

    public function scopeRecent($query)
    {
        return $query->orderBy('published_at', 'desc');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeByCategory($query, $category)
    {
        if (is_numeric($category)) {
            return $query->where('category_id', $category);
        }
        return $query->whereHas('category', function ($q) use ($category) {
            $q->where('slug', $category);
        });
    }

    public function scopeByTag($query, $tag)
    {
        $quotedTag = json_encode($tag);
        return $query->where(function ($q) use ($tag, $quotedTag) {
            $q->whereRaw('JSON_CONTAINS(tags, ?)', [$quotedTag])
              ->orWhere('tags', 'LIKE', '%"' . $tag . '"%')
              ->orWhere('tags', 'LIKE', '%' . $tag . '%');
        });
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('title', 'LIKE', "%{$search}%")
                ->orWhere('content', 'LIKE', "%{$search}%")
                ->orWhere('short_description', 'LIKE', "%{$search}%");
        });
    }

    public function scopeThisWeek($query)
    {
        return $query->whereBetween('published_at', [now()->startOfWeek(), now()->endOfWeek()]);
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('published_at', now()->month)
            ->whereYear('published_at', now()->year);
    }

      // =============================================
      // Methods
      // =============================================

    public function incrementViews()
    {
        $this->increment('views');
    }

    public function isPublished()
    {
        return $this->status && ($this->published_at && $this->published_at <= now());
    }

    public function isPending()
    {
        return !$this->isPublished();
    }

    public function getNextPost()
    {
        return self::published()
            ->where('published_at', '>', $this->published_at)
            ->orderBy('published_at', 'asc')
            ->first();
    }

    public function getPreviousPost()
    {
        return self::published()
            ->where('published_at', '<', $this->published_at)
            ->orderBy('published_at', 'desc')
            ->first();
    }

    public function getRelatedPosts($limit = 3)
    {
        if (!$this->category_id) {
            return collect();
        }

        return self::published()
            ->where('id', '!=', $this->id)
            ->where('category_id', $this->category_id)
            ->orderBy('published_at', 'desc')
            ->limit($limit)
            ->get();
    }

    public function getPopularPosts($limit = 5)
    {
        return self::published()
            ->orderBy('views', 'desc')
            ->limit($limit)
            ->get();
    }

      // =============================================
      // Boot Method
      // =============================================

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($blog) {
            if (empty($blog->slug)) {
                $blog->slug = Str::slug($blog->title);
            }
            if (empty($blog->published_at)) {
                $blog->published_at = now();
            }
            if (empty($blog->views)) {
                $blog->views = 0;
            }
        });

        static::updating(function ($blog) {
            if ($blog->isDirty('title') && empty($blog->slug)) {
                $blog->slug = Str::slug($blog->title);
            }
        });
    }
}
