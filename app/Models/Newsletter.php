<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Newsletter extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'newsletters';

    protected $fillable = [
        'email',
        'name',
        'phone',
        'status',
        'verification_token',
        'verified_at',
        'unsubscribed_at',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'verified_at' => 'datetime',
        'unsubscribed_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeUnsubscribed($query)
    {
        return $query->where('status', 'unsubscribed');
    }

    public function scopeVerified($query)
    {
        return $query->whereNotNull('verified_at');
    }

    // Methods
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function isVerified(): bool
    {
        return !is_null($this->verified_at);
    }

    public function verify(): void
    {
        $this->update([
            'verified_at' => now(),
            'verification_token' => null,
            'status' => 'active'
        ]);
    }

    public function unsubscribe(): void
    {
        $this->update([
            'status' => 'unsubscribed',
            'unsubscribed_at' => now()
        ]);
    }

    public static function generateVerificationToken(): string
    {
        return bin2hex(random_bytes(32));
    }
}
