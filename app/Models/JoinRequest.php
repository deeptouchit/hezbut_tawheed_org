<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JoinRequest extends Model
{
    protected $fillable = [
        'membership_type',
        'name',
        'join_date',
        'father_husband',
        'age',
        'occupation',
        'education',
        'phone',
        'current_unit_amir',
        'present_address',
        'permanent_address',
        'experience',
        'how_knew',
        'person_name',
        'person_phone',
        'status',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $appends = [
        'status_badge',
        'status_label',
        'type_badge',
        'formatted_date',
        'time_ago',
    ];

    // =============================================
    // Accessors
    // =============================================

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'unread'   => '<span class="badge bg-danger"><i class="fas fa-circle me-1"></i>অপঠিত</span>',
            'read'     => '<span class="badge bg-info"><i class="fas fa-check-circle me-1"></i>পঠিত</span>',
            'approved' => '<span class="badge bg-success"><i class="fas fa-check me-1"></i>অনুমোদিত</span>',
            'rejected' => '<span class="badge bg-secondary"><i class="fas fa-times me-1"></i>প্রত্যাখ্যাত</span>',
        ];
        return $badges[$this->status] ?? '<span class="badge bg-secondary">N/A</span>';
    }

    public function getStatusLabelAttribute()
    {
        $labels = [
            'unread'   => 'অপঠিত',
            'read'     => 'পঠিত',
            'approved' => 'অনুমোদিত',
            'rejected' => 'প্রত্যাখ্যাত',
        ];
        return $labels[$this->status] ?? 'N/A';
    }

    public function getTypeBadgeAttribute()
    {
        if ($this->membership_type === 'primary') {
            return '<span class="badge bg-success">প্রাথমিক সদস্য</span>';
        }
        return '<span class="badge bg-warning text-dark">পাঁচ দফা ভিত্তিক অঙ্গীকার</span>';
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

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }
}
