<?php

namespace App\Http\Controllers;

use App\Models\JoinRequest;
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
                'age'               => 'nullable|string|max:50',
                'father_husband'    => 'nullable|string|max:100',
                'phone'             => 'required|string|max:25',
                'present_address'   => 'required|string|max:500',
                'permanent_address' => 'nullable|string|max:500',
                'occupation'        => 'nullable|string|max:100',
                'education'         => 'nullable|string|max:100',
                'experience'        => 'nullable|string|max:500',
                'how_knew'          => 'required|string|max:100',
                'person_name'       => 'nullable|string|max:100',
                'person_phone'      => 'nullable|string|max:25',
                'current_unit_amir' => 'nullable|string|max:200',
                'join_date'         => 'nullable|string|max:50',
            ]);

            $joinRequest = JoinRequest::create([
                'membership_type'   => $request->membership_type,
                'name'              => $request->name,
                'join_date'         => $request->join_date ?? ($request->membership_type === 'pledge' ? null : date('Y-m-d')),
                'father_husband'    => $request->father_husband,
                'age'               => $request->age ?? ($request->dob ? now()->diff(\Carbon\Carbon::parse($request->dob))->y . ' বছর' : null),
                'occupation'        => $request->occupation,
                'education'         => $request->education,
                'phone'             => $request->phone,
                'current_unit_amir' => $request->current_unit_amir,
                'present_address'   => $request->present_address,
                'permanent_address' => $request->permanent_address,
                'experience'        => $request->experience,
                'how_knew'          => $request->how_knew,
                'person_name'       => $request->person_name,
                'person_phone'      => $request->person_phone,
                'ip_address'        => $request->ip(),
                'user_agent'        => $request->userAgent(),
                'status'            => 'unread',
            ]);

            // Send database notification to admins
            try {
                \App\Models\Notification::sendToAdmins(
                    'নতুন যোগদানের আবেদন',
                    $request->name . ' একটি নতুন সদস্য পদের আবেদন জমা দিয়েছেন।',
                    'customer',
                    route('admin.join-requests.show', $joinRequest->id)
                );
            } catch (\Exception $e) {
                Log::error('Join request notification error: ' . $e->getMessage());
            }

            return back()->with('success', 'আপনার সদস্য পদের আবেদনটি সফলভাবে গৃহীত হয়েছে! আমরা খুব শীঘ্রই আপনার সাথে যোগাযোগ করব ইনশাআল্লাহ।');

        } catch (\Exception $e) {
            Log::error('Join submit error: ' . $e->getMessage());
            return back()
                ->withInput()
                ->with('error', 'আবেদনপত্র জমা দিতে সমস্যা হয়েছে! দয়া করে সব তথ্য সঠিকভাবে পূরণ করে পুনরায় চেষ্টা করুন।');
        }
    }
}
