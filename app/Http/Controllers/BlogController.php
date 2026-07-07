<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\BlogComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class BlogController extends Controller
{
    /**
     * ব্লগ হোম পেজ
     */
    public function index(Request $request)
    {
        try {
            $query = Blog::published()
                ->with(['author', 'category', 'comments'])
                ->orderBy('published_at', 'desc');

            // সার্চ
            if ($request->filled('search')) {
                $query->search($request->search);
            }

            // ক্যাটাগরি ফিল্টার
            if ($request->filled('category')) {
                $query->byCategory($request->category);
            }

            // ট্যাগ ফিল্টার
            if ($request->filled('tag')) {
                $query->byTag($request->tag);
            }

            $blogs = $query->paginate(12);

            // সাইডবার ডাটা (ক্যাশে রাখা)
            $categories = Cache::remember('blog_categories', 3600, function () {
                return BlogCategory::active()->ordered()->get();
            });

            $popularPosts = Cache::remember('blog_popular_posts', 3600, function () {
                return Blog::published()->popular()->take(5)->get();
            });

            $recentPosts = Cache::remember('blog_recent_posts', 3600, function () {
                return Blog::published()->recent()->take(5)->get();
            });

            // সব ট্যাগ
            $allTags = Cache::remember('blog_all_tags', 3600, function () {
                return Blog::published()
                    ->pluck('tags')
                    ->flatten()
                    ->filter()
                    ->map(function ($tag) {
                        return trim($tag);
                    })
                    ->unique()
                    ->values()
                    ->take(30)
                    ->toArray();
            });

            return view('theme::pages.blog.index', compact(
                'blogs',
                'categories',
                'popularPosts',
                'recentPosts',
                'allTags'
            ));

        } catch (\Exception $e) {
            Log::error('Blog index error: ' . $e->getMessage());
            return back()->with('error', 'ব্লগ লোড করতে সমস্যা হয়েছে!');
        }
    }

    /**
     * ব্লগ ডিটেইল পেজ
     */
    public function show($slug)
    {
        try {
            $blog = Blog::published()
                ->with(['author', 'category', 'comments.user', 'comments.replies'])
                ->where(function ($q) use ($slug) {
                    $q->where('slug', $slug)
                      ->orWhere('slug', urlencode($slug))
                      ->orWhere('slug', strtolower(urlencode($slug)));
                })
                ->firstOrFail();

            // ভিউ ইনক্রিমেন্ট (সেশন ভিত্তিক ভিউ কাউন্ট প্রটেকশন)
            $viewedKey = 'viewed_blog_' . $blog->id;
            if (!session()->has($viewedKey)) {
                $blog->incrementViews();
                session()->put($viewedKey, true);
            }

            // রিলেটেড পোস্ট
            $relatedPosts = $blog->getRelatedPosts(3);

            // সাইডবার ডাটা
            $categories = Cache::remember('blog_categories', 3600, function () {
                return BlogCategory::active()->ordered()->get();
            });

            $popularPosts = Cache::remember('blog_popular_posts', 3600, function () {
                return Blog::published()->popular()->take(5)->get();
            });

            $recentPosts = Cache::remember('blog_recent_posts', 3600, function () {
                return Blog::published()->recent()->take(5)->get();
            });

            $allTags = Cache::remember('blog_all_tags', 3600, function () {
                return Blog::published()
                    ->pluck('tags')
                    ->flatten()
                    ->filter()
                    ->map(function ($tag) {
                        return trim($tag);
                    })
                    ->unique()
                    ->values()
                    ->take(30)
                    ->toArray();
            });

            return view('theme::pages.blog.show', compact(
                'blog',
                'relatedPosts',
                'categories',
                'popularPosts',
                'recentPosts',
                'allTags'
            ));

        } catch (\Exception $e) {
            Log::error('Blog show error: ' . $e->getMessage());
            abort(404, 'ব্লগ পোস্টটি পাওয়া যায়নি!');
        }
    }

    /**
     * ক্যাটাগরি ভিত্তিক ব্লগ
     */
    public function category($slug)
    {
        try {
            $category = BlogCategory::where('slug', $slug)->firstOrFail();

            $blogs = Blog::published()
                ->with(['author'])
                ->where('category_id', $category->id)
                ->orderBy('published_at', 'desc')
                ->paginate(12);

            $categories = Cache::remember('blog_categories', 3600, function () {
                return BlogCategory::active()->ordered()->get();
            });

            $popularPosts = Cache::remember('blog_popular_posts', 3600, function () {
                return Blog::published()->popular()->take(5)->get();
            });

            $recentPosts = Cache::remember('blog_recent_posts', 3600, function () {
                return Blog::published()->recent()->take(5)->get();
            });

            return view('theme::pages.blog.category', compact(
                'category',
                'blogs',
                'categories',
                'popularPosts',
                'recentPosts'
            ));

        } catch (\Exception $e) {
            Log::error('Blog category error: ' . $e->getMessage());
            abort(404, 'ক্যাটাগরিটি পাওয়া যায়নি!');
        }
    }

    /**
     * ট্যাগ ভিত্তিক ব্লগ
     */
    public function tag($tag)
    {
        try {
            // ✅ URL ডিকোড
            $decodedTag = urldecode($tag);

            $blogs = Blog::published()
                ->with(['author', 'category'])
                ->byTag($decodedTag)  // ← Scope ব্যবহার
                ->orderBy('published_at', 'desc')
                ->paginate(12);

            $categories = Cache::remember('blog_categories', 3600, function () {
                return BlogCategory::active()->ordered()->get();
            });

            $popularPosts = Cache::remember('blog_popular_posts', 3600, function () {
                return Blog::published()->popular()->take(5)->get();
            });

            $recentPosts = Cache::remember('blog_recent_posts', 3600, function () {
                return Blog::published()->recent()->take(5)->get();
            });

            return view('theme::pages.blog.tag', compact(
                'blogs',
                'tag',
                'categories',
                'popularPosts',
                'recentPosts'
            ));

        } catch (\Exception $e) {
            Log::error('Blog tag error: ' . $e->getMessage());
            return back()->with('error', 'ট্যাগ লোড করতে সমস্যা হয়েছে!');
        }
    }
    /**
     * সার্চ ব্লগ
     */
    public function search(Request $request)
    {
        try {
            $query = $request->get('q');

            if (empty($query)) {
                return redirect()->route('blog');
            }

            $blogs = Blog::published()
                ->with(['author', 'category'])
                ->search($query)
                ->orderBy('published_at', 'desc')
                ->paginate(12);

            $categories = Cache::remember('blog_categories', 3600, function () {
                return BlogCategory::active()->ordered()->get();
            });

            $popularPosts = Cache::remember('blog_popular_posts', 3600, function () {
                return Blog::published()->popular()->take(5)->get();
            });

            $recentPosts = Cache::remember('blog_recent_posts', 3600, function () {
                return Blog::published()->recent()->take(5)->get();
            });

            return view('theme::pages.blog.search', compact(
                'blogs',
                'query',
                'categories',
                'popularPosts',
                'recentPosts'
            ));

        } catch (\Exception $e) {
            Log::error('Blog search error: ' . $e->getMessage());
            return back()->with('error', 'সার্চ করতে সমস্যা হয়েছে!');
        }
    }

    /**
     * কমেন্ট সাবমিট
     */
    public function comment(Request $request, $blogId)
    {
        try {
            $validator = Validator::make($request->all(), [
                'comment' => 'required|string|min:3|max:1000',
                'name' => 'required_if:guest,true|string|max:100',
                'email' => 'required_if:guest,true|email|max:100',
                'parent_id' => 'nullable|exists:blog_comments,id',
                'website_url' => 'present|max:0' // Honeypot spam protection
            ]);

            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }

            $blog = Blog::findOrFail($blogId);

            $comment = new BlogComment();
            $comment->blog_id = $blog->id;
            $comment->comment = $request->comment;
            $comment->parent_id = $request->parent_id;
            $comment->is_approved = false;

            if (auth()->check()) {
                $comment->user_id = auth()->id();
                $comment->name = auth()->user()->name;
                $comment->email = auth()->user()->email;
            } else {
                $comment->name = $request->name;
                $comment->email = $request->email;
            }

            $comment->ip_address = $request->ip();
            $comment->user_agent = $request->userAgent();

            $comment->save();

            return back()->with('success', 'আপনার কমেন্টটি সফলভাবে জমা হয়েছে। মডারেশনের পরে প্রকাশ করা হবে।');

        } catch (\Exception $e) {
            Log::error('Blog comment error: ' . $e->getMessage());
            return back()->with('error', 'কমেন্ট জমা দিতে সমস্যা হয়েছে!');
        }
    }

    /**
     * কমেন্ট ডিলিট (AJAX)
     */
    public function deleteComment($id)
    {
        try {
            $comment = BlogComment::findOrFail($id);

            // পারমিশন চেক
            if (auth()->check() && (auth()->id() == $comment->user_id || auth()->user()->isAdmin())) {
                $comment->delete();
                return response()->json([
                    'success' => true,
                    'message' => 'কমেন্ট ডিলিট করা হয়েছে!'
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'আপনার এই কমেন্ট ডিলিট করার অনুমতি নেই!'
            ], 403);

        } catch (\Exception $e) {
            Log::error('Blog delete comment error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'কমেন্ট ডিলিট করতে সমস্যা হয়েছে!'
            ], 500);
        }
    }

    /**
     * ব্লগ ক্যাশ রিফ্রেশ (অ্যাডমিন কল করবে)
     */
    public function refreshCache()
    {
        try {
            Cache::forget('blog_categories');
            Cache::forget('blog_popular_posts');
            Cache::forget('blog_recent_posts');
            Cache::forget('blog_all_tags');

            return response()->json([
                'success' => true,
                'message' => 'ব্লগ ক্যাশ রিফ্রেশ করা হয়েছে!'
            ]);

        } catch (\Exception $e) {
            Log::error('Blog cache refresh error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'ক্যাশ রিফ্রেশ করতে সমস্যা হয়েছে!'
            ], 500);
        }
    }

    /**
     * ব্লগ পোস্ট আর্চিভ (সব পোস্ট দেখাবে)
     */
   /**
 * ব্লগ আর্কাইভ
 */
