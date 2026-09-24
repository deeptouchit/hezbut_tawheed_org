<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewsletterTemplate extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'newsletter_templates';

    protected $fillable = [
        'name', 'subject', 'content', 'thumbnail', 'is_default', 'status'
    ];

    protected $casts = [
        'is_default' => 'boolean',
        'status' => 'boolean',
    ];

    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }
}
