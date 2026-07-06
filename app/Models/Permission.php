<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Models\Permission as SpatiePermission;

class Permission extends SpatiePermission
{
    use HasFactory, LogsActivity;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'guard_name',
        'group_name',     // আপনার কাস্টম ফিল্ড
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'group_name' => 'string',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($permission) {
            if (empty($permission->group_name)) {
                $permission->group_name = 'general';
            }
        });
    }

    /**
     * Scope for ordering by group.
     */
    public function scopeOrderByGroup($query)
    {
        return $query->orderBy('group_name')->orderBy('name');
    }

    /**
     * Scope for filtering by group.
     */
    public function scopeByGroup($query, $group)
    {
        return $query->where('group_name', $group);
    }

    /**
     * Get all permissions grouped by group_name.
     */
    public static function getGroupedPermissions()
    {
        return self::orderBy('group_name')
            ->orderBy('name')
            ->get()
            ->groupBy('group_name');
    }

    /**
     * Get group badge HTML.
     */
    public function getGroupBadgeAttribute()
    {
        $colors = [
            'users' => 'primary',
            'roles' => 'info',
            'permissions' => 'warning',
            'backup' => 'success',
            'cache' => 'dark',
            'email' => 'danger',
            'sms' => 'purple',
            'activity' => 'secondary',
        ];

        $color = $colors[$this->group_name] ?? 'secondary';
        return '<span class="badge bg-' . $color . '">' . ucfirst($this->group_name) . '</span>';
    }
}
