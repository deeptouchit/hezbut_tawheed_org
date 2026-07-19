<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\BlogComment;
use App\Models\ContactMessage;
use App\Models\NewsletterSubscriber;
use App\Models\User;
use App\Models\ActivityLog;
use App\Models\JoinRequest;
use App\Models\Branch;
use App\Models\Leader;
use App\Models\Book;
use App\Models\Song;
use App\Models\Video;
use App\Models\Visitor;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        // Database status and info
        $dbStatus = 'Connected';
        $dbName = '';
        try {
            DB::connection()->getPdo();
            $dbName = DB::connection()->getDatabaseName();
        } catch (\Exception $e) {
            $dbStatus = 'Disconnected: ' . $e->getMessage();
        }

        $systemInfo = [
            'laravel_version' => app()->version(),
            'php_version'     => PHP_VERSION,
            'db_connection'   => config('database.default'),
            'db_name'         => $dbName,
            'db_status'       => $dbStatus,
            'environment'     => app()->environment(),
        ];

        // Statistics
        $stats = [
            'total_posts'           => Blog::count(),
            'total_categories'      => BlogCategory::count(),
            'total_comments'        => BlogComment::count(),
            'total_subscribers'     => NewsletterSubscriber::count(),
            'total_messages'        => ContactMessage::count(),
            'total_users'           => User::count(),
            'pending_comments'      => BlogComment::where('is_approved', false)->count(),
            'approved_comments'     => BlogComment::where('is_approved', true)->count(),
            'unread_messages'       => ContactMessage::where('status', 'unread')->count(),
            'total_visitors'        => Visitor::distinct('ip_address')->count('ip_address'),
            'today_visitors'        => Visitor::today()->distinct('ip_address')->count('ip_address'),
            'today_page_views'      => Visitor::today()->count(),
            // Added organization stats
            'total_join_requests'   => JoinRequest::count(),
            'unread_join_requests'  => JoinRequest::unread()->count(),
            'total_branches'        => Branch::count(),
            'total_leaders'         => Leader::count(),
            'total_books'           => Book::count(),
            'total_songs'           => Song::count(),
            'total_videos'          => Video::count(),
        ];

        // Recent Blog Posts
        $recentPosts = Blog::with('category')
            ->latest()
            ->limit(5)
            ->get()
            ->map(function($post) {
                return [
                    'id'         => $post->id,
                    'title'      => $post->title,
                    'category'   => $post->category?->name ?? 'Uncategorized',
                    'views'      => $post->views ?? 0,
                    'status'     => $post->status ?? 'draft',
                    'created_at' => $post->created_at->diffForHumans(),
                ];
            });

        // Recent Comments
        $recentComments = BlogComment::with('blog')
            ->latest()
            ->limit(5)
            ->get()
            ->map(function($comment) {
                return [
                    'id'         => $comment->id,
                    'author'     => $comment->commenter_name,
                    'comment'    => $comment->comment,
                    'post_title' => $comment->blog?->title ?? 'Deleted Post',
                    'status'     => $comment->is_approved ? 'approved' : 'pending',
                    'created_at' => $comment->created_at->diffForHumans(),
                ];
            });

        // Recent Messages
        $recentMessages = ContactMessage::latest()
            ->limit(5)
            ->get()
            ->map(function($msg) {
                return [
                    'id'         => $msg->id,
                    'name'       => $msg->name,
                    'email'      => $msg->email,
                    'subject'    => $msg->subject,
                    'is_read'    => $msg->status !== 'unread',
                    'created_at' => $msg->created_at->diffForHumans(),
                ];
            });

        // Recent Join Requests
        $recentJoinRequests = JoinRequest::latest()
            ->limit(5)
            ->get()
            ->map(function($req) {
                return [
                    'id'         => $req->id,
                    'name'       => $req->name,
                    'phone'      => $req->phone,
                    'status'     => $req->status,
                    'status_badge' => $req->status_badge,
                    'type_badge' => $req->type_badge,
                    'created_at' => $req->created_at->diffForHumans(),
                ];
            });

        // Recent Activity Logs
        $recentActivityLogs = ActivityLog::with('user')
            ->latest()
            ->limit(5)
            ->get();

        // Monthly Blog Posting Trends (last 12 months)
        $monthlyPosts = [];
        for ($i = 11; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $count = Blog::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->count();

            $monthlyPosts[] = [
                'month' => $month->format('M'),
                'count' => $count,
            ];
        }

        // Monthly Join Requests (last 12 months)
        $monthlyJoinRequests = [];
        for ($i = 11; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $count = JoinRequest::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->count();

            $monthlyJoinRequests[] = [
                'month' => $month->format('M'),
                'count' => $count,
            ];
        }

        // Daily comments count for current week
        $weeklyComments = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $weeklyComments[] = [
                'day'   => $date->format('D'),
                'count' => BlogComment::whereDate('created_at', $date)->count(),
            ];
        }

        // Top 5 Visited Pages
        $topVisitedPages = Visitor::select('url', DB::raw('count(*) as views_count'))
            ->groupBy('url')
            ->orderBy('views_count', 'desc')
            ->limit(5)
            ->get();

        // Device Breakdown
        $deviceBreakdown = Visitor::select('device', DB::raw('count(*) as count'))
            ->groupBy('device')
            ->get()
            ->pluck('count', 'device')
            ->toArray();

        // Monthly Unique Visitors (last 12 months)
        $monthlyUniqueVisitors = [];
        for ($i = 11; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $count = Visitor::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->distinct('ip_address')
                ->count('ip_address');

            $monthlyUniqueVisitors[] = [
                'month' => $month->format('M'),
                'count' => $count,
            ];
        }

        return view('admin.dashboard', compact(
            'stats',
            'recentPosts',
            'recentComments',
            'recentMessages',
            'recentJoinRequests',
            'recentActivityLogs',
            'monthlyPosts',
            'monthlyJoinRequests',
            'weeklyComments',
            'systemInfo',
            'topVisitedPages',
            'deviceBreakdown',
            'monthlyUniqueVisitors'
        ));
    }

    public function getStats(Request $request)
    {
        $period = $request->get('period', 'month'); // week, month, year

        switch ($period) {
            case 'week':
                $startDate = Carbon::now()->startOfWeek();
                $endDate = Carbon::now()->endOfWeek();
                break;
            case 'year':
                $startDate = Carbon::now()->startOfYear();
                $endDate = Carbon::now()->endOfYear();
                break;
            default:
                $startDate = Carbon::now()->startOfMonth();
                $endDate = Carbon::now()->endOfMonth();
        }

        $stats = [
            'total_posts'      => Blog::whereBetween('created_at', [$startDate, $endDate])->count(),
            'total_comments'   => BlogComment::whereBetween('created_at', [$startDate, $endDate])->count(),
            'total_subscribers' => NewsletterSubscriber::whereBetween('created_at', [$startDate, $endDate])->count(),
            'total_messages'   => ContactMessage::whereBetween('created_at', [$startDate, $endDate])->count(),
        ];

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }

    public function getChartData(Request $request)
    {
        $type = $request->get('type', 'posts'); // posts, comments
        $months = 12;

        $data = [];
        for ($i = $months - 1; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);

            if ($type === 'posts') {
                $value = Blog::whereYear('created_at', $month->year)
                    ->whereMonth('created_at', $month->month)
                    ->count();
            } else {
                $value = BlogComment::whereYear('created_at', $month->year)
                    ->whereMonth('created_at', $month->month)
                    ->count();
            }

            $data[] = [
                'month' => $month->format('M Y'),
                'value' => $value,
            ];
        }

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    public function visitors(Request $request)
    {
        $period = $request->get('period', 'all');
        $query = Visitor::query();
        $countryQuery = Visitor::select('country', DB::raw('count(distinct ip_address) as unique_visitors'))
            ->groupBy('country')
            ->orderBy('unique_visitors', 'desc');

        switch ($period) {
            case 'today':
                $query->whereDate('created_at', Carbon::today());
                $countryQuery->whereDate('created_at', Carbon::today());
                break;
            case 'week':
                $query->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                $countryQuery->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                break;
            case 'month':
                $query->whereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()]);
                $countryQuery->whereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()]);
                break;
            case 'year':
                $query->whereBetween('created_at', [Carbon::now()->startOfYear(), Carbon::now()->endOfYear()]);
                $countryQuery->whereBetween('created_at', [Carbon::now()->startOfYear(), Carbon::now()->endOfYear()]);
                break;
        }

        $visitors = $query->latest()->paginate(20)->withQueryString();
        $countrySummary = $countryQuery->get();

        return view('admin.visitors.index', compact('visitors', 'countrySummary', 'period'));
    }

    public function bulkDeleteVisitors(Request $request)
    {
        $ids = $request->input('ids', []);
        if (!empty($ids)) {
            Visitor::whereIn('id', $ids)->delete();
            return redirect()->back()->with('success', 'নির্বাচিত ভিজিটর লগ সফলভাবে মুছে ফেলা হয়েছে।');
        }
        return redirect()->back()->with('error', 'কোনো রেকর্ড নির্বাচন করা হয়নি।');
    }
}
