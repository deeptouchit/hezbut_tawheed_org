<?php

namespace App\Http\Controllers;

use App\Models\Slider;
use App\Models\Blog;
use App\Models\TeamMember;
use App\Models\Testimonial;
use App\Models\Activity;
use App\Models\Book;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * রাজনৈতিক পোর্টাল হোম পেজ দেখান
     */
    public function index()
    {
        // ১. স্লাইডার (সক্রিয় + হোম পেজ + সর্ট অর্ডার)
        $sliders = Slider::active()
            ->homepage()
            ->ordered()
            ->get();

        // ২. সর্বশেষ সংবাদ ও বিবৃতি (ব্লগ পোস্ট - সর্বোচ্চ ৩টি)
        $blogs = Blog::published()
            ->with(['author', 'category'])
            ->orderBy('published_at', 'desc')
            ->orderBy('id', 'desc')
            ->take(3)
            ->get();

        // ২.১. সাম্প্রতিক অনুষ্ঠান (সর্বোচ্চ ৩টি)
        $recentEvents = Blog::published()
            ->whereHas('category', function ($query) {
                $query->where('name', 'সাম্প্রতিক অনুষ্ঠান')
                      ->orWhere('slug', 'recent-events');
            })
            ->with(['author', 'category'])
            ->orderBy('published_at', 'desc')
            ->orderBy('id', 'desc')
            ->take(3)
            ->get();

        // ৩. কেন্দ্রীয় নেতৃবৃন্দ (টিম মেম্বার - সক্রিয় ও সর্ট অর্ডার)
        $teamMembers = TeamMember::where('is_active', true)
            ->orderBy('sort_order', 'asc')
            ->orderBy('name', 'asc')
            ->take(4)
            ->get();

        // ৪. নাগরিকদের মতামত / উদ্ধৃতি (টেস্টিমোনিয়াল)
        $testimonials = Testimonial::where('is_active', true)
            ->orderBy('sort_order', 'asc')
            ->take(20)
            ->get();

        // ৪.১. পথপ্রদর্শক ও বর্তমান নেতৃত্ব (লিডার)
        $founder = \App\Models\Leader::where('is_founder', true)->first();
        $currentLeader = \App\Models\Leader::where('is_founder', false)->orderBy('sort_order', 'asc')->first();

        // ৪.২. হোমপেজ বিল্ডার সেটিংস ও লেআউট
        $layoutSetting = \App\Models\Setting::where('key', 'homepage_sections_layout')->first();
        $homepageLayout = $layoutSetting ? json_decode($layoutSetting->value, true) : [];

        $contentSetting = \App\Models\Setting::where('key', 'homepage_sections_content')->first();
        $homepageContent = $contentSetting ? json_decode($contentSetting->value, true) : [];

        $cssSetting = \App\Models\Setting::where('key', 'homepage_custom_css')->first();
        $homepageCss = $cssSetting ? $cssSetting->value : '';

        // ৫. আমাদের কার্যক্রম (ব্লগ পোস্টের 'activities' ক্যাটাগরি থেকে - সর্বোচ্চ ৩টি)
        $activities = Blog::where('status', true)
            ->whereHas('category', function ($q) {
                $q->where('slug', 'activities');
            })
            ->orderBy('published_at', 'desc')
            ->take(3)
            ->get();

        // ৫.১. চিত্রশালার জন্য ফিচার্ড ইমেজ যুক্ত সর্বশেষ ৮টি ব্লগ পোস্ট (এন্টারপ্রাইজ গ্যালারি কুয়েরি ও ক্যাশিং)
        $galleryPosts = \Cache::remember('home_gallery_posts', 3600, function () {
            return Blog::published()
                ->where('is_gallery', true)
                ->whereNotNull('featured_image')
                ->where('featured_image', '!=', '')
                ->select('id', 'title', 'slug', 'featured_image', 'category_id', 'published_at')
                ->with('category:id,name,slug')
                ->orderBy('gallery_order', 'asc')
                ->orderBy('published_at', 'desc')
                ->orderBy('id', 'desc')
                ->take(8)
                ->get()
                ->filter(function($post) {
                    if (empty($post->featured_image)) return false;
                    $path = public_path($post->featured_image);
                    return file_exists($path) && filesize($path) > 0;
                });
        });

        // ভিউতে ডেটা পাঠানো
        return view('theme::pages.home', compact(
            'sliders',
            'blogs',
            'recentEvents',
            'teamMembers',
            'testimonials',
            'founder',
            'currentLeader',
            'homepageLayout',
            'homepageContent',
            'homepageCss',
            'activities',
            'galleryPosts'
        ));
    }

    public function page($slug)
    {
        $page = \App\Models\Page::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        if ($slug === 'publications') {
            // 1. Fetch 10 popular books from DB, sorted by popular_order
            $popularBooks = Book::where('is_active', true)
                ->where('is_popular', true)
                ->orderBy('popular_order', 'asc')
                ->orderBy('created_at', 'desc')
                ->take(10)
                ->get();

            // 2. Fetch all other books for bottom grid (excluding popular ones to avoid duplication)
            $popularIds = $popularBooks->pluck('id')->toArray();
            $books = Book::where('is_active', true)
                ->whereNotIn('id', $popularIds)
                ->orderBy('created_at', 'desc')
                ->paginate(12);

            return view('theme::pages.books.index', compact('page', 'popularBooks', 'books'));
        }

        return view('theme::pages.page', compact('page'));
    }

    /**
     * আমাদের কার্যক্রম লিস্টিং পেজ
     */
    public function activities()
    {
        return redirect()->route('blog.category', 'activities', 301);
    }

    /**
     * কার্যক্রমের বিস্তারিত পেজ
     */
    public function activityShow($slug)
    {
        return redirect()->route('blog.detail', $slug, 301);
    }

    /**
     * আমাদের বইসমূহ লিস্টিং পেজ
     */
    public function books()
    {
        return redirect()->route('page.show', 'publications', 301);
    }

    /**
     * Ajax load more books
     */
    public function loadMoreBooks(Request $request)
    {
        $page = intval($request->get('page', 2));
        $perPage = 12;

        // Exclude popular books here too to prevent duplicates in dynamic scroll
        $popularIds = Book::where('is_active', true)
            ->where('is_popular', true)
            ->orderBy('popular_order', 'asc')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->pluck('id')
            ->toArray();

        $books = Book::where('is_active', true)
            ->whereNotIn('id', $popularIds)
            ->orderBy('created_at', 'desc')
            ->paginate($perPage, ['*'], 'page', $page);

        if ($request->ajax()) {
            $html = view('theme::pages.books.partials.grid_items', compact('books'))->render();
            return response()->json([
                'success' => true,
                'html' => $html,
                'hasMore' => $books->hasMorePages()
            ]);
        }

        return redirect()->route('books.index');
    }

    /**
     * বইয়ের বিস্তারিত পেজ
     */
    public function bookShow($slug)
    {
        $book = Book::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        // Fetch other books for sidebar
        $recentBooks = Book::where('id', '!=', $book->id)
            ->where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('theme::pages.books.show', compact('book', 'recentBooks'));
    }

    /**
     * ডিজিটাল লাইব্রেরি লিস্টিং পেজ
     */
    public function library(Request $request)
    {
        $query = Book::where('is_active', true);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('writer', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $books = $query->orderBy('title', 'asc')->paginate(16);

        return view('theme::pages.library.index', compact('books'));
    }

    /**
     * ডিজিটাল লাইব্রেরি রিডার পেজ
     */
    public function libraryRead($slug)
    {
        $book = Book::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        // Fetch other books for quick navigation in sidebar
        $otherBooks = Book::where('id', '!=', $book->id)
            ->where('is_active', true)
            ->orderBy('title', 'asc')
            ->get();

        return view('theme::pages.library.read', compact('book', 'otherBooks'));
    }

    /**
     * ভিডিও গ্যালারী পেজ (ইউটিউব ভিডিও)
     */
    public function videos(Request $request)
    {
        $videos = \App\Models\Video::where('is_active', true)
            ->orderBy('sort_order', 'asc')
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('theme::pages.videos.index', compact('videos'));
    }

    /**
     * মতামত (Feedback) পেজ দেখান
     */
    public function feedback()
    {
        $feedbacks = Testimonial::active()
            ->ordered()
            ->paginate(12);

        return view('theme::pages.feedback', compact('feedbacks'));
    }

    /**
     * মতামত ফর্ম সাবমিট
     */
    public function submitFeedback(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:100',
                'email' => 'required|email|max:100',
                'phone' => 'nullable|string|max:20',
                'designation' => 'required|string|max:100',
                'content' => 'required|string|min:5|max:3000',
                'rating' => 'required|integer|min:1|max:5',
            ]);

            // Save to testimonials table (is_active = false for admin moderation)
            Testimonial::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'designation' => $request->designation,
                'content' => $request->content,
                'rating' => $request->rating,
                'is_active' => false, // requires admin approval
                'company' => 'সাধারণ নাগরিক',
            ]);

            return back()->with('success', 'আপনার মূল্যবান মতামত পাঠানোর জন্য ধন্যবাদ! এটি মডারেটরের অনুমোদনের পর ওয়েবসাইটে প্রকাশ করা হবে।');

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Feedback submit error: ' . $e->getMessage());
            return back()
                ->withInput()
                ->with('error', 'মতামত পাঠাতে সমস্যা হয়েছে! দয়া করে সব তথ্য পূরণ করে পুনরায় চেষ্টা করুন।');
        }
    }

    /**
     * যোগদান (Join) পেজ দেখান
     */
    public function join()
    {
        return view('theme::pages.join');
    }

    /**
     * যোগদান ফর্ম সাবমিট
     */
    public function submitJoin(Request $request)
    {
        try {
            $request->validate([
                'membership_type' => 'required|string|in:primary,pledge',
                'name' => 'required|string|max:100',
                'dob' => 'nullable|date',
                'father_husband' => 'nullable|string|max:100',
                'phone' => 'required|string|max:25',
                'present_address' => 'nullable|string|max:500',
                'permanent_address' => 'nullable|string|max:500',
                'occupation' => 'nullable|string|max:100',
                'education' => 'nullable|string|max:100',
                'experience' => 'nullable|string|max:500',
                'how_knew' => 'required|string|max:100',
                'person_name' => 'nullable|string|max:100',
                'person_phone' => 'nullable|string|max:25',
            ]);

            // Save inside contact_messages for admin processing with special subject and metadata
            $howKnewStr = $request->how_knew;
            $typeLabel = $request->membership_type === 'primary' ? 'প্রাথমিক সদস্য পদ' : 'পাঁচ দফা ভিত্তিক অঙ্গীকার পত্র';

            $msgContent = "যোগদানের আবেদন টাইপ: {$typeLabel}\n" .
                          "জন্ম তারিখ: " . ($request->dob ?? 'N/A') . "\n" .
                          "পিতা / স্বামীর নাম: " . ($request->father_husband ?? 'N/A') . "\n" .
                          "বর্তমান ঠিকানা: " . ($request->present_address ?? 'N/A') . "\n" .
                          "স্থায়ী ঠিকানা: " . ($request->permanent_address ?? 'N/A') . "\n" .
                          "পেশা: " . ($request->occupation ?? 'N/A') . "\n" .
                          "শিক্ষাগত যোগ্যতা: " . ($request->education ?? 'N/A') . "\n" .
                          "দক্ষতা / পারদর্শিতা: " . ($request->experience ?? 'N/A') . "\n" .
                          "আন্দোলন সম্পর্কে জানার উপায়: {$howKnewStr}\n" .
                          "পরিচিত ব্যক্তির নাম: " . ($request->person_name ?? 'N/A') . "\n" .
                          "পরিচিত ব্যক্তির মোবাইল নম্বর: " . ($request->person_phone ?? 'N/A');

            \App\Models\ContactMessage::create([
                'name' => $request->name,
                'email' => 'membership@hezbuttawheed.org',
                'phone' => $request->phone,
                'subject' => "নতুন সদস্য পদের আবেদন - ({$typeLabel})",
                'message' => $msgContent,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'status' => 'unread',
            ]);

            return back()->with('success', 'আপনার সদস্য পদের আবেদনটি সফলভাবে গৃহীত হয়েছে! আমরা খুব শীঘ্রই আপনার সাথে যোগাযোগ করব ইনশাআল্লাহ।');

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Join submit error: ' . $e->getMessage());
            return back()
                ->withInput()
                ->with('error', 'আবেদনপত্র জমা দিতে সমস্যা হয়েছে! দয়া করে সব তথ্য সঠিকভাবে পূরণ করে পুনরায় চেষ্টা করুন।');
        }
    }
}
