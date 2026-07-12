<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactReplyMail;

class ContactMessageController extends Controller
{
    /**
     * Display a listing of contact messages.
     */
    public function index(Request $request)
    {
        $query = ContactMessage::with('replier');

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('subject', 'like', "%{$search}%")
                    ->orWhere('message', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($request->filled('status')) {
            if ($request->status == 'unread') {
                $query->unread();
            } elseif ($request->status == 'read') {
                $query->read();
            } elseif ($request->status == 'replied') {
                $query->replied();
            }
        }

        // Date range filter
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Sorting
        $sortField = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');
        $allowedSortFields = ['id', 'name', 'email', 'subject', 'status', 'created_at', 'updated_at'];

        if (in_array($sortField, $allowedSortFields)) {
            $query->orderBy($sortField, $sortDirection);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $perPage = $request->get('per_page', 20);

        // AJAX Request
        if ($request->ajax()) {
            $messages = ($perPage == '-1') ? $query->get() : $query->paginate((int)$perPage);
            $html = view('admin.contacts.partials.table', compact('messages'))->render();
            return response()->json([
                'success' => true,
                'html' => $html,
                'total' => $messages->total() ?? $messages->count(),
                'pagination' => [
                    'total' => $messages->total() ?? $messages->count(),
                    'current_page' => $messages->currentPage() ?? 1,
                    'last_page' => $messages->lastPage() ?? 1,
                ]
            ]);
        }

        $messages = $query->paginate($perPage);

        // Statistics
        $stats = [
            'total' => ContactMessage::count(),
            'unread' => ContactMessage::unread()->count(),
            'read' => ContactMessage::read()->count(),
            'replied' => ContactMessage::replied()->count(),
            'today' => ContactMessage::whereDate('created_at', today())->count(),
            'this_week' => ContactMessage::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'this_month' => ContactMessage::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
        ];

        return view('admin.contacts.index', compact('messages', 'stats'));
    }

    /**
     * Display the specified contact message.
     */
    public function show($id)
    {
        $message = ContactMessage::with('replier')->findOrFail($id);

        // Mark as read if unread
        if ($message->status == 'unread') {
            $message->markAsRead();
        }

        if (request()->ajax()) {
            $html = view('admin.contacts.partials.detail', compact('message'))->render();
            return response()->json([
                'success' => true,
                'html' => $html
            ]);
        }

        return view('admin.contacts.show', compact('message'));
    }

    /**
     * Reply to a contact message.
     */
    public function reply(Request $request, $id)
    {
        $message = ContactMessage::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'reply_message' => 'required|string|min:10',
            'send_email' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // Mark as replied
            $message->markAsReplied($request->reply_message, auth()->id());

            // Send email reply if enabled
            if ($request->has('send_email') && $request->send_email == 1) {
                Mail::to($message->email)->send(new ContactReplyMail($message, $request->reply_message));
            }

            return redirect()->route('admin.contacts.index')
                ->with('success', 'রিপ্লাই সফলভাবে পাঠানো হয়েছে!');

        } catch (\Exception $e) {
            Log::error('Contact reply failed: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'রিপ্লাই পাঠাতে ব্যর্থ হয়েছে: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Delete the specified contact message.
     */
    public function destroy($id)
    {
        try {
            $message = ContactMessage::findOrFail($id);
            $message->delete();

            return response()->json([
                'success' => true,
                'message' => 'যোগাযোগ বার্তা সফলভাবে মুছে ফেলা হয়েছে!'
            ]);

        } catch (\Exception $e) {
            Log::error('Contact message deletion failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'যোগাযোগ বার্তা মুছে ফেলতে ব্যর্থ হয়েছে!'
            ], 500);
        }
    }

    /**
     * Bulk delete contact messages.
     */
    public function bulkDelete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ids' => 'required|array',
            'ids.*' => 'required|integer|exists:contact_messages,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        try {
            ContactMessage::whereIn('id', $request->ids)->delete();

            return response()->json([
                'success' => true,
                'message' => count($request->ids) . ' টি যোগাযোগ বার্তা সফলভাবে মুছে ফেলা হয়েছে!'
            ]);

        } catch (\Exception $e) {
            Log::error('Bulk delete failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'বাল্ক ডিলিট করতে ব্যর্থ হয়েছে!'
            ], 500);
        }
    }

