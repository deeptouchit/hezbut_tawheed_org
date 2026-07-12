<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class BranchController extends Controller
{
    /**
     * Display a listing of branches.
     */
    public function index(Request $request)
    {
        $query = Branch::query();

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('contact_person_name', 'like', "%{$search}%");
            });
        }

        // Type filter
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $query->orderBy('sort_order', 'asc')->orderBy('id', 'asc');
        $perPage = $request->get('per_page', 15);

        if ($request->ajax() || $request->has('ajax')) {
            $branches = ($perPage == '-1') ? $query->paginate(9999) : $query->paginate((int)$perPage);
            $html = view('admin.branches.partials.table', compact('branches'))->render();
            return response()->json([
                'success' => true,
                'html' => $html,
                'total' => $branches->total()
            ]);
        }

        $branches = ($perPage == '-1') ? $query->paginate(9999) : $query->paginate((int)$perPage);

        // Stats
        $stats = [
            'total' => Branch::count(),
            'central' => Branch::where('type', 'central')->count(),
            'division' => Branch::where('type', 'division')->count(),
            'district' => Branch::where('type', 'district')->count(),
            'upazila' => Branch::where('type', 'upazila')->count(),
            'international' => Branch::where('type', 'international')->count()
        ];

        return view('admin.branches.index', compact('branches', 'stats'));
    }

    /**
     * Show the form for creating a new branch.
     */
    public function create()
    {
        return view('admin.branches.create');
    }

    /**
     * Store a newly created branch in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|in:central,division,district,upazila,international',
            'address' => 'required|string',
            'phone' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'google_map_url' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'sort_order' => 'required|integer|min:0',
        ]);

        $data = $request->except('image');
        $data['is_active'] = $request->has('is_active');

        // Handle image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $path = 'uploads/branches/' . $filename;
            
            // Create directory if not exists
            if (!File::exists(public_path('uploads/branches'))) {
                File::makeDirectory(public_path('uploads/branches'), 0777, true, true);
            }
            
            $image->move(public_path('uploads/branches'), $filename);
            $data['image'] = $path;
        }

        $branch = Branch::create($data);

        // Process dynamic officials list
        $officialsData = $request->input('officials', []);
        $officialsFiles = $request->file('officials', []);

        foreach ($officialsData as $key => $official) {
            if (empty($official['name']) || empty($official['designation'])) {
                continue; // Skip incomplete items
            }

            $imagePath = null;
            if (isset($officialsFiles[$key]['image'])) {
                $file = $officialsFiles[$key]['image'];
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                
                if (!File::exists(public_path('uploads/branches/officials'))) {
                    File::makeDirectory(public_path('uploads/branches/officials'), 0777, true, true);
                }

                $file->move(public_path('uploads/branches/officials'), $filename);
                $imagePath = 'uploads/branches/officials/' . $filename;
            }

            $branch->officials()->create([
                'designation' => $official['designation'],
                'name' => $official['name'],
                'phone' => $official['phone'] ?? null,
                'email' => $official['email'] ?? null,
                'image' => $imagePath,
                'sort_order' => $official['sort_order'] ?? 0,
            ]);
        }

        return redirect()->route('admin.branches.index')
            ->with('success', 'কার্যালয় সফলভাবে যুক্ত করা হয়েছে!');
    }

    /**
     * Show the form for editing the specified branch.
     */
    public function edit($id)
    {
        $branch = Branch::findOrFail($id);
        return view('admin.branches.edit', compact('branch'));
    }

    /**
     * Update the specified branch in storage.
     */
    public function update(Request $request, $id)
    {
        $branch = Branch::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|in:central,division,district,upazila,international',
            'address' => 'required|string',
            'phone' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'google_map_url' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'sort_order' => 'required|integer|min:0',
        ]);

        $data = $request->except('image');
        $data['is_active'] = $request->has('is_active');

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($branch->image && File::exists(public_path($branch->image))) {
                File::delete(public_path($branch->image));
            }

            $image = $request->file('image');
            $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $path = 'uploads/branches/' . $filename;
            
            if (!File::exists(public_path('uploads/branches'))) {
                File::makeDirectory(public_path('uploads/branches'), 0777, true, true);
            }
            
            $image->move(public_path('uploads/branches'), $filename);
            $data['image'] = $path;
        }

        $branch->update($data);

        // Gather all new/updated officials
        $officialsData = $request->input('officials', []);
        $officialsFiles = $request->file('officials', []);

        // List of image paths to keep (so we don't accidentally delete their files)
        $keptImages = [];
        foreach ($officialsData as $official) {
            if (!empty($official['existing_image'])) {
                $keptImages[] = $official['existing_image'];
            }
        }

        // Delete old officials' files if they are not in keptImages
        foreach ($branch->officials as $oldOfficial) {
            if ($oldOfficial->image && !in_array($oldOfficial->image, $keptImages) && File::exists(public_path($oldOfficial->image))) {
                File::delete(public_path($oldOfficial->image));
            }
        }

        // Delete all old official rows
        $branch->officials()->delete();

        // Create new/updated officials rows
        foreach ($officialsData as $key => $official) {
            if (empty($official['name']) || empty($official['designation'])) {
                continue; // Skip incomplete items
            }

            $imagePath = $official['existing_image'] ?? null;

            if (isset($officialsFiles[$key]['image'])) {
                // Delete previous image file for this item if updated
                if ($imagePath && File::exists(public_path($imagePath))) {
                    File::delete(public_path($imagePath));
                }

                $file = $officialsFiles[$key]['image'];
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

                if (!File::exists(public_path('uploads/branches/officials'))) {
                    File::makeDirectory(public_path('uploads/branches/officials'), 0777, true, true);
                }

                $file->move(public_path('uploads/branches/officials'), $filename);
                $imagePath = 'uploads/branches/officials/' . $filename;
            }

            $branch->officials()->create([
                'designation' => $official['designation'],
                'name' => $official['name'],
                'phone' => $official['phone'] ?? null,
                'email' => $official['email'] ?? null,
                'image' => $imagePath,
                'sort_order' => $official['sort_order'] ?? 0,
            ]);
        }

        return redirect()->route('admin.branches.index')
            ->with('success', 'কার্যালয়ের তথ্য সফলভাবে সংশোধন করা হয়েছে!');
    }

    public function destroy($id)
    {
        try {
            $branch = Branch::findOrFail($id);

            // Delete officials image files
            foreach ($branch->officials as $official) {
                if ($official->image && File::exists(public_path($official->image))) {
                    File::delete(public_path($official->image));
                }
            }

            // Delete image file
            if ($branch->image && File::exists(public_path($branch->image))) {
                File::delete(public_path($branch->image));
            }

            $branch->delete();

            return response()->json([
                'success' => true,
                'message' => 'কার্যালয় সফলভাবে মুছে ফেলা হয়েছে!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'কার্যালয় মুছতে ব্যর্থ হয়েছে: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle branch active status via AJAX.
     */
    public function toggleStatus($id)
    {
        try {
            $branch = Branch::findOrFail($id);
            $branch->is_active = !$branch->is_active;
            $branch->save();

            return response()->json([
                'success' => true,
                'is_active' => $branch->is_active,
                'message' => 'স্ট্যাটাস সফলভাবে পরিবর্তন করা হয়েছে!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'স্ট্যাটাস পরিবর্তন করতে ব্যর্থ হয়েছে!'
            ], 500);
        }
    }
}
