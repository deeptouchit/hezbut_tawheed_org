<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BranchOfficial extends Model
{
    use HasFactory;

    protected $table = 'branch_officials';

    protected $fillable = [
        'branch_id',
        'designation',
        'name',
        'phone',
        'email',
        'image',
        'sort_order',
    ];

    protected $casts = [
        'sort_order' => 'integer',
    ];

    /**
     * Get parent branch.
     */
    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    /**
     * Get official image URL.
     */
    public function getImageUrlAttribute(): string
    {
        if ($this->image && file_exists(public_path($this->image))) {
            return asset($this->image);
        }

        if ($this->image && filter_var($this->image, FILTER_VALIDATE_URL)) {
            return $this->image;
        }

        // Return a clean default avatar path
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=006A4E&color=fff&size=128';
    }
}
