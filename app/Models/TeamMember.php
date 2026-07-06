<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TeamMember extends Model
{
    protected $fillable = [
        'name',
        'designation',
        'bio',
        'image',
        'email',
        'phone',
        'social_links',
        'experience',
        'education',
        'skills',
        'department',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'social_links' => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * Get the image URL attribute (আপডেটেড)
     */
    public function getImageUrlAttribute(): ?string
    {
        if ($this->image && file_exists(public_path($this->image))) {
            return asset($this->image);
        }
        
        // ডিফল্ট অ্যাভাটার
        $name = urlencode($this->name ?? 'User');
        return "https://ui-avatars.com/api/?name={$name}&size=200&background=6366f1&color=fff&rounded=true";
    }

    /**
     * Get full image path
     */
    public function getImagePathAttribute(): ?string
    {
        if ($this->image && file_exists(public_path($this->image))) {
            return public_path($this->image);
        }
        return null;
    }

    // ... অন্যান্য মেথড
}