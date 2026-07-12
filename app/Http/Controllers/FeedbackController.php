<?php

namespace App\Http\Controllers;

use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class FeedbackController extends Controller
{
    public function index()
    {
        $feedbacks = Testimonial::active()
            ->ordered()
            ->paginate(12);

        return view('theme::pages.feedback', compact('feedbacks'));
    }

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

            Testimonial::create([
                'name'        => $request->name,
                'email'       => $request->email,
                'phone'       => $request->phone,
                'designation' => $request->designation,
                'content'     => $request->content,
                'rating'      => $request->rating,
                'is_active'   => false,
                'company'     => 'সাধারণ নাগরিক',
            ]);

            return back()->with('success', 'আপনার মূল্যবান মতামত পাঠানোর জন্য ধন্যবাদ! এটি মডারেটরের অনুমোদনের পর ওয়েবসাইটে প্রকাশ করা হবে।');

        } catch (\Exception $e) {
            Log::error('Feedback submit error: ' . $e->getMessage());
            return back()
                ->withInput()
                ->with('error', 'মতামত পাঠাতে সমস্যা হয়েছে! দয়া করে সব তথ্য পূরণ করে পুনরায় চেষ্টা করুন।');
        }
    }
}
