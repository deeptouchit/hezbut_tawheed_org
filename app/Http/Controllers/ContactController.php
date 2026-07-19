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
            // Honeypot spam check: if 'website' field has any content, it is likely a bot.
            if ($request->filled('website')) {
                Log::warning('Contact form honeypot spam attempt blocked from IP: ' . $request->ip());
                // Return success to fool the bot into thinking it succeeded
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => true,
                        'message' => 'আপনার বার্তা সফলভাবে পাঠানো হয়েছে! আমরা শীঘ্রই আপনার সাথে যোগাযোগ করব।'
                    ]);
                }
                return back()->with('success', 'আপনার বার্তা সফলভাবে পাঠানো হয়েছে! আমরা শীঘ্রই আপনার সাথে যোগাযোগ করব।');
            }

            // Validation
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:100',
                'email' => 'required|email|max:100',
                'phone' => 'required|string|max:20',
                'subject' => 'required|string|max:200',
                'message' => 'required|string|min:10|max:5000',
            ], [
                'name.required' => 'দয়া করে আপনার নাম লিখুন।',
                'name.string' => 'নাম অবশ্যই সঠিক টেক্সট হতে হবে।',
                'name.max' => 'নাম ১০০ অক্ষরের বেশি হতে পারবে না।',
                'email.required' => 'দয়া করে আপনার ইমেইল ঠিকানা লিখুন।',
                'email.email' => 'একটি সঠিক ইমেইল ঠিকানা লিখুন।',
                'email.max' => 'ইমেইল ১০০ অক্ষরের বেশি হতে পারবে না।',
                'phone.required' => 'দয়া করে আপনার মোবাইল নম্বর লিখুন।',
                'phone.max' => 'মোবাইল নম্বর ২০ অক্ষরের বেশি হতে পারবে না।',
                'subject.required' => 'দয়া করে বার্তার বিষয় লিখুন।',
                'subject.max' => 'বিষয় ২০০ অক্ষরের বেশি হতে পারবে না।',
                'message.required' => 'দয়া করে আপনার বার্তাটি লিখুন।',
                'message.min' => 'বার্তাটি কমপক্ষে ১০ অক্ষরের হতে হবে।',
                'message.max' => 'বার্তাটি ৫০০০ অক্ষরের বেশি হতে পারবে না।',
            ]);

            if ($validator->fails()) {
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'errors' => $validator->errors(),
                        'message' => 'দয়া করে সব তথ্য সঠিকভাবে পূরণ করুন।'
                    ], 422);
                }
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

            // Send database notification to admins
            try {
                \App\Models\Notification::sendToAdmins(
                    'নতুন যোগাযোগের বার্তা',
                    $contact->name . ' একটি যোগাযোগের বার্তা পাঠিয়েছেন: ' . \Illuminate\Support\Str::limit($contact->subject, 50),
                    'system',
                    route('admin.contacts.show', $contact->id)
                );
            } catch (\Exception $e) {
                Log::error('Contact notification error: ' . $e->getMessage());
            }

            // Send email to admin
            try {
                Mail::to(\App\Helpers\SettingsHelper::getSetting('company_email', config('mail.from.address', 'askhezbuttawheed@gmail.com')))
                    ->send(new ContactMail($contact));
            } catch (\Exception $e) {
                Log::error('Contact mail send error: ' . $e->getMessage());
                // Continue even if mail fails
            }

            // ✅ সফল মেসেজ
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'আপনার বার্তা সফলভাবে পাঠানো হয়েছে! আমরা শীঘ্রই আপনার সাথে যোগাযোগ করব।'
                ]);
            }
            return back()->with('success', 'আপনার বার্তা সফলভাবে পাঠানো হয়েছে! আমরা শীঘ্রই আপনার সাথে যোগাযোগ করব।');

        } catch (\Exception $e) {
            Log::error('Contact form error: ' . $e->getMessage());
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'বার্তা পাঠাতে সমস্যা হয়েছে! দয়া করে পুনরায় চেষ্টা করুন।'
                ], 500);
            }
            return back()
                ->withInput()
                ->with('error', 'বার্তা পাঠাতে সমস্যা হয়েছে! দয়া করে পুনরায় চেষ্টা করুন।');
        }
    }
}
