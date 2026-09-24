<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class VideoController extends Controller
{
    /**
     * Display a listing of videos.
     */
    public function index(Request $request)
    {
        $query = Video::query();

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('youtube_id', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('is_active', $request->status == 'active');
        }

        // Sorting
        $query->orderBy('sort_order', 'asc')->orderBy('created_at', 'desc');

        $perPage = $request->get('per_page', 15);

        if ($request->ajax()) {
            $videos = ($perPage == '-1') ? $query->get() : $query->paginate((int)$perPage);
            $html = view('admin.videos.partials.table', compact('videos'))->render();
            return response()->json([
                'success' => true,
                'html' => $html,
                'total' => ($videos instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator) ? $videos->total() : $videos->count()
            ]);
        }

        $videos = ($perPage == '-1') ? $query->get() : $query->paginate((int)$perPage);

        // Statistics
        $stats = [
            'total' => Video::count(),
            'active' => Video::where('is_active', true)->count(),
            'inactive' => Video::where('is_active', false)->count()
        ];

        return view('admin.videos.index', compact('videos', 'stats'));
    }

    /**
     * Show form for creating a new video.
     */
    public function create()
    {
        return view('admin.videos.create');
    }

    /**
     * Store a newly created video in database.
     */
    public function store(Request $request)
    {
        // Helper to extract youtube ID from full URL
        if ($request->filled('youtube_id')) {
            $id = $this->extractYoutubeId($request->youtube_id);
            $request->merge(['youtube_id' => $id]);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:200',
            'youtube_id' => 'required|string|max:50',
            'description' => 'nullable|string|max:1000',
            'sort_order' => 'integer|min:0',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            $data = $request->only(['title', 'youtube_id', 'description', 'sort_order']);
            $data['is_active'] = $request->boolean('is_active', true);

            Video::create($data);

            DB::commit();

            return redirect()->route('admin.videos.index')
                ->with('success', 'নতুন ভিডিও সফলভাবে যোগ করা হয়েছে!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Video creation failed: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'ভিডিও যোগ করতে ব্যর্থ হয়েছে: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Show form for editing a video.
     */
    public function edit($id)
    {
        $video = Video::findOrFail($id);
        return view('admin.videos.edit', compact('video'));
    }

    /**
     * Update a video in database.
     */
    public function update(Request $request, $id)
    {
        $video = Video::findOrFail($id);

        if ($request->filled('youtube_id')) {
            $video_id = $this->extractYoutubeId($request->youtube_id);
            $request->merge(['youtube_id' => $video_id]);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:200',
            'youtube_id' => 'required|string|max:50',
            'description' => 'nullable|string|max:1000',
            'sort_order' => 'integer|min:0',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            $data = $request->only(['title', 'youtube_id', 'description', 'sort_order']);
            $data['is_active'] = $request->boolean('is_active', true);

            $video->update($data);

            DB::commit();

            return redirect()->route('admin.videos.index')
                ->with('success', 'ভিডিওর তথ্য সফলভাবে আপডেট করা হয়েছে!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Video update failed: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'ভিডিও আপডেট করতে ব্যর্থ হয়েছে: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Delete a video.
     */
    public function destroy($id)
    {
        try {
            $video = Video::findOrFail($id);
            $video->delete();

            return response()->json([
                'success' => true,
                'message' => 'ভিডিও সফলভাবে ডিলিট করা হয়েছে!'
            ]);
        } catch (\Exception $e) {
            Log::error('Video deletion failed: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'ভিডিও ডিলিট করতে ব্যর্থ হয়েছে!'
            ], 500);
        }
    }

    /**
     * Toggle active status.
     */
    public function toggleStatus($id)
    {
        try {
            $video = Video::findOrFail($id);
            $video->is_active = !$video->is_active;
            $video->save();

            return response()->json([
                'success' => true,
                'message' => 'ভিডিওর স্ট্যাটাস পরিবর্তন করা হয়েছে!',
                'is_active' => $video->is_active
            ]);
        } catch (\Exception $e) {
            Log::error('Video status toggle failed: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'স্ট্যাটাস পরিবর্তন করতে ব্যর্থ হয়েছে!'
            ], 500);
        }
    }

    /**
     * Helper to extract Youtube Video ID from any youtube link or return the raw id.
     */
    protected function extractYoutubeId($url): string
    {
        $url = trim($url);
        // If it's already an 11-char ID
        if (strlen($url) == 11 && !str_contains($url, '/')) {
            return $url;
        }

        $pattern = '/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/ ]{11})/i';
        if (preg_match($pattern, $url, $matches)) {
            return $matches[1];
        }

        return $url;
    }
}
