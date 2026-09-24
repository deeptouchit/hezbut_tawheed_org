<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\BookChapter;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BookChapterController extends Controller
{
    /**
     * Display a listing of book chapters.
     */
    public function index(Request $request)
    {
        $query = BookChapter::with('book');

        // Book Filter
        if ($request->filled('book_id')) {
            $query->where('book_id', $request->book_id);
        }

        // Search Filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('chapter_number', 'like', "%{$search}%")
                    ->orWhere('content', 'like', "%{$search}%");
            });
        }

        $chapters = $query->orderBy('book_id', 'asc')
            ->orderBy('sort_order', 'asc')
            ->paginate(20)
            ->withQueryString();

        $books = Book::where('is_active', true)->orderBy('title', 'asc')->get();

        return view('admin.book-chapters.index', compact('chapters', 'books'));
    }

    /**
     * Show the form for creating a new chapter.
     */
    public function create(Request $request)
    {
        $books = Book::where('is_active', true)->orderBy('title', 'asc')->get();
        $selectedBookId = $request->get('book_id');

        return view('admin.book-chapters.create', compact('books', 'selectedBookId'));
    }

    /**
     * Store a newly created chapter.
     */
    public function store(Request $request)
    {
        $request->validate([
            'book_id' => 'required|exists:books,id',
            'chapter_number' => 'nullable|string|max:100',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'pdf_url' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);

        $slug = Str::slug($request->title);
        $count = BookChapter::where('book_id', $request->book_id)->where('slug', $slug)->count();
        if ($count > 0) {
            $slug .= '-' . time();
        }

        BookChapter::create([
            'book_id' => $request->book_id,
            'chapter_number' => $request->chapter_number,
            'title' => $request->title,
            'slug' => $slug,
            'content' => $request->content,
            'pdf_url' => $request->pdf_url,
            'sort_order' => $request->get('sort_order', 0),
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.book-chapters.index', ['book_id' => $request->book_id])
            ->with('success', 'অধ্যায় সফলভাবে তৈরি করা হয়েছে!');
    }

    /**
     * Show the form for editing the chapter.
     */
    public function edit($id)
    {
        $chapter = BookChapter::findOrFail($id);
        $books = Book::where('is_active', true)->orderBy('title', 'asc')->get();

        return view('admin.book-chapters.edit', compact('chapter', 'books'));
    }

    /**
     * Update the chapter.
     */
    public function update(Request $request, $id)
    {
        $chapter = BookChapter::findOrFail($id);

        $request->validate([
            'book_id' => 'required|exists:books,id',
            'chapter_number' => 'nullable|string|max:100',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'pdf_url' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer',
        ]);

        if ($chapter->title !== $request->title) {
            $slug = Str::slug($request->title);
            $count = BookChapter::where('book_id', $request->book_id)->where('slug', $slug)->where('id', '!=', $id)->count();
            if ($count > 0) {
                $slug .= '-' . time();
            }
            $chapter->slug = $slug;
        }

        $chapter->update([
            'book_id' => $request->book_id,
            'chapter_number' => $request->chapter_number,
            'title' => $request->title,
            'content' => $request->content,
            'pdf_url' => $request->pdf_url,
            'sort_order' => $request->get('sort_order', 0),
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.book-chapters.index', ['book_id' => $chapter->book_id])
            ->with('success', 'অধ্যায় সফলভাবে আপডেট করা হয়েছে!');
    }

    /**
     * Toggle chapter active status.
     */
    public function toggleStatus($id)
    {
        $chapter = BookChapter::findOrFail($id);
        $chapter->is_active = !$chapter->is_active;
        $chapter->save();

        return back()->with('success', 'স্ট্যাটাস আপডেট করা হয়েছে!');
    }

    /**
     * Remove the chapter.
     */
    public function destroy($id)
    {
        $chapter = BookChapter::findOrFail($id);
        $bookId = $chapter->book_id;
        $chapter->delete();

        return redirect()->route('admin.book-chapters.index', ['book_id' => $bookId])
            ->with('success', 'অধ্যায় সফলভাবে মুছে ফেলা হয়েছে!');
    }
}
