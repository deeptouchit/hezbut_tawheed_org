<?php

namespace App\Http\Controllers;

use App\Models\LiveBroadcast;
use Illuminate\Http\Request;

class LiveController extends Controller
{
    /**
     * Display the live broadcasting page.
     */
    public function index()
    {
        // 1. Find currently active live session
        $live = LiveBroadcast::where('is_live', true)
            ->where('is_active', true)
            ->first();

        $upcoming = null;
        if (!$live) {
            // 2. Find closest upcoming scheduled live session
            $upcoming = LiveBroadcast::where('is_live', false)
                ->where('is_active', true)
                ->where('schedule_time', '>', now())
                ->orderBy('schedule_time', 'asc')
                ->first();
        }

        // 3. Find archived/past live streams
        $archives = LiveBroadcast::where('is_live', false)
            ->where('is_active', true)
            ->where('schedule_time', '<=', now())
            ->orderBy('schedule_time', 'desc')
            ->paginate(12);

        return view('theme::pages.live', compact('live', 'upcoming', 'archives'));
    }

    /**
     * Display a specific archived broadcast.
     */
    public function show($id)
    {
        $live = LiveBroadcast::where('id', $id)
            ->where('is_active', true)
            ->firstOrFail();

        $upcoming = null;

        $archives = LiveBroadcast::where('is_live', false)
            ->where('is_active', true)
            ->where('id', '!=', $id)
            ->where('schedule_time', '<=', now())
            ->orderBy('schedule_time', 'desc')
            ->paginate(12);

        return view('theme::pages.live', compact('live', 'upcoming', 'archives'));
    }

    /**
     * Increment the views count.
     */
    public function incrementView($id)
    {
        try {
            $broadcast = LiveBroadcast::findOrFail($id);
            $broadcast->increment('view_count');

            return response()->json([
                'success' => true,
                'view_count' => $broadcast->view_count
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get the current view count.
     */
    public function getViewCount($id)
    {
        try {
            $broadcast = LiveBroadcast::findOrFail($id);

            return response()->json([
                'success' => true,
                'view_count' => $broadcast->view_count
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
