<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class TagController extends Controller
{
    /**
     * Display a listing of tags.
     */
    public function index(Request $request)
    {
        $query = Tag::query();

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('slug', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%");
            });
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

        // AJAX Request
        if ($request->ajax()) {
            $tags = ($perPage == '-1') ? $query->get() : $query->paginate((int)$perPage);
            // Calculate blog counts for each tag
            foreach ($tags as $tag) {
                $tag->blogs_count = Blog::whereJsonContains('tags', $tag->name)
                    ->orWhere('tags', 'LIKE', '%"' . $tag->name . '"%')
                    ->count();
            }
            $html = view('admin.blog.tags.partials.table', compact('tags'))->render();
            return response()->json([
                'success' => true,
                'html' => $html,
                'total' => $tags->total() ?? $tags->count(),
                'pagination' => [
                    'total' => $tags->total() ?? $tags->count(),
                    'current_page' => $tags->currentPage() ?? 1,
                    'last_page' => $tags->lastPage() ?? 1,
                ]
            ]);
        }

        $tags = $query->paginate($perPage);
        foreach ($tags as $tag) {
            $tag->blogs_count = Blog::whereJsonContains('tags', $tag->name)
                ->orWhere('tags', 'LIKE', '%"' . $tag->name . '"%')
                ->count();
        }

        // Statistics
        $stats = [
            'total' => Tag::count(),
            'active' => Tag::where('status', true)->count(),
            'inactive' => Tag::where('status', false)->count(),
        ];

        return view('admin.blog.tags.index', compact('tags', 'stats'));
    }

    /**
     * Show form for creating new tag.
     */
    public function create()
    {
        return view('admin.blog.tags.create');
    }

    /**
     * Store a newly created tag.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100|unique:tags,name',
            'slug' => 'nullable|string|max:100|unique:tags,slug',
            'color' => 'nullable|string|max:10',
            'description' => 'nullable|string|max:500',
            'status' => 'boolean',
            'sort_order' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            $data = $request->all();

            // Generate slug if empty
            if (empty($data['slug'])) {
                $data['slug'] = Str::slug($data['name']);
            } else {
                $data['slug'] = Str::slug($data['slug']);
            }

            // Check for duplicate slug
            if (Tag::where('slug', $data['slug'])->exists()) {
                $data['slug'] = $data['slug'] . '-' . time();
            }

            $data['sort_order'] = $data['sort_order'] ?? Tag::max('sort_order') + 1;
            $data['status'] = $request->boolean('status', true);
            $data['color'] = $data['color'] ?? '#6c757d';

            Tag::create($data);

            DB::commit();

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'ট্যাগ সফলভাবে তৈরি করা হয়েছে!'
                ]);
            }

            return redirect()->route('admin.blog.tags.index')
                ->with('success', 'ট্যাগ সফলভাবে তৈরি করা হয়েছে!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Tag creation failed: ' . $e->getMessage());

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'ট্যাগ তৈরি করতে ব্যর্থ হয়েছে: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()
                ->with('error', 'ট্যাগ তৈরি করতে ব্যর্থ হয়েছে: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Show form for editing tag.
     */
    public function edit($id)
    {
        $tag = Tag::findOrFail($id);
        $tag->blogs_count = Blog::whereJsonContains('tags', $tag->name)
            ->orWhere('tags', 'LIKE', '%"' . $tag->name . '"%')
            ->count();
            
        return view('admin.blog.tags.edit', compact('tag'));
    }

    /**
     * Update the specified tag.
     */
    public function update(Request $request, $id)
    {
        $tag = Tag::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100|unique:tags,name,' . $tag->id,
            'slug' => 'nullable|string|max:100|unique:tags,slug,' . $tag->id,
            'color' => 'nullable|string|max:10',
            'description' => 'nullable|string|max:500',
            'status' => 'boolean',
            'sort_order' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            $data = $request->all();

            // Generate slug if empty
            if (empty($data['slug'])) {
                $data['slug'] = Str::slug($data['name']);
            } else {
                $data['slug'] = Str::slug($data['slug']);
            }

            // Check for duplicate slug (excluding current)
            if (Tag::where('slug', $data['slug'])->where('id', '!=', $tag->id)->exists()) {
                $data['slug'] = $data['slug'] . '-' . time();
            }

            $data['status'] = $request->boolean('status', $tag->status);
            $data['color'] = $data['color'] ?? '#6c757d';

            $tag->update($data);

            DB::commit();

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'ট্যাগ সফলভাবে আপডেট করা হয়েছে!'
                ]);
            }

            return redirect()->route('admin.blog.tags.index')
                ->with('success', 'ট্যাগ সফলভাবে আপডেট করা হয়েছে!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Tag update failed: ' . $e->getMessage());

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'ট্যাগ আপডেট করতে ব্যর্থ হয়েছে!'
                ], 500);
            }

            return redirect()->back()
                ->with('error', 'ট্যাগ আপডেট করতে ব্যর্থ হয়েছে!')
                ->withInput();
        }
    }

    /**
     * Delete the specified tag.
     */
    public function destroy($id)
    {
        try {
            $tag = Tag::findOrFail($id);

            // Check if tag is used in any blog
            $blogCount = Blog::whereJsonContains('tags', $tag->name)
                ->orWhere('tags', 'LIKE', '%"' . $tag->name . '"%')
                ->count();
                
            if ($blogCount > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'এই ট্যাগের অধীনে ' . $blogCount . ' টি ব্লগ পোস্ট আছে। প্রথমে ব্লগ পোস্ট গুলো থেকে ট্যাগটি সরান!'
                ], 400);
            }

            $tag->delete();

            return response()->json([
                'success' => true,
                'message' => 'ট্যাগ সফলভাবে মুছে ফেলা হয়েছে!'
            ]);

        } catch (\Exception $e) {
            Log::error('Tag deletion failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'ট্যাগ মুছে ফেলতে ব্যর্থ হয়েছে!'
            ], 500);
        }
    }

    /**
     * Toggle status.
     */
    public function toggleStatus($id)
    {
        try {
            $tag = Tag::findOrFail($id);
            $tag->status = !$tag->status;
            $tag->save();

            return response()->json([
                'success' => true,
                'message' => 'স্ট্যাটাস পরিবর্তন করা হয়েছে!',
                'status' => $tag->status
            ]);

        } catch (\Exception $e) {
            Log::error('Tag status toggle failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'স্ট্যাটাস পরিবর্তন করতে ব্যর্থ হয়েছে!'
            ], 500);
        }
    }

    /**
     * Reorder tags.
     */
    public function reorder(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order' => 'required|array',
            'order.*' => 'required|integer|exists:tags,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        try {
            foreach ($request->order as $index => $id) {
                Tag::where('id', $id)->update(['sort_order' => $index + 1]);
            }

            return response()->json([
                'success' => true,
                'message' => 'সর্ট অর্ডার সফলভাবে আপডেট করা হয়েছে!'
            ]);

        } catch (\Exception $e) {
            Log::error('Tag reorder failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'সর্ট অর্ডার আপডেট করতে ব্যর্থ হয়েছে!'
            ], 500);
        }
    }

    /**
     * Bulk delete.
     */
    public function bulkDelete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ids' => 'required|array',
            'ids.*' => 'required|integer|exists:tags,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        try {
            $tags = Tag::whereIn('id', $request->ids)->get();
            $notDeletableCount = 0;

            foreach ($tags as $tag) {
                $blogCount = Blog::whereJsonContains('tags', $tag->name)
                    ->orWhere('tags', 'LIKE', '%"' . $tag->name . '"%')
                    ->count();
                if ($blogCount > 0) {
                    $notDeletableCount++;
                } else {
                    $tag->delete();
                }
            }

            if ($notDeletableCount > 0) {
                return response()->json([
                    'success' => true,
                    'message' => 'কিছু ট্যাগ মুছে ফেলা হয়েছে। কিন্তু ' . $notDeletableCount . ' টি ট্যাগ ব্লগ পোস্টে ব্যবহৃত থাকায় মুছে ফেলা যায়নি!'
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'নির্বাচিত ট্যাগগুলো সফলভাবে মুছে ফেলা হয়েছে!'
            ]);

        } catch (\Exception $e) {
            Log::error('Tag bulk delete failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'বাল্ক ডিলিট করতে ব্যর্থ হয়েছে!'
            ], 500);
        }
    }

    /**
     * Export tags.
     */
    public function export(Request $request)
    {
        $fileName = 'tags_export_' . time() . '.csv';
        $headers = [
            "Content-type"        => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['ID', 'Name', 'Slug', 'Color', 'Description', 'Sort Order', 'Status', 'Created At'];

        $callback = function() use($columns) {
            $file = fopen('php://output', 'w');
            // Add UTF-8 BOM
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            fputcsv($file, $columns);

            $tags = Tag::orderBy('sort_order', 'asc')->get();

            foreach ($tags as $tag) {
                fputcsv($file, [
                    $tag->id,
                    $tag->name,
                    $tag->slug,
                    $tag->color,
                    $tag->description,
                    $tag->sort_order,
                    $tag->status ? 'Active' : 'Inactive',
                    $tag->created_at ? $tag->created_at->format('Y-m-d H:i:s') : 'N/A'
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
