<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function index()
    {
        return redirect()->route('page.show', 'publications', 301);
    }

    public function loadMore(Request $request)
    {
        $page = intval($request->get('page', 2));
        $perPage = 12;
        $categorySlug = $request->get('category');

        $selectedCategory = null;
        if ($categorySlug) {
            $selectedCategory = \App\Models\BookCategory::where('slug', $categorySlug)->first();
        }

        $popularQuery = Book::where('is_active', true)->where('is_popular', true);
        $bestsellerQuery = Book::where('is_active', true)->where('is_bestseller', true);
        $booksQuery = Book::where('is_active', true);

        if ($selectedCategory) {
            $popularQuery->where('category_id', $selectedCategory->id);
            $bestsellerQuery->where('category_id', $selectedCategory->id);
            $booksQuery->where('category_id', $selectedCategory->id);
        }

        $popularIds = $popularQuery->orderBy('popular_order', 'asc')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->pluck('id')
            ->toArray();

        $bestsellerIds = $bestsellerQuery->orderBy('popular_order', 'asc')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->pluck('id')
            ->toArray();

        $excludedIds = array_unique(array_merge($popularIds, $bestsellerIds));

        $books = $booksQuery->whereNotIn('id', $excludedIds)
            ->orderBy('created_at', 'desc')
            ->paginate($perPage, ['*'], 'page', $page);

        if ($request->ajax()) {
            $html = view('theme::pages.books.partials.grid_items', compact('books'))->render();
            return response()->json([
                'success' => true,
                'html' => $html,
                'hasMore' => $books->hasMorePages()
            ]);
        }

        return redirect()->route('books.index');
    }

    public function show($slug)
    {
        $book = Book::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $recentBooks = Book::where('id', '!=', $book->id)
            ->where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('theme::pages.books.show', compact('book', 'recentBooks'));
    }

    public function storeReview(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:5',
        ]);

        \App\Models\BookReview::create([
            'book_id' => $id,
            'name' => $request->name,
            'email' => $request->email,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'is_active' => true,
        ]);

        return back()->with('success', 'আপনার রিভিউটি সফলভাবে জমা হয়েছে!');
    }
}
