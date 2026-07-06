<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class PageController extends Controller
{
    /**
     * Display a listing of custom pages.
     */
    public function index(Request $request)
    {
        $query = Page::query();

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('is_active', $request->status == 'active');
        }

        // Sorting
        $sortField = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');
        $allowedSortFields = ['id', 'title', 'slug', 'is_active', 'created_at'];

        if (in_array($sortField, $allowedSortFields)) {
            $query->orderBy($sortField, $sortDirection);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $perPage = $request->get('per_page', 20);

        if ($request->ajax()) {
            $pages = ($perPage == '-1') ? $query->get() : $query->paginate((int)$perPage);
            $html = view('admin.pages.partials.table', compact('pages'))->render();
            return response()->json([
                'success' => true,
                'html' => $html,
                'total' => $pages->total() ?? $pages->count()
            ]);
        }

        $pages = $query->paginate($perPage);

        // Statistics
        $stats = [
            'total' => Page::count(),
            'active' => Page::where('is_active', true)->count(),
            'inactive' => Page::where('is_active', false)->count()
        ];

        return view('admin.pages.index', compact('pages', 'stats'));
    }

    /**
     * Show form for creating new page.
     */
    public function create()
    {
        return view('admin.pages.create');
    }

    /**
     * Store a newly created page in database.
     */
    public function store(Request $request)
    {
        // Auto-generate slug from title if empty
        if (!$request->filled('slug') && $request->filled('title')) {
            $request->merge(['slug' => Str::slug($request->title)]);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:200',
            'slug' => 'required|string|max:200|unique:pages,slug',
            'content' => 'nullable|string',
            'meta_description' => 'nullable|string|max:500',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            $data = $request->only(['title', 'slug', 'content', 'meta_description']);
            $data['is_active'] = $request->boolean('is_active', true);

            $page = Page::create($data);

            DB::commit();

            return redirect()->route('admin.pages.index')
                ->with('success', 'নতুন পেজ সফলভাবে তৈরি করা হয়েছে!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Page creation failed: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'পেজ তৈরি করতে ব্যর্থ হয়েছে: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Show form for editing page.
     */
    public function edit($id)
    {
        $page = Page::findOrFail($id);
        return view('admin.pages.edit', compact('page'));
    }

    /**
     * Update the specified page in database.
     */
    public function update(Request $request, $id)
    {
        $page = Page::findOrFail($id);

        // Auto-generate slug from title if empty
        if (!$request->filled('slug') && $request->filled('title')) {
            $request->merge(['slug' => Str::slug($request->title)]);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:200',
            'slug' => 'required|string|max:200|unique:pages,slug,' . $id,
            'content' => 'nullable|string',
            'meta_description' => 'nullable|string|max:500',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            $data = $request->only(['title', 'slug', 'content', 'meta_description']);
            $data['is_active'] = $request->boolean('is_active', true);

            $page->update($data);

            DB::commit();

            return redirect()->route('admin.pages.index')
                ->with('success', 'পেজ সফলভাবে আপডেট করা হয়েছে!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Page update failed: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'পেজ আপডেট করতে ব্যর্থ হয়েছে: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified page from database.
     */
    public function destroy($id)
    {
        try {
            $page = Page::findOrFail($id);
            $page->delete();

            return response()->json([
                'success' => true,
                'message' => 'পেজ সফলভাবে ডিলিট করা হয়েছে!'
            ]);
        } catch (\Exception $e) {
            Log::error('Page deletion failed: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'পেজ ডিলিট করতে ব্যর্থ হয়েছে: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle active status of a page.
     */
    public function toggleStatus($id)
    {
        try {
            $page = Page::findOrFail($id);
            $page->is_active = !$page->is_active;
            $page->save();

            return response()->json([
                'success' => true,
                'message' => 'পেজের স্ট্যাটাস পরিবর্তন করা হয়েছে!',
                'is_active' => $page->is_active
            ]);
        } catch (\Exception $e) {
            Log::error('Page status toggle failed: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'স্ট্যাটাস পরিবর্তন করতে ব্যর্থ হয়েছে!'
            ], 500);
        }
    }
}
