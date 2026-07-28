<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\BookChapter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ConstitutionController extends Controller
{
    /**
     * Display the official Constitution page.
     */
    public function index()
    {
        // 1. Fetch Constitution Book
        $book = Book::where('slug', 'constitution')->first();

        // 2. Fetch active chapters sorted
        $chapters = collect();
        if ($book) {
            $chapters = BookChapter::where('book_id', $book->id)
                ->where('is_active', true)
                ->orderBy('sort_order', 'asc')
                ->get();
        }

        return view('theme::pages.constitution', compact('book', 'chapters'));
    }
}
