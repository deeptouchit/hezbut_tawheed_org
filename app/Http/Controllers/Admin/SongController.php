<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Song;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class SongController extends Controller
{
    /**
     * Display a listing of songs.
     */
    public function index(Request $request)
    {
        $query = Song::query();

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('lyrics', 'like', "%{$search}%");
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
            $songs = ($perPage == '-1') ? $query->get() : $query->paginate((int)$perPage);
            $html = view('admin.songs.partials.table', compact('songs'))->render();
            return response()->json([
                'success' => true,
                'html' => $html,
                'total' => ($songs instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator) ? $songs->total() : $songs->count()
            ]);
        }

        $songs = ($perPage == '-1') ? $query->get() : $query->paginate((int)$perPage);

        // Statistics
        $stats = [
            'total' => Song::count(),
            'active' => Song::where('is_active', true)->count(),
            'inactive' => Song::where('is_active', false)->count()
        ];

        return view('admin.songs.index', compact('songs', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.songs.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if ($request->filled('youtube_id')) {
            $youtubeId = $this->extractYoutubeId($request->youtube_id);
            $request->merge(['youtube_id' => $youtubeId]);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'youtube_id' => 'nullable|string|max:50',
            'audio_file' => 'nullable|file|mimes:mp3,wav,ogg,aac,m4a|max:20480', // Max 20MB
            'video_file' => 'nullable|file|mimes:mp4,webm|max:51200', // Max 50MB
            'lyrics' => 'nullable|string',
            'category' => 'required|string|max:100',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            $song = new Song();
            $song->title = $request->title;
            $song->lyrics = $request->lyrics;
            $song->category = $request->category;
            $song->youtube_id = $request->youtube_id;
            $song->is_active = $request->boolean('is_active', true);
            $song->sort_order = $request->input('sort_order', 0);

            if ($song->youtube_id) {
                $song->thumbnail_url = "https://img.youtube.com/vi/{$song->youtube_id}/mqdefault.jpg";
            }

            if ($request->hasFile('audio_file')) {
                $song->audio_file = $this->uploadFile($request->file('audio_file'), 'uploads/songs/audio', 'song_audio_');
            }

            if ($request->hasFile('video_file')) {
                $song->video_file = $this->uploadFile($request->file('video_file'), 'uploads/songs/video', 'song_video_');
            }

            $song->save();

            DB::commit();

            return redirect()->route('admin.songs.index')->with('success', 'গানটি সফলভাবে সংরক্ষণ করা হয়েছে।');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Song creation failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'গান সংরক্ষণ করতে ব্যর্থ হয়েছে: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $song = Song::findOrFail($id);
        return view('admin.songs.edit', compact('song'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        if ($request->filled('youtube_id')) {
            $youtubeId = $this->extractYoutubeId($request->youtube_id);
            $request->merge(['youtube_id' => $youtubeId]);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'youtube_id' => 'nullable|string|max:50',
            'audio_file' => 'nullable|file|mimes:mp3,wav,ogg,aac,m4a|max:20480',
            'video_file' => 'nullable|file|mimes:mp4,webm|max:51200',
            'lyrics' => 'nullable|string',
            'category' => 'required|string|max:100',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            $song = Song::findOrFail($id);
            $song->title = $request->title;
            $song->lyrics = $request->lyrics;
            $song->category = $request->category;
            $song->youtube_id = $request->youtube_id;
            $song->is_active = $request->boolean('is_active', true);
            $song->sort_order = $request->input('sort_order', 0);

            if ($song->youtube_id) {
                $song->thumbnail_url = "https://img.youtube.com/vi/{$song->youtube_id}/mqdefault.jpg";
            }

            if ($request->hasFile('audio_file')) {
                $this->deleteFile($song->audio_file);
                $song->audio_file = $this->uploadFile($request->file('audio_file'), 'uploads/songs/audio', 'song_audio_');
            }

            if ($request->hasFile('video_file')) {
                $this->deleteFile($song->video_file);
                $song->video_file = $this->uploadFile($request->file('video_file'), 'uploads/songs/video', 'song_video_');
            }

            $song->save();

            DB::commit();

            return redirect()->route('admin.songs.index')->with('success', 'গানটি সফলভাবে আপডেট করা হয়েছে।');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Song update failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'গান আপডেট করতে ব্যর্থ হয়েছে: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $song = Song::findOrFail($id);
            $this->deleteFile($song->audio_file);
            $this->deleteFile($song->video_file);
            $song->delete();

            return response()->json([
                'success' => true,
                'message' => 'গানটি সফলভাবে মুছে ফেলা হয়েছে।'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'গানটি মুছতে ব্যর্থ হয়েছে: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Toggle the status of the specified resource.
     */
    public function toggleStatus($id)
    {
        try {
            $song = Song::findOrFail($id);
            $song->is_active = !$song->is_active;
            $song->save();

            return response()->json([
                'success' => true,
                'message' => 'স্ট্যাটাস সফলভাবে পরিবর্তন করা হয়েছে।',
                'is_active' => $song->is_active
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'স্ট্যাটাস পরিবর্তন করা সম্ভব হয়নি।'
            ]);
        }
    }

    /**
     * File Upload helper (Stores in public directory).
     */
    protected function uploadFile($file, $folder, $prefix): string
    {
        $destinationPath = public_path($folder);

        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0777, true);
        }

        $fileName = $prefix . Str::random(10) . '_' . time() . '.' . $file->getClientOriginalExtension();
        $file->move($destinationPath, $fileName);

        return $folder . '/' . $fileName;
    }

    /**
     * File Delete helper.
     */
    protected function deleteFile($path): void
    {
        if ($path && file_exists(public_path($path))) {
            @unlink(public_path($path));
        }
    }

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
