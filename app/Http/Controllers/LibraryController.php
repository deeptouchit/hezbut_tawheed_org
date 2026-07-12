<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class LibraryController extends Controller
{
    public function index(Request $request)
    {
        $query = Book::where('is_active', true);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('writer', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $books = $query->orderBy('title', 'asc')->paginate(16);

        return view('theme::pages.library.index', compact('books'));
    }

    public function read($slug)
    {
        $book = Book::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $otherBooks = Book::where('id', '!=', $book->id)
            ->where('is_active', true)
            ->orderBy('title', 'asc')
            ->get();

        return view('theme::pages.library.read', compact('book', 'otherBooks'));
    }
}
