<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function index(Request $request)
    {
        $categories = \App\Models\BookCategory::where('is_active', true)->get();
        $categorySlug = $request->get('category');
        $selectedCategory = null;

        if ($categorySlug) {
            $selectedCategory = \App\Models\BookCategory::where('slug', $categorySlug)->first();
        }

        $search = $request->get('search');

        if ($search) {
            $popularBooks = collect();
            $bestSellingBooks = collect();
            
            $booksQuery = Book::where('is_active', true);
            if ($selectedCategory) {
                $booksQuery->where('category_id', $selectedCategory->id);
            }
            $booksQuery->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('writer', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
            
            $books = $booksQuery->orderBy('title', 'asc')
                ->paginate(12)
                ->withQueryString();
        } else {
            $popularQuery = Book::where('is_active', true)->where('is_popular', true);
            $bestsellerQuery = Book::where('is_active', true)->where('is_bestseller', true);
            $booksQuery = Book::where('is_active', true);

            if ($selectedCategory) {
                $popularQuery->where('category_id', $selectedCategory->id);
                $bestsellerQuery->where('category_id', $selectedCategory->id);
                $booksQuery->where('category_id', $selectedCategory->id);
            }

            $popularBooks = $popularQuery->orderBy('popular_order', 'asc')
                ->orderBy('created_at', 'desc')
                ->take(10)
                ->get();

            $bestSellingBooks = $bestsellerQuery->orderBy('popular_order', 'asc')
                ->orderBy('created_at', 'desc')
                ->take(10)
                ->get();

            $excludedIds = array_unique(array_merge(
                $popularBooks->pluck('id')->toArray(),
                $bestSellingBooks->pluck('id')->toArray()
            ));

            $books = $booksQuery->whereNotIn('id', $excludedIds)
                ->orderBy('created_at', 'desc')
                ->paginate(12)
                ->withQueryString();
        }

        // Fetch page model if exists
        $page = \App\Models\Page::where('slug', 'publications')->first();

        return view('theme::pages.books.index', compact('page', 'categories', 'selectedCategory', 'popularBooks', 'bestSellingBooks', 'books'));
    }

    public function loadMore(Request $request)
    {
        $page = intval($request->get('page', 2));
        $perPage = 12;
        $categorySlug = $request->get('category');
        $search = $request->get('search');

        $selectedCategory = null;
        if ($categorySlug) {
            $selectedCategory = \App\Models\BookCategory::where('slug', $categorySlug)->first();
        }

        $booksQuery = Book::where('is_active', true);

        if ($selectedCategory) {
            $booksQuery->where('category_id', $selectedCategory->id);
        }

        if ($search) {
            $booksQuery->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('writer', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
            $books = $booksQuery->orderBy('title', 'asc')
                ->paginate($perPage, ['*'], 'page', $page);
        } else {
            $popularQuery = Book::where('is_active', true)->where('is_popular', true);
            $bestsellerQuery = Book::where('is_active', true)->where('is_bestseller', true);

            if ($selectedCategory) {
                $popularQuery->where('category_id', $selectedCategory->id);
                $bestsellerQuery->where('category_id', $selectedCategory->id);
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
        }

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

    public function read($slug)
    {
        $book = Book::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $otherBooks = Book::where('id', '!=', $book->id)
            ->where('is_active', true)
            ->orderBy('is_popular', 'desc')
            ->orderBy('is_bestseller', 'desc')
            ->orderBy('popular_order', 'asc')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('theme::pages.books.read', compact('book', 'otherBooks'));
    }

    public function libraryIndex()
    {
        $firstBook = Book::where('is_active', true)
            ->orderBy('is_popular', 'desc')
            ->orderBy('is_bestseller', 'desc')
            ->orderBy('popular_order', 'asc')
            ->orderBy('created_at', 'desc')
            ->first();
        if ($firstBook) {
            return redirect()->route('books.read', $firstBook->slug);
        }
        return redirect()->route('books.index');
    }

    public function storeReview(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:5',
        ]);

        $book = Book::findOrFail($id);

        \App\Models\BookReview::create([
            'book_id' => $id,
            'name' => $request->name,
            'email' => $request->email,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'is_active' => true,
        ]);

        // Send database notification to admins
        try {
            \App\Models\Notification::sendToAdmins(
                'নতুন বই রিভিউ',
                $request->name . ' "' . $book->title . '" বইটির উপর একটি রিভিউ দিয়েছেন।',
                'system',
                route('admin.books.show', $book->id)
            );
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Book review notification error: ' . $e->getMessage());
        }

        return back()->with('success', 'আপনার রিভিউটি সফলভাবে জমা হয়েছে!');
    }
}
