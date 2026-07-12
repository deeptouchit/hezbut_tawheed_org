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
use Illuminate\Http\Request;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        $monthStart = Carbon::now()->startOfMonth();

        // Statistics
        $stats = [
            'total_posts'       => Blog::count(),
            'total_categories'  => BlogCategory::count(),
            'total_comments'    => BlogComment::count(),
            'total_subscribers' => NewsletterSubscriber::count(),
            'total_messages'    => ContactMessage::count(),
            'total_users'       => User::count(),
            'pending_comments'  => BlogComment::where('is_approved', false)->count(),
            'approved_comments' => BlogComment::where('is_approved', true)->count(),
            'unread_messages'   => ContactMessage::where('status', 'unread')->count(),
            'total_visitors'    => ActivityLog::where('action', 'login')->count(),
            'today_visitors'    => ActivityLog::where('action', 'login')->whereDate('created_at', $today)->count(),
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

        // Daily comments count for current week
        $weeklyComments = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $weeklyComments[] = [
                'day'   => $date->format('D'),
                'count' => BlogComment::whereDate('created_at', $date)->count(),
            ];
        }

        return view('admin.dashboard', compact(
            'stats',
            'recentPosts',
            'recentComments',
            'recentMessages',
            'monthlyPosts',
            'weeklyComments'
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
}
