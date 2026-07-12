<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Suggestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class SuggestionController extends Controller
{
    /**
     * Display a listing of suggestions.
     */
    public function index(Request $request)
    {
        $query = Suggestion::query();

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('contact', 'like', "%{$search}%")
                    ->orWhere('subject', 'like', "%{$search}%")
                    ->orWhere('message', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Sorting
        $sortField = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');
        $allowedSortFields = ['id', 'name', 'contact', 'subject', 'status', 'created_at'];

        if (in_array($sortField, $allowedSortFields)) {
            $query->orderBy($sortField, $sortDirection);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $perPage = $request->get('per_page', 20);
        $suggestions = $query->paginate($perPage);

        // Statistics
        $stats = [
            'total' => Suggestion::count(),
            'pending' => Suggestion::where('status', 'pending')->count(),
            'reviewed' => Suggestion::where('status', 'reviewed')->count(),
            'today' => Suggestion::whereDate('created_at', today())->count(),
        ];

        return view('admin.suggestions.index', compact('suggestions', 'stats'));
    }

    /**
     * Display the specified suggestion.
     */
    public function show($id)
    {
        $suggestion = Suggestion::findOrFail($id);

        // Mark as reviewed if pending
        if ($suggestion->status == 'pending') {
            $suggestion->update(['status' => 'reviewed']);
        }

        return view('admin.suggestions.show', compact('suggestion'));
    }

    /**
     * Toggle suggestion status.
     */
    public function toggleStatus($id)
    {
        try {
            $suggestion = Suggestion::findOrFail($id);
            $newStatus = $suggestion->status == 'pending' ? 'reviewed' : 'pending';
            $suggestion->update(['status' => $newStatus]);

            return response()->json([
                'success' => true,
                'message' => 'পরামর্শের স্ট্যাটাস সফলভাবে পরিবর্তন করা হয়েছে!',
                'status' => $newStatus
            ]);
        } catch (\Exception $e) {
            Log::error('Suggestion toggle status failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'স্ট্যাটাস পরিবর্তন করতে ব্যর্থ হয়েছে!'
            ], 500);
        }
    }

    /**
     * Delete the specified suggestion.
     */
    public function destroy($id)
    {
        try {
            $suggestion = Suggestion::findOrFail($id);
            $suggestion->delete();

            return response()->json([
                'success' => true,
                'message' => 'পরামর্শ বার্তা সফলভাবে মুছে ফেলা হয়েছে!'
            ]);

        } catch (\Exception $e) {
            Log::error('Suggestion deletion failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'পরামর্শ বার্তা মুছে ফেলতে ব্যর্থ হয়েছে!'
            ], 500);
        }
    }

    /**
     * Bulk delete suggestions.
     */
    public function bulkDelete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ids' => 'required|array',
            'ids.*' => 'required|integer|exists:suggestions,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        try {
            Suggestion::whereIn('id', $request->ids)->delete();

            return response()->json([
                'success' => true,
                'message' => count($request->ids) . ' টি পরামর্শ সফলভাবে মুছে ফেলা হয়েছে!'
            ]);

        } catch (\Exception $e) {
            Log::error('Suggestion Bulk delete failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'বাল্ক ডিলিট করতে ব্যর্থ হয়েছে!'
            ], 500);
        }
    }
}
