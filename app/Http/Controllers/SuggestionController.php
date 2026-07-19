<?php

namespace App\Http\Controllers;

use App\Models\Suggestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SuggestionController extends Controller
{
    /**
     * Store a newly created suggestion in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:100',
                'contact' => 'required|string|max:100',
                'subject' => 'nullable|string|max:150',
                'message' => 'required|string|min:5|max:5000',
            ]);

            $suggestion = Suggestion::create([
                'name' => strip_tags($request->name),
                'contact' => strip_tags($request->contact),
                'subject' => $request->subject ? strip_tags($request->subject) : 'মতামত/পরামর্শ',
                'message' => strip_tags($request->message),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'status' => 'pending',
            ]);

            // Send database notification to admins
            try {
                \App\Models\Notification::sendToAdmins(
                    'নতুন পরামর্শ/মতামত',
                    $request->name . ' একটি নতুন পরামর্শ/মতামত পাঠিয়েছেন: ' . \Illuminate\Support\Str::limit($request->message, 50),
                    'system',
                    route('admin.suggestions.show', $suggestion->id)
                );
            } catch (\Exception $e) {
                Log::error('Suggestion notification error: ' . $e->getMessage());
            }

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'আপনার মূল্যবান পরামর্শ ও মতামত পাঠানোর জন্য ধন্যবাদ! এটি সফলভাবে আমাদের কেন্দ্রীয় তথ্যভাণ্ডারে সংরক্ষিত হয়েছে।'
                ]);
            }

            return back()->with('success', 'আপনার মূল্যবান পরামর্শ ও মতামত পাঠানোর জন্য ধন্যবাদ! এটি সফলভাবে আমাদের কেন্দ্রীয় তথ্যভাণ্ডারে সংরক্ষিত হয়েছে।');

        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'দয়া করে ফর্মের প্রতিটি তথ্য সঠিক নিয়মে পূরণ করুন।',
                    'errors' => $e->errors()
                ], 422);
            }
            return back()->withInput()->withErrors($e->errors())->with('error', 'দয়া করে ফর্মের প্রতিটি তথ্য সঠিক নিয়মে পূরণ করুন।');
        } catch (\Exception $e) {
            Log::error('Suggestion submit error: ' . $e->getMessage());
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'পরামর্শ পাঠাতে কারিগরি ত্রুটি হয়েছে! দয়া করে কিছুক্ষণ পর আবার চেষ্টা করুন।'
                ], 500);
            }
            return back()->withInput()->with('error', 'পরামর্শ পাঠাতে কারিগরি ত্রুটি হয়েছে! দয়া করে কিছুক্ষণ পর আবার চেষ্টা করুন।');
        }
    }
}
