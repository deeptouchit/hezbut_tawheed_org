<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmailTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class EmailTemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = EmailTemplate::query()
            ->when($request->search, function ($q, $search) {
                $q->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('subject', 'LIKE', "%{$search}%")
                    ->orWhere('body', 'LIKE', "%{$search}%");
            })
            ->when($request->type, function ($q, $type) {
                $q->where('type', $type);
            })
            ->when($request->status !== null && $request->status !== '', function ($q) use ($request) {
                $q->where('is_active', $request->status);
            })
            ->orderBy('name', 'asc');

        $perPage = $request->input('per_page', 20);

        if ($perPage == 'all' || $perPage == '-1') {
            $templates = $query->paginate(999999);
            $templates->appends($request->query());
        } else {
            $perPage = is_numeric($perPage) ? (int)$perPage : 20;
            $perPage = in_array($perPage, [10, 20, 30, 50, 100, 200]) ? $perPage : 20;
            $templates = $query->paginate($perPage);
            $templates->appends($request->query());
        }

        $stats = [
            'total' => EmailTemplate::count(),
            'active' => EmailTemplate::where('is_active', true)->count(),
            'inactive' => EmailTemplate::where('is_active', false)->count(),
        ];

        $types = EmailTemplate::distinct()->pluck('type');

        if ($request->ajax()) {
            $html = view('admin.email-templates.partials.table', compact('templates'))->render();
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

        return view('admin.email-templates.index', compact('templates', 'types', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.email-templates.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:150|unique:email_templates,name',
            'subject' => 'nullable|string|max:255',
            'body' => 'required|string',
            'type' => 'nullable|string|max:50',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            $template = EmailTemplate::create([
                'name' => $request->name,
                'subject' => $request->subject,
                'body' => $request->body, // মডেলের accessor auto-handle করবে
                'type' => $request->type ?? 'general',
                'is_active' => $request->has('is_active'),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'ইমেইল টেমপ্লেট সফলভাবে তৈরি করা হয়েছে।',
                'data' => $template
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Email template store error: ' . $e->getMessage(), [
                'request' => $request->all(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'ইমেইল টেমপ্লেট তৈরি করতে ব্যর্থ হয়েছে। দয়া করে আবার চেষ্টা করুন।'
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(EmailTemplate $emailTemplate)
    {
        return response()->json([
            'success' => true,
            'data' => $emailTemplate
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EmailTemplate $emailTemplate)
    {
        return response()->json([
            'success' => true,
            'data' => $emailTemplate
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, EmailTemplate $emailTemplate)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:150|unique:email_templates,name,' . $emailTemplate->id,
            'subject' => 'nullable|string|max:255',
            'body' => 'required|string',
            'type' => 'nullable|string|max:50',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            $emailTemplate->update([
                'name' => $request->name,
                'subject' => $request->subject,
                'body' => $request->body, // মডেলের accessor auto-handle করবে
                'type' => $request->type ?? 'general',
                'is_active' => $request->has('is_active'),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'ইমেইল টেমপ্লেট সফলভাবে আপডেট করা হয়েছে।',
                'data' => $emailTemplate
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Email template update error: ' . $e->getMessage(), [
                'template_id' => $emailTemplate->id,
                'request' => $request->all(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'ইমেইল টেমপ্লেট আপডেট করতে ব্যর্থ হয়েছে। দয়া করে আবার চেষ্টা করুন।'
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EmailTemplate $emailTemplate)
    {
        try {
            $templateName = $emailTemplate->name;
            $emailTemplate->delete();

            return response()->json([
                'success' => true,
                'message' => "‘{$templateName}’ ইমেইল টেমপ্লেট মুছে ফেলা হয়েছে।"
            ]);

        } catch (\Exception $e) {
            Log::error('Email template delete error: ' . $e->getMessage(), [
                'template_id' => $emailTemplate->id ?? null,
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'ইমেইল টেমপ্লেট মুছে ফেলতে ব্যর্থ হয়েছে।'
            ], 500);
        }
    }

    /**
     * Toggle template status (active/inactive).
     */
    public function toggleStatus(EmailTemplate $emailTemplate)
    {
        try {
            $emailTemplate->is_active = !$emailTemplate->is_active;
            $emailTemplate->save();

            return response()->json([
                'success' => true,
                'status' => $emailTemplate->is_active,
                'message' => $emailTemplate->is_active
                    ? "‘{$emailTemplate->name}’ টেমপ্লেট সক্রিয় করা হয়েছে।"
                    : "‘{$emailTemplate->name}’ টেমপ্লেট নিষ্ক্রিয় করা হয়েছে।"
            ]);

        } catch (\Exception $e) {
            Log::error('Toggle status error: ' . $e->getMessage(), [
                'template_id' => $emailTemplate->id,
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'স্ট্যাটাস পরিবর্তন করতে ব্যর্থ হয়েছে।'
            ], 500);
        }
    }

    /**
     * Preview email template.
     *
     * মডেলের getBodyAttribute accessor স্বয়ংক্রিয়ভাবে HTML ডিকোড করবে,
     * তাই আলাদাভাবে html_entity_decode করার প্রয়োজন নেই।
     */
    public function preview(EmailTemplate $emailTemplate)
    {
        // মডেলের accessor body কে ডিকোড করবে
        // শুধু ভিউতে পাঠানোর প্রয়োজন
        return view('admin.email-templates.preview', compact('emailTemplate'));
    }

    /**
     * Bulk delete templates
     */
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:email_templates,id'
        ]);

        try {
            $deleted = EmailTemplate::whereIn('id', $request->ids)->delete();

            return response()->json([
                'success' => true,
                'message' => "{$deleted} টি টেমপ্লেট মুছে ফেলা হয়েছে।"
            ]);

        } catch (\Exception $e) {
            Log::error('Bulk delete error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'একাধিক টেমপ্লেট মুছে ফেলতে ব্যর্থ হয়েছে।'
            ], 500);
        }
    }

    /**
     * Duplicate template
     */
    public function duplicate(EmailTemplate $emailTemplate)
    {
        try {
            $newTemplate = $emailTemplate->duplicate();

            return response()->json([
                'success' => true,
                'message' => 'টেমপ্লেট সফলভাবে কপি করা হয়েছে।',
                'data' => $newTemplate
            ]);

        } catch (\Exception $e) {
            Log::error('Template duplicate error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'টেমপ্লেট কপি করতে ব্যর্থ হয়েছে।'
            ], 500);
        }
    }

    /**
     * Get template placeholders
     */
    public function getPlaceholders(EmailTemplate $emailTemplate)
    {
        return response()->json([
            'success' => true,
            'placeholders' => $emailTemplate->placeholders_list
        ]);
    }

    /**
     * Test template rendering
     */
    public function testRender(Request $request, EmailTemplate $emailTemplate)
    {
        $request->validate([
            'test_data' => 'nullable|array'
        ]);

        try {
            $rendered = $emailTemplate->render($request->test_data ?? []);

            return response()->json([
                'success' => true,
                'rendered_html' => $rendered
            ]);

        } catch (\Exception $e) {
            Log::error('Test render error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'টেমপ্লেট রেন্ডার করতে ব্যর্থ হয়েছে।'
            ], 500);
        }
    }
}
