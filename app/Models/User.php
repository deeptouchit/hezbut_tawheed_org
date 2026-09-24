<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes, HasRoles, LogsActivity;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'email_verified_at',
        'password',
        'image',
        'role',
        'status',
        'last_login_at',
        'last_login_ip',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships

    // Accessors
    public function getAvatarAttribute()
    {
        return $this->image ? asset('storage/' . $this->image) : 'https://ui-avatars.com/api/?background=0D8ABC&color=fff&name=' . urlencode($this->name);
    }

    public function getIsSuperAdminAttribute()
    {
        return $this->role === 'super_admin';
    }

    public function getIsAdminAttribute()
    {
        return in_array($this->role, ['super_admin', 'admin']);
    }

    public function getIsManagerAttribute()
    {
        return $this->role === 'manager';
    }



    public function getIsActiveAttribute()
    {
        return $this->status === 'active';
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }



    public function scopeAdmins($query)
    {
        return $query->whereIn('role', ['super_admin', 'admin', 'manager']);
    }

    // Methods
    public function updateLastLogin()
    {
        $this->update([
            'last_login_at' => now(),
            'last_login_ip' => request()->ip(),
        ]);
    }

    public function hasPermission($permission)
    {
        if ($this->is_super_admin) {
            return true;
        }

        return $this->hasPermissionTo($permission);
    }


    /**
     * Get all notifications for the user
     */
    public function notifications()
    {
        return $this->hasMany(Notification::class)->latest();
    }

    /**
     * Get unread notifications
     */
    public function unreadNotifications()
    {
        return $this->hasMany(Notification::class)->where('is_read', false);
    }

    /**
     * Get unread notifications count
     */
    public function getUnreadNotificationsCountAttribute()
    {
        return $this->unreadNotifications()->count();
    }

    public function notificationSetting()
    {
        return $this->hasOne(NotificationSetting::class);
    }
}
