<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Slider extends Model
{
    use HasFactory;
    use SoftDeletes;
    use LogsActivity;

    protected $table = 'sliders';

    protected $fillable = [
        'title',
        'sub_title',
        'image',
        'mobile_image',
        'link',
        'link_text',
        'button_text',
        'button_link',
        'button_color',
        'position',
        'sort_order',
        'status',
        'start_date',
        'end_date',
        'target',
        'alt_text',
    ];

    protected $casts = [
        'status'     => 'boolean',
        'start_date' => 'datetime',
        'end_date'   => 'datetime',
        'sort_order' => 'integer',
    ];

    // ============================================
    // SCOPES
    // ============================================

    /**
     * সক্রিয় স্লাইডার
     */
    public function scopeActive($query)
    {
        return $query->where('status', true)
            ->where(function ($q) {
                $q->whereNull('start_date')
                  ->orWhere('start_date', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('end_date')
                  ->orWhere('end_date', '>=', now());
            });
    }

    /**
     * হোম পেজের স্লাইডার
     */
    public function scopeHomepage($query)
    {
        return $query->where('position', 'homepage');
    }

    /**
     * সর্ট অর্ডার অনুযায়ী
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order', 'asc');
    }

    // ============================================
    // ACCESSORS
    // ============================================

    /**
     * ইমেজ ইউআরএল
     */
    public function getImageUrlAttribute()
    {
        // ইমেজ না থাকলে ডিফল্ট
        if (empty($this->image)) {
            return asset('uploads/sliders/default-slider.jpg');
        }

        // পাথ ক্লিন করুন (শুরুতে '/' থাকলে বাদ দিন)
        $path = ltrim($this->image, '/');

        // যদি 'uploads/' না থাকে, যোগ করুন
        if (!str_starts_with($path, 'uploads/')) {
            $path = 'uploads/sliders/' . $path;
        }

        // যদি 'sliders/' না থাকে, যোগ করুন
        if (!str_contains($path, 'sliders/')) {
            $path = str_replace('uploads/', 'uploads/sliders/', $path);
        }

        return asset($path);
    }

    /**
     * মোবাইল ইমেজ ইউআরএল
     */
    public function getMobileImageUrlAttribute()
    {
        if (empty($this->mobile_image)) {
            return $this->image_url;
        }

        $path = ltrim($this->mobile_image, '/');

        if (!str_starts_with($path, 'uploads/')) {
            $path = 'uploads/sliders/' . $path;
        }

        if (!str_contains($path, 'sliders/')) {
            $path = str_replace('uploads/', 'uploads/sliders/', $path);
        }

        return asset($path);
    }

    /**
     * স্ট্যাটাস ব্যাজ
     */
    public function getStatusBadgeAttribute()
    {
        if (!$this->status) {
            return '<span class="badge bg-danger">নিষ্ক্রিয়</span>';
        }

        $now = now();

        if ($this->start_date && $this->start_date > $now) {
            return '<span class="badge bg-warning">আসন্ন</span>';
        }

        if ($this->end_date && $this->end_date < $now) {
            return '<span class="badge bg-danger">মেয়াদ উত্তীর্ণ</span>';
        }

        return '<span class="badge bg-success">সক্রিয়</span>';
    }

    /**
     * অবস্থা টেক্সট
     */
    public function getStatusTextAttribute()
    {
        if (!$this->status) {
            return 'নিষ্ক্রিয়';
        }

        $now = now();

        if ($this->start_date && $this->start_date > $now) {
            return 'আসন্ন';
        }

        if ($this->end_date && $this->end_date < $now) {
            return 'মেয়াদ উত্তীর্ণ';
        }

        return 'সক্রিয়';
    }

    /**
     * বাটন লিংক (প্রথমে button_link, নাহলে link)
     */
    public function getButtonLinkAttribute()
    {
        return $this->button_link ?? $this->link;
    }

    /**
     * বাটন টেক্সট (প্রথমে button_text, নাহলে link_text)
     */
    public function getButtonTextAttribute()
    {
        return $this->button_text ?? $this->link_text ?? 'বিস্তারিত';
    }

    /**
     * টার্গেট (ডিফল্ট _self)
     */
    public function getTargetAttribute()
    {
        return $this->attributes['target'] ?? '_self';
    }

    /**
     * Alt টেক্সট (SEO)
     */
    public function getAltTextAttribute()
    {
        return $this->attributes['alt_text'] ?? $this->title ?? 'স্লাইডার ব্যানার';
    }

    // ============================================
    // METHODS
    // ============================================

    /**
     * স্লাইডার সক্রিয় কিনা চেক করুন
     */
    public function isActive(): bool
    {
        if (!$this->status) {
            return false;
        }

        $now = now();

        if ($this->start_date && $this->start_date > $now) {
            return false;
        }

        if ($this->end_date && $this->end_date < $now) {
            return false;
        }

        return true;
    }

    /**
     * স্লাইডারের লিংক আছে কিনা
     */
    public function hasLink(): bool
    {
        return !empty($this->button_link) || !empty($this->link);
    }

    // ============================================
    // BOOT
    // ============================================

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($slider) {
            if (empty($slider->sort_order)) {
                $slider->sort_order = static::max('sort_order') + 1;
            }

            if (empty($slider->position)) {
                $slider->position = 'homepage';
            }

            if (empty($slider->target)) {
                $slider->target = '_self';
            }
        });
    }
}
