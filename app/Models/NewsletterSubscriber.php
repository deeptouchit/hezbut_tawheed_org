<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NewsletterSubscriber extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $table = 'newsletter_subscribers';

    protected $fillable = [
        'email',
        'name',
        'verification_token',
        'verified_at',
        'is_active',
        'ip_address',
        'source'
    ];

    protected $casts = [
        'verified_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeVerified($query)
    {
        return $query->whereNotNull('verified_at');
    }

    public function getStatusBadgeAttribute()
    {
        if (!$this->is_active) {
            return '<span class="badge bg-danger">আনসাবস্ক্রাইবড</span>';
        }
        if ($this->verified_at) {
            return '<span class="badge bg-success">ভেরিফাইড</span>';
        }
        return '<span class="badge bg-warning text-dark">পেন্ডিং</span>';
    }
}
