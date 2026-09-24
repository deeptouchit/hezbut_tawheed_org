<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Song;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class SongController extends Controller
{
    /**
     * Display the songs portal.
     */
    public function index()
    {
        try {
            // Check cache to sync only once every 24 hours (86400 seconds)
            if (!Cache::has('youtube_songs_synced')) {
                $apiKey = 'AIzaSyDj921v3iuYLuT6vQZPLHuXICH7AEh27-Q';
                $channelId = 'UCq-1ypvG-1t6hILukVKgxxQ'; // Corrected Maati Music Channel ID
                
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
                        
                        // Map playlist titles to readable categories
                        $category = 'awakening';
                        if (str_contains($playlistTitle, 'দেশাত্মবোধক') || str_contains($playlistTitle, 'জাতীয়') || str_contains($playlistTitle, 'Desh')) {
                            $category = 'national';
                        } elseif (str_contains($playlistTitle, 'দলীয়') || str_contains($playlistTitle, ' Anthem')) {
                            $category = 'party_anthem';
                        }
                        
                        if ($playlistId) {
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
                                    $thumbnail = $item['snippet']['thumbnails']['medium']['url'] ?? '';
                                    $publishedAt = isset($item['snippet']['publishedAt']) ? date('Y-m-d H:i:s', strtotime($item['snippet']['publishedAt'])) : now();
                                    
                                    if ($youtubeId) {
                                        $song = Song::where('youtube_id', $youtubeId)->first();
                                        if (!$song) {
                                            $song = new Song();
                                            $song->youtube_id = $youtubeId;
                                            $song->is_active = true;
                                            $song->sort_order = 0;
                                        }
                                        $song->title = $title;
                                        $song->lyrics = $description;
                                        $song->category = $category;
                                        $song->thumbnail_url = $thumbnail;
                                        $song->created_at = $publishedAt;
                                        $song->save();
                                    }
                                }
                            }
                        }
                    }
                }

                // 3. Also sync the main Uploads playlist
                $uploadPlaylistId = 'UUq-1ypvG-1t6hILukVKgxxQ';
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
                        $thumbnail = $item['snippet']['thumbnails']['medium']['url'] ?? '';
                        $publishedAt = isset($item['snippet']['publishedAt']) ? date('Y-m-d H:i:s', strtotime($item['snippet']['publishedAt'])) : now();

                        if ($youtubeId) {
                            $song = Song::where('youtube_id', $youtubeId)->first();
                            if (!$song) {
                                $song = new Song();
                                $song->youtube_id = $youtubeId;
                                $song->is_active = true;
                                $song->sort_order = 0;
                                $song->title = $title;
                                $song->lyrics = $description;
                                $song->category = 'awakening';
                                $song->thumbnail_url = $thumbnail;
                                $song->created_at = $publishedAt;
                                $song->save();
                            } else {
                                $song->title = $title;
                                $song->thumbnail_url = $thumbnail;
                                $song->save();
                            }
                        }
                    }
                }

                // Cache for 24 hours
                Cache::put('youtube_songs_synced', true, 86400);
            }
        } catch (\Exception $e) {
            // Silence API sync errors to fallback to existing database records
            \Illuminate\Support\Facades\Log::error('Song YouTube sync failed: ' . $e->getMessage());
        }

        $songs = Song::where('is_active', true)
            ->orderBy('sort_order', 'asc')
            ->orderBy('created_at', 'desc')
            ->get();

        // Group songs by category
        $groupedSongs = $songs->groupBy('category');

        // Extract featured song (e.g., party anthem)
        $featuredSong = $songs->where('category', 'party_anthem')->first() ?? $songs->first();

        return view('theme::pages.songs.index', compact('songs', 'groupedSongs', 'featuredSong'));
    }
}
