<?php

namespace App\Http\Controllers;

use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TestimonialController extends Controller
{
    /**
     * Display a listing of active testimonials.
     */
    public function index(Request $request)
    {
        $testimonials = Testimonial::active()
            ->latest('id')
            ->paginate(12);

        if ($request->ajax()) {
            return response()->json([
                'html' => view('theme::pages.testimonials_list', compact('testimonials'))->render(),
                'hasMore' => $testimonials->hasMorePages()
            ]);
        }

        return view('theme::pages.testimonials', compact('testimonials'));
    }

    /**
     * Store a newly created testimonial in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'name'        => 'required|string|max:100',
                'email'       => 'required|email|max:100',
                'phone'       => 'nullable|string|max:20',
                'designation' => 'required|string|max:100',
                'content'     => 'required|string|min:5|max:3000',
                'rating'      => 'required|integer|min:1|max:5',
            ]);

            $testimonial = Testimonial::create([
                'name'        => strip_tags($request->name),
                'email'       => strip_tags($request->email),
                'phone'       => $request->phone ? strip_tags($request->phone) : null,
                'designation' => strip_tags($request->designation),
                'content'     => strip_tags($request->content),
                'rating'      => $request->rating,
                'is_active'   => false,
                'company'     => 'সাধারণ নাগরিক',
            ]);

            // Send database notification to admins
            try {
                \App\Models\Notification::sendToAdmins(
                    'নতুন মতামত/উদ্ধৃতি',
                    $request->name . ' একটি নতুন মতামত পাঠিয়েছেন যা অনুমোদনের অপেক্ষায় আছে।',
                    'system',
                    route('admin.testimonials.show', $testimonial->id)
                );
            } catch (\Exception $e) {
                Log::error('Testimonial notification error: ' . $e->getMessage());
            }

            return back()->with('success', 'আপনার মূল্যবান মতামত পাঠানোর জন্য ধন্যবাদ! এটি মডারেটরের অনুমোদনের পর ওয়েবসাইটে প্রকাশ করা হবে।');
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            Log::error('Testimonial submit error: ' . $e->getMessage());
            return back()
                ->withInput()
                ->with('error', 'মতামত পাঠাতে সমস্যা হয়েছে! দয়া করে সব তথ্য পূরণ করে পুনরায় চেষ্টা করুন।');
        }
    }
}
