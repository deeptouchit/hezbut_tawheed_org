<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class JoinController extends Controller
{
    public function index()
    {
        return view('theme::pages.join');
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'membership_type'   => 'required|string|in:primary,pledge',
                'name'              => 'required|string|max:100',
                'dob'               => 'nullable|date',
                'father_husband'    => 'nullable|string|max:100',
                'phone'             => 'required|string|max:25',
                'present_address'   => 'nullable|string|max:500',
                'permanent_address' => 'nullable|string|max:500',
                'occupation'        => 'nullable|string|max:100',
                'education'         => 'nullable|string|max:100',
                'experience'        => 'nullable|string|max:500',
                'how_knew'          => 'required|string|max:100',
                'person_name'       => 'nullable|string|max:100',
                'person_phone'      => 'nullable|string|max:25',
            ]);

            $howKnewStr = $request->how_knew;
            $typeLabel = $request->membership_type === 'primary' ? 'প্রাথমিক সদস্য পদ' : 'পাঁচ দফা ভিত্তিক অঙ্গীকার পত্র';

            $msgContent = "যোগদানের আবেদন টাইপ: {$typeLabel}\n" .
                          "জন্ম তারিখ: " . ($request->dob ?? 'N/A') . "\n" .
                          "পিতা / স্বামীর নাম: " . ($request->father_husband ?? 'N/A') . "\n" .
                          "বর্তমান ঠিকানা: " . ($request->present_address ?? 'N/A') . "\n" .
                          "স্থায়ী ঠিকানা: " . ($request->permanent_address ?? 'N/A') . "\n" .
                          "পেশা: " . ($request->occupation ?? 'N/A') . "\n" .
                          "শিক্ষাগত যোগ্যতা: " . ($request->education ?? 'N/A') . "\n" .
                          "দক্ষতা / পারদর্শিতা: " . ($request->experience ?? 'N/A') . "\n" .
                          "আন্দোলন সম্পর্কে জানার উপায়: {$howKnewStr}\n" .
                          "পরিচিত ব্যক্তির নাম: " . ($request->person_name ?? 'N/A') . "\n" .
                          "পরিচিত ব্যক্তির মোবাইল নম্বর: " . ($request->person_phone ?? 'N/A');

            ContactMessage::create([
                'name'       => $request->name,
                'email'      => 'membership@hezbuttawheed.org',
                'phone'      => $request->phone,
                'subject'    => "নতুন সদস্য পদের আবেদন - ({$typeLabel})",
                'message'    => $msgContent,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'status'     => 'unread',
            ]);

            return back()->with('success', 'আপনার সদস্য পদের আবেদনটি সফলভাবে গৃহীত হয়েছে! আমরা খুব শীঘ্রই আপনার সাথে যোগাযোগ করব ইনশাআল্লাহ।');

        } catch (\Exception $e) {
            Log::error('Join submit error: ' . $e->getMessage());
            return back()
                ->withInput()
                ->with('error', 'আবেদনপত্র জমা দিতে সমস্যা হয়েছে! দয়া করে সব তথ্য সঠিকভাবে পূরণ করে পুনরায় চেষ্টা করুন।');
        }
    }
}
