<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class BlogCategoryController extends Controller
{
    /**
     * Display a listing of categories.
     */
    public function index(Request $request)
    {
        $query = BlogCategory::withCount('blogs');

        // Search filter
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status == 'active');
        }

        // Date range filter
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Sorting
        $sortField = $request->get('sort', 'sort_order');
        $sortDirection = $request->get('direction', 'asc');
        $allowedSortFields = ['id', 'name', 'status', 'sort_order', 'created_at', 'updated_at'];

        if (in_array($sortField, $allowedSortFields)) {
            $query->orderBy($sortField, $sortDirection);
        } else {
            $query->orderBy('sort_order', 'asc');
        }

        $perPage = $request->get('per_page', 20);
        $perPageValue = ($perPage == '-1') ? max(1, $query->count()) : (int)$perPage;

        // AJAX Request
        if ($request->ajax()) {
            $categories = $query->paginate($perPageValue);
            $html = view('admin.blog.categories.partials.table', compact('categories'))->render();
            return response()->json([
                'success' => true,
                'html' => $html,
                'total' => $categories->total(),
                'pagination' => [
                    'total' => $categories->total(),
                    'current_page' => $categories->currentPage(),
                    'last_page' => $categories->lastPage(),
                ]
            ]);
        }

        $categories = $query->paginate($perPageValue);

        // Statistics
        $stats = [
            'total' => BlogCategory::count(),
            'active' => BlogCategory::where('status', true)->count(),
            'inactive' => BlogCategory::where('status', false)->count(),
            'total_blogs' => BlogCategory::withCount('blogs')->get()->sum('blogs_count'),
        ];

        // Get all categories for filter dropdown
        $allCategories = BlogCategory::ordered()->get(['id', 'name']);

        return view('admin.blog.categories.index', compact('categories', 'stats', 'allCategories'));
    }

    /**
     * Show form for creating new category.
     */
    public function create()
    {
        return view('admin.blog.categories.create');
    }

    /**
     * Store a newly created category.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100|unique:blog_categories,name',
            'slug' => 'nullable|string|max:100|unique:blog_categories,slug',
            'description' => 'nullable|string|max:500',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'meta_title' => 'nullable|string|max:200',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:500',
            'status' => 'boolean',
            'sort_order' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            $data = $request->except('image');

            // Handle image upload
            if ($request->hasFile('image')) {
                $data['image'] = $this->uploadImage($request->file('image'));
            }

            // Generate slug if empty
            if (empty($data['slug'])) {
                $data['slug'] = $this->generateSlug($data['name']);
            }

            // Check for duplicate slug
            if (BlogCategory::where('slug', $data['slug'])->exists()) {
                $data['slug'] = $data['slug'] . '-' . time();
            }

            $data['sort_order'] = $data['sort_order'] ?? BlogCategory::max('sort_order') + 1;
            $data['status'] = $request->boolean('status', true);

            $category = BlogCategory::create($data);

            DB::commit();

            return redirect()->route('admin.blog.categories.index')
                ->with('success', 'ব্লগ ক্যাটাগরি সফলভাবে তৈরি করা হয়েছে!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Category creation failed: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'ব্লগ ক্যাটাগরি তৈরি করতে ব্যর্থ হয়েছে: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified category.
     */
    public function show($id)
    {
        $category = BlogCategory::withCount('blogs')->findOrFail($id);

        if (request()->ajax()) {
            $html = view('admin.blog.categories.partials.detail', compact('category'))->render();
            return response()->json([
                'success' => true,
                'html' => $html
            ]);
        }

        return view('admin.blog.categories.show', compact('category'));
    }

    /**
     * Show form for editing category.
     */
    public function edit($id)
    {
        $category = BlogCategory::withCount('blogs')->findOrFail($id);
        return view('admin.blog.categories.edit', compact('category'));
    }

    /**
     * Update the specified category.
     */
    public function update(Request $request, $id)
    {
        $category = BlogCategory::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100|unique:blog_categories,name,' . $category->id,
            'slug' => 'nullable|string|max:100|unique:blog_categories,slug,' . $category->id,
            'description' => 'nullable|string|max:500',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'meta_title' => 'nullable|string|max:200',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:500',
            'status' => 'boolean',
            'sort_order' => 'nullable|integer',
            'remove_image' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            $data = $request->except('image', 'remove_image');

            // Handle image update
            if ($request->hasFile('image')) {
                if ($category->image) {
                    $this->deleteImage($category->image);
                }
                $data['image'] = $this->uploadImage($request->file('image'));
            }

            // Handle image removal
            if ($request->has('remove_image') && $request->remove_image == 1) {
                if ($category->image) {
                    $this->deleteImage($category->image);
                }
                $data['image'] = null;
            }

            // Generate slug if empty
            if (empty($data['slug'])) {
                $data['slug'] = $this->generateSlug($data['name']);
            }

            // Check for duplicate slug (excluding current)
            if (BlogCategory::where('slug', $data['slug'])->where('id', '!=', $category->id)->exists()) {
                $data['slug'] = $data['slug'] . '-' . time();
            }

            $data['status'] = $request->boolean('status', $category->status);

            $category->update($data);

            DB::commit();

            return redirect()->route('admin.blog.categories.index')
                ->with('success', 'ব্লগ ক্যাটাগরি সফলভাবে আপডেট করা হয়েছে!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Category update failed: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'ব্লগ ক্যাটাগরি আপডেট করতে ব্যর্থ হয়েছে!')
                ->withInput();
        }
    }

    /**
     * Delete the specified category.
     */
    public function destroy($id)
    {
        try {
            $category = BlogCategory::findOrFail($id);

            // Check if category has blogs
            $blogCount = $category->blogs()->count();
            if ($blogCount > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'এই ক্যাটাগরির অধীনে ' . $blogCount . ' টি ব্লগ পোস্ট আছে। প্রথমে ব্লগ পোস্ট গুলো সরান বা অন্য ক্যাটাগরিতে স্থানান্তর করুন!'
                ], 400);
            }

            // Delete image
            if ($category->image) {
                $this->deleteImage($category->image);
            }

            $category->delete();

            return response()->json([
                'success' => true,
                'message' => 'ব্লগ ক্যাটাগরি সফলভাবে মুছে ফেলা হয়েছে!'
            ]);

        } catch (\Exception $e) {
            Log::error('Category deletion failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'ব্লগ ক্যাটাগরি মুছে ফেলতে ব্যর্থ হয়েছে!'
            ], 500);
        }
    }

    /**
     * Toggle category status.
     */
    public function toggleStatus($id)
    {
        try {
            $category = BlogCategory::findOrFail($id);
            $category->status = !$category->status;
            $category->save();

            return response()->json([
                'success' => true,
                'message' => 'স্ট্যাটাস পরিবর্তন করা হয়েছে!',
                'status' => $category->status,
                'badge' => $category->status ? '<span class="badge bg-success">সক্রিয়</span>' : '<span class="badge bg-danger">নিষ্ক্রিয়</span>'
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
     * Reorder categories (drag and drop).
     */
    public function reorder(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order' => 'required|array',
            'order.*' => 'required|integer|exists:blog_categories,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        try {
            foreach ($request->order as $index => $id) {
                BlogCategory::where('id', $id)->update(['sort_order' => $index + 1]);
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
     * Bulk delete categories.
     */
    public function bulkDelete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ids' => 'required|array',
            'ids.*' => 'required|integer|exists:blog_categories,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        try {
            DB::beginTransaction();

            $categories = BlogCategory::whereIn('id', $request->ids)->get();

            // Check if any category has blogs
            $hasBlogs = false;
            foreach ($categories as $category) {
                if ($category->blogs()->count() > 0) {
                    $hasBlogs = true;
                    break;
                }
            }

            if ($hasBlogs) {
                return response()->json([
                    'success' => false,
                    'message' => 'কিছু ক্যাটাগরির অধীনে ব্লগ পোস্ট আছে। প্রথমে ব্লগ পোস্ট গুলো সরান বা অন্য ক্যাটাগরিতে স্থানান্তর করুন!'
                ], 400);
            }

            // Delete images
            foreach ($categories as $category) {
                if ($category->image) {
                    $this->deleteImage($category->image);
                }
            }

            BlogCategory::whereIn('id', $request->ids)->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => count($request->ids) . ' টি ক্যাটাগরি সফলভাবে মুছে ফেলা হয়েছে!'
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
     * Export categories.
     */
    public function export(Request $request)
    {
        $query = BlogCategory::query();

        if ($request->filled('search')) {
            $query->search($request->search);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status == 'active');
        }

        $categories = $query->ordered()->get();

        $filename = 'blog_categories_' . date('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($categories) {
            $file = fopen('php://output', 'w');

            // CSV Header
            fputcsv($file, [
                'ID',
                'নাম',
                'স্লাগ',
                'বিবরণ',
                'স্ট্যাটাস',
                'ব্লগ কাউন্ট',
                'সর্ট অর্ডার',
                'তৈরির তারিখ'
            ]);

            foreach ($categories as $category) {
                fputcsv($file, [
                    $category->id,
                    $category->name,
                    $category->slug,
                    strip_tags($category->description ?? 'N/A'),
                    $category->status ? 'সক্রিয়' : 'নিষ্ক্রিয়',
                    $category->blogs()->count(),
                    $category->sort_order,
                    $category->created_at?->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Get all categories for dropdown (AJAX).
     */
    public function getAll()
    {
        $categories = BlogCategory::active()->ordered()->get(['id', 'name']);
        return response()->json([
            'success' => true,
            'data' => $categories
        ]);
    }

    /**
     * Get category by slug (Frontend).
     */
    public function showBySlug($slug)
    {
        $category = BlogCategory::where('slug', $slug)->withCount('blogs')->firstOrFail();

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'data' => $category
            ]);
        }

        return view('blog.category', compact('category'));
    }

    /**
     * Get category statistics (AJAX).
     */
    public function getStatistics()
    {
        $stats = [
            'total' => BlogCategory::count(),
            'active' => BlogCategory::where('status', true)->count(),
            'inactive' => BlogCategory::where('status', false)->count(),
            'total_blogs' => BlogCategory::withCount('blogs')->get()->sum('blogs_count'),
            'categories_with_blogs' => BlogCategory::has('blogs')->count(),
            'categories_without_blogs' => BlogCategory::doesntHave('blogs')->count(),
        ];

        // Get last 7 days category creation
        $last7Days = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $count = BlogCategory::whereDate('created_at', $date)->count();
            $last7Days[] = [
                'date' => $date->format('Y-m-d'),
                'label' => $date->format('d M'),
                'count' => $count
            ];
        }

        return response()->json([
            'success' => true,
            'stats' => $stats,
            'chart_data' => $last7Days
        ]);
    }

    /**
     * Upload image helper.
     */
    protected function uploadImage($image): string
    {
        $destinationPath = public_path('uploads/blog/categories');

        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0777, true);
        }

        $fileName = 'category_' . Str::random(10) . '_' . time() . '.' . $image->getClientOriginalExtension();
        $image->move($destinationPath, $fileName);

        return 'uploads/blog/categories/' . $fileName;
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
     * Generate SEO-friendly slug supporting Unicode/Bengali characters
     */
    protected function generateSlug($name)
    {
        $slug = preg_replace('/[^\p{L}\p{N}]+/u', '-', $name);
        $slug = trim($slug, '-');
        return mb_strtolower($slug);
    }
}
