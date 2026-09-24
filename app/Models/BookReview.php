<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookReview extends Model
{
    use HasFactory;

    protected $fillable = [
        'book_id',
        'name',
        'email',
        'rating',
        'comment',
        'is_active',
    ];

    public function book()
    {
        return $this->belongsTo(Book::class);
    }
}
