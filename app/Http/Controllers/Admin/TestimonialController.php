<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class TestimonialController extends Controller
{
    /**
     * Display a listing of testimonials.
     */
    public function index(Request $request)
    {
        $query = Testimonial::query();

        // Apply filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->search($search);
        }

        if ($request->filled('rating')) {
            $query->rating($request->rating);
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status == 'active');
        }

        // Sorting
        $sortField = $request->get('sort', 'sort_order');
        $sortDirection = $request->get('direction', 'asc');
        $allowedSortFields = ['id', 'name', 'rating', 'sort_order', 'created_at', 'is_active'];

        if (in_array($sortField, $allowedSortFields)) {
            $query->orderBy($sortField, $sortDirection);
        } else {
            $query->orderBy('sort_order', 'asc');
        }

        $perPage = $request->get('per_page', 20);

        if ($request->ajax()) {
            if ($perPage == '-1') {
                $testimonials = $query->paginate(999999);
            } else {
                $testimonials = $query->paginate((int)$perPage);
            }
            $html = view('admin.testimonials.partials.table', compact('testimonials'))->render();
            return response()->json([
                'success' => true,
                'html' => $html,
                'total' => $testimonials->total(),
                'pagination' => [
                    'total' => $testimonials->total(),
                    'current_page' => $testimonials->currentPage(),
                    'last_page' => $testimonials->lastPage(),
                ]
            ]);
        }

        $testimonials = ($perPage == '-1') ? $query->paginate(999999) : $query->paginate((int)$perPage);

        // Statistics
        $stats = [
            'total' => Testimonial::count(),
            'active' => Testimonial::where('is_active', true)->count(),
            'inactive' => Testimonial::where('is_active', false)->count(),
            'avg_rating' => round(Testimonial::avg('rating') ?? 0, 2),
            'ratings' => [
                '5' => Testimonial::where('rating', 5)->count(),
                '4' => Testimonial::where('rating', 4)->count(),
                '3' => Testimonial::where('rating', 3)->count(),
                '2' => Testimonial::where('rating', 2)->count(),
                '1' => Testimonial::where('rating', 1)->count(),
            ]
        ];

        return view('admin.testimonials.index', compact('testimonials', 'stats'));
    }

    /**
     * Show form for creating new testimonial.
     */
    public function create()
    {
        return view('admin.testimonials.create');
    }

    /**
     * Store a newly created testimonial.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'designation' => 'nullable|string|max:100',
            'company' => 'nullable|string|max:100',
            'email' => 'nullable|email|max:100',
            'phone' => 'nullable|string|max:20',
            'content' => 'required|string|min:10',
            'rating' => 'required|integer|min:1|max:5',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'is_active' => 'boolean',
            'meta_title' => 'nullable|string|max:200',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            $data = $request->except('avatar');

            // Handle avatar upload
            if ($request->hasFile('avatar')) {
                $data['avatar'] = $this->uploadAvatar($request->file('avatar'));
            }

            // Set sort order
            $data['sort_order'] = Testimonial::max('sort_order') + 1;

            // Set default status
            $data['is_active'] = $request->boolean('is_active', true);

            $testimonial = Testimonial::create($data);

            DB::commit();

            return redirect()->route('admin.testimonials.index')
                ->with('success', 'টেস্টিমোনিয়াল সফলভাবে তৈরি করা হয়েছে!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Testimonial creation failed: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'টেস্টিমোনিয়াল তৈরি করতে ব্যর্থ হয়েছে: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified testimonial.
     */
    public function show($id)
    {
        $testimonial = Testimonial::findOrFail($id);

        if (request()->ajax()) {
            $html = view('admin.testimonials.partials.detail', compact('testimonial'))->render();
            return response()->json([
                'success' => true,
                'html' => $html
            ]);
        }

        return view('admin.testimonials.show', compact('testimonial'));
    }

    /**
     * Show form for editing testimonial.
     */
    public function edit($id)
    {
        $testimonial = Testimonial::findOrFail($id);
        return view('admin.testimonials.edit', compact('testimonial'));
    }

    /**
     * Update the specified testimonial.
     */
    public function update(Request $request, $id)
    {
        $testimonial = Testimonial::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'designation' => 'nullable|string|max:100',
            'company' => 'nullable|string|max:100',
            'email' => 'nullable|email|max:100',
            'phone' => 'nullable|string|max:20',
            'content' => 'required|string|min:10',
            'rating' => 'required|integer|min:1|max:5',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'is_active' => 'boolean',
            'meta_title' => 'nullable|string|max:200',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            $data = $request->except('avatar');

            // Handle avatar update
            if ($request->hasFile('avatar')) {
                // Delete old avatar
                if ($testimonial->avatar) {
                    $testimonial->deleteAvatar();
                }
                $data['avatar'] = $this->uploadAvatar($request->file('avatar'));
            }

            // Set status
            $data['is_active'] = $request->boolean('is_active', $testimonial->is_active);

            $testimonial->update($data);

            DB::commit();

            return redirect()->route('admin.testimonials.index')
                ->with('success', 'টেস্টিমোনিয়াল সফলভাবে আপডেট করা হয়েছে!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Testimonial update failed: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'টেস্টিমোনিয়াল আপডেট করতে ব্যর্থ হয়েছে।')
                ->withInput();
        }
    }

    /**
     * Delete the specified testimonial.
     */
    public function destroy($id)
    {
        try {
            $testimonial = Testimonial::findOrFail($id);

            // Delete avatar
            if ($testimonial->avatar) {
                $testimonial->deleteAvatar();
            }

            $testimonial->delete();

            return response()->json([
                'success' => true,
                'message' => 'টেস্টিমোনিয়াল সফলভাবে মুছে ফেলা হয়েছে!'
            ]);

        } catch (\Exception $e) {
            Log::error('Testimonial deletion failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'টেস্টিমোনিয়াল মুছে ফেলতে ব্যর্থ হয়েছে!'
            ], 500);
        }
    }

    /**
     * Toggle testimonial status.
     */
    public function toggleStatus($id)
    {
        try {
            $testimonial = Testimonial::findOrFail($id);
            $testimonial->is_active = !$testimonial->is_active;
            $testimonial->save();

            return response()->json([
                'success' => true,
                'message' => 'স্ট্যাটাস পরিবর্তন করা হয়েছে!',
                'is_active' => $testimonial->is_active,
                'badge' => $testimonial->status_badge
            ]);

        } catch (\Exception $e) {
            Log::error('Status toggle failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'স্ট্যাটাস পরিবর্তন করতে ব্যর্থ হয়েছে!'
            ], 500);
        }
    }

    /**
     * Reorder testimonials (drag and drop).
     */
    public function reorder(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order' => 'required|array',
            'order.*' => 'required|integer|exists:testimonials,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        try {
            foreach ($request->order as $index => $id) {
                Testimonial::where('id', $id)->update(['sort_order' => $index + 1]);
            }

            return response()->json([
                'success' => true,
                'message' => 'সর্ট অর্ডার সফলভাবে আপডেট করা হয়েছে!'
            ]);

        } catch (\Exception $e) {
            Log::error('Reorder failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'সর্ট অর্ডার আপডেট করতে ব্যর্থ হয়েছে!'
            ], 500);
        }
    }

    /**
     * Bulk delete testimonials.
     */
    public function bulkDelete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ids' => 'required|array',
            'ids.*' => 'required|integer|exists:testimonials,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        try {
            $testimonials = Testimonial::whereIn('id', $request->ids)->get();

            foreach ($testimonials as $testimonial) {
                if ($testimonial->avatar) {
                    $testimonial->deleteAvatar();
                }
            }

            Testimonial::whereIn('id', $request->ids)->delete();

            return response()->json([
                'success' => true,
                'message' => count($testimonials) . ' টি টেস্টিমোনিয়াল সফলভাবে মুছে ফেলা হয়েছে!'
            ]);

        } catch (\Exception $e) {
            Log::error('Bulk delete failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'বাল্ক ডিলিট করতে ব্যর্থ হয়েছে!'
            ], 500);
        }
    }

    /**
     * Export testimonials.
     */
    public function export(Request $request)
    {
        $query = Testimonial::query();

        if ($request->filled('search')) {
            $query->search($request->search);
        }

        if ($request->filled('rating')) {
            $query->rating($request->rating);
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status == 'active');
        }

        $testimonials = $query->ordered()->get();

        $filename = 'testimonials_' . date('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($testimonials) {
            $file = fopen('php://output', 'w');

            // CSV Header
            fputcsv($file, [
                'ID',
                'নাম',
                'ডিজাইনেশন',
                'কোম্পানি',
                'ইমেইল',
                'ফোন',
                'রেটিং',
                'স্ট্যাটাস',
                'সর্ট অর্ডার',
                'তারিখ'
            ]);

            foreach ($testimonials as $testimonial) {
                fputcsv($file, [
                    $testimonial->id,
                    $testimonial->name,
                    $testimonial->designation ?? 'N/A',
                    $testimonial->company ?? 'N/A',
                    $testimonial->email ?? 'N/A',
                    $testimonial->phone ?? 'N/A',
                    $testimonial->rating . ' ★',
                    $testimonial->is_active ? 'সক্রিয়' : 'নিষ্ক্রিয়',
                    $testimonial->sort_order,
                    $testimonial->created_at?->format('Y-m-d'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Upload avatar helper method.
     */
    protected function uploadAvatar($avatar): string
    {
        $destinationPath = public_path('uploads/testimonials');
        
        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0777, true);
        }
        
        $fileName = 'testimonial_' . Str::random(10) . '_' . time() . '.' . $avatar->getClientOriginalExtension();
        $avatar->move($destinationPath, $fileName);
        
        return 'uploads/testimonials/' . $fileName;
    }
}