<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\BlogComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class BlogController extends Controller
{
      /**
     * Display a listing of blogs.
     */
    public function index(Request $request)
    {
        $query = Blog::with(['author', 'category']);

          // Apply filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->search($search);
        }

        if ($request->filled('category')) {
            $query->byCategory($request->category);
        }

        if ($request->filled('status')) {
            if ($request->status == 'published') {
                $query->published();
            } elseif ($request->status == 'draft') {
                $query->where('status', false);
            }
        }

        if ($request->filled('date_from')) {
            $query->whereDate('published_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('published_at', '<=', $request->date_to);
        }

        if ($request->filled('author')) {
            $query->where('author_id', $request->author);
        }

        if ($request->filled('gallery')) {
            if ($request->gallery == 'gallery') {
                $query->where('is_gallery', true);
            } elseif ($request->gallery == 'non_gallery') {
                $query->where('is_gallery', false);
            }
        }

          // Sorting
        $sortField         = $request->get('sort', 'published_at');
        $sortDirection     = $request->get('direction', 'desc');
        $allowedSortFields = ['id', 'title', 'views', 'status', 'published_at', 'created_at', 'sort_order', 'gallery_order'];

        if (in_array($sortField, $allowedSortFields)) {
            $query->orderBy($sortField, $sortDirection);
        } else {
            $query->orderBy('published_at', 'desc');
        }

        $perPage = $request->get('per_page', 20);
        $perPageValue = ($perPage == '-1') ? max(1, $query->count()) : (int)$perPage;

        if ($request->ajax()) {
            $blogs = $query->paginate($perPageValue);
            $html  = view('admin.blog.partials.table', compact('blogs'))->render();
            return response()->json([
                'success'    => true,
                'html'       => $html,
                'total'      => $blogs->total(),
                'pagination' => [
                    'total'        => $blogs->total(),
                    'current_page' => $blogs->currentPage(),
                    'last_page'    => $blogs->lastPage(),
                ]
            ]);
        }

        $blogs = $query->paginate($perPageValue);

          // Get filter options
        $categories = BlogCategory::active()->ordered()->get();
        $authors    = \App\Models\User::where('status', true)->get(['id', 'name']);

          // Statistics
        $stats = [
            'total'            => Blog::count(),
            'published'        => Blog::published()->count(),
            'draft'            => Blog::where('status', false)->count(),
            'total_views'      => Blog::sum('views'),
            'categories'       => BlogCategory::count(),
            'comments'         => BlogComment::where('is_approved', true)->count(),
            'pending_comments' => BlogComment::where('is_approved', false)->count(),
            'this_month'       => Blog::thisMonth()->count(),
            'this_week'        => Blog::thisWeek()->count(),
        ];

        return view('admin.blog.index', compact('blogs', 'categories', 'stats', 'authors'));
    }

      /**
     * Show form for creating new blog.
     */
    public function create()
    {
        $categories = BlogCategory::active()->ordered()->get();
        $tags       = $this->getAllTags();
        $authors    = \App\Models\User::where('status', true)->get(['id', 'name']);

        return view('admin.blog.create', compact('categories', 'tags', 'authors'));
    }

      /**
     * Store a newly created blog.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title'             => 'required|string|max:255',
            'slug'              => 'nullable|string|max:255|unique:blogs,slug',
            'short_description' => 'nullable|string|max:500',
            'content'           => 'required|string',
            'featured_image'    => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'author_id'         => 'required|exists:users,id',
            'category_id'       => 'nullable|exists:blog_categories,id',
            'tags'              => 'nullable|array',
            'meta_title'        => 'nullable|string|max:200',
            'meta_description'  => 'nullable|string|max:500',
            'meta_keywords'     => 'nullable|string|max:500',
            'status'            => 'boolean',
            'published_at'      => 'nullable|date',
            'sort_order'        => 'nullable|integer',
            'is_gallery'        => 'nullable|boolean',
            'gallery_order'     => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        if ($request->has('is_gallery') && !$request->hasFile('featured_image')) {
            return redirect()->back()
                ->withErrors(['featured_image' => 'গ্যালারিতে যুক্ত করার জন্য ফিচার্ড ইমেজ দেওয়া আবশ্যক!'])
                ->withInput();
        }

        try {
            DB::beginTransaction();

            $data = $request->except('featured_image', 'tags');

              // Handle featured image
            if ($request->hasFile('featured_image')) {
                $data['featured_image'] = $this->uploadImage($request->file('featured_image'));
            }

              // Handle tags
            if ($request->has('tags')) {
                $data['tags'] = array_filter($request->tags);
            }

              // Handle slug
            if (empty($data['slug'])) {
                $data['slug'] = $this->generateSlug($data['title']);
            }

              // Check for duplicate slug
            if (Blog::where('slug', $data['slug'])->exists()) {
                $data['slug'] = $data['slug'] . '-' . time();
            }

              // Handle published_at
            if (empty($data['published_at'])) {
                $data['published_at'] = now();
            }

              // Set default values
            $data['views']      = 0;
            $data['sort_order'] = $data['sort_order'] ?? Blog::max('sort_order') + 1;
            $data['is_gallery'] = $request->has('is_gallery') ? 1 : 0;
            $data['gallery_order'] = $request->input('gallery_order', 0);

            $blog = Blog::create($data);

            DB::commit();

            // Clear gallery cache
            \Cache::forget('home_gallery_posts');
            \Cache::forget('api_gallery_posts');

            return redirect()->route('admin.blog.posts.index')
                ->with('success', 'ব্লগ পোস্ট সফলভাবে তৈরি করা হয়েছে!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Blog creation failed: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'ব্লগ পোস্ট তৈরি করতে ব্যর্থ হয়েছে: ' . $e->getMessage())
                ->withInput();
        }
    }

      /**
     * Display the specified blog.
     */
    public function show($id)
    {
        $blog = Blog::with(['author', 'category', 'approvedComments.user'])->findOrFail($id);

        if (request()->ajax()) {
            $html = view('admin.blog.partials.detail', compact('blog'))->render();
            return response()->json([
                'success' => true,
                'html'    => $html
            ]);
        }

        return view('admin.blog.show', compact('blog'));
    }

      /**
     * Show form for editing blog.
     */
    public function edit($id)
    {
        $blog       = Blog::findOrFail($id);
        $categories = BlogCategory::active()->ordered()->get();
        $tags       = $this->getAllTags();
        $authors    = \App\Models\User::where('status', true)->get(['id', 'name']);

        return view('admin.blog.edit', compact('blog', 'categories', 'tags', 'authors'));
    }

      /**
     * Update the specified blog.
     */
    public function update(Request $request, $id)
    {
        $blog = Blog::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'title'             => 'required|string|max:255',
            'slug'              => 'nullable|string|max:255|unique:blogs,slug,' . $blog->id,
            'short_description' => 'nullable|string|max:500',
            'content'           => 'required|string',
            'featured_image'    => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'author_id'         => 'required|exists:users,id',
            'category_id'       => 'nullable|exists:blog_categories,id',
            'tags'              => 'nullable|array',
            'meta_title'        => 'nullable|string|max:200',
            'meta_description'  => 'nullable|string|max:500',
            'meta_keywords'     => 'nullable|string|max:500',
            'status'            => 'boolean',
            'published_at'      => 'nullable|date',
            'sort_order'        => 'nullable|integer',
            'remove_image'      => 'nullable|boolean',
            'is_gallery'        => 'nullable|boolean',
            'gallery_order'     => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        if ($request->has('is_gallery') && !$request->hasFile('featured_image') && !$blog->featured_image && $request->remove_image != 1) {
            return redirect()->back()
                ->withErrors(['featured_image' => 'গ্যালারিতে যুক্ত করার জন্য ফিচার্ড ইমেজ থাকা আবশ্যক!'])
                ->withInput();
        }

        try {
            DB::beginTransaction();

            $data = $request->except('featured_image', 'tags', 'remove_image');

              // Handle featured image
            if ($request->hasFile('featured_image')) {
                  // Delete old image
                if ($blog->featured_image) {
                    $this->deleteImage($blog->featured_image);
                }
                $data['featured_image'] = $this->uploadImage($request->file('featured_image'));
            }

              // Handle image removal
            if ($request->has('remove_image') && $request->remove_image == 1) {
                if ($blog->featured_image) {
                    $this->deleteImage($blog->featured_image);
                }
                $data['featured_image'] = null;
            }

              // Handle tags
            if ($request->has('tags')) {
                $data['tags'] = array_filter($request->tags);
            }

              // Handle slug
            if (empty($data['slug'])) {
                $data['slug'] = $this->generateSlug($data['title']);
            }

              // Check for duplicate slug (excluding current)
            if (Blog::where('slug', $data['slug'])->where('id', '!=', $blog->id)->exists()) {
                $data['slug'] = $data['slug'] . '-' . time();
            }

              // Handle published_at
            if (empty($data['published_at'])) {
                $data['published_at'] = $blog->published_at ?? now();
            }

            $data['is_gallery'] = $request->has('is_gallery') ? 1 : 0;
            $data['gallery_order'] = $request->input('gallery_order', 0);

            $blog->update($data);

            DB::commit();

            // Clear gallery cache
            \Cache::forget('home_gallery_posts');
            \Cache::forget('api_gallery_posts');

            return redirect()->route('admin.blog.posts.index')
                ->with('success', 'ব্লগ পোস্ট সফলভাবে আপডেট করা হয়েছে!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Blog update failed: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'ব্লগ পোস্ট আপডেট করতে ব্যর্থ হয়েছে!')
                ->withInput();
        }
    }

      /**
     * Delete the specified blog.
     */
    public function destroy($id)
    {
        try {
            $blog = Blog::findOrFail($id);

              // Check if blog has comments
            $commentCount = $blog->allComments()->count();
            if ($commentCount > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'এই পোস্টে ' . $commentCount . ' টি মন্তব্য আছে। প্রথমে মন্তব্য গুলো মুছুন!'
                ], 400);
            }

              // Delete featured image
            if ($blog->featured_image) {
                $this->deleteImage($blog->featured_image);
            }

            $blog->delete();

            return response()->json([
                'success' => true,
                'message' => 'ব্লগ পোস্ট সফলভাবে মুছে ফেলা হয়েছে!'
            ]);

        } catch (\Exception $e) {
            Log::error('Blog deletion failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'ব্লগ পোস্ট মুছে ফেলতে ব্যর্থ হয়েছে!'
            ], 500);
        }
    }

      /**
     * Toggle blog status.
     */
    public function toggleStatus($id)
    {
        try {
            $blog         = Blog::findOrFail($id);
            $blog->status = !$blog->status;

            if ($blog->status && empty($blog->published_at)) {
                $blog->published_at = now();
            }

            $blog->save();

            return response()->json([
                'success' => true,
                'message' => 'স্ট্যাটাস পরিবর্তন করা হয়েছে!',
                'status'  => $blog->status,
                'badge'   => $blog->status ? '<span class="badge bg-success">প্রকাশিত</span>' : '<span class="badge bg-warning">খসড়া</span>'
            ]);

        } catch (\Exception $e) {
            Log::error('Status toggle failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'স্ট্যাটাস পরিবর্তন করতে ব্যর্থ হয়েছে!'
            ], 500);
        }
    }

      /**
     * Bulk delete blogs.
     */
    public function bulkDelete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ids'   => 'required|array',
            'ids.*' => 'required|integer|exists:blogs,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        try {
            DB::beginTransaction();

            $blogs = Blog::whereIn('id', $request->ids)->get();

              // Check if any blog has comments
            $hasComments = false;
            foreach ($blogs as $blog) {
                if ($blog->allComments()->count() > 0) {
                    $hasComments = true;
                    break;
                }
            }

            if ($hasComments) {
                return response()->json([
                    'success' => false,
                    'message' => 'কিছু পোস্টে মন্তব্য আছে। প্রথমে মন্তব্য গুলো মুছুন!'
                ], 400);
            }

            foreach ($blogs as $blog) {
                if ($blog->featured_image) {
                    $this->deleteImage($blog->featured_image);
                }
            }

            Blog::whereIn('id', $request->ids)->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => count($blogs) . ' টি ব্লগ পোস্ট সফলভাবে মুছে ফেলা হয়েছে!'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Bulk delete failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'বাল্ক ডিলিট করতে ব্যর্থ হয়েছে!'
            ], 500);
        }
    }

      /**
     * Bulk status update.
     */
    public function bulkStatusUpdate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ids'    => 'required|array',
            'ids.*'  => 'required|integer|exists:blogs,id',
            'status' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        try {
            Blog::whereIn('id', $request->ids)->update([
                'status'       => $request->status,
                'published_at' => $request->status ? now() : null
            ]);

            return response()->json([
                'success' => true,
                'message' => count($request->ids) . ' টি ব্লগ পোস্টের স্ট্যাটাস আপডেট করা হয়েছে!'
            ]);

        } catch (\Exception $e) {
            Log::error('Bulk status update failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'বাল্ক স্ট্যাটাস আপডেট করতে ব্যর্থ হয়েছে!'
            ], 500);
        }
    }

      /**
     * Bulk category update.
     */
    public function bulkCategoryUpdate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ids'         => 'required|array',
            'ids.*'       => 'required|integer|exists:blogs,id',
            'category_id' => 'required|exists:blog_categories,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        try {
            Blog::whereIn('id', $request->ids)->update([
                'category_id' => $request->category_id
            ]);

            return response()->json([
                'success' => true,
                'message' => count($request->ids) . ' টি ব্লগ পোস্টের ক্যাটাগরি আপডেট করা হয়েছে!'
            ]);

        } catch (\Exception $e) {
            Log::error('Bulk category update failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'বাল্ক ক্যাটাগরি আপডেট করতে ব্যর্থ হয়েছে!'
            ], 500);
        }
    }

      /**
     * Export blogs.
     */
    public function export(Request $request)
    {
        $query = Blog::with(['author', 'category']);

        if ($request->filled('search')) {
            $query->search($request->search);
        }

        if ($request->filled('category')) {
            $query->byCategory($request->category);
        }

        if ($request->filled('status')) {
            if ($request->status == 'published') {
                $query->published();
            } elseif ($request->status == 'draft') {
                $query->where('status', false);
            }
        }

        if ($request->filled('date_from')) {
            $query->whereDate('published_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('published_at', '<=', $request->date_to);
        }

        $blogs = $query->orderBy('published_at', 'desc')->get();

        $filename = 'blogs_' . date('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($blogs) {
            $file = fopen('php://output', 'w');

              // CSV Header
            fputcsv($file, [
                'ID',
                'শিরোনাম',
                'স্লাগ',
                'ক্যাটাগরি',
                'লেখক',
                'ভিউ',
                'মন্তব্য',
                'স্ট্যাটাস',
                'প্রকাশের তারিখ',
                'তৈরির তারিখ'
            ]);

            foreach ($blogs as $blog) {
                fputcsv($file, [
                    $blog->id,
                    $blog->title,
                    $blog->slug,
                    $blog->category?->name ?? 'N/A',
                    $blog->author?->name ?? 'N/A',
                    $blog->views,
                    $blog->approvedComments()->count(),
                    $blog->status ? 'প্রকাশিত': 'খসড়া',
                    $blog->published_at?->format('Y-m-d'),
                    $blog->created_at?->format('Y-m-d'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

      /**
     * Duplicate a blog post.
     */
    public function duplicate($id)
    {
        try {
            $blog = Blog::findOrFail($id);

            $newBlog               = $blog->replicate();
            $newBlog->title        = $blog->title . ' (Copy)';
            $newBlog->slug         = $blog->slug . '-copy-' . time();
            $newBlog->views        = 0;
            $newBlog->status       = false;
            $newBlog->published_at = null;
            $newBlog->save();

            return response()->json([
                'success' => true,
                'message' => 'ব্লগ পোস্ট ডুপ্লিকেট করা হয়েছে!',
                'id'      => $newBlog->id
            ]);

        } catch (\Exception $e) {
            Log::error('Blog duplication failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'ব্লগ পোস্ট ডুপ্লিকেট করতে ব্যর্থ হয়েছে!'
            ], 500);
        }
    }

      /**
     * Increment views (AJAX).
     */
    public function incrementViews($id)
    {
        try {
            $blog = Blog::findOrFail($id);
            $blog->increment('views');

            return response()->json([
                'success' => true,
                'views'   => $blog->views
            ]);

        } catch (\Exception $e) {
            Log::error('View increment failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'ভিউ আপডেট করতে ব্যর্থ হয়েছে!'
            ], 500);
        }
    }

      /**
     * Get related posts (AJAX).
     */
    public function getRelatedPosts($id)
    {
        try {
            $blog    = Blog::findOrFail($id);
            $related = $blog->getRelatedPosts(5);

            return response()->json([
                'success' => true,
                'data'    => $related->map(function ($post) {
                    return [
                        'id'                 => $post->id,
                        'title'              => $post->title,
                        'slug'               => $post->slug,
                        'url'                => $post->url,
                        'featured_image_url' => $post->featured_image_url,
                        'published_at'       => $post->published_at?->format('d M, Y'),
                    ];
                })
            ]);

        } catch (\Exception $e) {
            Log::error('Get related posts failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'রিলেটেড পোস্ট লোড করতে ব্যর্থ হয়েছে!'
            ], 500);
        }
    }

      /**
     * Get posts by category (AJAX).
     */
    public function getPostsByCategory($categoryId)
    {
        try {
            $posts = Blog::published()
                ->byCategory($categoryId)
                ->orderBy('published_at', 'desc')
                ->limit(10)
                ->get();

            return response()->json([
                'success' => true,
                'data'    => $posts
            ]);

        } catch (\Exception $e) {
            Log::error('Get posts by category failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'পোস্ট লোড করতে ব্যর্থ হয়েছে!'
            ], 500);
        }
    }

      /**
     * Get posts by tag (AJAX).
     */
    public function getPostsByTag($tag)
    {
        try {
            $posts = Blog::published()
                ->byTag($tag)
                ->orderBy('published_at', 'desc')
                ->limit(10)
                ->get();

            return response()->json([
                'success' => true,
                'data'    => $posts
            ]);

        } catch (\Exception $e) {
            Log::error('Get posts by tag failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'পোস্ট লোড করতে ব্যর্থ হয়েছে!'
            ], 500);
        }
    }

      /**
     * Search posts (AJAX).
     */
    public function searchPosts(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'q' => 'required|string|min:2',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        try {
            $posts = Blog::published()
                ->search($request->q)
                ->orderBy('published_at', 'desc')
                ->limit(10)
                ->get();

            return response()->json([
                'success' => true,
                'data'    => $posts
            ]);

        } catch (\Exception $e) {
            Log::error('Search posts failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'সার্চ করতে ব্যর্থ হয়েছে!'
            ], 500);
        }
    }

      /**
     * Get recent posts (AJAX).
     */
    public function getRecentPosts()
    {
        try {
            $posts = Blog::published()
                ->recent()
                ->limit(10)
                ->get();

            return response()->json([
                'success' => true,
                'data'    => $posts
            ]);

        } catch (\Exception $e) {
            Log::error('Get recent posts failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'রিসেন্ট পোস্ট লোড করতে ব্যর্থ হয়েছে!'
            ], 500);
        }
    }

      /**
     * Get popular posts (AJAX).
     */
    public function getPopularPosts()
    {
        try {
            $posts = Blog::published()
                ->popular()
                ->limit(10)
                ->get();

            return response()->json([
                'success' => true,
                'data'    => $posts
            ]);

        } catch (\Exception $e) {
            Log::error('Get popular posts failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'পপুলার পোস্ট লোড করতে ব্যর্থ হয়েছে!'
            ], 500);
        }
    }

      /**
     * Get blog statistics (AJAX).
     */
    public function getStatistics()
    {
        try {
            $stats = [
                'total'            => Blog::count(),
                'published'        => Blog::published()->count(),
                'draft'            => Blog::where('status', false)->count(),
                'total_views'      => Blog::sum('views'),
                'categories'       => BlogCategory::count(),
                'comments'         => BlogComment::where('is_approved', true)->count(),
                'pending_comments' => BlogComment::where('is_approved', false)->count(),
                'this_month'       => Blog::thisMonth()->count(),
                'this_week'        => Blog::thisWeek()->count(),
            ];

              // Get last 7 days post creation
            $last7Days = [];
            for ($i = 6; $i >= 0; $i--) {
                $date        = now()->subDays($i);
                $count       = Blog::whereDate('created_at', $date)->count();
                $last7Days[] = [
                    'date'  => $date->format('Y-m-d'),
                    'label' => $date->format('d M'),
                    'count' => $count
                ];
            }

            return response()->json([
                'success'    => true,
                'stats'      => $stats,
                'chart_data' => $last7Days
            ]);

        } catch (\Exception $e) {
            Log::error('Get statistics failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'স্ট্যাটিস্টিক্স লোড করতে ব্যর্থ হয়েছে!'
            ], 500);
        }
    }

      /**
     * Upload image helper.
     */
    protected function uploadImage($image): string
    {
        $destinationPath = public_path('uploads/blog');

        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0777, true);
        }

        $fileName = 'blog_' . Str::random(10) . '_' . time() . '.' . $image->getClientOriginalExtension();
        $image->move($destinationPath, $fileName);

        return 'uploads/blog/' . $fileName;
    }

      /**
     * Delete image helper.
     */
    protected function deleteImage($path): void
    {
        if ($path && file_exists(public_path($path))) {
            @unlink(public_path($path));
        }
    }

      /**
     * Get all tags from existing blogs.
     */
    protected function getAllTags(): array
    {
        $tags    = Blog::whereNotNull('tags')->pluck('tags')->toArray();
        $allTags = [];

        foreach ($tags as $tagArray) {
            if (is_array($tagArray)) {
                $allTags = array_merge($allTags, $tagArray);
            }
        }

        return array_unique($allTags);
    }


    /**
 * Reorder blogs (drag and drop).
 */
