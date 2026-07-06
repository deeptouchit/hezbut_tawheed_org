<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LiveBroadcast;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class LiveBroadcastController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = LiveBroadcast::query();

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('video_id', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Status Filter
        if ($request->filled('status')) {
            $status = $request->status;
            if ($status === 'live') {
                $query->where('is_live', true)->where('is_active', true);
            } elseif ($status === 'scheduled') {
                $query->where('is_live', false)
                      ->where('is_active', true)
                      ->where('schedule_time', '>', now());
            } elseif ($status === 'archive') {
                $query->where('is_live', false)
                      ->where('is_active', true)
                      ->where('schedule_time', '<=', now());
            } elseif ($status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        $query->orderBy('schedule_time', 'desc');
        $perPage = $request->get('per_page', 15);

        if ($request->ajax()) {
            $broadcasts = ($perPage == '-1') ? $query->paginate(9999) : $query->paginate((int)$perPage);
            $html = view('admin.live_broadcasts.partials.table', compact('broadcasts'))->render();
            return response()->json([
                'success' => true,
                'html' => $html,
                'total' => $broadcasts->total()
            ]);
        }

        $broadcasts = ($perPage == '-1') ? $query->paginate(9999) : $query->paginate((int)$perPage);

        // Stats
        $stats = [
            'total' => LiveBroadcast::count(),
            'live' => LiveBroadcast::where('is_live', true)->where('is_active', true)->count(),
            'scheduled' => LiveBroadcast::where('is_live', false)->where('is_active', true)->where('schedule_time', '>', now())->count(),
            'archive' => LiveBroadcast::where('is_live', false)->where('is_active', true)->where('schedule_time', '<=', now())->count()
        ];

        return view('admin.live_broadcasts.index', compact('broadcasts', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.live_broadcasts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Parse Youtube ID if URL is pasted
        if ($request->filled('video_id') && $request->source_type === 'youtube') {
            $request->merge(['video_id' => $this->extractYoutubeId($request->video_id)]);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'source_type' => 'required|in:youtube,facebook',
            'video_id' => 'required|string|max:255',
            'schedule_time' => 'required|date',
            'is_live' => 'boolean',
            'is_active' => 'boolean',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            $data = $request->only(['title', 'source_type', 'video_id', 'schedule_time', 'description']);
            $data['is_live'] = $request->boolean('is_live', false);
            $data['is_active'] = $request->boolean('is_active', true);

            // If this is set to live, turn off other live broadcasts
            if ($data['is_live']) {
                LiveBroadcast::where('is_live', true)->update(['is_live' => false]);
            }

            LiveBroadcast::create($data);

            DB::commit();

            return redirect()->route('admin.live-broadcasts.index')
                ->with('success', 'নতুন লাইভ অনুষ্ঠান সফলভাবে যুক্ত করা হয়েছে!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('LiveBroadcast store error: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'লাইভ অনুষ্ঠান যোগ করতে সমস্যা হয়েছে: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $broadcast = LiveBroadcast::findOrFail($id);
        return view('admin.live_broadcasts.edit', compact('broadcast'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $broadcast = LiveBroadcast::findOrFail($id);

        // Parse Youtube ID if URL is pasted
        if ($request->filled('video_id') && $request->source_type === 'youtube') {
            $request->merge(['video_id' => $this->extractYoutubeId($request->video_id)]);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'source_type' => 'required|in:youtube,facebook',
            'video_id' => 'required|string|max:255',
            'schedule_time' => 'required|date',
            'is_live' => 'boolean',
            'is_active' => 'boolean',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            $data = $request->only(['title', 'source_type', 'video_id', 'schedule_time', 'description']);
            $data['is_live'] = $request->boolean('is_live', false);
            $data['is_active'] = $request->boolean('is_active', true);

            // If this is set to live, turn off other live broadcasts
            if ($data['is_live']) {
                LiveBroadcast::where('id', '!=', $id)->where('is_live', true)->update(['is_live' => false]);
            }

            $broadcast->update($data);

            DB::commit();

            return redirect()->route('admin.live-broadcasts.index')
                ->with('success', 'লাইভ অনুষ্ঠানের তথ্য সফলভাবে সংশোধন করা হয়েছে!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('LiveBroadcast update error: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'সংশোধন করতে সমস্যা হয়েছে: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $broadcast = LiveBroadcast::findOrFail($id);
            $broadcast->delete();

            return response()->json([
                'success' => true,
                'message' => 'লাইভ অনুষ্ঠানটি সফলভাবে ডিলিট করা হয়েছে!'
            ]);
        } catch (\Exception $e) {
            Log::error('LiveBroadcast destroy error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'ডিলিট করতে সমস্যা হয়েছে!'
            ], 500);
        }
    }

    /**
     * Toggle active live status.
     */
    public function toggleStatus($id)
    {
        try {
            $broadcast = LiveBroadcast::findOrFail($id);
            $broadcast->is_live = !$broadcast->is_live;

            if ($broadcast->is_live) {
                // Deactivate all other live broadcasts first
                LiveBroadcast::where('id', '!=', $id)->where('is_live', true)->update(['is_live' => false]);
            }

            $broadcast->save();

            return response()->json([
                'success' => true,
                'message' => $broadcast->is_live ? 'অনুষ্ঠানটি এখন সরাসরি সম্প্রচার (Live) করা হয়েছে!' : 'সরাসরি সম্প্রচার বন্ধ করা হয়েছে!',
                'is_live' => $broadcast->is_live
            ]);
        } catch (\Exception $e) {
            Log::error('LiveBroadcast toggleStatus error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'স্ট্যাটাস টগল করতে সমস্যা হয়েছে!'
            ], 500);
        }
    }

    /**
     * Helper to extract Youtube Video ID.
     */
    protected function extractYoutubeId($url): string
    {
        $url = trim($url);
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
