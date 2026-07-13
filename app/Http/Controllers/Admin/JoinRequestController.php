<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JoinRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class JoinRequestController extends Controller
{
    /**
     * Display a listing of join requests.
     */
    public function index(Request $request)
    {
        $query = JoinRequest::query();

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('father_husband', 'like', "%{$search}%")
                    ->orWhere('present_address', 'like', "%{$search}%")
                    ->orWhere('occupation', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Membership Type filter
        if ($request->filled('membership_type')) {
            $query->where('membership_type', $request->membership_type);
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
        $allowedSortFields = ['id', 'name', 'membership_type', 'status', 'created_at'];

        if (in_array($sortField, $allowedSortFields)) {
            $query->orderBy($sortField, $sortDirection);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $perPage = $request->get('per_page', 20);

        // AJAX Request
        if ($request->ajax()) {
            $requests = ($perPage == '-1') ? $query->get() : $query->paginate((int)$perPage);
            $html = view('admin.join_requests.partials.table', compact('requests'))->render();
            return response()->json([
                'success' => true,
                'html' => $html,
                'total' => $requests->total() ?? $requests->count(),
                'pagination' => [
                    'total' => $requests->total() ?? $requests->count(),
                    'current_page' => $requests->currentPage() ?? 1,
                    'last_page' => $requests->lastPage() ?? 1,
                ]
            ]);
        }

        $requests = $query->paginate($perPage);

        // Statistics
        $stats = [
            'total' => JoinRequest::count(),
            'unread' => JoinRequest::unread()->count(),
            'read' => JoinRequest::read()->count(),
            'approved' => JoinRequest::approved()->count(),
            'rejected' => JoinRequest::rejected()->count(),
            'primary' => JoinRequest::where('membership_type', 'primary')->count(),
            'pledge' => JoinRequest::where('membership_type', 'pledge')->count(),
        ];

        return view('admin.join_requests.index', compact('requests', 'stats'));
    }

    /**
     * Display the specified join request.
     */
    public function show($id)
    {
        $requestData = JoinRequest::findOrFail($id);

        // Mark as read if unread
        if ($requestData->status == 'unread') {
            $requestData->update(['status' => 'read']);
        }

        if (request()->ajax()) {
            $html = view('admin.join_requests.partials.detail', compact('requestData'))->render();
            return response()->json([
                'success' => true,
                'html' => $html
            ]);
        }

        return view('admin.join_requests.show', compact('requestData'));
    }

    /**
     * Update the status of the specified join request.
     */
    public function updateStatus(Request $request, $id)
    {
        $requestData = JoinRequest::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'status' => 'required|string|in:unread,read,approved,rejected',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        try {
            $requestData->update(['status' => $request->status]);

            return response()->json([
                'success' => true,
                'message' => 'আবেদনের স্ট্যাটাস সফলভাবে আপডেট করা হয়েছে!'
            ]);
        } catch (\Exception $e) {
            Log::error('JoinRequest status update failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'স্ট্যাটাস আপডেট করতে ব্যর্থ হয়েছে!'
            ], 500);
        }
    }

    /**
     * Delete the specified join request.
     */
    public function destroy($id)
    {
        try {
            $requestData = JoinRequest::findOrFail($id);
            $requestData->delete();

            return response()->json([
                'success' => true,
                'message' => 'সদস্য পদের আবেদন সফলভাবে মুছে ফেলা হয়েছে!'
            ]);
        } catch (\Exception $e) {
            Log::error('JoinRequest deletion failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'সদস্য পদের আবেদন মুছে ফেলতে ব্যর্থ হয়েছে!'
            ], 500);
        }
    }

    /**
     * Bulk delete join requests.
     */
    public function bulkDelete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ids' => 'required|array',
            'ids.*' => 'required|integer|exists:join_requests,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        try {
            JoinRequest::whereIn('id', $request->ids)->delete();

            return response()->json([
                'success' => true,
                'message' => count($request->ids) . ' টি আবেদন সফলভাবে মুছে ফেলা হয়েছে!'
            ]);
        } catch (\Exception $e) {
            Log::error('JoinRequest bulk delete failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'বাল্ক ডিলিট করতে ব্যর্থ হয়েছে!'
            ], 500);
        }
    }

    /**
     * Bulk status update for join requests.
     */
    public function bulkStatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ids' => 'required|array',
            'ids.*' => 'required|integer|exists:join_requests,id',
            'status' => 'required|string|in:unread,read,approved,rejected',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        try {
            JoinRequest::whereIn('id', $request->ids)->update(['status' => $request->status]);

            return response()->json([
                'success' => true,
                'message' => count($request->ids) . ' টি আবেদনের স্ট্যাটাস সফলভাবে আপডেট করা হয়েছে!'
            ]);
        } catch (\Exception $e) {
            Log::error('JoinRequest bulk status update failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'বাল্ক স্ট্যাটাস আপডেট করতে ব্যর্থ হয়েছে!'
            ], 500);
        }
    }
}
