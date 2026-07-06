<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Division extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'bn_name',
        'is_active',
    ];

    /**
     * Get the districts for the division.
     */
    public function districts()
    {
        return $this->hasMany(District::class, 'division_id');
    }

    public function addresses()
    {
        return $this->hasMany(CustomerAddress::class);
    }
}
