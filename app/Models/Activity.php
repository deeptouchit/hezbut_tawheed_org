<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'image',
        'description',
        'content',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the activity image URL or a default placeholder.
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
        
        return asset('images/default-activity.jpg');
    }
}
