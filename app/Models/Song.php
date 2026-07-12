<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Song extends Model
{
    use HasFactory;

    protected $table = 'songs';

    protected $fillable = [
        'title',
        'youtube_id',
        'audio_file',
        'video_file',
        'thumbnail_url',
        'lyrics',
        'category',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Get Audio URL path.
     */
    public function getAudioUrlAttribute()
    {
        if ($this->audio_file) {
            return asset($this->audio_file);
        }
        return null;
    }

    /**
     * Get Video URL path.
     */
    public function getVideoUrlAttribute()
    {
        if ($this->video_file) {
            return asset($this->video_file);
        }
        return null;
    }

    /**
     * Get Thumbnail URL.
     */
    public function getThumbnailUrlAttribute($value)
    {
        if ($value) {
            return asset($value);
        }
        if ($this->youtube_id) {
            return "https://img.youtube.com/vi/{$this->youtube_id}/mqdefault.jpg";
        }
        return "https://images.unsplash.com/photo-1470225620780-dba8ba36b745?q=80&w=400"; // premium fallback
    }
}
