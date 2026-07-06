<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $table = 'notifications';

    protected $fillable = [
        'user_id',
        'type',
        'title',
        'message',
        'icon',
        'color',
        'link',
        'is_read',
        'read_at'
    ];

    protected $casts = [
        'is_read'    => 'boolean',
        'read_at'    => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user that owns the notification
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope for unread notifications
     */
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    /**
     * Scope for read notifications
     */
    public function scopeRead($query)
    {
        return $query->where('is_read', true);
    }

    /**
     * Scope by type
     */
    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Mark notification as read
     */
    public function markAsRead()
    {
        $this->update([
            'is_read' => true,
            'read_at' => now()
        ]);
    }

    /**
     * Send notification to a user
     */
    public static function send($userId, $title, $message, $type = 'system', $link = null)
    {
        // Set icon based on type
        $icon = match($type) {
            'order'    => 'shopping-cart',
            'stock'    => 'box',
            'customer' => 'user-plus',
            'system'   => 'bell',
            default    => 'bell'
        };

        // Set color based on type
        $color = match($type) {
            'order'    => 'success',
            'stock'    => 'warning',
            'customer' => 'info',
            'system'   => 'primary',
            default    => 'primary'
        };

        return self::create([
            'user_id' => $userId,
            'type'    => $type,
            'title'   => $title,
            'message' => $message,
            'icon'    => $icon,
            'color'   => $color,
            'link'    => $link,
            'is_read' => false
        ]);
    }

    /**
     * Send notification to multiple users
     */
    public static function sendToUsers($userIds, $title, $message, $type = 'system', $link = null)
    {
        $notifications = [];
        foreach ($userIds as $userId) {
            $notifications[] = self::send($userId, $title, $message, $type, $link);
        }
        return $notifications;
    }

    /**
     * Send notification to all admins
     */
    public static function sendToAdmins($title, $message, $type = 'system', $link = null)
    {
        $adminIds = User::role('admin')->pluck('id')->toArray();
        return self::sendToUsers($adminIds, $title, $message, $type, $link);
    }

    /**
     * Get time ago in human readable format
     */
    public function getTimeAgoAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    /**
     * Get status badge HTML
     */
    public function getStatusBadgeAttribute()
    {
        if ($this->is_read) {
            return '<span class="badge bg-success">Read</span>';
        }
        return '<span class="badge bg-warning">Unread</span>';
    }

    /**
     * Get type badge HTML
     */
    public function getTypeBadgeAttribute()
    {
        $colors = [
            'order'    => 'success',
            'stock'    => 'warning',
            'customer' => 'info',
            'system'   => 'primary'
        ];
        $color = $colors[$this->type] ?? 'secondary';
        return '<span class="badge bg-' . $color . '">' . ucfirst($this->type) . '</span>';
    }
}
