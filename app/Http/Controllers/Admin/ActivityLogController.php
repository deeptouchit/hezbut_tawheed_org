<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ActivityLogController extends Controller
{

    /**
     * Display a listing of activity logs
     */
    public function index(Request $request)
    {
        // Get filter values with defaults
        $search = $request->get('search', '');
        $action = $request->get('action', '');
        $module = $request->get('module', '');
        $userId = $request->get('user_id', '');
        $dateFrom = $request->get('date_from', '');
        $dateTo = $request->get('date_to', '');
        $perPage = $request->get('per_page', 20);

        $query = ActivityLog::with('user')->latest();

        // Apply filters - FIXED
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhere('ip_address', 'like', "%{$search}%")
                  ->orWhere('url', 'like', "%{$search}%");
            });
        }

        if (!empty($action)) {
            $query->where('action', $action);
        }

        if (!empty($module)) {
            $query->where('module', $module);
        }

        if (!empty($userId) && $userId != '') {
            $query->where('user_id', $userId);
        }

        if (!empty($dateFrom)) {
            $query->whereDate('created_at', '>=', $dateFrom);
        }

        if (!empty($dateTo)) {
            $query->whereDate('created_at', '<=', $dateTo);
        }

        // FIXED: Per page handling
        if ($perPage == '-1' || $perPage == 'all') {
            $logs = $query->paginate(999999);
            $logs->appends($request->query());
        } else {
            $perPage = is_numeric($perPage) ? (int)$perPage : 20;
            $logs = $query->paginate($perPage);
            $logs->appends($request->query());
        }

        // Get statistics
        $stats = [
            'total' => ActivityLog::count(),
            'today' => ActivityLog::whereDate('created_at', today())->count(),
            'week' => ActivityLog::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'month' => ActivityLog::whereMonth('created_at', now()->month)->count(),
        ];

        // For AJAX request - FIXED
        if ($request->ajax() || $request->wantsJson()) {
            $html = view('admin.activity-logs.partials.table', compact('logs'))->render();
            return response()->json([
                'success' => true,
                'html' => $html,
                'stats' => $stats,
                'pagination' => [
                    'total' => $logs->total(),
                    'current_page' => $logs->currentPage(),
                    'last_page' => $logs->lastPage(),
                ]
            ]);
        }

        // Get filter options for dropdowns
        $actions = ActivityLog::select('action')->distinct()->whereNotNull('action')->pluck('action');
        $modules = ActivityLog::select('module')->distinct()->whereNotNull('module')->pluck('module');
        $users = User::select('id', 'name')->orderBy('name')->get();

        return view('admin.activity-logs.index', compact(
            'logs', 'actions', 'modules', 'users', 'stats',
            'search', 'action', 'module', 'userId', 'dateFrom', 'dateTo', 'perPage'
        ));
    }

    /**
     * Display the specified activity log
     */
    public function show($id)
    {
        $log = ActivityLog::with('user')->findOrFail($id);
        return view('admin.activity-logs.show', compact('log'));
    }

    /**
     * Remove the specified activity log
     */
    public function destroy($id): JsonResponse
    {
        try {
            $log = ActivityLog::findOrFail($id);
            $log->delete();

            return response()->json([
                'success' => true,
                'message' => 'Activity log deleted successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Activity log delete failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete activity log'
            ], 500);
        }
    }

    /**
     * Clear all activity logs
     */
    public function clearAll(): JsonResponse
    {
        try {
            $count = ActivityLog::count();
            ActivityLog::truncate();

            return response()->json([
                'success' => true,
                'message' => "Successfully cleared {$count} activity logs",
                'count' => $count
            ]);

        } catch (\Exception $e) {
            Log::error('Clear activity logs failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to clear activity logs'
            ], 500);
        }
    }

    /**
     * Export activity logs
     */
    public function export(Request $request)
    {
        $format = $request->get('format', 'csv');

        $query = ActivityLog::with('user')->latest();

        // Apply same filters
        if ($request->filled('search')) {
            $query->where('description', 'like', "%{$request->search}%");
        }
        if ($request->filled('action')) $query->where('action', $request->action);
        if ($request->filled('module')) $query->where('module', $request->module);
        if ($request->filled('user_id')) $query->where('user_id', $request->user_id);
        if ($request->filled('date_from')) $query->whereDate('created_at', '>=', $request->date_from);
        if ($request->filled('date_to')) $query->whereDate('created_at', '<=', $request->date_to);

        $logs = $query->get();

        if ($format === 'csv') {
            return $this->exportCsv($logs);
        }

        return $this->exportJson($logs);
    }

    /**
     * Export as CSV
     */
    private function exportCsv($logs)
    {
        $filename = 'activity_logs_' . date('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($logs) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'User', 'Action', 'Module', 'Description', 'IP Address', 'URL', 'Method', 'Created At']);

            foreach ($logs as $log) {
                fputcsv($file, [
                    $log->id,
                    $log->user?->name ?? 'System',
                    $log->action,
                    $log->module,
                    $log->description,
                    $log->ip_address,
                    $log->url,
                    $log->method,
                    $log->created_at
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export as JSON
     */
    private function exportJson($logs)
    {
        $data = $logs->map(function($log) {
            return [
                'id' => $log->id,
                'user' => $log->user?->name ?? 'System',
                'action' => $log->action,
                'module' => $log->module,
                'description' => $log->description,
                'ip_address' => $log->ip_address,
                'url' => $log->url,
                'method' => $log->method,
                'user_agent' => $log->user_agent,
                'old_data' => $log->old_data,
                'new_data' => $log->new_data,
                'created_at' => $log->created_at->toISOString(),
            ];
        });

        return response()->json($data, 200, [
            'Content-Disposition' => 'attachment; filename="activity_logs_' . date('Y-m-d_H-i-s') . '.json"'
        ]);
    }

    /**
     * Get statistics (AJAX)
     */
    public function statistics(): JsonResponse
    {
        // Get last 7 days stats
        $last7Days = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $last7Days[] = [
                'date' => $date->format('Y-m-d'),
                'count' => ActivityLog::whereDate('created_at', $date)->count(),
            ];
        }

        $stats = [
            'total' => ActivityLog::count(),
            'today' => ActivityLog::whereDate('created_at', today())->count(),
            'week' => ActivityLog::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'month' => ActivityLog::whereMonth('created_at', now()->month)->count(),
            'year' => ActivityLog::whereYear('created_at', now()->year)->count(),
            'last_7_days' => $last7Days,
            'by_action' => ActivityLog::select('action', DB::raw('count(*) as total'))->whereNotNull('action')->groupBy('action')->get(),
            'by_module' => ActivityLog::select('module', DB::raw('count(*) as total'))->whereNotNull('module')->groupBy('module')->get(),
        ];

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }
}
