<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NewsletterCampaign extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $table = 'newsletter_campaigns';

    protected $fillable = [
        'subject',
        'title',
        'content',
        'template',
        'status',
        'recipient_type',
        'selected_emails',
        'total_recipients',
        'sent_count',
        'opened_count',
        'clicked_count',
        'scheduled_at',
        'sent_at',
        'created_by'
    ];

    protected $casts = [
        'selected_emails' => 'array',
        'scheduled_at' => 'datetime',
        'sent_at' => 'datetime',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'draft' => '<span class="badge bg-secondary">ড্রাফট</span>',
            'scheduled' => '<span class="badge bg-info">শিডিউলড</span>',
            'sending' => '<span class="badge bg-primary">সেন্ডিং</span>',
            'sent' => '<span class="badge bg-success">সেন্ট</span>',
            'failed' => '<span class="badge bg-danger">ফেইলড</span>',
            'cancelled' => '<span class="badge bg-warning text-dark">ক্যান্সেলড</span>',
        ];
        return $badges[$this->status] ?? '<span class="badge bg-secondary">' . $this->status . '</span>';
    }
}
