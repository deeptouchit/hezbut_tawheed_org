<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Gallery extends Model
{
    protected $table = 'galleries';

    protected $fillable = [
        'title',
        'image_path',
        'blog_id',
        'gallery_order',
        'is_active',
        'is_custom',
    ];

    protected $casts = [
        'gallery_order' => 'integer',
        'is_active' => 'boolean',
        'is_custom' => 'boolean',
    ];

    /**
     * Get the blog post associated with the gallery image.
     */
    public function blog(): BelongsTo
    {
        return $this->belongsTo(Blog::class, 'blog_id');
    }
}
