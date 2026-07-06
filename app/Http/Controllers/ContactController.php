<?php

namespace App\Http\Controllers;

use App\Mail\ContactMail;
use App\Models\ContactMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class ContactController extends Controller
{
    /**
     * Contact Page দেখান
     */
    public function index()
    {
        $branches = \App\Models\Branch::where('is_active', true)
            ->orderBy('sort_order', 'asc')
            ->get();
        return view('theme::pages.contact', compact('branches'));
    }

    /**
     * Contact Form Submit
     */
    public function send(Request $request)
    {
        try {
            // Validation
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:100',
                'email' => 'required|email|max:100',
                'phone' => 'required|string|max:20',
                'subject' => 'required|string|max:200',
                'message' => 'required|string|min:10|max:5000',
            ]);

            if ($validator->fails()) {
                return back()
                    ->withErrors($validator)
                    ->withInput()
                    ->with('error', 'দয়া করে সব তথ্য সঠিকভাবে পূরণ করুন।');
            }

            // Save to database
            $contact = ContactMessage::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'subject' => $request->subject,
                'message' => $request->message,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'status' => 'unread',
            ]);

            // Send email to admin
            try {
                Mail::to(config('mail.from.address', 'info@bogurabazar.com'))
                    ->send(new ContactMail($contact));
            } catch (\Exception $e) {
                Log::error('Contact mail send error: ' . $e->getMessage());
                // Continue even if mail fails
            }

            // ✅ সফল মেসেজ
            return back()->with('success', 'আপনার বার্তা সফলভাবে পাঠানো হয়েছে! আমরা শীঘ্রই আপনার সাথে যোগাযোগ করব।');

        } catch (\Exception $e) {
            Log::error('Contact form error: ' . $e->getMessage());
            return back()
                ->withInput()
                ->with('error', 'বার্তা পাঠাতে সমস্যা হয়েছে! দয়া করে পুনরায় চেষ্টা করুন।');
        }
    }
}