    /**
     * Mark messages as read.
     */
    public function markAsRead(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ids' => 'required|array',
            'ids.*' => 'required|integer|exists:contact_messages,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        try {
            ContactMessage::whereIn('id', $request->ids)
                ->where('status', 'unread')
                ->update(['status' => 'read']);

            return response()->json([
                'success' => true,
                'message' => count($request->ids) . ' টি বার্তা পড়া হয়েছে!'
            ]);

        } catch (\Exception $e) {
            Log::error('Mark as read failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'বার্তা পড়া করতে ব্যর্থ হয়েছে!'
            ], 500);
        }
    }

    /**
     * Mark messages as unread.
     */
    public function markAsUnread(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ids' => 'required|array',
            'ids.*' => 'required|integer|exists:contact_messages,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        try {
            ContactMessage::whereIn('id', $request->ids)->update(['status' => 'unread']);

            return response()->json([
                'success' => true,
                'message' => count($request->ids) . ' টি বার্তা অপঠিত করা হয়েছে!'
            ]);

        } catch (\Exception $e) {
            Log::error('Mark as unread failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'বার্তা অপঠিত করতে ব্যর্থ হয়েছে!'
            ], 500);
        }
    }

    /**
     * Export contact messages.
     */
    public function export(Request $request)
    {
        $query = ContactMessage::with('replier');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('subject', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            if ($request->status == 'unread') {
                $query->unread();
            } elseif ($request->status == 'read') {
                $query->read();
            } elseif ($request->status == 'replied') {
                $query->replied();
            }
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $messages = $query->orderBy('created_at', 'desc')->get();

        $filename = 'contacts_' . date('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($messages) {
            $file = fopen('php://output', 'w');

            // CSV Header
            fputcsv($file, [
                'ID',
                'নাম',
                'ইমেইল',
                'ফোন',
                'বিষয়',
                'বার্তা',
                'স্ট্যাটাস',
                'রিপ্লাই',
                'তারিখ'
            ]);

            foreach ($messages as $message) {
                fputcsv($file, [
                    $message->id,
                    $message->name,
                    $message->email,
                    $message->phone ?? 'N/A',
                    $message->subject ?? 'N/A',
                    strip_tags($message->message),
                    $message->status == 'unread' ? 'অপঠিত' : ($message->status == 'read' ? 'পঠিত' : 'উত্তর দেওয়া'),
                    $message->reply_message ? strip_tags($message->reply_message) : 'N/A',
                    $message->created_at?->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Get statistics (AJAX).
     */
    public function getStatistics()
    {
        $stats = [
            'total' => ContactMessage::count(),
            'unread' => ContactMessage::unread()->count(),
            'read' => ContactMessage::read()->count(),
            'replied' => ContactMessage::replied()->count(),
            'today' => ContactMessage::whereDate('created_at', today())->count(),
            'this_week' => ContactMessage::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'this_month' => ContactMessage::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
        ];

        // Get last 7 days data for chart
        $last7Days = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $count = ContactMessage::whereDate('created_at', $date)->count();
            $last7Days[] = [
                'date' => $date->format('Y-m-d'),
                'label' => $date->format('d M'),
                'count' => $count
            ];
        }

        return response()->json([
            'success' => true,
            'stats' => $stats,
            'chart_data' => $last7Days
        ]);
    }

    /**
     * Fetch unread messages for admin header dropdown.
     */
    public function fetchUnread()
    {
        $messages = ContactMessage::unread()
            ->latest()
            ->limit(5)
            ->get()
            ->map(function ($message) {
                return [
                    'id'         => $message->id,
                    'name'       => $message->name,
                    'subject'    => $message->subject ?? 'No Subject',
                    'message'    => \Illuminate\Support\Str::limit(strip_tags($message->message), 50),
                    'time_ago'   => $message->created_at?->diffForHumans() ?? '',
                    'created_at' => $message->created_at?->toISOString()
                ];
            });

        $unreadCount = ContactMessage::unread()->count();

        return response()->json([
            'success'      => true,
            'messages'     => $messages,
            'unread_count' => $unreadCount
        ]);
    }
}
