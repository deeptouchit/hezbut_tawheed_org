<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory;

    protected $table = 'branches';

    protected $fillable = [
        'name',
        'type',
        'address',
        'phone',
        'email',
        'contact_person_name',
        'contact_person_designation',
        'google_map_url',
        'image',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Get branch type labels in Bengali.
     */
    public function getTypeLabelAttribute(): string
    {
        $labels = [
            'central' => 'কেন্দ্রীয় কার্যালয়',
            'division' => 'বিভাগীয় কার্যালয়',
            'district' => 'জেলা কার্যালয়',
            'upazila' => 'উপজেলা কার্যালয়',
            'international' => 'আন্তর্জাতিক শাখা',
        ];

        return $labels[$this->type] ?? $this->type;
    }

    /**
     * Get branch image URL.
     */
    public function getImageUrlAttribute(): string
    {
        if ($this->image && file_exists(public_path($this->image))) {
            return asset($this->image);
        }

        if ($this->image && filter_var($this->image, FILTER_VALIDATE_URL)) {
            return $this->image;
        }

        // Fallbacks based on branch type
        if ($this->type === 'central') {
            return 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?q=80&w=300';
        }
        if ($this->type === 'international') {
            return 'https://images.unsplash.com/photo-1529156069898-49953e39b3ac?q=80&w=300';
        }

        return 'https://images.unsplash.com/photo-1497366216548-37526070297c?q=80&w=300';
    }

    /**
     * Get the officials associated with the branch.
     */
    public function officials()
    {
        return $this->hasMany(BranchOfficial::class, 'branch_id')->orderBy('sort_order', 'asc');
    }
}