public function reorder(Request $request)
{
    $validator = Validator::make($request->all(), [
        'order' => 'required|array',
        'order.*' => 'required|integer|exists:blogs,id',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'message' => $validator->errors()->first()
        ], 422);
    }

    try {
        foreach ($request->order as $index => $id) {
            Blog::where('id', $id)->update(['sort_order' => $index + 1]);
        }

        return response()->json([
            'success' => true,
            'message' => 'সর্ট অর্ডার সফলভাবে আপডেট করা হয়েছে!'
        ]);

    } catch (\Exception $e) {
        Log::error('Reorder failed: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'সর্ট অর্ডার আপডেট করতে ব্যর্থ হয়েছে!'
        ], 500);
    }
}

    /**
     * Toggle blog gallery status.
     */
    public function toggleGallery($id)
    {
        try {
            $blog = Blog::findOrFail($id);

            if (!$blog->is_gallery && !$blog->featured_image) {
                return response()->json([
                    'success' => false,
                    'message' => 'ফিচার্ড ইমেজ ছাড়া কোনো পোস্ট গ্যালারিতে যুক্ত করা যাবে না!'
                ], 422);
            }

            $blog->is_gallery = !$blog->is_gallery;
            $blog->save();

            // Clear cache
            \Cache::forget('home_gallery_posts');
            \Cache::forget('api_gallery_posts');

            // Log activity
            try {
                \App\Models\ActivityLog::create([
                    'user_id' => auth()->id(),
                    'action' => 'gallery_toggle',
                    'model' => 'Blog',
                    'model_id' => $blog->id,
                    'details' => 'গ্যালারি স্ট্যাটাস পরিবর্তন: ' . ($blog->is_gallery ? 'যুক্ত' : 'বাদ'),
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent()
                ]);
            } catch (\Exception $e) {
                Log::warning('Activity log failed: ' . $e->getMessage());
            }

            return response()->json([
                'success'    => true,
                'message'    => 'গ্যালারি সেটিং পরিবর্তন করা হয়েছে!',
                'is_gallery' => $blog->is_gallery,
                'badge'      => $blog->is_gallery ? '<span class="badge bg-success">গ্যালারিতে যুক্ত</span>' : '<span class="badge bg-secondary">যুক্ত নয়</span>'
            ]);

        } catch (\Exception $e) {
            Log::error('Gallery toggle failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'গ্যালারি সেটিং পরিবর্তন করতে ব্যর্থ হয়েছে!'
            ], 500);
        }
    }

    /**
     * Bulk gallery update.
     */
    public function bulkGalleryUpdate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ids'        => 'required|array',
            'ids.*'      => 'required|integer|exists:blogs,id',
            'is_gallery' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        try {
            $is_gallery = $request->is_gallery;

            if ($is_gallery) {
                // Verify all selected posts have featured images
                $hasNoImageCount = Blog::whereIn('id', $request->ids)
                    ->where(function($q) {
                        $q->whereNull('featured_image')->orWhere('featured_image', '');
                    })
                    ->count();

                if ($hasNoImageCount > 0) {
                    return response()->json([
                        'success' => false,
                        'message' => 'ফিচার্ড ইমেজ ছাড়া কোনো পোস্ট গ্যালারিতে যুক্ত করা যাবে না!'
                    ], 422);
                }
            }

            Blog::whereIn('id', $request->ids)->update([
                'is_gallery' => $is_gallery
            ]);

            // Clear cache
            \Cache::forget('home_gallery_posts');
            \Cache::forget('api_gallery_posts');

            // Log activity
            try {
                \App\Models\ActivityLog::create([
                    'user_id' => auth()->id(),
                    'action' => 'gallery_bulk_update',
                    'model' => 'Blog',
                    'details' => count($request->ids) . ' টি ব্লগ পোস্ট গ্যালারি সেটিং আপডেট: ' . ($is_gallery ? 'যুক্ত' : 'বাদ'),
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent()
                ]);
            } catch (\Exception $e) {
                Log::warning('Activity log failed: ' . $e->getMessage());
            }

            return response()->json([
                'success' => true,
                'message' => count($request->ids) . ' টি ব্লগ পোস্ট গ্যালারি সেটিং আপডেট করা হয়েছে!'
            ]);

        } catch (\Exception $e) {
            Log::error('Bulk gallery update failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'বাল্ক গ্যালারি আপডেট করতে ব্যর্থ হয়েছে!'
            ], 500);
        }
    }

    /**
     * Reorder gallery posts.
     */
    public function reorderGallery(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order' => 'required|array',
            'order.*' => 'required|integer|exists:blogs,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        try {
            foreach ($request->order as $index => $id) {
                Blog::where('id', $id)->update(['gallery_order' => $index + 1]);
            }

            // Clear cache
            \Cache::forget('home_gallery_posts');
            \Cache::forget('api_gallery_posts');

            return response()->json([
                'success' => true,
                'message' => 'গ্যালারি সর্ট অর্ডার সফলভাবে আপডেট করা হয়েছে!'
            ]);

        } catch (\Exception $e) {
            Log::error('Gallery reorder failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'গ্যালারি সর্ট অর্ডার আপডেট করতে ব্যর্থ হয়েছে!'
            ], 500);
        }
    }

    /**
     * Display a listing of the gallery posts.
     */
    public function galleryIndex()
    {
        // Fetch all published posts currently in the gallery
        $galleryPosts = Blog::published()
            ->where('is_gallery', true)
            ->whereNotNull('featured_image')
            ->where('featured_image', '!=', '')
            ->orderBy('gallery_order', 'asc')
            ->orderBy('published_at', 'desc')
            ->orderBy('id', 'desc')
            ->get();

        // Fetch eligible published posts NOT in the gallery (having featured image)
        $availablePosts = Blog::published()
            ->where('is_gallery', false)
            ->whereNotNull('featured_image')
            ->where('featured_image', '!=', '')
            ->orderBy('published_at', 'desc')
            ->get();

        return view('admin.gallery.index', compact('galleryPosts', 'availablePosts'));
    }

    /**
     * Add a post to the gallery.
     */
    public function galleryAdd(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'blog_id' => 'required|integer|exists:blogs,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        try {
            $blog = Blog::findOrFail($request->blog_id);

            if (!$blog->featured_image) {
                return response()->json([
                    'success' => false,
                    'message' => 'ফিচার্ড ইমেজ ছাড়া কোনো পোস্ট গ্যালারিতে যুক্ত করা যাবে না!'
                ], 422);
            }

            // Find next sort order
            $nextOrder = Blog::where('is_gallery', true)->max('gallery_order') + 1;

            $blog->is_gallery = true;
            $blog->gallery_order = $nextOrder;
            $blog->save();

            // Clear cache
            \Cache::forget('home_gallery_posts');
            \Cache::forget('api_gallery_posts');

            // Log activity
            try {
                \App\Models\ActivityLog::create([
                    'user_id' => auth()->id(),
                    'action' => 'gallery_add',
                    'model' => 'Blog',
                    'model_id' => $blog->id,
                    'details' => 'গ্যালারিতে পোস্ট যুক্ত করা হয়েছে: ' . $blog->title,
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent()
                ]);
            } catch (\Exception $e) {
                Log::warning('Activity log failed: ' . $e->getMessage());
            }

            return response()->json([
                'success' => true,
                'message' => 'পোস্ট সফলভাবে গ্যালারিতে যুক্ত করা হয়েছে!'
            ]);

        } catch (\Exception $e) {
            Log::error('Gallery add failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'গ্যালারিতে পোস্ট যুক্ত করতে ব্যর্থ হয়েছে!'
            ], 500);
        }
    }

    /**
     * Generate SEO-friendly slug supporting Unicode/Bengali characters
     */
    protected function generateSlug($title)
    {
        $slug = preg_replace('/[^\p{L}\p{N}]+/u', '-', $title);
        $slug = trim($slug, '-');
        return mb_strtolower($slug);
    }
}
