<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Slider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SliderController extends Controller
{
    public function index(Request $request)
    {
        $query = Slider::query()
            ->when($request->search, function($q, $search) {
                $q->where('title', 'LIKE', "%{$search}%")
                  ->orWhere('sub_title', 'LIKE', "%{$search}%");
            })
            ->when($request->status !== null && $request->status !== '', function($q) use ($request) {
                $q->where('status', $request->status);
            })
            ->when($request->position, function($q, $position) {
                $q->where('position', $position);
            })
            ->orderBy('sort_order', 'asc')
            ->orderBy('id', 'desc');

        $perPage = $request->input('per_page', 20);

        if ($perPage == 'all') {
            $sliders     = $query->get();
            $currentPage = \Illuminate\Pagination\LengthAwarePaginator::resolveCurrentPage();
            $sliders     = new \Illuminate\Pagination\LengthAwarePaginator(
                $sliders->forPage($currentPage, $sliders->count()),
                $sliders->count(),
                $sliders->count(),
                $currentPage,
                ['path' => \Illuminate\Pagination\Paginator::resolveCurrentPath()]
            );
        } else {
            $perPage = is_numeric($perPage) ? (int)$perPage : 20;
            $perPage = in_array($perPage, [10, 20, 30, 50, 100, 200]) ? $perPage : 20;
            $sliders = $query->paginate($perPage);
            $sliders->appends($request->query());
        }

        $positions = ['homepage', 'banner', 'popup'];
        $stats     = [
            'total'    => Slider::count(),
            'active'   => Slider::where('status', true)->count(),
            'inactive' => Slider::where('status', false)->count(),
        ];

        if ($request->ajax()) {
            $html = view('admin.sliders.partials.table', compact('sliders'))->render();
            return response()->json([
                'success' => true,
                'html'    => $html,
                'stats'   => $stats,
            ]);
        }

        return view('admin.sliders.index', compact('sliders', 'positions', 'stats'));
    }

    public function create()
    {
        return view('admin.sliders.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'        => 'nullable|string|max:255',
            'sub_title'    => 'nullable|string|max:255',
            'image'        => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
            'mobile_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'link'         => 'nullable|string|max:255',
            'link_text'    => 'nullable|string|max:100',
            'button_text'  => 'nullable|string|max:100',
            'button_link'  => 'nullable|string|max:255',
            'button_color' => 'nullable|string|max:20',
            'position'     => 'required|in:homepage,banner,popup',
            'sort_order'   => 'nullable|integer|min:0',
            'start_date'   => 'nullable|date',
            'end_date'     => 'nullable|date|after_or_equal:start_date',
        ]);

        try {
            DB::beginTransaction();

            $slider = Slider::create([
                'title'        => $request->title,
                'sub_title'    => $request->sub_title,
                'link'         => $request->link,
                'link_text'    => $request->link_text,
                'button_text'  => $request->button_text,
                'button_link'  => $request->button_link,
                'button_color' => $request->button_color ?? '#007bff',
                'position'     => $request->position,
                'sort_order'   => $request->sort_order ?? 0,
                'status'       => $request->has('status'),
                'start_date'   => $request->start_date,
                'end_date'     => $request->end_date,
            ]);

              // Upload main image
            if ($request->hasFile('image')) {
                $file            = $request->file('image');
                $fileName        = 'slider_' . $slider->id . '_' . time() . '.' . $file->getClientOriginalExtension();
                $destinationPath = public_path('uploads/sliders');

                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0777, true);
                }

                $file->move($destinationPath, $fileName);
                $slider->image = 'uploads/sliders/' . $fileName;
                $slider->save();
            }

              // Upload mobile image
            if ($request->hasFile('mobile_image')) {
                $file            = $request->file('mobile_image');
                $fileName        = 'slider_mobile_' . $slider->id . '_' . time() . '.' . $file->getClientOriginalExtension();
                $destinationPath = public_path('uploads/sliders');
                $file->move($destinationPath, $fileName);
                $slider->mobile_image = 'uploads/sliders/' . $fileName;
                $slider->save();
            }

            DB::commit();

            return redirect()->route('admin.sliders.index')
                ->with('success', 'স্লাইডার সফলভাবে তৈরি করা হয়েছে।');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Slider store error: ' . $e->getMessage());
            return back()->withInput()->with('error', 'স্লাইডার তৈরি করতে ব্যর্থ হয়েছে।');
        }
    }

    public function edit($id)
    {
        $slider = Slider::findOrFail($id);

        return view('admin.sliders.edit', compact('slider'));
    }

    public function update(Request $request, $id)
    {
        $slider = Slider::findOrFail($id);

        $request->validate([
            'title'        => 'nullable|string|max:255',
            'sub_title'    => 'nullable|string|max:255',
            'image'        => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'mobile_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'link'         => 'nullable|string|max:255',
            'link_text'    => 'nullable|string|max:100',
            'button_text'  => 'nullable|string|max:100',
            'button_link'  => 'nullable|string|max:255',
            'button_color' => 'nullable|string|max:20',
            'position'     => 'required|in:homepage,banner,popup',
            'sort_order'   => 'nullable|integer|min:0',
            'start_date'   => 'nullable|date',
            'end_date'     => 'nullable|date|after_or_equal:start_date',
        ]);

        try {
            DB::beginTransaction();

            $slider->update([
                'title'        => $request->title,
                'sub_title'    => $request->sub_title,
                'link'         => $request->link,
                'link_text'    => $request->link_text,
                'button_text'  => $request->button_text,
                'button_link'  => $request->button_link,
                'button_color' => $request->button_color ?? '#007bff',
                'position'     => $request->position,
                'sort_order'   => $request->sort_order ?? 0,
                'status'       => $request->has('status'),
                'start_date'   => $request->start_date,
                'end_date'     => $request->end_date,
            ]);

              // Upload main image
            if ($request->hasFile('image')) {
                  // Delete old image
                if ($slider->image && file_exists(public_path($slider->image))) {
                    @unlink(public_path($slider->image));
                }

                $file            = $request->file('image');
                $fileName        = 'slider_' . $slider->id . '_' . time() . '.' . $file->getClientOriginalExtension();
                $destinationPath = public_path('uploads/sliders');
                $file->move($destinationPath, $fileName);
                $slider->image = 'uploads/sliders/' . $fileName;
                $slider->save();
            }

              // Upload mobile image
            if ($request->hasFile('mobile_image')) {
                if ($slider->mobile_image && file_exists(public_path($slider->mobile_image))) {
                    @unlink(public_path($slider->mobile_image));
                }

                $file            = $request->file('mobile_image');
                $fileName        = 'slider_mobile_' . $slider->id . '_' . time() . '.' . $file->getClientOriginalExtension();
                $destinationPath = public_path('uploads/sliders');
                $file->move($destinationPath, $fileName);
                $slider->mobile_image = 'uploads/sliders/' . $fileName;
                $slider->save();
            }

            DB::commit();

            return redirect()->route('admin.sliders.index')
                ->with('success', 'স্লাইডার সফলভাবে আপডেট করা হয়েছে।');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Slider update error: ' . $e->getMessage());
            return back()->withInput()->with('error', 'স্লাইডার আপডেট করতে ব্যর্থ হয়েছে।');
        }
    }

    public function destroy($id)
    {
        try {
            $slider = Slider::findOrFail($id);

              // Delete images
            if ($slider->image && file_exists(public_path($slider->image))) {
                @unlink(public_path($slider->image));
            }
            if ($slider->mobile_image && file_exists(public_path($slider->mobile_image))) {
                @unlink(public_path($slider->mobile_image));
            }

            $slider->delete();

            return response()->json([
                'success' => true,
                'message' => 'স্লাইডার মুছে ফেলা হয়েছে।'
            ]);

        } catch (\Exception $e) {
            Log::error('Slider delete error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'স্লাইডার মুছে ফেলতে ব্যর্থ হয়েছে।'
            ], 500);
        }
    }

    public function toggleStatus($id)
    {
        try {
            $slider         = Slider::findOrFail($id);
            $slider->status = !$slider->status;
            $slider->save();

            return response()->json([
                'success' => true,
                'status'  => $slider->status,
                'message' => $slider->status ? 'স্লাইডার সক্রিয় করা হয়েছে।' : 'স্লাইডার নিষ্ক্রিয় করা হয়েছে।'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'স্ট্যাটাস পরিবর্তন করতে ব্যর্থ হয়েছে।'
            ], 500);
        }
    }

    public function updateOrder(Request $request)
    {
        $request->validate([
            'orders'              => 'required|array',
            'orders.*.id'         => 'required|exists:sliders,id',
            'orders.*.sort_order' => 'required|integer|min:0'
        ]);

        try {
            foreach ($request->orders as $order) {
                Slider::where('id', $order['id'])->update(['sort_order' => $order['sort_order']]);
            }

            return response()->json([
                'success' => true,
                'message' => 'ক্রম সফলভাবে আপডেট করা হয়েছে।'
            ]);

        } catch (\Exception $e) {
            Log::error('Update order error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'ক্রম আপডেট করতে ব্যর্থ হয়েছে।'
            ], 500);
        }
    }
}
