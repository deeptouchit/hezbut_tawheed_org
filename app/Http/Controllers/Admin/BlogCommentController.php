<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\BlogComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class BlogCommentController extends Controller
{
      /**
     * Display a listing of comments.
     */
    public function index(Request $request)
    {
        $query = BlogComment::with(['blog', 'user', 'parent']);

          // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('comment', 'like', "%{$search}%");
            });
        }

          // Status filter
        if ($request->filled('status')) {
            if ($request->status == 'approved') {
                $query->approved();
            } elseif ($request->status == 'pending') {
                $query->pending();
            }
        }

          // Blog filter
        if ($request->filled('blog_id')) {
            $query->forBlog($request->blog_id);
        }

          // Type filter (parent or reply)
        if ($request->filled('type')) {
            if ($request->type == 'parent') {
                $query->parentOnly();
            } elseif ($request->type == 'reply') {
                $query->repliesOnly();
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
        $sortField         = $request->get('sort', 'created_at');
        $sortDirection     = $request->get('direction', 'desc');
        $allowedSortFields = ['id', 'name', 'email', 'is_approved', 'created_at', 'updated_at'];

        if (in_array($sortField, $allowedSortFields)) {
            $query->orderBy($sortField, $sortDirection);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $perPage = $request->get('per_page', 20);

          // AJAX Request
        if ($request->ajax()) {
            $comments = ($perPage == '-1') ? $query->get() : $query->paginate((int)$perPage);
            $html     = view('admin.blog.comments.partials.table', compact('comments'))->render();
            return response()->json([
                'success'    => true,
                'html'       => $html,
                'total'      => $comments->total() ?? $comments->count(),
                'pagination' => [
                    'total'        => $comments->total() ?? $comments->count(),
                    'current_page' => $comments->currentPage() ?? 1,
                    'last_page'    => $comments->lastPage() ?? 1,
                ]
            ]);
        }

        $comments = $query->paginate($perPage);

          // Statistics
        $stats = [
            'total'           => BlogComment::count(),
            'approved'        => BlogComment::approved()->count(),
            'pending'         => BlogComment::pending()->count(),
            'today'           => BlogComment::todayCount(),
            'this_week'       => BlogComment::thisWeek()->count(),
            'this_month'      => BlogComment::thisMonth()->count(),
            'parent_comments' => BlogComment::parentOnly()->count(),
            'replies'         => BlogComment::repliesOnly()->count(),
        ];

          // Get blogs for filter dropdown
        $blogs = Blog::published()->orderBy('title')->get(['id', 'title']);

        return view('admin.blog.comments.index', compact('comments', 'stats', 'blogs'));
    }

      /**
     * Display the specified comment with replies.
     */
    public function show($id)
    {
        $comment = BlogComment::with(['blog', 'user', 'parent', 'replies.user'])->findOrFail($id);

        if (request()->ajax()) {
            $html = view('admin.blog.comments.partials.detail', compact('comment'))->render();
            return response()->json([
                'success' => true,
                'html'    => $html
            ]);
        }

        return view('admin.blog.comments.show', compact('comment'));
    }

      /**
     * Approve a comment.
     */
    public function approve($id)
    {
        try {
            $comment = BlogComment::findOrFail($id);
            $comment->approve();

              // If this is a reply, also approve parent if needed
            if ($comment->parent_id) {
                $parent = $comment->parent;
                if ($parent && !$parent->is_approved) {
                    $parent->approve();
                }
            }

            return response()->json([
                'success'     => true,
                'message'     => 'মন্তব্য অ্যাপ্রুভ করা হয়েছে!',
                'is_approved' => true,
                'badge'       => '<span class="badge bg-success">অ্যাপ্রুভড</span>'
            ]);

        } catch (\Exception $e) {
            Log::error('Comment approval failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'মন্তব্য অ্যাপ্রুভ করতে ব্যর্থ হয়েছে!'
            ], 500);
        }
    }

      /**
     * Disapprove a comment.
     */
    public function disapprove($id)
    {
        try {
            $comment = BlogComment::findOrFail($id);
            $comment->disapprove();

            return response()->json([
                'success'     => true,
                'message'     => 'মন্তব্য ডিসঅ্যাপ্রুভ করা হয়েছে!',
                'is_approved' => false,
                'badge'       => '<span class="badge bg-warning">পেন্ডিং</span>'
            ]);

        } catch (\Exception $e) {
            Log::error('Comment disapproval failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'মন্তব্য ডিসঅ্যাপ্রুভ করতে ব্যর্থ হয়েছে!'
            ], 500);
        }
    }

      /**
     * Delete a comment.
     */
    public function destroy($id)
    {
        try {
            $comment = BlogComment::findOrFail($id);

              // If this is a parent comment, delete all replies
            if (!$comment->parent_id) {
                $comment->allReplies()->delete();
            }

            $comment->delete();

            return response()->json([
                'success' => true,
                'message' => 'মন্তব্য সফলভাবে মুছে ফেলা হয়েছে!'
            ]);

        } catch (\Exception $e) {
            Log::error('Comment deletion failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'মন্তব্য মুছে ফেলতে ব্যর্থ হয়েছে!'
            ], 500);
        }
    }

      /**
     * Bulk delete comments.
     */
    public function bulkDelete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ids'   => 'required|array',
            'ids.*' => 'required|integer|exists:blog_comments,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        try {
            DB::beginTransaction();

              // Get all comments including their replies
            $comments  = BlogComment::whereIn('id', $request->ids)->get();
            $parentIds = $comments->whereNull('parent_id')->pluck('id')->toArray();

              // Delete replies of parent comments
            if (!empty($parentIds)) {
                BlogComment::whereIn('parent_id', $parentIds)->delete();
            }

              // Delete the selected comments
            BlogComment::whereIn('id', $request->ids)->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => count($request->ids) . ' টি মন্তব্য সফলভাবে মুছে ফেলা হয়েছে!'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Bulk delete failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'বাল্ক ডিলিট করতে ব্যর্থ হয়েছে!'
            ], 500);
        }
    }

      /**
     * Bulk approve comments.
     */
    public function bulkApprove(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ids'   => 'required|array',
            'ids.*' => 'required|integer|exists:blog_comments,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        try {
            DB::beginTransaction();

              // Approve selected comments
            BlogComment::whereIn('id', $request->ids)->update(['is_approved' => true]);

              // Also approve their parent comments if they exist
            $parentIds = BlogComment::whereIn('id', $request->ids)
                ->whereNotNull('parent_id')
                ->pluck('parent_id')
                ->toArray();

            if (!empty($parentIds)) {
                BlogComment::whereIn('id', $parentIds)->update(['is_approved' => true]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => count($request->ids) . ' টি মন্তব্য অ্যাপ্রুভ করা হয়েছে!'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Bulk approve failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'বাল্ক অ্যাপ্রুভ করতে ব্যর্থ হয়েছে!'
            ], 500);
        }
    }

      /**
     * Reply to a comment.
     */
    public function reply(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'comment' => 'required|string|min:2|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        try {
            $parent = BlogComment::findOrFail($id);

            $reply = BlogComment::create([
                'blog_id'     => $parent->blog_id,
                'user_id'     => auth()->id() ?? null,
                'name'        => $request->name ?? auth()->user()?->name ?? 'Admin',
                'email'       => $request->email ?? auth()->user()?->email ?? 'admin@example.com',
                'comment'     => $request->comment,
                'parent_id'   => $id,
                'is_approved' => true,
                'ip_address'  => $request->ip(),
                'user_agent'  => $request->userAgent(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'রিপ্লাই যোগ করা হয়েছে!',
                'reply'   => $reply->load('user')
            ]);

        } catch (\Exception $e) {
            Log::error('Reply failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'রিপ্লাই যোগ করতে ব্যর্থ হয়েছে!'
            ], 500);
        }
    }

      /**
     * Get comments by blog (AJAX).
     */
    public function getCommentsByBlog($blogId)
    {
        try {
            $blog     = Blog::findOrFail($blogId);
            $comments = BlogComment::where('blog_id', $blogId)
                ->where('is_approved', true)
                ->whereNull('parent_id')
                ->with(['replies' => function ($q) {
                    $q->where('is_approved', true);
                }, 'replies.user', 'user'])
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'data'    => $comments,
                'total'   => $comments->count()
            ]);

        } catch (\Exception $e) {
            Log::error('Get comments by blog failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'মন্তব্য লোড করতে ব্যর্থ হয়েছে!'
            ], 500);
        }
    }

      /**
     * Export comments.
     */
    public function export(Request $request)
    {
        $query = BlogComment::with(['blog', 'user']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('comment', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            if ($request->status == 'approved') {
                $query->approved();
            } elseif ($request->status == 'pending') {
                $query->pending();
            }
        }

        if ($request->filled('blog_id')) {
            $query->forBlog($request->blog_id);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $comments = $query->orderBy('created_at', 'desc')->get();

        $filename = 'blog_comments_' . date('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($comments) {
            $file = fopen('php://output', 'w');

              // CSV Header
            fputcsv($file, [
                'ID',
                'ব্লগ',
                'মন্তব্যকারী',
                'ইমেইল',
                'মন্তব্য',
                'স্ট্যাটাস',
                'প্যারেন্ট',
                'আইপি',
                'তারিখ',
                'আপডেটের তারিখ'
            ]);

            foreach ($comments as $comment) {
                fputcsv($file, [
                    $comment->id,
                    $comment->blog?->title ?? 'N/A',
                    $comment->name ?? $comment->user?->name ?? 'N/A',
                    $comment->email ?? $comment->user?->email ?? 'N/A',
                    strip_tags($comment->comment),
                    $comment->is_approved ? 'অ্যাপ্রুভড': 'পেন্ডিং',
                    $comment->parent_id   ? 'রিপ্লাই'   : 'প্যারেন্ট',
                    $comment->ip_address ?? 'N/A',
                    $comment->created_at?->format('Y-m-d H:i:s'),
                    $comment->updated_at?->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

      /**
     * Get comment statistics (AJAX).
     */
    public function getStatistics()
    {
        $stats = [
            'total'           => BlogComment::count(),
            'approved'        => BlogComment::approved()->count(),
            'pending'         => BlogComment::pending()->count(),
            'today'           => BlogComment::todayCount(),
            'this_week'       => BlogComment::thisWeek()->count(),
            'this_month'      => BlogComment::thisMonth()->count(),
            'parent_comments' => BlogComment::parentOnly()->count(),
            'replies'         => BlogComment::repliesOnly()->count(),
        ];

          // Get last 7 days comment count for chart
        $last7Days = [];
        for ($i = 6; $i >= 0; $i--) {
            $date        = now()->subDays($i);
            $count       = BlogComment::whereDate('created_at', $date)->count();
            $last7Days[] = [
                'date'  => $date->format('Y-m-d'),
                'label' => $date->format('d M'),
                'count' => $count
            ];
        }

        return response()->json([
            'success'    => true,
            'stats'      => $stats,
            'chart_data' => $last7Days
        ]);
    }

      /**
     * Delete all pending comments.
     */
    public function deleteAllPending()
    {
        try {
            $count = BlogComment::pending()->count();

              // Delete pending comments and their replies
            $pendingIds = BlogComment::pending()->pluck('id')->toArray();
            if (!empty($pendingIds)) {
                BlogComment::whereIn('parent_id', $pendingIds)->delete();
                BlogComment::whereIn('id', $pendingIds)->delete();
            }

            return response()->json([
                'success' => true,
                'message' => $count . ' টি পেন্ডিং মন্তব্য মুছে ফেলা হয়েছে!'
            ]);

        } catch (\Exception $e) {
            Log::error('Delete all pending failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'পেন্ডিং মন্তব্য মুছে ফেলতে ব্যর্থ হয়েছে!'
            ], 500);
        }
    }
}
