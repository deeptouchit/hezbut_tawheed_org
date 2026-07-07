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

use App\Models\Gallery;
use Illuminate\Support\Facades\Cache;

Route::get('/gallery', function () {
    $posts = Cache::remember('api_gallery_posts', 3600, function () {
        return Gallery::where('is_active', true)
            ->orderBy('gallery_order', 'asc')
            ->orderBy('updated_at', 'desc')
            ->take(8)
            ->with(['blog', 'blog.category'])
            ->get()
            ->filter(function($post) {
                if (empty($post->image_path)) return false;
                $path = public_path($post->image_path);
                return file_exists($path) && filesize($path) > 0;
            })
            ->values();
    });

    return response()->json([
        'success' => true,
        'data' => $posts->map(function ($post) {
            return [
                'id' => $post->id,
                'title' => $post->title ?? ($post->blog ? $post->blog->title : null),
                'url' => $post->blog ? url('/articles/' . $post->blog->slug) : null,
                'image_url' => asset($post->image_path),
                'category' => ($post->blog && $post->blog->category) ? $post->blog->category->name : null,
                'published_at' => $post->blog && $post->blog->published_at ? $post->blog->published_at->toIso8601String() : null,
            ];
        })
    ]);
});


