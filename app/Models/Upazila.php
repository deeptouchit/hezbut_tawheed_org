<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Upazila extends Model
{
    use HasFactory;

    protected $fillable = ['district_id', 'name', 'bn_name', 'slug', 'status'];

    public function district()
    {
        return $this->belongsTo(District::class);
    }

    public function addresses()
    {
        return $this->hasMany(CustomerAddress::class);
    }
}
