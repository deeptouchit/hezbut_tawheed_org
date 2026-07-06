<?php

namespace App\Http\Controllers;

use App\Models\NewsletterSubscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class NewsletterController extends Controller
{
    /**
     * নিউজলেটার সাবস্ক্রাইব
     */
    public function subscribe(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:newsletter_subscribers,email',
            'name' => 'nullable|string|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        $subscriber = NewsletterSubscriber::create([
            'email' => $request->email,
            'name' => $request->name,
            'ip_address' => $request->ip(),
            'subscribed_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'সাবস্ক্রিপশন সফল! ধন্যবাদ।'
        ]);
    }
}
