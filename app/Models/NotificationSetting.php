<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationSetting extends Model
{
    use HasFactory;

    protected $table = 'notification_settings';

    protected $fillable = [
        'user_id',
        'sound_enabled',
        'desktop_enabled',
        'email_digest',
        'quiet_hours_start',
        'quiet_hours_end'
    ];

    protected $casts = [
        'sound_enabled' => 'boolean',
        'desktop_enabled' => 'boolean',
        'email_digest' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
