<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Display notifications list page
     */
    public function index(Request $request)
    {
        $query = Notification::where('user_id', auth()->id());

        // Filter by type
        if ($request->type && $request->type != 'all') {
            $query->where('type', $request->type);
        }

        // Filter by read status
        if ($request->status == 'read') {
            $query->where('is_read', true);
        } elseif ($request->status == 'unread') {
            $query->where('is_read', false);
        }

        // Search
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                    ->orWhere('message', 'like', '%' . $request->search . '%');
            });
        }

        $notifications = $query->latest()->paginate(20);

        // Get counts for filter badges (সব নটিফিকেশন থেকে - user_id সহ)
        $counts = [
            'all'      => Notification::where('user_id', auth()->id())->count(),
            'unread'   => Notification::where('user_id', auth()->id())->where('is_read', false)->count(),
            'order'    => Notification::where('user_id', auth()->id())->where('type', 'order')->count(),
            'stock'    => Notification::where('user_id', auth()->id())->where('type', 'stock')->count(),
            'customer' => Notification::where('user_id', auth()->id())->where('type', 'customer')->count(),
            'system'   => Notification::where('user_id', auth()->id())->where('type', 'system')->count(),
        ];

        return view('admin.notifications.index', compact('notifications', 'counts'));
    }

    /**
     * Fetch notifications for AJAX polling
     */
    public function fetch(Request $request)
    {
        // শুধু বর্তমান ইউজারের আনরিড নটিফিকেশন আনবে
        $notifications = Notification::where('user_id', auth()->id())
            ->where('is_read', false)
            ->latest()
            ->limit(10)
            ->get()
            ->map(function ($notification) {
                return [
                    'id'         => $notification->id,
                    'type'       => $notification->type,
                    'title'      => $notification->title,
                    'message'    => $notification->message,
                    'icon'       => $notification->icon,
                    'color'      => $notification->color,
                    'link'       => $notification->link,
                    'is_read'    => $notification->is_read,
                    'time_ago'   => $notification->time_ago,
                    'created_at' => $notification->created_at->toISOString()
                ];
            });

        $unreadCount = Notification::where('user_id', auth()->id())->where('is_read', false)->count();

        $lastNotification = Notification::where('user_id', auth()->id())->where('is_read', false)->latest()->first();

        return response()->json([
            'success' => true,
            'notifications' => $notifications,
            'unread_count' => $unreadCount,
            'last_notification_time' => $lastNotification ? $lastNotification->created_at->toISOString() : null
        ]);
    }
    /**
     * Get unread count only (lightweight endpoint)
     */
    public function getUnreadCount()
    {
        $count = Notification::where('user_id', auth()->id())->where('is_read', false)->count();

        return response()->json([
            'success' => true,
            'unread_count' => $count
        ]);
    }

    /**
     * Mark a single notification as read
     */
    public function markAsRead($id)
    {
        try {
            $notification = Notification::where('user_id', auth()->id())->findOrFail($id);
            $notification->markAsRead();

            return response()->json([
                'success' => true,
                'message' => 'Notification marked as read'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Notification not found'
            ], 404);
        }
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead()
    {
        Notification::where('user_id', auth()->id())
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now()
            ]);

        return response()->json([
            'success' => true,
            'message' => 'All notifications marked as read'
        ]);
    }

    /**
     * Delete a notification
     */
    public function destroy($id)
    {
        try {
            $notification = Notification::where('user_id', auth()->id())->findOrFail($id);
            $notification->delete();

            return response()->json([
                'success' => true,
                'message' => 'Notification deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Notification not found'
            ], 404);
        }
    }

    /**
     * Delete all read notifications
     */
    public function deleteAllRead()
    {
        $count = Notification::where('user_id', auth()->id())->where('is_read', true)->delete();

        return response()->json([
            'success' => true,
            'message' => $count . ' read notifications deleted',
            'deleted_count' => $count
        ]);
    }

    /**
     * Get notification settings (সরল ভার্সন - user_id ছাড়া)
     */
    public function getSettings()
    {
        return response()->json([
            'success' => true,
            'settings' => [
                'sound_enabled'     => true,
                'desktop_enabled'   => false,
                'email_digest'      => false,
                'quiet_hours_start' => null,
                'quiet_hours_end'   => null
            ]
        ]);
    }

    /**
     * Update notification settings
     */
    public function updateSettings(Request $request)
    {
        // Settings সংরক্ষণের জন্য (অপশনাল - চাইলে ব্যবহার করুন)
        $validated = $request->validate([
            'sound_enabled'     => 'boolean',
            'desktop_enabled'   => 'boolean',
            'email_digest'      => 'boolean',
            'quiet_hours_start' => 'nullable|string',
            'quiet_hours_end'   => 'nullable|string'
        ]);

        // আপনি চাইলে এখানে সেটিংস ডাটাবেসে সংরক্ষণ করতে পারেন
        // অথবা শুধু success রিটার্ন করুন

        return response()->json([
            'success' => true,
            'message' => 'Settings updated successfully',
            'settings' => $validated
        ]);
    }
}
