<?php

namespace App\Http\Controllers;

use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class VideoController extends Controller
{
    public function index(Request $request)
    {
        try {
            // Check cache to sync only once every 24 hours (86400 seconds)
            if (!\Illuminate\Support\Facades\Cache::has('youtube_videos_synced')) {
                $apiKey = 'AIzaSyDj921v3iuYLuT6vQZPLHuXICH7AEh27-Q';
                $channelId = 'UCSCJ8zB-CPD0H5P3R67HzCw';
                
                // 1. Fetch Playlists of the channel
                $playlistsUrl = "https://www.googleapis.com/youtube/v3/playlists?key={$apiKey}&channelId={$channelId}&part=snippet&maxResults=15";
                
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $playlistsUrl);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                $playlistsResponse = curl_exec($ch);
                curl_close($ch);
                
                $playlistsData = json_decode($playlistsResponse, true);
                
                if (isset($playlistsData['items']) && count($playlistsData['items']) > 0) {
                    foreach ($playlistsData['items'] as $playlistItem) {
                        $playlistId = $playlistItem['id'] ?? '';
                        $playlistTitle = $playlistItem['snippet']['title'] ?? '';
                        
                        if ($playlistId && $playlistTitle) {
                            // 2. Fetch up to 25 items for this specific playlist
                            $itemsUrl = "https://www.googleapis.com/youtube/v3/playlistItems?key={$apiKey}&playlistId={$playlistId}&part=snippet,contentDetails&maxResults=25";
                            
                            $ch = curl_init();
                            curl_setopt($ch, CURLOPT_URL, $itemsUrl);
                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                            $itemsResponse = curl_exec($ch);
                            curl_close($ch);
                            
                            $itemsData = json_decode($itemsResponse, true);
                            
                            if (isset($itemsData['items']) && count($itemsData['items']) > 0) {
                                foreach ($itemsData['items'] as $item) {
                                    $youtubeId = $item['contentDetails']['videoId'] ?? '';
                                    $title = $item['snippet']['title'] ?? '';
                                    $description = $item['snippet']['description'] ?? '';
                                    $publishedAt = isset($item['snippet']['publishedAt']) ? date('Y-m-d H:i:s', strtotime($item['snippet']['publishedAt'])) : now();
                                    
                                    if ($youtubeId) {
                                        $video = Video::where('youtube_id', $youtubeId)->first();
                                        if (!$video) {
                                            $video = new Video();
                                            $video->youtube_id = $youtubeId;
                                            $video->is_active = true;
                                            $video->sort_order = 0;
                                        }
                                        $video->title = $title;
                                        $video->description = Str::limit($description, 1000, '');
                                        $video->playlist_id = $playlistId;
                                        $video->playlist_title = $playlistTitle;
                                        $video->created_at = $publishedAt;
                                        $video->save();
                                    }
                                }
                            }
                        }
                    }
                }

                // 3. Also sync the main Uploads playlist (to get videos not in any playlists)
                $uploadPlaylistId = 'UUSCJ8zB-CPD0H5P3R67HzCw';
                $uploadsUrl = "https://www.googleapis.com/youtube/v3/playlistItems?key={$apiKey}&playlistId={$uploadPlaylistId}&part=snippet,contentDetails&maxResults=50";

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $uploadsUrl);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                $uploadsResponse = curl_exec($ch);
                curl_close($ch);

                $uploadsData = json_decode($uploadsResponse, true);

                if (isset($uploadsData['items']) && count($uploadsData['items']) > 0) {
                    foreach ($uploadsData['items'] as $item) {
                        $youtubeId = $item['contentDetails']['videoId'] ?? '';
                        $title = $item['snippet']['title'] ?? '';
                        $description = $item['snippet']['description'] ?? '';
                        $publishedAt = isset($item['snippet']['publishedAt']) ? date('Y-m-d H:i:s', strtotime($item['snippet']['publishedAt'])) : now();

                        if ($youtubeId) {
                            $video = Video::where('youtube_id', $youtubeId)->first();
                            if (!$video) {
                                $video = new Video();
                                $video->youtube_id = $youtubeId;
                                $video->is_active = true;
                                $video->sort_order = 0;
                                $video->title = $title;
                                $video->description = Str::limit($description, 1000, '');
                                $video->created_at = $publishedAt;
                                $video->save();
                            } else {
                                // If it already exists, just update title and description but don't clear its playlist_id if set
                                $video->title = $title;
                                $video->description = Str::limit($description, 1000, '');
                                $video->save();
                            }
                        }
                    }
                }
                
                // Keep cache active for 24 hours
                \Illuminate\Support\Facades\Cache::put('youtube_videos_synced', true, 86400);
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('YouTube Sync Exception: ' . $e->getMessage());
        }

        $latestVideos = Video::where('is_active', true)
            ->orderBy('sort_order', 'asc')
            ->orderBy('created_at', 'desc')
            ->take(12)
            ->get();

        // Get unique playlists that have synced videos
        $playlists = Video::where('is_active', true)
            ->whereNotNull('playlist_id')
            ->where('playlist_id', '!=', '')
            ->select('playlist_id', 'playlist_title')
            ->groupBy('playlist_id', 'playlist_title')
            ->get();

        // Attach latest 12 videos to each playlist
        foreach ($playlists as $playlist) {
            $playlist->videos = Video::where('is_active', true)
                ->where('playlist_id', $playlist->playlist_id)
                ->orderBy('created_at', 'desc')
                ->take(12)
                ->get();
        }

        // Keep all videos for the top theater player list
        $videos = Video::where('is_active', true)
            ->orderBy('sort_order', 'asc')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('theme::pages.videos.index', compact('videos', 'playlists', 'latestVideos'));
    }
}
