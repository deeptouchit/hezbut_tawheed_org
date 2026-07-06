<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Leader;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class LeaderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Leader::query();

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('english_name', 'like', "%{$search}%")
                  ->orWhere('designation', 'like', "%{$search}%");
            });
        }

        // Category filter
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $query->orderBy('sort_order', 'asc')->orderBy('id', 'asc');
        $perPage = $request->get('per_page', 15);

        if ($request->ajax()) {
            $leaders = ($perPage == '-1') ? $query->paginate(9999) : $query->paginate((int)$perPage);
            $html = view('admin.leaders.partials.table', compact('leaders'))->render();
            return response()->json([
                'success' => true,
                'html' => $html,
                'total' => $leaders->total()
            ]);
        }

        $leaders = ($perPage == '-1') ? $query->paginate(9999) : $query->paginate((int)$perPage);

        // Stats
        $stats = [
            'total' => Leader::count(),
            'central' => Leader::where('category', 'central')->count(),
            'advisory' => Leader::where('category', 'advisory')->count(),
            'executive' => Leader::where('category', 'executive')->count()
        ];

        return view('admin.leaders.index', compact('leaders', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.leaders.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'english_name' => 'required|string|max:255',
            'designation' => 'required|string|max:255',
            'category' => 'required|string|in:central,advisory,executive,regional',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'signature_image' => 'nullable|image|mimes:png,svg|max:1024',
            'speech_video_url' => 'nullable|string|max:255',
            'quote' => 'nullable|string',
            'bio' => 'nullable|string',
            'facebook_url' => 'nullable|url|max:255',
            'twitter_url' => 'nullable|url|max:255',
            'linkedin_url' => 'nullable|url|max:255',
            'email' => 'nullable|email|max:255',
            'sort_order' => 'required|integer',
            'is_active' => 'boolean',
            'is_founder' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $data = $request->except(['image', 'signature_image']);
            $data['is_active'] = $request->boolean('is_active', true);
            $data['is_founder'] = $request->boolean('is_founder', false);

            // Handle Image Upload
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = 'leader_' . time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('uploads/leaders'), $imageName);
                $data['image'] = 'uploads/leaders/' . $imageName;
            }

            // Handle Signature Image Upload
            if ($request->hasFile('signature_image')) {
                $sig = $request->file('signature_image');
                $sigName = 'sig_' . time() . '.' . $sig->getClientOriginalExtension();
                $sig->move(public_path('uploads/leaders'), $sigName);
                $data['signature_image'] = 'uploads/leaders/' . $sigName;
            }

            Leader::create($data);

            return redirect()->route('admin.leaders.index')
                ->with('success', 'নেতৃত্বের প্রোফাইল সফলভাবে যুক্ত করা হয়েছে!');
        } catch (\Exception $e) {
            Log::error('Leader store error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'প্রোফাইল যোগ করতে সমস্যা হয়েছে: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $leader = Leader::findOrFail($id);
        return view('admin.leaders.edit', compact('leader'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $leader = Leader::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'english_name' => 'required|string|max:255',
            'designation' => 'required|string|max:255',
            'category' => 'required|string|in:central,advisory,executive,regional',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'signature_image' => 'nullable|image|mimes:png,svg|max:1024',
            'speech_video_url' => 'nullable|string|max:255',
            'quote' => 'nullable|string',
            'bio' => 'nullable|string',
            'facebook_url' => 'nullable|url|max:255',
            'twitter_url' => 'nullable|url|max:255',
            'linkedin_url' => 'nullable|url|max:255',
            'email' => 'nullable|email|max:255',
            'sort_order' => 'required|integer',
            'is_active' => 'boolean',
            'is_founder' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $data = $request->except(['image', 'signature_image']);
            $data['is_active'] = $request->boolean('is_active', true);
            $data['is_founder'] = $request->boolean('is_founder', false);

            // Handle Image Upload
            if ($request->hasFile('image')) {
                // Delete old image
                if ($leader->image && File::exists(public_path($leader->image))) {
                    File::delete(public_path($leader->image));
                }

                $image = $request->file('image');
                $imageName = 'leader_' . time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('uploads/leaders'), $imageName);
                $data['image'] = 'uploads/leaders/' . $imageName;
            }

            // Handle Signature Image Upload
            if ($request->hasFile('signature_image')) {
                // Delete old signature
                if ($leader->signature_image && File::exists(public_path($leader->signature_image))) {
                    File::delete(public_path($leader->signature_image));
                }

                $sig = $request->file('signature_image');
                $sigName = 'sig_' . time() . '.' . $sig->getClientOriginalExtension();
                $sig->move(public_path('uploads/leaders'), $sigName);
                $data['signature_image'] = 'uploads/leaders/' . $sigName;
            }

            $leader->update($data);

            return redirect()->route('admin.leaders.index')
                ->with('success', 'নেতৃত্বের প্রোফাইল সফলভাবে সংশোধন করা হয়েছে!');
        } catch (\Exception $e) {
            Log::error('Leader update error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'সংশোধন করতে সমস্যা হয়েছে: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $leader = Leader::findOrFail($id);

            // Delete files
            if ($leader->image && File::exists(public_path($leader->image))) {
                File::delete(public_path($leader->image));
            }
            if ($leader->signature_image && File::exists(public_path($leader->signature_image))) {
                File::delete(public_path($leader->signature_image));
            }

            $leader->delete();

            return response()->json([
                'success' => true,
                'message' => 'নেতৃত্বের প্রোফাইলটি সফলভাবে ডিলিট করা হয়েছে!'
            ]);
        } catch (\Exception $e) {
            Log::error('Leader destroy error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'ডিলিট করতে সমস্যা হয়েছে!'
            ], 500);
        }
    }

    /**
     * Toggle active status.
     */
    public function toggleStatus($id)
    {
        try {
            $leader = Leader::findOrFail($id);
            $leader->is_active = !$leader->is_active;
            $leader->save();

            return response()->json([
                'success' => true,
                'message' => $leader->is_active ? 'প্রোফাইলটি সফলভাবে সক্রিয় করা হয়েছে!' : 'প্রোফাইলটি নিষ্ক্রিয় করা হয়েছে!',
                'is_active' => $leader->is_active
            ]);
        } catch (\Exception $e) {
            Log::error('Leader toggleStatus error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'স্ট্যাটাস টগল করতে সমস্যা হয়েছে!'
            ], 500);
        }
    }

    /**
     * Update sort order inline.
     */
    public function updateOrder(Request $request, $id)
    {
        $request->validate([
            'sort_order' => 'required|integer'
        ]);

        try {
            $leader = Leader::findOrFail($id);
            $leader->sort_order = $request->sort_order;
            $leader->save();

            return response()->json([
                'success' => true,
                'message' => 'ক্রম বিন্যাস সফলভাবে আপডেট করা হয়েছে!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'আপডেট করতে ব্যর্থ হয়েছে!'
            ], 500);
        }
    }

    /**
     * Renders hierarchy builder.
     */
    public function hierarchy()
    {
        $leaders = Leader::where('is_active', true)->orderBy('sort_order', 'asc')->get();
        
        // Group items by parent_id
        $rootItems = [];
        $childrenByParent = [];

        foreach ($leaders as $leader) {
            $pId = $leader->parent_id;
            if (!$pId) {
                $rootItems[] = $leader;
            } else {
                $childrenByParent[$pId][] = $leader;
            }
        }

        // Recursively build a flat array ordered hierarchically
        $sortedLeaders = [];
        $traverse = function ($items) use (&$sortedLeaders, $childrenByParent, &$traverse) {
            foreach ($items as $item) {
                $sortedLeaders[] = $item;
                if (isset($childrenByParent[$item->id])) {
                    $traverse($childrenByParent[$item->id]);
                }
            }
        };

        $traverse($rootItems);

        // Add orphans
        foreach ($leaders as $leader) {
            if (!in_array($leader, $sortedLeaders)) {
                $sortedLeaders[] = $leader;
            }
        }

        $leaders = collect($sortedLeaders);

        return view('admin.leaders.hierarchy', compact('leaders'));
    }

    /**
     * Updates hierarchy via AJAX.
     */
    public function updateHierarchy(Request $request)
    {
        $items = $request->input('items');
        if (!is_array($items)) {
            return response()->json(['success' => false, 'message' => 'ভুল ডাটা পাঠানো হয়েছে!'], 400);
        }

        try {
            \Illuminate\Support\Facades\DB::transaction(function () use ($items) {
                foreach ($items as $item) {
                    $leader = Leader::find($item['id']);
                    if ($leader) {
                        $leader->parent_id = $item['parent_id'] ?: null;
                        $leader->sort_order = $item['position'];
                        $leader->save();
                    }
                }
            });

            return response()->json([
                'success' => true,
                'message' => 'সাংগঠনিক হায়ারার্কি সফলভাবে সংরক্ষণ করা হয়েছে!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'সংরক্ষণ করতে ব্যর্থ হয়েছে: ' . $e->getMessage()
            ], 500);
        }
    }
}
