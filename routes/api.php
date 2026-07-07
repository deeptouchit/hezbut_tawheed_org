<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

use App\Models\Blog;
use Illuminate\Support\Facades\Cache;

Route::get('/gallery', function () {
    $posts = Cache::remember('api_gallery_posts', 3600, function () {
        return Blog::published()
            ->where('is_gallery', true)
            ->whereNotNull('featured_image')
            ->where('featured_image', '!=', '')
            ->select('id', 'title', 'slug', 'featured_image', 'category_id', 'published_at')
            ->with('category:id,name,slug')
            ->orderBy('gallery_order', 'asc')
            ->orderBy('published_at', 'desc')
            ->orderBy('id', 'desc')
            ->take(8)
            ->get()
            ->filter(function($post) {
                if (empty($post->featured_image)) return false;
                $path = public_path($post->featured_image);
                return file_exists($path) && filesize($path) > 0;
            })
            ->values();
    });

    return response()->json([
        'success' => true,
        'data' => $posts->map(function ($post) {
            return [
                'id' => $post->id,
                'title' => $post->title,
                'slug' => $post->slug,
                'url' => url('/articles/' . $post->slug),
                'image_url' => asset($post->featured_image),
                'category' => $post->category ? $post->category->name : null,
                'published_at' => $post->published_at ? $post->published_at->toIso8601String() : null,
            ];
        })
    ]);
});


