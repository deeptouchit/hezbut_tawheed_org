<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContactMessage extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'subject',
        'message',
        'ip_address',
        'user_agent',
        'status',
        'replied_by',
        'reply_message',
        'replied_at',
    ];

    protected $casts = [
        'replied_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $appends = [
        'status_badge',
        'status_label',
        'short_message',
        'formatted_date',
        'time_ago',
    ];

    // =============================================
    // Relationships
    // =============================================

    public function replier()
    {
        return $this->belongsTo(User::class, 'replied_by');
    }

    // =============================================
    // Accessors
    // =============================================

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'unread' => '<span class="badge bg-danger"><i class="fas fa-circle me-1"></i>অপঠিত</span>',
            'read' => '<span class="badge bg-info"><i class="fas fa-check-circle me-1"></i>পঠিত</span>',
            'replied' => '<span class="badge bg-success"><i class="fas fa-reply me-1"></i>উত্তর দেওয়া</span>',
        ];
        return $badges[$this->status] ?? '<span class="badge bg-secondary">N/A</span>';
    }

    public function getStatusLabelAttribute()
    {
        $labels = [
            'unread' => 'অপঠিত',
            'read' => 'পঠিত',
            'replied' => 'উত্তর দেওয়া',
        ];
        return $labels[$this->status] ?? 'N/A';
    }

    public function getShortMessageAttribute()
    {
        return \Illuminate\Support\Str::limit(strip_tags($this->message), 100);
    }

    public function getFormattedDateAttribute()
    {
        return $this->created_at?->format('d M, Y h:i A');
    }

    public function getTimeAgoAttribute()
    {
        return $this->created_at?->diffForHumans();
    }

    // =============================================
    // Scopes
    // =============================================

    public function scopeUnread($query)
    {
        return $query->where('status', 'unread');
    }

    public function scopeRead($query)
    {
        return $query->where('status', 'read');
    }

    public function scopeReplied($query)
    {
        return $query->where('status', 'replied');
    }

    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }

    public function scopeThisWeek($query)
    {
        return $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year);
    }

    // =============================================
    // Methods
    // =============================================

    public function markAsRead()
    {
        $this->update(['status' => 'read']);
    }

    public function markAsUnread()
    {
        $this->update(['status' => 'unread']);
    }

    public function markAsReplied($replyMessage, $userId = null)
    {
        $this->update([
            'status' => 'replied',
            'reply_message' => $replyMessage,
            'replied_by' => $userId ?? auth()->id(),
            'replied_at' => now(),
        ]);
    }

    public function isUnread()
    {
        return $this->status === 'unread';
    }

    public function isRead()
    {
        return $this->status === 'read';
    }

    public function isReplied()
    {
        return $this->status === 'replied';
    }
}
