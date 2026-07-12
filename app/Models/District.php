<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    use HasFactory;

    protected $fillable = [
        'division_id',
        'name',
        'bn_name',
        'is_active',
    ];

    /**
     * Get the division that owns the district.
     */
    public function division()
    {
        return $this->belongsTo(Division::class, 'division_id');
    }

    /**
     * Get the upazilas for the district.
     */
    public function upazilas()
    {
        return $this->hasMany(Upazila::class, 'district_id');
    }

       public function addresses()
    {
        return $this->hasMany(CustomerAddress::class);
    }
}
