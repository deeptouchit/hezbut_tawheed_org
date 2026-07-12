<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;


class ProfileController extends Controller
{
    /**
     * Display user profile
     */
    public function index()
    {
        $user = auth()->user();
        return view('admin.profile.index', compact('user'));
    }

    /**
     * Show edit profile form
     */
    public function edit()
    {
        $user = auth()->user();
        return view('admin.profile.edit', compact('user'));
    }

    /**
     * Update user profile
     */
    public function update(Request $request)
    {
        $user = auth()->user();

        $validator = Validator::make($request->all(), [
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|unique:users,email,' . $user->id,
            'phone'   => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
        ]);



        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $oldData = $user->toArray();

            $user->update([
                'name'    => $request->name,
                'email'   => $request->email,
                'phone'   => $request->phone,
                'address' => $request->address,
            ]);

            ActivityLog::log('update', 'profile', 'Profile updated successfully', $oldData, $user->toArray());

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Profile updated successfully'
                ]);
            }

            return redirect()->route('admin.profile.index')->with([
                'message' => 'Profile updated successfully',
                'alert-type' => 'success'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update profile: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Upload avatar (using image column from users table)
     */
    public function uploadAvatar(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid image file. Max 2MB, allowed: jpeg, png, jpg, gif'
            ], 422);
        }

        try {
            $user = auth()->user();
            $file = $request->file('avatar');

            // Set destination path
            $destinationPath = public_path('uploads/avatars');

            // Create directory if not exists
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }

            // Delete old avatar if exists (using image column)
            if ($user->image && file_exists(public_path($user->image))) {
                @unlink(public_path($user->image));
            }

            // Generate unique filename
            $fileName = 'avatar_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();

            // Move file to destination
            $file->move($destinationPath, $fileName);

            // Save file path to database (image column)
            $filePath = 'uploads/avatars/' . $fileName;
            $user->image = $filePath;
            $user->save();

            // Log activity
            ActivityLog::log('update', 'profile', 'Avatar updated successfully');

            return response()->json([
                'success' => true,
                'message' => 'Avatar updated successfully',
                'avatar_url' => asset($filePath)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to upload avatar: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show change password form
     */
    public function changePassword()
    {
        return view('admin.profile.change-password');
    }

    /**
     * Send OTP for password change
     */
    public function sendOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $user = auth()->user();

        // Verify current password
        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Current password is incorrect'
            ], 422);
        }

        // Generate OTP
        $otp = rand(100000, 999999);

        // Store OTP in cache (expires in 10 minutes)
        Cache::put('password_otp_' . $user->id, $otp, 600);
        Cache::put('password_otp_expires_' . $user->id, now()->addMinutes(10), 600);

        // Send OTP via email
        try {
            Mail::send('emails.password-otp', [
                'name'       => $user->name,
                'otp'        => $otp,
                'expires_in' => 10
            ], function ($message) use ($user) {
                $message->to($user->email)
                        ->subject('Password Change OTP - ' . config('app.name'));
            });

            ActivityLog::log('send_otp', 'profile', 'Password change OTP sent');

            return response()->json([
                'success' => true,
                'message' => 'OTP sent to your email address. Valid for 10 minutes.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send OTP: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Verify OTP and change password
     */
    public function verifyAndChangePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'otp'          => 'required|string|size:6',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $user = auth()->user();
        $cachedOtp = Cache::get('password_otp_' . $user->id);
        $expiresAt = Cache::get('password_otp_expires_' . $user->id);

        if (!$cachedOtp) {
            return response()->json([
                'success' => false,
                'message' => 'OTP expired or not found. Please request a new OTP.'
            ], 422);
        }

        if ($expiresAt && now()->gt($expiresAt)) {
            Cache::forget('password_otp_' . $user->id);
            Cache::forget('password_otp_expires_' . $user->id);
            return response()->json([
                'success' => false,
                'message' => 'OTP has expired. Please request a new OTP.'
            ], 422);
        }

        if ($request->otp != $cachedOtp) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid OTP. Please try again.'
            ], 422);
        }

        try {
            // Update password
            $user->update([
                'password' => Hash::make($request->new_password),
                'password_changed_at' => now(),
            ]);

            // Clear OTP from cache
            Cache::forget('password_otp_' . $user->id);
            Cache::forget('password_otp_expires_' . $user->id);

            ActivityLog::log('change_password', 'profile', 'Password changed successfully');

            return response()->json([
                'success' => true,
                'message' => 'Password changed successfully. Please login again.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to change password: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get user statistics (AJAX)
     */
    public function getStats()
    {
        $user = auth()->user();

        $stats = [
            'total_activities'     => ActivityLog::where('user_id', $user->id)->count(),
            'today_activities'     => ActivityLog::where('user_id', $user->id)->whereDate('created_at', today())->count(),
            'last_week_activities' => ActivityLog::where('user_id', $user->id)->whereBetween('created_at', [now()->subDays(7), now()])->count(),
            'total_logins'         => ActivityLog::where('user_id', $user->id)->where('action', 'login')->count(),
        ];

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }

    /**
     * Get recent activities (AJAX)
     */
    public function getActivities()
    {
        $user = auth()->user();

        $activities = ActivityLog::where('user_id', $user->id)
            ->latest()
            ->limit(20)
            ->get()
            ->map(function($activity) {
                return [
                    'id'          => $activity->id,
                    'action'      => $activity->action,
                    'description' => $activity->description,
                    'ip_address'  => $activity->ip_address,
                    'created_at'  => $activity->created_at->toDateTimeString(),
                    'time_ago'    => $activity->created_at->diffForHumans(),
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $activities
        ]);
    }

    /**
     * Get active sessions (AJAX) - using last_login_user_agent from users table
     */
    public function getSessions()
    {
        $user = auth()->user();

        $sessions = [];

        // Current session from request
        $sessions[] = [
            'id'                => session()->getId(),
            'browser'           => $this->getBrowser($user->last_login_user_agent ?? request()->userAgent()),
            'platform'          => $this->getPlatform($user->last_login_user_agent ?? request()->userAgent()),
            'device_type'       => $this->getDeviceType($user->last_login_user_agent ?? request()->userAgent()),
            'ip_address'        => $user->last_login_ip ?? request()->ip(),
            'last_login_at'     => $user->last_login_at ? $user->last_login_at->diffForHumans() : 'Just now',
            'last_activity_ago' => $user->last_login_at ? $user->last_login_at->diffForHumans() : 'Just now',
            'is_current'        => true,
        ];

        return response()->json([
            'success' => true,
            'data' => $sessions
        ]);
    }

    /**
     * Logout from all devices
     */
    public function logoutAllDevices()
    {
        try {
            auth()->logout();

            ActivityLog::log('logout_all', 'profile', 'Logged out from all devices');

            return response()->json([
                'success' => true,
                'message' => 'Logged out from all devices successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to logout from all devices'
            ], 500);
        }
    }

    /**
     * Logout from specific session
     */
    public function logoutSession(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'session_id' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid session ID'
            ], 422);
        }

        try {
            ActivityLog::log('logout_session', 'profile', 'Logged out from specific session');

            return response()->json([
                'success' => true,
                'message' => 'Session revoked successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to revoke session'
            ], 500);
        }
    }

    /**
     * Update email notification settings
     */
    public function updateNotifications(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'enabled' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid request'
            ], 422);
        }

        try {
            $user = auth()->user();
            $user->email_notifications = $request->enabled;
            $user->save();

            ActivityLog::log('update', 'profile', 'Email notification settings updated');

            return response()->json([
                'success' => true,
                'message' => $request->enabled ? 'Email notifications enabled' : 'Email notifications disabled'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update notification settings'
            ], 500);
        }
    }

    /**
     * Download user data
     */
    public function downloadData()
    {
        $user = auth()->user();

        $data = [
            'user' => [
                'name'       => $user->name,
                'email'      => $user->email,
                'phone'      => $user->phone,
                'address'    => $user->address,
                'image'      => $user->image,
                'role'       => $user->role,
                'status'     => $user->status,
                'created_at' => $user->created_at->toDateTimeString(),
                'updated_at' => $user->updated_at->toDateTimeString(),
                'last_login_at' => $user->last_login_at ? $user->last_login_at->toDateTimeString() : null,
                'last_login_ip' => $user->last_login_ip,
            ],
            'activities' => ActivityLog::where('user_id', $user->id)
                ->latest()
                ->limit(100)
                ->get()
                ->map(function($activity) {
                    return [
                        'action'      => $activity->action,
                        'description' => $activity->description,
                        'ip_address'  => $activity->ip_address,
                        'created_at'  => $activity->created_at->toDateTimeString(),
                    ];
                }),
            'exported_at' => now()->toDateTimeString(),
        ];

        $json = json_encode($data, JSON_PRETTY_PRINT);
        $filename = 'user_data_' . $user->id . '_' . date('Y-m-d_H-i-s') . '.json';

        ActivityLog::log('export', 'profile', 'Profile data exported');

        return response($json, 200, [
            'Content-Type' => 'application/json',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    /**
     * Get browser name from user agent
     */
    private function getBrowser($userAgent)
    {
        if (strpos($userAgent, 'Chrome') !== false) return 'Chrome';
        if (strpos($userAgent, 'Firefox') !== false) return 'Firefox';
        if (strpos($userAgent, 'Safari') !== false) return 'Safari';
        if (strpos($userAgent, 'Edge') !== false) return 'Edge';
        if (strpos($userAgent, 'Opera') !== false) return 'Opera';
        return 'Unknown';
    }

    /**
     * Get platform from user agent
     */
    private function getPlatform($userAgent)
    {
        if (strpos($userAgent, 'Windows') !== false) return 'Windows';
        if (strpos($userAgent, 'Mac') !== false) return 'macOS';
        if (strpos($userAgent, 'Linux') !== false) return 'Linux';
        if (strpos($userAgent, 'Android') !== false) return 'Android';
        if (strpos($userAgent, 'iOS') !== false) return 'iOS';
        return 'Unknown';
    }

    /**
     * Get device type from user agent
     */
    private function getDeviceType($userAgent)
    {
        if (strpos($userAgent, 'Mobile') !== false) return 'mobile-alt';
        if (strpos($userAgent, 'Tablet') !== false) return 'tablet-alt';
        return 'desktop';
    }
}