public function archive(Request $request)
{
    try {
        $query = Blog::published()
            ->with(['author', 'category']);

        // ক্যাটাগরি ফিল্টার
        if ($request->filled('category')) {
            $query->byCategory($request->category);
        }

        // সর্টিং
        switch ($request->get('sort', 'newest')) {
            case 'oldest':
                $query->orderBy('published_at', 'asc');
                break;
            case 'popular':
                $query->orderBy('average_rating', 'desc');
                break;
            case 'views':
                $query->orderBy('views', 'desc');
                break;
            case 'newest':
            default:
                $query->orderBy('published_at', 'desc');
                break;
        }

        $blogs = $query->paginate(24);

        $categories = Cache::remember('blog_categories', 3600, function () {
            return BlogCategory::active()->ordered()->get();
        });

        return view('theme::pages.blog.archive', compact(
            'blogs',
            'categories'
        ));

    } catch (\Exception $e) {
        Log::error('Blog archive error: ' . $e->getMessage());
        return back()->with('error', 'আর্কাইভ লোড করতে সমস্যা হয়েছে!');
    }
}

    /**
     * ঘোষণা ও বিবৃতি লিস্টিং পেজ
     */
    public function announcements()
    {
        try {
            $blogs = Blog::published()
                ->whereHas('category', function ($query) {
                    $query->where('slug', 'movement-news');
                })
                ->with(['author', 'category'])
                ->orderBy('published_at', 'desc')
                ->paginate(12);

            $categories = Cache::remember('blog_categories', 3600, function () {
                return BlogCategory::active()->ordered()->get();
            });

            $popularPosts = Cache::remember('blog_popular_posts', 3600, function () {
                return Blog::published()->popular()->take(5)->get();
            });

            $recentPosts = Cache::remember('blog_recent_posts', 3600, function () {
                return Blog::published()->recent()->take(5)->get();
            });

            $allTags = Cache::remember('blog_all_tags', 3600, function () {
                return Blog::published()
                    ->pluck('tags')
                    ->flatten()
                    ->filter()
                    ->map(function ($tag) {
                        return trim($tag);
                    })
                    ->unique()
                    ->values()
                    ->take(30)
                    ->toArray();
            });

            return view('theme::pages.announcements', compact(
                'blogs',
                'categories',
                'popularPosts',
                'recentPosts',
                'allTags'
            ));

        } catch (\Exception $e) {
            Log::error('Announcements index error: ' . $e->getMessage());
            return back()->with('error', 'ঘোষণাসমূহ লোড করতে সমস্যা হয়েছে!');
        }
    }

    /**
     * ইভেন্ট ও অনুষ্ঠানসমূহ লিস্টিং পেজ
     */
    public function events()
    {
        try {
            $blogs = Blog::published()
                ->whereHas('category', function ($query) {
                    $query->where('slug', 'events-and-programs');
                })
                ->with(['author', 'category'])
                ->orderBy('published_at', 'desc')
                ->paginate(12);

            $categories = Cache::remember('blog_categories', 3600, function () {
                return BlogCategory::active()->ordered()->get();
            });

            $popularPosts = Cache::remember('blog_popular_posts', 3600, function () {
                return Blog::published()->popular()->take(5)->get();
            });

            $recentPosts = Cache::remember('blog_recent_posts', 3600, function () {
                return Blog::published()->recent()->take(5)->get();
            });

            $allTags = Cache::remember('blog_all_tags', 3600, function () {
                return Blog::published()
                    ->pluck('tags')
                    ->flatten()
                    ->filter()
                    ->map(function ($tag) {
                        return trim($tag);
                    })
                    ->unique()
                    ->values()
                    ->take(30)
                    ->toArray();
            });

            return view('theme::pages.events', compact(
                'blogs',
                'categories',
                'popularPosts',
                'recentPosts',
                'allTags'
            ));

        } catch (\Exception $e) {
            Log::error('Events index error: ' . $e->getMessage());
            return back()->with('error', 'ইভেন্টসমূহ লোড করতে সমস্যা হয়েছে!');
        }
    }

    /**
     * আরএসএস নিউজ ফিড (RSS Feed) জেনারেট করুন
     */
    public function feed()
    {
        $blogs = Blog::published()
            ->latest('published_at')
            ->limit(20)
            ->get();

        return response()
            ->view('theme::pages.blog.feed', compact('blogs'))
            ->header('Content-Type', 'text/xml');
    }
}
