<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class BlogComment extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'blog_comments';

    protected $fillable = [
        'blog_id',
        'user_id',
        'name',
        'email',
        'comment',
        'parent_id',
        'is_approved',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'is_approved' => 'boolean',
        'parent_id' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // =============================================
    // Relationships
    // =============================================

    /**
     * মন্তব্যটি কোন ব্লগের
     */
    public function blog()
    {
        return $this->belongsTo(Blog::class);
    }

    /**
     * মন্তব্যটি কোন ইউজারের (যদি লগইন করা থাকে)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * প্যারেন্ট মন্তব্য (যার উত্তর এই মন্তব্য)
     */
    public function parent()
    {
        return $this->belongsTo(BlogComment::class, 'parent_id');
    }

    /**
     * এই মন্তব্যের উত্তরসমূহ
     */
    public function replies()
    {
        return $this->hasMany(BlogComment::class, 'parent_id')->where('is_approved', true);
    }

    /**
     * এই মন্তব্যের সব উত্তর (অ্যাপ্রুভ ছাড়া)
     */
    public function allReplies()
    {
        return $this->hasMany(BlogComment::class, 'parent_id');
    }

    /**
     * অ্যাপ্রুভড রিপ্লাই
     */
    public function approvedReplies()
    {
        return $this->replies()->where('is_approved', true);
    }

    /**
     * পেন্ডিং রিপ্লাই
     */
    public function pendingReplies()
    {
        return $this->replies()->where('is_approved', false);
    }

    // =============================================
    // Accessors
    // =============================================

    /**
     * ফরম্যাটেড তৈরি তারিখ (ইংরেজি)
     */
    public function getFormattedDateAttribute()
    {
        return $this->created_at->format('F j, Y \a\t g:i A');
    }

    /**
     * ফরম্যাটেড তৈরি তারিখ (সংক্ষিপ্ত)
     */
    public function getShortDateAttribute()
    {
        return $this->created_at->format('M j, Y');
    }

    /**
     * ফরম্যাটেড তৈরি তারিখ (বাংলা)
     */
    public function getFormattedDateBanglaAttribute()
    {
        $banglaMonths = [
            'January' => 'জানুয়ারি', 'February' => 'ফেব্রুয়ারি', 'March' => 'মার্চ',
            'April' => 'এপ্রিল', 'May' => 'মে', 'June' => 'জুন',
            'July' => 'জুলাই', 'August' => 'আগস্ট', 'September' => 'সেপ্টেম্বর',
            'October' => 'অক্টোবর', 'November' => 'নভেম্বর', 'December' => 'ডিসেম্বর'
        ];

        $banglaNumbers = ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯'];
        $banglaTimeNumbers = ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯'];

        $englishMonth = $this->created_at->format('F');
        $banglaMonth = $banglaMonths[$englishMonth] ?? $englishMonth;

        $day = str_replace(range(0, 9), $banglaNumbers, $this->created_at->format('d'));
        $year = str_replace(range(0, 9), $banglaNumbers, $this->created_at->format('Y'));
        $hour = str_replace(range(0, 9), $banglaTimeNumbers, $this->created_at->format('g'));
        $minute = str_replace(range(0, 9), $banglaTimeNumbers, $this->created_at->format('i'));
        $ampm = $this->created_at->format('A') == 'AM' ? 'সকাল' : 'রাত';

        return "{$banglaMonth} {$day}, {$year} {$ampm} {$hour}:{$minute}";
    }

    /**
     * সময় আগে (যেমন: ২ ঘন্টা আগে)
     */
    public function getTimeAgoAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    /**
     * মন্তব্যের এক্সসার্প্ট (সংক্ষিপ্ত)
     */
    public function getExcerptAttribute($length = 100)
    {
        return Str::limit(strip_tags($this->comment), $length);
    }

    /**
     * মন্তব্যকারীর নাম (ইউজার নেইলে গেস্ট নাম)
     */
    public function getCommenterNameAttribute()
    {
        if ($this->user) {
            return $this->user->name;
        }
        return $this->name ?? 'গেস্ট ইউজার';
    }

    /**
     * মন্তব্যকারীর ইমেইল
     */
    public function getCommenterEmailAttribute()
    {
        if ($this->user) {
            return $this->user->email;
        }
        return $this->email;
    }

    /**
     * মন্তব্যকারীর আভাতার (Gravatar)
     */
    public function getAvatarAttribute()
    {
        $email = $this->getCommenterEmailAttribute();
        $hash = md5(strtolower(trim($email)));
        return "https://www.gravatar.com/avatar/{$hash}?s=50&d=mp";
    }

    /**
     * রিপ্লাই কাউন্ট
     */
    public function getRepliesCountAttribute()
    {
        return $this->approvedReplies()->count();
    }

    /**
     * এই মন্তব্যের URL
     */
    public function getUrlAttribute()
    {
        return $this->blog->url . '#comment-' . $this->id;
    }

    // =============================================
    // Scopes
    // =============================================

    /**
     * শুধুমাত্র অ্যাপ্রুভড মন্তব্য
     */
    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }

    /**
     * শুধুমাত্র পেন্ডিং মন্তব্য
     */
    public function scopePending($query)
    {
        return $query->where('is_approved', false);
    }

    /**
     * শুধুমাত্র প্যারেন্ট মন্তব্য (যার উত্তর নেই)
     */
    public function scopeParentOnly($query)
    {
        return $query->whereNull('parent_id');
    }

    /**
     * শুধুমাত্র রিপ্লাই মন্তব্য
     */
    public function scopeRepliesOnly($query)
    {
        return $query->whereNotNull('parent_id');
    }

    /**
     * সাম্প্রতিক মন্তব্য
     */
    public function scopeRecent($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    /**
     * পুরাতন মন্তব্য
     */
    public function scopeOldest($query)
    {
        return $query->orderBy('created_at', 'asc');
    }

    /**
     * নির্দিষ্ট ব্লগের মন্তব্য
     */
    public function scopeForBlog($query, $blogId)
    {
        return $query->where('blog_id', $blogId);
    }

    /**
     * নির্দিষ্ট ইউজারের মন্তব্য
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * নির্দিষ্ট আইপি থেকে মন্তব্য
     */
    public function scopeFromIp($query, $ip)
    {
        return $query->where('ip_address', $ip);
    }

    /**
     * টুডে মন্তব্য
     */
    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }

    /**
     * এই সপ্তাহের মন্তব্য
     */
    public function scopeThisWeek($query)
    {
        return $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
    }

    /**
     * এই মাসের মন্তব্য
     */
    public function scopeThisMonth($query)
    {
        return $query->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year);
    }

    // =============================================
    // Methods
    // =============================================

    /**
     * মন্তব্য অ্যাপ্রুভ করা
     */
    public function approve()
    {
        return $this->update(['is_approved' => true]);
    }

    /**
     * মন্তব্য ডিসঅ্যাপ্রুভ করা
     */
    public function disapprove()
    {
        return $this->update(['is_approved' => false]);
    }

    /**
     * মন্তব্য অ্যাপ্রুভড কিনা
     */
    public function isApproved()
    {
        return $this->is_approved;
    }

    /**
     * মন্তব্য পেন্ডিং কিনা
     */
    public function isPending()
    {
        return !$this->is_approved;
    }

    /**
     * রিপ্লাই আছে কিনা
     */
    public function hasReplies()
    {
        return $this->approvedReplies()->count() > 0;
    }

    /**
     * ইউজার লগইন করা কিনা
     */
    public function isGuest()
    {
        return is_null($this->user_id);
    }

    /**
     * মন্তব্য এডিট করার অনুমতি
     */
    public function canEdit($userId)
    {
        return $this->user_id == $userId;
    }

    /**
     * মন্তব্য ডিলিট করার অনুমতি
     */
    public function canDelete($userId)
    {
        return $this->user_id == $userId;
    }

    /**
     * এই মন্তব্যের রিপ্লাই লোড করা
     */
    public function loadReplies()
    {
        return $this->replies()->with('user')->get();
    }

    /**
     * রিপ্লাই ট্রি তৈরি করা
     */
    public function getReplyTree()
    {
        $replies = $this->approvedReplies()->with('user')->get();
        return $replies;
    }

    // =============================================
    // Static Methods
    // =============================================

    /**
     * আজকের মন্তব্য কাউন্ট
     */
    public static function todayCount()
    {
        return static::whereDate('created_at', today())->count();
    }

    /**
     * পেন্ডিং মন্তব্য কাউন্ট
     */
    public static function pendingCount()
    {
        return static::where('is_approved', false)->count();
    }

    /**
     * মোট মন্তব্য কাউন্ট
     */
    public static function totalCount()
    {
        return static::count();
    }

    /**
     * সাম্প্রতিক মন্তব্য লিস্ট
     */
    public static function getRecent($limit = 10)
    {
        return static::approved()
            ->with('blog', 'user')
            ->recent()
            ->limit($limit)
            ->get();
    }

    // =============================================
    // Boot Method
    // =============================================

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($comment) {
            if (empty($comment->ip_address) && request()) {
                $comment->ip_address = request()->ip();
            }
            if (empty($comment->user_agent) && request()) {
                $comment->user_agent = request()->userAgent();
            }
        });

        static::deleting(function ($comment) {
            // সব রিপ্লাই ডিলিট করা
            if ($comment->isForceDeleting()) {
                $comment->allReplies()->forceDelete();
            } else {
                $comment->allReplies()->delete();
            }
        });
    }
}
