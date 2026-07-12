<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LiveBroadcast extends Model
{
    use HasFactory;

    protected $table = 'live_broadcasts';

    protected $fillable = [
        'title',
        'source_type',
        'video_id',
        'schedule_time',
        'is_live',
        'is_active',
        'description',
        'view_count',
    ];

    protected $casts = [
        'schedule_time' => 'datetime',
        'is_live' => 'boolean',
        'is_active' => 'boolean',
        'view_count' => 'integer',
    ];

    /**
     * Get Embed URL based on source type.
     */
    public function getEmbedUrlAttribute()
    {
        if ($this->source_type === 'youtube') {
            return "https://www.youtube.com/embed/{$this->video_id}?autoplay=1";
        }

        // Facebook Embed Link
        $url = $this->video_id;
        // If they only put Facebook video ID, construct full watch link
        if (is_numeric($url) || (!str_contains($url, 'facebook.com') && !str_contains($url, 'fb.watch'))) {
            $url = "https://www.facebook.com/watch/?v=" . $url;
        }

        $encodedUrl = urlencode($url);
        return "https://www.facebook.com/plugins/video.php?href={$encodedUrl}&show_text=false&t=0";
    }

    /**
     * Get Preview Thumbnail.
     */
    public function getThumbnailUrlAttribute()
    {
        if ($this->source_type === 'youtube') {
            return "https://img.youtube.com/vi/{$this->video_id}/mqdefault.jpg";
        }

        // Unsplash premium placeholder for Facebook broadcasts
        return "https://images.unsplash.com/photo-1478737270239-2f02b77fc618?q=80&w=400";
    }
}
