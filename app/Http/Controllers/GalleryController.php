<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    public function index(Request $request)
    {
        $galleryPosts = Gallery::where('show_on_gallery', true)
            ->orderBy('gallery_page_order', 'asc')
            ->orderBy('updated_at', 'desc')
            ->with(['blog', 'blog.category'])
            ->paginate(12);

        if ($request->ajax() || $request->has('ajax')) {
            $html = view('theme::pages.gallery_cards', compact('galleryPosts'))->render();

            $newImagesData = $galleryPosts->map(function ($post) {
                return [
                    'image'    => asset($post->image_path),
                    'title'    => $post->title ?? ($post->blog ? $post->blog->title : 'স্থিরচিত্র'),
                    'category' => ($post->blog && $post->blog->category) ? $post->blog->category->name : 'চিত্রশালা',
                    'date'     => $post->created_at->format('d M, Y'),
                    'url'      => $post->blog ? route('blog.detail', $post->blog->slug) : ''
                ];
            })->values()->all();

            return response()->json([
                'success'   => true,
                'html'      => $html,
                'hasMore'   => $galleryPosts->hasMorePages(),
                'newImages' => $newImagesData
            ]);
        }

        return view('theme::pages.gallery', compact('galleryPosts'));
    }
}
