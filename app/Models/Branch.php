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
        'latitude',
        'longitude',
        'image',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Mutator to ensure phone is always saved in English digits with hyphen format.
     */
    public function setPhoneAttribute($value): void
    {
        if (empty($value)) {
            $this->attributes['phone'] = $value;
            return;
        }

        $bnDigits = ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯'];
        $enDigits = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];

        $converted = str_replace($bnDigits, $enDigits, trim($value));
        $digitsOnly = preg_replace('/[^0-9]/', '', $converted);

        if (str_starts_with($digitsOnly, '8801') && strlen($digitsOnly) === 13) {
            $digitsOnly = substr($digitsOnly, 2);
        }

        if (strlen($digitsOnly) === 11 && str_starts_with($digitsOnly, '01')) {
            $this->attributes['phone'] = substr($digitsOnly, 0, 5) . '-' . substr($digitsOnly, 5);
        } else {
            $this->attributes['phone'] = preg_replace('/[^\d\-\+]/', '', $converted) ?: $converted;
        }
    }

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
