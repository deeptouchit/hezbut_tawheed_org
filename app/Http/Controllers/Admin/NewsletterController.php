<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NewsletterSubscriber;
use App\Models\NewsletterCampaign;
use App\Models\NewsletterTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class NewsletterController extends Controller
{
    // =============================================
    // SUBSCRIBERS SECTION
    // =============================================

    public function subscribers(Request $request)
    {
        $query = NewsletterSubscriber::query()
            ->when($request->search, function ($q, $search) {
                $q->where('email', 'LIKE', "%{$search}%")
                    ->orWhere('name', 'LIKE', "%{$search}%");
            })
            ->when($request->status !== null && $request->status !== '', function ($q) use ($request) {
                if ($request->status == 'verified') {
                    $q->whereNotNull('verified_at');
                } elseif ($request->status == 'pending') {
                    $q->whereNull('verified_at');
                } elseif ($request->status == 'active') {
                    $q->where('is_active', true);
                } elseif ($request->status == 'inactive') {
                    $q->where('is_active', false);
                }
            })
            ->orderBy('id', 'desc');

        $perPage = $request->input('per_page', 20);

        if ($perPage == 'all') {
            $subscribers = $query->get();
            $currentPage = \Illuminate\Pagination\LengthAwarePaginator::resolveCurrentPage();
            $total = $subscribers->count();
            $subscribers = new \Illuminate\Pagination\LengthAwarePaginator(
                $subscribers->forPage($currentPage, $total),
                $total,
                $total > 0 ? $total : 20,
                $currentPage,
                ['path' => \Illuminate\Pagination\Paginator::resolveCurrentPath()]
            );
        } else {
            $perPage = is_numeric($perPage) ? (int)$perPage : 20;
            $perPage = in_array($perPage, [10, 20, 30, 50, 100, 200]) ? $perPage : 20;
            $subscribers = $query->paginate($perPage);
            $subscribers->appends($request->query());
        }

        $stats = [
            'total' => NewsletterSubscriber::count(),
            'verified' => NewsletterSubscriber::whereNotNull('verified_at')->count(),
            'active' => NewsletterSubscriber::where('is_active', true)->count(),
            'inactive' => NewsletterSubscriber::where('is_active', false)->count(),
        ];

        if ($request->ajax()) {
            $html = view('admin.newsletter.subscribers.partials.table', compact('subscribers'))->render();
            return response()->json([
                'success' => true,
                'html' => $html,
                'stats' => $stats,
                'pagination' => [
                    'total' => $subscribers->total(),
                    'current_page' => $subscribers->currentPage(),
                    'last_page' => $subscribers->lastPage(),
                ]
            ]);
        }

        return view('admin.newsletter.subscribers.index', compact('subscribers', 'stats'));
    }

    public function addSubscriber(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:newsletter_subscribers,email',
            'name' => 'nullable|string|max:255',
        ]);

        try {
            DB::beginTransaction();

            $subscriber = NewsletterSubscriber::create([
                'email' => $request->email,
                'name' => $request->name,
                'verification_token' => Str::random(60),
                'ip_address' => $request->ip(),
                'source' => 'admin',
                'is_active' => true,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'সাবস্ক্রাইবার সফলভাবে যোগ করা হয়েছে।'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Add subscriber error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'সাবস্ক্রাইবার যোগ করতে ব্যর্থ হয়েছে।'
            ], 500);
        }
    }

    public function deleteSubscriber($id)
    {
        try {
            $subscriber = NewsletterSubscriber::findOrFail($id);
            $subscriber->delete();

            return response()->json([
                'success' => true,
                'message' => 'সাবস্ক্রাইবার মুছে ফেলা হয়েছে।'
            ]);
        } catch (\Exception $e) {
            Log::error('Delete subscriber error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'সাবস্ক্রাইবার মুছে ফেলতে ব্যর্থ হয়েছে।'
            ], 500);
        }
    }

    public function exportSubscribers()
    {
        $subscribers = NewsletterSubscriber::all();

        $filename = 'newsletter-subscribers-' . date('Y-m-d') . '.csv';

        $callback = function () use ($subscribers) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            fputcsv($file, ['ইমেইল', 'নাম', 'ভেরিফাইড', 'সাবস্ক্রাইব তারিখ']);

            foreach ($subscribers as $subscriber) {
                fputcsv($file, [
                    $subscriber->email,
                    $subscriber->name ?? '-',
                    $subscriber->verified_at ? 'হ্যাঁ' : 'না',
                    $subscriber->created_at->format('Y-m-d H:i:s')
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}",
        ]);
    }
    /**
     * Display campaigns list
     */
    public function campaigns(Request $request)
    {
        $query = NewsletterCampaign::with('creator')
            ->when($request->search, function ($q, $search) {
                $q->where('subject', 'LIKE', "%{$search}%")
                    ->orWhere('title', 'LIKE', "%{$search}%");
            })
            ->when($request->status, function ($q, $status) {
                $q->where('status', $status);
            })
            ->orderBy('id', 'desc');

        $perPage = $request->input('per_page', 20);

        if ($perPage == 'all') {
            $campaigns = $query->get();
            $currentPage = \Illuminate\Pagination\LengthAwarePaginator::resolveCurrentPage();
            $total = $campaigns->count();
            $campaigns = new \Illuminate\Pagination\LengthAwarePaginator(
                $campaigns->forPage($currentPage, $total),
                $total,
                $total > 0 ? $total : 20,
                $currentPage,
                ['path' => \Illuminate\Pagination\Paginator::resolveCurrentPath()]
            );
        } else {
            $perPage = is_numeric($perPage) ? (int)$perPage : 20;
            $perPage = in_array($perPage, [10, 20, 30, 50, 100, 200]) ? $perPage : 20;
            $campaigns = $query->paginate($perPage);
            $campaigns->appends($request->query());
        }

        $stats = [
            'total' => NewsletterCampaign::count(),
            'sent' => NewsletterCampaign::where('status', 'sent')->count(),
            'scheduled' => NewsletterCampaign::where('status', 'scheduled')->count(),
            'draft' => NewsletterCampaign::where('status', 'draft')->count(),
        ];

        if ($request->ajax()) {
            $html = view('admin.newsletter.campaigns.partials.table', compact('campaigns'))->render();
            return response()->json([
                'success' => true,
                'html' => $html,
                'stats' => $stats,
            ]);
        }

        return view('admin.newsletter.campaigns.index', compact('campaigns', 'stats'));
    }

    /**
     * Show create campaign form
     */
    public function createCampaign()
    {
        $templates = NewsletterTemplate::where('status', true)->get();
        return view('admin.newsletter.campaigns.create', compact('templates'));
    }

    /**
     * Store campaign
     */
    public function storeCampaign(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'title' => 'nullable|string|max:255',
            'content' => 'required|string',
            'recipient_type' => 'required|in:all,active_only,selected',
            'selected_emails' => 'array|nullable',
            'scheduled_at' => 'nullable|date|after:now',
        ]);

        try {
            DB::beginTransaction();

            // Calculate total recipients
            $totalRecipients = 0;
            if ($request->recipient_type == 'all') {
                $totalRecipients = NewsletterSubscriber::count();
            } elseif ($request->recipient_type == 'active_only') {
                $totalRecipients = NewsletterSubscriber::where('is_active', true)->count();
            } elseif ($request->recipient_type == 'selected' && $request->selected_emails) {
                $totalRecipients = count($request->selected_emails);
            }

            $campaign = NewsletterCampaign::create([
                'subject' => $request->subject,
                'title' => $request->title,
                'content' => $request->content,
                'template' => $request->template ?? 'default',
                'recipient_type' => $request->recipient_type,
                'selected_emails' => $request->selected_emails,
                'total_recipients' => $totalRecipients,
                'scheduled_at' => $request->scheduled_at,
                'status' => $request->scheduled_at ? 'scheduled' : 'draft',
                'created_by' => auth()->id(),
            ]);

            DB::commit();

            $message = $request->scheduled_at ? 'ক্যাম্পেইন শিডিউল করা হয়েছে।' : 'ক্যাম্পেইন ড্রাফট হিসেবে সংরক্ষণ করা হয়েছে।';

            return redirect()->route('admin.newsletter.campaigns.index')
                ->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Campaign store error: ' . $e->getMessage());
            return back()->withInput()->with('error', 'ক্যাম্পেইন তৈরি করতে ব্যর্থ হয়েছে।');
        }
    }

    /**
     * Show campaign details
     */
    public function showCampaign($id)
    {
        $campaign = NewsletterCampaign::with('creator')->findOrFail($id);
        return view('admin.newsletter.campaigns.show', compact('campaign'));
    }

    /**
     * Send campaign immediately
     */
    public function sendCampaign($id)
    {
        try {
            $campaign = NewsletterCampaign::findOrFail($id);

            // Get recipients
            if ($campaign->recipient_type == 'all') {
                $recipients = NewsletterSubscriber::pluck('email')->toArray();
            } elseif ($campaign->recipient_type == 'active_only') {
                $recipients = NewsletterSubscriber::where('is_active', true)->pluck('email')->toArray();
            } else {
                $recipients = $campaign->selected_emails ?? [];
            }

            $campaign->update([
                'status' => 'sending',
                'sent_at' => now(),
            ]);

            // Send emails in queue
            foreach ($recipients as $email) {
                // Mail::to($email)->queue(new NewsletterMail($campaign));
            }

            $campaign->update([
                'status' => 'sent',
                'sent_count' => count($recipients),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'ক্যাম্পেইন সফলভাবে পাঠানো হয়েছে।'
            ]);
        } catch (\Exception $e) {
            Log::error('Campaign send error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'ক্যাম্পেইন পাঠাতে ব্যর্থ হয়েছে।'
            ], 500);
        }
    }

    /**
     * Delete campaign
     */
    public function deleteCampaign($id)
    {
        try {
            $campaign = NewsletterCampaign::findOrFail($id);
            $campaign->delete();

            return response()->json([
                'success' => true,
                'message' => 'ক্যাম্পেইন মুছে ফেলা হয়েছে।'
            ]);
        } catch (\Exception $e) {
            Log::error('Campaign delete error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'ক্যাম্পেইন মুছে ফেলতে ব্যর্থ হয়েছে।'
            ], 500);
        }
    }

        // =============================================
    // TEMPLATES SECTION
    // =============================================

    /**
     * Display templates list
     */
    public function templates(Request $request)
    {
        $query = NewsletterTemplate::query()
            ->when($request->search, function($q, $search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('subject', 'LIKE', "%{$search}%");
            })
            ->when($request->status !== null && $request->status !== '', function($q) use ($request) {
                $q->where('status', $request->status);
            })
            ->orderBy('is_default', 'desc')
            ->orderBy('name', 'asc');

        $perPage = $request->input('per_page', 20);

        if ($perPage == 'all') {
            $templates = $query->get();
            $currentPage = \Illuminate\Pagination\LengthAwarePaginator::resolveCurrentPage();
            $total = $templates->count();
            $templates = new \Illuminate\Pagination\LengthAwarePaginator(
                $templates->forPage($currentPage, $total),
                $total,
                $total > 0 ? $total : 20,
                $currentPage,
                ['path' => \Illuminate\Pagination\Paginator::resolveCurrentPath()]
            );
        } else {
            $perPage = is_numeric($perPage) ? (int)$perPage : 20;
            $perPage = in_array($perPage, [10, 20, 30, 50, 100, 200]) ? $perPage : 20;
            $templates = $query->paginate($perPage);
            $templates->appends($request->query());
        }

        $stats = [
            'total' => NewsletterTemplate::count(),
            'active' => NewsletterTemplate::where('status', true)->count(),
            'inactive' => NewsletterTemplate::where('status', false)->count(),
            'default' => NewsletterTemplate::where('is_default', true)->count(),
        ];

        if ($request->ajax()) {
            $html = view('admin.newsletter.templates.partials.table', compact('templates'))->render();
            return response()->json([
                'success' => true,
                'html' => $html,
                'stats' => $stats,
                'pagination' => [
                    'total' => $templates->total(),
                    'current_page' => $templates->currentPage(),
                    'last_page' => $templates->lastPage(),
                ]
            ]);
        }

        return view('admin.newsletter.templates.index', compact('templates', 'stats'));
    }

    /**
     * Show create template form
     */
    public function createTemplate()
    {
        return view('admin.newsletter.templates.create');
    }

    /**
     * Store template
     */
    public function storeTemplate(Request $request)
    {
        $request->validate([
            'name'      => 'required|string|max:255',
            'subject'   => 'required|string|max:255',
            'content'   => 'required|string',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        try {
            DB::beginTransaction();

            // If this template is set as default, remove default from others
            if ($request->has('is_default')) {
                NewsletterTemplate::where('is_default', true)->update(['is_default' => false]);
            }

            $template = NewsletterTemplate::create([
                'name' => $request->name,
                'subject' => $request->subject,
                'content' => $request->content,
                'is_default' => $request->has('is_default'),
                'status' => $request->has('status'),
            ]);

            // Upload thumbnail
            if ($request->hasFile('thumbnail')) {
                $file = $request->file('thumbnail');
                $fileName = 'template_' . $template->id . '_' . time() . '.' . $file->getClientOriginalExtension();
                $destinationPath = public_path('uploads/newsletter/templates');

                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0777, true);
                }

                $file->move($destinationPath, $fileName);
                $template->thumbnail = 'uploads/newsletter/templates/' . $fileName;
                $template->save();
            }

            DB::commit();

            return redirect()->route('admin.newsletter.templates.index')
                ->with('success', 'টেমপ্লেট সফলভাবে তৈরি করা হয়েছে।');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Template store error: ' . $e->getMessage());
            return back()->withInput()->with('error', 'টেমপ্লেট তৈরি করতে ব্যর্থ হয়েছে।');
        }
    }

    /**
     * Show edit template form
     */
    public function editTemplate($id)
    {
        $template = NewsletterTemplate::findOrFail($id);
        return view('admin.newsletter.templates.edit', compact('template'));
    }

    /**
     * Update template
     */
    public function updateTemplate(Request $request, $id)
    {
        $template = NewsletterTemplate::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'content' => 'required|string',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        try {
            DB::beginTransaction();

            // If this template is set as default, remove default from others
            if ($request->has('is_default') && !$template->is_default) {
                NewsletterTemplate::where('is_default', true)->update(['is_default' => false]);
            }

            $template->update([
                'name' => $request->name,
                'subject' => $request->subject,
                'content' => $request->content,
                'is_default' => $request->has('is_default'),
                'status' => $request->has('status'),
            ]);

            // Upload thumbnail
            if ($request->hasFile('thumbnail')) {
                // Delete old thumbnail
                if ($template->thumbnail && file_exists(public_path($template->thumbnail))) {
                    @unlink(public_path($template->thumbnail));
                }

                $file = $request->file('thumbnail');
                $fileName = 'template_' . $template->id . '_' . time() . '.' . $file->getClientOriginalExtension();
                $destinationPath = public_path('uploads/newsletter/templates');
                $file->move($destinationPath, $fileName);
                $template->thumbnail = 'uploads/newsletter/templates/' . $fileName;
                $template->save();
            }

            DB::commit();

            return redirect()->route('admin.newsletter.templates.index')
                ->with('success', 'টেমপ্লেট সফলভাবে আপডেট করা হয়েছে।');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Template update error: ' . $e->getMessage());
            return back()->withInput()->with('error', 'টেমপ্লেট আপডেট করতে ব্যর্থ হয়েছে।');
        }
    }

    /**
     * Delete template
     */
    public function deleteTemplate($id)
    {
        try {
            $template = NewsletterTemplate::findOrFail($id);

            // Check if template is default
            if ($template->is_default) {
                return response()->json([
                    'success' => false,
                    'message' => 'ডিফল্ট টেমপ্লেট মুছে ফেলা যাবে না।'
                ], 422);
            }

            // Delete thumbnail
            if ($template->thumbnail && file_exists(public_path($template->thumbnail))) {
                @unlink(public_path($template->thumbnail));
            }

            $template->delete();

            return response()->json([
                'success' => true,
                'message' => 'টেমপ্লেট মুছে ফেলা হয়েছে।'
            ]);

        } catch (\Exception $e) {
            Log::error('Template delete error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'টেমপ্লেট মুছে ফেলতে ব্যর্থ হয়েছে।'
            ], 500);
        }
    }

    /**
     * Toggle template status
     */
    public function toggleTemplateStatus($id)
    {
        try {
            $template = NewsletterTemplate::findOrFail($id);

            // Don't deactivate default template
            if ($template->is_default && $template->status) {
                return response()->json([
                    'success' => false,
                    'message' => 'ডিফল্ট টেমপ্লেট নিষ্ক্রিয় করা যাবে না।'
                ], 422);
            }

            $template->status = !$template->status;
            $template->save();

            return response()->json([
                'success' => true,
                'status' => $template->status,
                'message' => $template->status ? 'টেমপ্লেট সক্রিয় করা হয়েছে।' : 'টেমপ্লেট নিষ্ক্রিয় করা হয়েছে।'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'স্ট্যাটাস পরিবর্তন করতে ব্যর্থ হয়েছে।'
            ], 500);
        }
    }

    /**
     * Set template as default
     */
    public function setDefaultTemplate($id)
    {
        try {
            DB::beginTransaction();

            // Remove default from all templates
            NewsletterTemplate::where('is_default', true)->update(['is_default' => false]);

            // Set new default
            $template = NewsletterTemplate::findOrFail($id);
            $template->is_default = true;
            $template->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'ডিফল্ট টেমপ্লেট সেট করা হয়েছে।'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'ডিফল্ট টেমপ্লেট সেট করতে ব্যর্থ হয়েছে।'
            ], 500);
        }
    }

    /**
     * Preview template
     */
    public function previewTemplate($id)
    {
        $template = NewsletterTemplate::findOrFail($id);
        return view('admin.newsletter.templates.preview', compact('template'));
    }

}
