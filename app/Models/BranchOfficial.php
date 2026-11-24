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
        return asset('uploads/avatars/default.svg');
    }

    /**
     * Get formatted designation label.
     */
    public function getFormattedDesignationAttribute(): string
    {
        $d = trim((string) $this->designation);

        if ($d === 'দায়িত্বশীল' || $d === 'দায়িত্বশীল') {
            return 'জেলা আমির';
        }

        if ($d === 'সহকারী দায়িত্বশীল' || $d === 'সহকারী দায়িত্বশীল') {
            return 'সহকারী আমির';
        }

        $d = str_replace(['সহকারী দায়িত্বশীল', 'সহকারী দায়িত্বশীল'], 'সহকারী আমির', $d);
        $d = str_replace(['দায়িত্বশীল', 'দায়িত্বশীল'], 'জেলা আমির', $d);

        return $d ?: 'জেলা আমির';
    }
}
