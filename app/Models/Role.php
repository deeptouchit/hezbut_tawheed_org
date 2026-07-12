<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    use HasFactory, LogsActivity;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'guard_name',
    ];

    /**
     * Get users count.
     */
    public function getUsersCountAttribute()
    {
        return $this->users()->count();
    }

    /**
     * Check if role is deletable.
     */
    public function isDeletable()
    {
        return $this->name !== 'super_admin' && $this->users()->count() === 0;
    }
}
