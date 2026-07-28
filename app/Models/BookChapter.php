<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookChapter extends Model
{
    use HasFactory;

    protected $fillable = [
        'book_id',
        'chapter_number',
        'title',
        'slug',
        'content',
        'pdf_url',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'book_id' => 'integer',
        'sort_order' => 'integer',
        'is_active' => 'boolean',
    ];

    /**
     * Get the book that owns the chapter.
     */
    public function book()
    {
        return $this->belongsTo(Book::class, 'book_id');
    }

    /**
     * Scope active chapters
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
