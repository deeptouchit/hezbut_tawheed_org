<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Testimonial extends Model
{
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'designation',
        'company',
        'email',
        'phone',
        'avatar',
        'content',
        'rating',
        'is_active',
        'sort_order',
        'meta_title',
        'meta_description',
        'meta_keywords',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'is_active' => 'boolean',
        'rating' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     */
    protected $appends = [
        'avatar_url',
        'rating_stars',
        'short_content',
        'status_badge',
        'rating_percentage',
    ];

    /**
     * Get the avatar URL attribute.
     */
    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar && file_exists(public_path($this->avatar))) {
            return asset($this->avatar);
        }
        
        // Default avatar based on name
        $name = urlencode($this->name ?? 'Client');
        return "https://ui-avatars.com/api/?name={$name}&size=200&background=6366f1&color=fff&rounded=true";
    }

    /**
     * Get rating stars HTML.
     */
    public function getRatingStarsAttribute(): string
    {
        $stars = '';
        $rating = $this->rating ?? 0;
        for ($i = 1; $i <= 5; $i++) {
            if ($i <= $rating) {
                $stars .= '<i class="fas fa-star text-warning"></i>';
            } else {
                $stars .= '<i class="far fa-star text-muted"></i>';
            }
        }
        return $stars;
    }

    /**
     * Get short content attribute. (FIXED)
     */
    public function getShortContentAttribute(): string
    {
        if (empty($this->content)) {
            return 'কন্টেন্ট নেই';
        }
        return Str::limit($this->content, 150, '...');
    }

    /**
     * Get status badge attribute.
     */
    public function getStatusBadgeAttribute(): string
    {
        return $this->is_active 
            ? '<span class="badge bg-success">সক্রিয়</span>'
            : '<span class="badge bg-danger">নিষ্ক্রিয়</span>';
    }

    /**
     * Get rating percentage.
     */
    public function getRatingPercentageAttribute(): float
    {
        return ($this->rating / 5) * 100;
    }

    /**
     * Scope a query to only include active testimonials.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to order testimonials by sort order.
     */
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order', 'asc');
    }

    /**
     * Scope a query to filter by rating.
     */
    public function scopeRating(Builder $query, int $rating): Builder
    {
        return $query->where('rating', $rating);
    }

    /**
     * Scope a query to search testimonials.
     */
    public function scopeSearch(Builder $query, string $search): Builder
    {
        return $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('designation', 'like', "%{$search}%")
              ->orWhere('company', 'like', "%{$search}%")
              ->orWhere('content', 'like', "%{$search}%");
        });
    }

    /**
     * Get top rated testimonials.
     */
    public static function getTopRated(int $limit = 5)
    {
        return self::active()
            ->orderBy('rating', 'desc')
            ->orderBy('sort_order', 'asc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get random testimonials.
     */
    public static function getRandom(int $limit = 3)
    {
        return self::active()
            ->inRandomOrder()
            ->limit($limit)
            ->get();
    }

    /**
     * Delete the avatar image.
     */
    public function deleteAvatar(): bool
    {
        if ($this->avatar && file_exists(public_path($this->avatar))) {
            return @unlink(public_path($this->avatar));
        }
        return false;
    }

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->sort_order)) {
                $model->sort_order = static::max('sort_order') + 1;
            }
        });

        static::deleting(function ($model) {
            // Delete the avatar when the model is deleted
            if ($model->avatar && file_exists(public_path($model->avatar))) {
                @unlink(public_path($model->avatar));
            }
        });
    }
}