<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Leader extends Model
{
    use HasFactory;

    protected $table = 'leaders';

    protected $fillable = [
        'parent_id',
        'name',
        'english_name',
        'slug',
        'designation',
        'category',
        'image',
        'signature_image',
        'speech_video_url',
        'quote',
        'bio',
        'facebook_url',
        'twitter_url',
        'linkedin_url',
        'email',
        'sort_order',
        'is_active',
        'is_founder',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_founder' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Boot function to auto-generate slug.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($leader) {
            if (empty($leader->slug)) {
                $leader->slug = static::generateUniqueSlug($leader->english_name);
            }
        });

        static::updating(function ($leader) {
            if (empty($leader->slug) || $leader->isDirty('english_name')) {
                $leader->slug = static::generateUniqueSlug($leader->english_name, $leader->id);
            }
        });
    }

    /**
     * Generate unique slug helper.
     */
    protected static function generateUniqueSlug($name, $excludeId = 0): string
    {
        $slug = Str::slug($name);
        $originalSlug = $slug;
        $count = 1;

        while (static::where('slug', $slug)->where('id', '!=', $excludeId)->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }

        return $slug;
    }

    /**
     * Get profile image URL.
     */
    public function getImageUrlAttribute(): string
    {
        if ($this->image && file_exists(public_path($this->image))) {
            return asset($this->image);
        }

        if ($this->image && filter_var($this->image, FILTER_VALIDATE_URL)) {
            return $this->image;
        }

        // Return a premium Unsplash face placeholder if no image exists
        return "https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?q=80&w=300";
    }

    /**
     * Get signature image URL.
     */
    public function getSignatureUrlAttribute(): ?string
    {
        if ($this->signature_image && file_exists(public_path($this->signature_image))) {
            return asset($this->signature_image);
        }

        if ($this->signature_image && filter_var($this->signature_image, FILTER_VALIDATE_URL)) {
            return $this->signature_image;
        }

        return null;
    }

    /**
     * Get publications/books written by this leader.
     */
    public function getBooksAttribute()
    {
        return Book::where('is_active', true)
            ->where(function($q) {
                $q->where('writer', 'like', "%{$this->name}%")
                  ->orWhere('writer', 'like', "%{$this->english_name}%");
            })
            ->get();
    }

    /**
     * Parent relationship.
     */
    public function parent()
    {
        return $this->belongsTo(Leader::class, 'parent_id');
    }

    /**
     * Children relationships.
     */
    public function children()
    {
        return $this->hasMany(Leader::class, 'parent_id')->orderBy('sort_order', 'asc');
    }

    /**
     * Compute hierarchy depth level (0 = root, 1 = child, 2 = grandchild, etc.)
     */
    public function getLevelAttribute(): int
    {
        $level = 0;
        $parent = $this->parent;
        while ($parent) {
            $level++;
            $parent = $parent->parent;
        }
        return $level;
    }
}
