<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ActivityController extends Controller
{
    /**
     * Display a listing of activities.
     */
    public function index(Request $request)
    {
        $query = Activity::query();

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
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
            $activities = ($perPage == '-1') ? $query->get() : $query->paginate((int)$perPage);
            $html = view('admin.activities.partials.table', compact('activities'))->render();
            return response()->json([
                'success' => true,
                'html' => $html,
                'total' => $activities->total() ?? $activities->count()
            ]);
        }

        $activities = $query->paginate($perPage);

        // Statistics
        $stats = [
            'total' => Activity::count(),
            'active' => Activity::where('is_active', true)->count(),
            'inactive' => Activity::where('is_active', false)->count()
        ];

        return view('admin.activities.index', compact('activities', 'stats'));
    }

    /**
     * Show form for creating new activity.
     */
    public function create()
    {
        return view('admin.activities.create');
    }

    /**
     * Store a newly created activity in database.
     */
    public function store(Request $request)
    {
        // Auto-generate slug from title if empty
        if (!$request->filled('slug') && $request->filled('title')) {
            $request->merge(['slug' => $this->generateSlug($request->title)]);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:200',
            'slug' => 'required|string|max:200|unique:activities,slug',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'description' => 'nullable|string|max:1000',
            'content' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            $data = $request->only(['title', 'slug', 'description', 'content']);
            $data['is_active'] = $request->boolean('is_active', true);

            // Handle image upload
            if ($request->hasFile('image')) {
                $data['image'] = $this->uploadImage($request->file('image'));
            }

            $activity = Activity::create($data);

            DB::commit();

            return redirect()->route('admin.activities.index')
                ->with('success', 'নতুন কার্যক্রম সফলভাবে যোগ করা হয়েছে!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Activity creation failed: ' . $e->getMessage());

            // Delete uploaded file if transaction failed
            if (isset($data['image'])) {
                $this->deleteImage($data['image']);
            }

            return redirect()->back()
                ->with('error', 'কার্যক্রম তৈরি করতে ব্যর্থ হয়েছে: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Show form for editing activity.
     */
    public function edit($id)
    {
        $activity = Activity::findOrFail($id);
        return view('admin.activities.edit', compact('activity'));
    }

    /**
     * Update the specified activity in database.
     */
    public function update(Request $request, $id)
    {
        $activity = Activity::findOrFail($id);

        // Auto-generate slug from title if empty
        if (!$request->filled('slug') && $request->filled('title')) {
            $request->merge(['slug' => $this->generateSlug($request->title)]);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:200',
            'slug' => 'required|string|max:200|unique:activities,slug,' . $id,
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'description' => 'nullable|string|max:1000',
            'content' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $oldImage = $activity->image;

        try {
            DB::beginTransaction();

            $data = $request->only(['title', 'slug', 'description', 'content']);
            $data['is_active'] = $request->boolean('is_active', true);

            // Handle image upload
            if ($request->hasFile('image')) {
                $data['image'] = $this->uploadImage($request->file('image'));
            }

            $activity->update($data);

            DB::commit();

            // Delete old image if a new one was uploaded
            if ($request->hasFile('image') && $oldImage) {
                $this->deleteImage($oldImage);
            }

            return redirect()->route('admin.activities.index')
                ->with('success', 'কার্যক্রম সফলভাবে আপডেট করা হয়েছে!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Activity update failed: ' . $e->getMessage());

            // Delete newly uploaded file if transaction failed
            if (isset($data['image'])) {
                $this->deleteImage($data['image']);
            }

            return redirect()->back()
                ->with('error', 'কার্যক্রম আপডেট করতে ব্যর্থ হয়েছে: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified activity from database.
     */
    public function destroy($id)
    {
        try {
            $activity = Activity::findOrFail($id);
            $image = $activity->image;

            $activity->delete();

            // Delete image file
            if ($image) {
                $this->deleteImage($image);
            }

            return response()->json([
                'success' => true,
                'message' => 'কার্যক্রম সফলভাবে ডিলিট করা হয়েছে!'
            ]);
        } catch (\Exception $e) {
            Log::error('Activity deletion failed: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'কার্যক্রম ডিলিট করতে ব্যর্থ হয়েছে: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle active status of an activity.
     */
    public function toggleStatus($id)
    {
        try {
            $activity = Activity::findOrFail($id);
            $activity->is_active = !$activity->is_active;
            $activity->save();

            return response()->json([
                'success' => true,
                'message' => 'কার্যক্রমের স্ট্যাটাস পরিবর্তন করা হয়েছে!',
                'is_active' => $activity->is_active
            ]);
        } catch (\Exception $e) {
            Log::error('Activity status toggle failed: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'স্ট্যাটাস পরিবর্তন করতে ব্যর্থ হয়েছে!'
            ], 500);
        }
    }

    /**
     * Upload image helper.
     */
    protected function uploadImage($image): string
    {
        $destinationPath = public_path('uploads/activities');

        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0777, true);
        }

        $fileName = 'activity_' . Str::random(10) . '_' . time() . '.' . $image->getClientOriginalExtension();
        $image->move($destinationPath, $fileName);

        return 'uploads/activities/' . $fileName;
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
     * Generate SEO-friendly slug supporting Unicode/Bengali characters.
     */
    protected function generateSlug($title)
    {
        $slug = preg_replace('/[^\p{L}\p{N}]+/u', '-', $title);
        $slug = trim($slug, '-');
        return mb_strtolower($slug);
    }
}
