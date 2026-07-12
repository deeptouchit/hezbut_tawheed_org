<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookCategory extends Model
{
    use HasFactory;

    protected $table = 'book_categories';

    protected $fillable = [
        'name',
        'slug',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the books associated with this category.
     */
    public function books()
    {
        return $this->hasMany(Book::class, 'category_id');
    }

    /**
     * Get active books in this category.
     */
    public function activeBooks()
    {
        return $this->books()->where('is_active', true);
    }
}
