<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class BookController extends Controller
{
    /**
     * Display a listing of books.
     */
    public function index(Request $request)
    {
        $query = Book::query();

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('is_active', $request->status == 'active');
        }

        // Sorting
        $sortField = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');
        $allowedSortFields = ['id', 'title', 'slug', 'is_active', 'created_at'];

        if ($request->has('sort') && in_array($sortField, $allowedSortFields)) {
            $query->orderBy($sortField, $sortDirection);
        } else {
            $query->orderBy('is_popular', 'desc')
                  ->orderBy('popular_order', 'asc')
                  ->orderBy('created_at', 'desc');
        }

        $perPage = $request->get('per_page', 20);

        if ($request->ajax()) {
            $books = ($perPage == '-1') ? $query->get() : $query->paginate((int)$perPage);
            $html = view('admin.books.partials.table', compact('books'))->render();
            return response()->json([
                'success' => true,
                'html' => $html,
                'total' => $books->total() ?? $books->count()
            ]);
        }

        $books = $query->paginate($perPage);

        // Statistics
        $stats = [
            'total' => Book::count(),
            'active' => Book::where('is_active', true)->count(),
            'inactive' => Book::where('is_active', false)->count()
        ];

        return view('admin.books.index', compact('books', 'stats'));
    }

    /**
     * Show form for creating a new book.
     */
    public function create()
    {
        return view('admin.books.create');
    }

    /**
     * Store a newly created book in database.
     */
    public function store(Request $request)
    {
        // Auto-generate slug from title if empty
        if (!$request->filled('slug') && $request->filled('title')) {
            $request->merge(['slug' => $this->generateSlug($request->title)]);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:200',
            'writer' => 'nullable|string|max:200',
            'slug' => 'required|string|max:200|unique:books,slug',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'description' => 'nullable|string|max:1000',
            'content' => 'nullable|string',
            'pdf_url' => 'nullable|string|max:255',
            'pdf_file' => 'nullable|file|mimes:pdf|max:51200',
            'price' => 'nullable|string|max:50',
            'old_price' => 'nullable|string|max:50',
            'is_active' => 'boolean',
            'is_popular' => 'boolean',
            'popular_order' => 'integer|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            $data = $request->only(['title', 'writer', 'slug', 'description', 'content', 'pdf_url', 'price', 'old_price', 'popular_order']);
            $data['is_active'] = $request->boolean('is_active', true);
            $data['is_popular'] = $request->boolean('is_popular', false);

            // Handle image upload
            if ($request->hasFile('image')) {
                $data['image'] = $this->uploadImage($request->file('image'));
            }

            // Handle PDF upload
            if ($request->hasFile('pdf_file')) {
                $data['pdf_url'] = $this->uploadPdf($request->file('pdf_file'));
            }

            $book = Book::create($data);

            DB::commit();

            return redirect()->route('admin.books.index')
                ->with('success', 'নতুন বই সফলভাবে যোগ করা হয়েছে!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Book creation failed: ' . $e->getMessage());

            // Delete uploaded file if transaction failed
            if (isset($data['image'])) {
                $this->deleteImage($data['image']);
            }

            return redirect()->back()
                ->with('error', 'বই তৈরি করতে ব্যর্থ হয়েছে: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Show form for editing the specified book.
     */
    public function edit($id)
    {
        $book = Book::findOrFail($id);
        return view('admin.books.edit', compact('book'));
    }

    /**
     * Update the specified book in database.
     */
    public function update(Request $request, $id)
    {
        $book = Book::findOrFail($id);

        // Auto-generate slug from title if empty
        if (!$request->filled('slug') && $request->filled('title')) {
            $request->merge(['slug' => $this->generateSlug($request->title)]);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:200',
            'writer' => 'nullable|string|max:200',
            'slug' => 'required|string|max:200|unique:books,slug,' . $id,
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'description' => 'nullable|string|max:1000',
            'content' => 'nullable|string',
            'pdf_url' => 'nullable|string|max:255',
            'pdf_file' => 'nullable|file|mimes:pdf|max:51200',
            'price' => 'nullable|string|max:50',
            'old_price' => 'nullable|string|max:50',
            'is_active' => 'boolean',
            'is_popular' => 'boolean',
            'popular_order' => 'integer|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $oldImage = $book->image;
        $oldPdf = $book->getRawOriginal('pdf_url');

        try {
            DB::beginTransaction();

            $data = $request->only(['title', 'writer', 'slug', 'description', 'content', 'pdf_url', 'price', 'old_price', 'popular_order']);
            $data['is_active'] = $request->boolean('is_active', true);
            $data['is_popular'] = $request->boolean('is_popular', false);

            // Handle image upload
            if ($request->hasFile('image')) {
                $data['image'] = $this->uploadImage($request->file('image'));
            }

            // Handle PDF upload
            if ($request->hasFile('pdf_file')) {
                $data['pdf_url'] = $this->uploadPdf($request->file('pdf_file'));
            }

            $book->update($data);

            DB::commit();

            // Delete old image if a new one was uploaded
            if ($request->hasFile('image') && $oldImage) {
                $this->deleteImage($oldImage);
            }

            // Delete old PDF if a new one was uploaded
            if ($request->hasFile('pdf_file') && $oldPdf) {
                $this->deletePdf($oldPdf);
            }

            return redirect()->route('admin.books.index')
                ->with('success', 'বইয়ের তথ্য সফলভাবে আপডেট করা হয়েছে!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Book update failed: ' . $e->getMessage());

            // Delete newly uploaded file if transaction failed
            if (isset($data['image'])) {
                $this->deleteImage($data['image']);
            }

            return redirect()->back()
                ->with('error', 'বই আপডেট করতে ব্যর্থ হয়েছে: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified book from database.
     */
    public function destroy($id)
    {
        try {
            $book = Book::findOrFail($id);
            $image = $book->image;
            $pdf = $book->getRawOriginal('pdf_url');

            $book->delete();

            // Delete image file
            if ($image) {
                $this->deleteImage($image);
            }

            // Delete PDF file
            if ($pdf) {
                $this->deletePdf($pdf);
            }

            return response()->json([
                'success' => true,
                'message' => 'বই সফলভাবে ডিলিট করা হয়েছে!'
            ]);
        } catch (\Exception $e) {
            Log::error('Book deletion failed: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'বই ডিলিট করতে ব্যর্থ হয়েছে: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle active status of a book.
     */
    public function toggleStatus($id)
    {
        try {
            $book = Book::findOrFail($id);
            $book->is_active = !$book->is_active;
            $book->save();

            return response()->json([
                'success' => true,
                'message' => 'বইয়ের স্ট্যাটাস পরিবর্তন করা হয়েছে!',
                'is_active' => $book->is_active
            ]);
        } catch (\Exception $e) {
            Log::error('Book status toggle failed: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'স্ট্যাটাস পরিবর্তন করতে ব্যর্থ হয়েছে!'
            ], 500);
        }
    }

    /**
     * Toggle popular status of a book.
     */
    public function togglePopular($id)
    {
        try {
            $book = Book::findOrFail($id);
            $book->is_popular = !$book->is_popular;
            $book->save();

            return response()->json([
                'success' => true,
                'message' => 'বইয়ের জনপ্রিয় স্ট্যাটাস পরিবর্তন করা হয়েছে!',
                'is_popular' => $book->is_popular
            ]);
        } catch (\Exception $e) {
            Log::error('Book popular status toggle failed: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'স্ট্যাটাস পরিবর্তন করতে ব্যর্থ হয়েছে!'
            ], 500);
        }
    }

    /**
     * Update popular order of a book.
     */
    public function updateOrder(Request $request, $id)
    {
        try {
            $book = Book::findOrFail($id);
            $book->popular_order = intval($request->get('popular_order', 0));
            $book->save();

            return response()->json([
                'success' => true,
                'message' => 'বইয়ের অর্ডারিং নম্বর আপডেট করা হয়েছে!'
            ]);
        } catch (\Exception $e) {
            Log::error('Book order update failed: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'অর্ডারিং নম্বর আপডেট করতে ব্যর্থ হয়েছে!'
            ], 500);
        }
    }

    /**
     * Upload image helper.
     */
    protected function uploadImage($image): string
    {
        $destinationPath = public_path('uploads/books');

        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0777, true);
        }

        $fileName = 'book_' . Str::random(10) . '_' . time() . '.' . $image->getClientOriginalExtension();
        $image->move($destinationPath, $fileName);

        return 'uploads/books/' . $fileName;
    }

    /**
     * Upload PDF helper.
     */
    protected function uploadPdf($pdf): string
    {
        $destinationPath = public_path('uploads/books/pdfs');

        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0777, true);
        }

        $fileName = 'book_pdf_' . Str::random(10) . '_' . time() . '.' . $pdf->getClientOriginalExtension();
        $pdf->move($destinationPath, $fileName);

        return 'uploads/books/pdfs/' . $fileName;
    }

    /**
     * Delete PDF helper.
     */
    protected function deletePdf($path): void
    {
        // Strip out the asset URL if present
        $cleanPath = str_replace(asset(''), '', $path);
        if ($cleanPath && file_exists(public_path($cleanPath))) {
            @unlink(public_path($cleanPath));
        }
    }

    /**
     * Delete image helper.
     */
    protected function deleteImage($path): void
    {
        if ($path && file_exists(public_path($path))) {
            @unlink(public_path($path));
        }
    }

    /**
     * Generate SEO-friendly slug supporting Unicode/Bengali characters.
     */
    protected function generateSlug($title)
    {
        $slug = preg_replace('/[^\p{L}\p{N}]+/u', '-', $title);
        $slug = trim($slug, '-');
        return mb_strtolower($slug);
    }
}
