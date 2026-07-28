<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Suggestion extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'contact',
        'subject',
        'message',
        'status',
        'allow_publish',
        'ip_address',
        'user_agent',
    ];

    /**
     * Get the avatar URL attribute.
     */
    public function getAvatarUrlAttribute(): string
    {
        $name = urlencode($this->name ?? 'User');
        return "https://ui-avatars.com/api/?name={$name}&size=200&background=006A4E&color=fff&rounded=true";
    }

    /**
     * Get rating stars HTML.
     */
    public function getRatingStarsAttribute(): string
    {
        return '<i class="fas fa-star text-warning me-1"></i><i class="fas fa-star text-warning me-1"></i><i class="fas fa-star text-warning me-1"></i><i class="fas fa-star text-warning me-1"></i><i class="fas fa-star text-warning me-1"></i>';
    }
}
