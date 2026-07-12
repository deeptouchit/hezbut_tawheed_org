<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'title',
        'writer',
        'slug',
        'image',
        'description',
        'content',
        'pdf_url',
        'price',
        'old_price',
        'is_popular',
        'is_bestseller',
        'popular_order',
        'is_active',
    ];

    protected $casts = [
        'category_id' => 'integer',
        'is_active' => 'boolean',
        'is_popular' => 'boolean',
        'is_bestseller' => 'boolean',
        'popular_order' => 'integer',
    ];

    /**
     * Get the category that owns the book.
     */
    public function category()
    {
        return $this->belongsTo(BookCategory::class, 'category_id');
    }

    /**
     * Get the reviews for the book.
     */
    public function reviews()
    {
        return $this->hasMany(BookReview::class)->where('is_active', true);
    }

    /**
     * Get the book cover image URL or a default placeholder.
     */
    public function getImageUrlAttribute()
    {
        if ($this->image) {
            // Check if it's already a full URL or stored in public
            if (filter_var($this->image, FILTER_VALIDATE_URL)) {
                return $this->image;
            }
            return asset($this->image);
        }
        
        return 'https://placehold.co/400x600/e9ecef/adb5bd?text=' . urlencode($this->title);
    }

    /**
     * Get the full PDF URL (checks if relative or absolute).
     */
    public function getPdfUrlAttribute($value)
    {
        if ($value) {
            if (filter_var($value, FILTER_VALIDATE_URL)) {
                return $value;
            }
            return asset($value);
        }
        return null;
    }
}
