<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use App\Helpers\SettingsHelper;

class SettingController extends Controller
{
    public function index()
    {
        $groups = Setting::select('group')
            ->distinct()
            ->orderBy('group')
            ->pluck('group');

        $settings = Setting::orderBy('group')
            ->orderBy('sort_order')
            ->get()
            ->groupBy('group');

        return view('admin.settings.index', compact('settings', 'groups'));
    }

    public function store(Request $request)
    {
        // ১. রিকোয়েস্টের টোকেন এবং রিমুভ কিগুলো আলাদা করার জন্য ফিল্টার করি
        $inputs = $request->except(['_token']);
        
        $destinationPath = public_path('uploads/settings');
        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0777, true);
        }

        // ২. রিকোয়েস্টের ইনপুটগুলো লুপ করে প্রসেস করি
        foreach ($inputs as $key => $value) {
            // রিমুভ ডিরেক্টিভগুলো এড়িয়ে চলি, এগুলো নিচে হ্যান্ডেল করা হবে
            if (str_starts_with($key, 'remove_')) {
                continue;
            }

            // যদি এই ইনপুটটি কোনো ফাইল আপলোড হয়
            if ($request->hasFile($key)) {
                $file = $request->file($key);
                
                // কাস্টম ফাইল নেমিং কনভেনশন ঠিক রাখা
                if ($key === 'company_logo') {
                    $prefix = 'logo_';
                } elseif ($key === 'favicon') {
                    $prefix = 'favicon_';
                } elseif ($key === 'og_image') {
                    $prefix = 'og_';
                } elseif ($key === 'invoice_logo') {
                    $prefix = 'invoice_';
                } else {
                    $prefix = $key . '_';
                }

                $fileName = $prefix . time() . '.' . $file->getClientOriginalExtension();
                
                // ডেটাবেজে $destinationPath (আপেক্ষিক বা রিলেটিভ পথ) সহ স্টোর করার জন্য
                $dbRelativePath = 'uploads/settings/' . $fileName;

                // বিদ্যমান সেটিং অবজেক্ট খুঁজে বের করি
                $setting = Setting::where('key', $key)->first();
                if ($setting) {
                    // পুরাতন ফাইল ডিলিট করি (যেহেতু ডেটাবেজে ফুল রিলেটিভ বা শুধু ফাইলনেম যেকোনোটি থাকতে পারে)
                    $oldValue = $setting->value;
                    if ($oldValue) {
                        $cleanOldPath = str_starts_with($oldValue, 'uploads/settings/') ? substr($oldValue, 17) : $oldValue;
                        $fullOldPath = public_path('uploads/settings/' . $cleanOldPath);
                        if (file_exists($fullOldPath)) {
                            @unlink($fullOldPath);
                        }
                    }
                }

                // ফাইলটি আপলোড ফোল্ডারে মুভ করি
                $file->move($destinationPath, $fileName);
                $value = $dbRelativePath;
            }

            // ডেটাবেজে সেভ বা আপডেট করি
            $setting = Setting::where('key', $key)->first();
            if ($setting) {
                // যদি সেটিংস টাইপ ইমেজ বা ফাইল না হয়, তাহলে যেকোনো ভ্যালু (স্ট্রিং বা নাল) দিয়ে আপডেট করি
                if (!in_array($setting->type, ['image', 'file'])) {
                    $setting->update(['value' => $value]);
                } 
                // আর যদি ইমেজ বা ফাইল হয়, তাহলে শুধুমাত্র নতুন ফাইল আপলোড হলেই (যা স্ট্রিং পাথ হিসেবে আসে) আপডেট করব
                elseif (is_string($value)) {
                    $setting->update(['value' => $value]);
                }
            } else {
                if ($value !== null) {
                    // নতুন সেটিং তৈরি করি
                    Setting::create([
                        'key'   => $key,
                        'value' => $value,
                        'type'  => $request->hasFile($key) ? 'image' : 'text',
                        'label' => ucwords(str_replace('_', ' ', $key))
                    ]);
                }
            }
        }

        // ৩. ডাইনামিক ফাইল রিমুভাল হ্যান্ডেল করি (যেমন: remove_company_logo = 1)
        foreach ($request->all() as $k => $v) {
            if (str_starts_with($k, 'remove_') && $v == 1) {
                $settingKey = substr($k, 7); // 'remove_' বাদ দিয়ে কী বের করি
                $setting = Setting::where('key', $settingKey)->first();
                if ($setting) {
                    $oldValue = $setting->value;
                    if ($oldValue) {
                        $cleanOldPath = str_starts_with($oldValue, 'uploads/settings/') ? substr($oldValue, 17) : $oldValue;
                        $fullOldPath = public_path('uploads/settings/' . $cleanOldPath);
                        if (file_exists($fullOldPath)) {
                            @unlink($fullOldPath);
                        }
                    }
                    $setting->update(['value' => null]);
                }
            }
        }

        // ৪. ক্যাশ পরিষ্কার করা
        SettingsHelper::clearCache();

        return redirect()->route('admin.settings.index')
            ->with('success', 'সেটিংস সফলভাবে সংরক্ষণ করা হয়েছে।');
    }

    public function createSetting(Request $request)
    {
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'key'   => 'required|unique:settings,key|max:255',
            'label' => 'required|max:255',
            'type'  => 'required|in:text,textarea,number,email,url,image,file,color,select,boolean,json',
            'group' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors()
            ], 422);
        }

        try {
            $setting = Setting::create([
                'key'         => $request->key,
                'label'       => $request->label,
                'type'        => $request->type,
                'group'       => $request->group,
                'value'       => $request->value,
                'options'     => $request->options,
                'placeholder' => $request->placeholder,
                'help_text'   => $request->help_text,
                'sort_order'  => Setting::where('group', $request->group)->count()
            ]);

            SettingsHelper::clearCache();

            return response()->json([
                'success' => true,
                'message' => 'সেটিংস তৈরি করা হয়েছে!',
                'setting' => $setting
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'সেটিংস তৈরি করতে ব্যর্থ হয়েছে: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateSetting(Request $request, $id)
    {
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'key'   => 'required|unique:settings,key,'.$id.'|max:255',
            'label' => 'required|max:255',
            'type'  => 'required|in:text,textarea,number,email,url,image,file,color,select,boolean,json',
            'group' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors()
            ], 422);
        }

        try {
            $setting = Setting::findOrFail($id);

            $setting->update([
                'key'         => $request->key,
                'label'       => $request->label,
                'type'        => $request->type,
                'group'       => $request->group,
                'options'     => $request->options,
                'placeholder' => $request->placeholder,
                'help_text'   => $request->help_text,
                'sort_order'  => $request->sort_order ?? $setting->sort_order,
            ]);

            SettingsHelper::clearCache();

            return response()->json([
                'success' => true,
                'message' => 'সেটিংস আপডেট করা হয়েছে!',
                'setting' => $setting
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'সেটিংস আপডেট করতে ব্যর্থ হয়েছে: ' . $e->getMessage()
            ], 500);
        }
    }
    public function updateValue(Request $request, $id)
    {
        $setting = Setting::findOrFail($id);

        // Handle file upload if needed
        if ($request->hasFile('value')) {
            $file            = $request->file('value');
            $fileName        = $setting->key . '_' . time() . '.' . $file->getClientOriginalExtension();
            $destinationPath = public_path('uploads/settings');

            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }

            // Delete old file
            $oldValue = $setting->value;
            if ($oldValue) {
                $cleanOldPath = str_starts_with($oldValue, 'uploads/settings/') ? substr($oldValue, 17) : $oldValue;
                $fullOldPath = public_path('uploads/settings/' . $cleanOldPath);
                if (file_exists($fullOldPath)) {
                    @unlink($fullOldPath);
                }
            }

            $file->move($destinationPath, $fileName);
            $setting->value = 'uploads/settings/' . $fileName;
        } else {
            $setting->value = $request->value;
        }

        $setting->save();
        SettingsHelper::clearCache();

        return response()->json([
            'success' => true,
            'message' => 'সেটিংস ভ্যালু আপডেট করা হয়েছে!'
        ]);
    }

    public function destroy($id)
    {
        try {
            $setting = Setting::findOrFail($id);

              // Delete file if exists
            if (in_array($setting->type, ['image', 'file']) && $setting->value) {
                $oldValue = $setting->value;
                $cleanOldPath = str_starts_with($oldValue, 'uploads/settings/') ? substr($oldValue, 17) : $oldValue;
                $filePath = public_path('uploads/settings/' . $cleanOldPath);
                if (file_exists($filePath)) {
                    @unlink($filePath);
                }
            }

            $setting->delete();
            SettingsHelper::clearCache();

            return response()->json([
                'success' => true,
                'message' => 'সেটিংস মুছে ফেলা হয়েছে!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'ত্রুটি: ' . $e->getMessage()
            ], 500);
        }
    }

    public function bulkDelete(Request $request)
    {
        try {
            $ids = $request->ids;

            if (empty($ids)) {
                return response()->json([
                    'success' => false,
                    'message' => 'কোন সেটিংস সিলেক্ট করা হয়নি!'
                ], 400);
            }

            $settings = Setting::whereIn('id', $ids)->get();

            foreach ($settings as $setting) {
                  // Delete file if exists
                if (in_array($setting->type, ['image', 'file']) && $setting->value) {
                    $filePath = public_path('uploads/settings/' . $setting->value);
                    if (file_exists($filePath)) {
                        @unlink($filePath);
                    }
                }
            }

            Setting::whereIn('id', $ids)->delete();
            SettingsHelper::clearCache();

            return response()->json([
                'success' => true,
                'message' => 'সিলেক্টেড সেটিংস মুছে ফেলা হয়েছে!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'ত্রুটি: ' . $e->getMessage()
            ], 500);
        }
    }

    public function export()
    {
        $settings = Setting::all();

        $data = [];
        foreach ($settings as $setting) {
            $data[] = [
                'key'         => $setting->key,
                'value'       => $setting->value,
                'label'       => $setting->label,
                'type'        => $setting->type,
                'group'       => $setting->group,
                'options'     => $setting->options,
                'placeholder' => $setting->placeholder,
                'help_text'   => $setting->help_text,
                'sort_order'  => $setting->sort_order
            ];
        }

        return response()->json($data);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:json|max:2048'
        ]);

        $content = json_decode(file_get_contents($request->file('file')), true);

        foreach ($content as $item) {
            Setting::updateOrCreate(
                ['key' => $item['key']],
                [
                    'label'       => $item['label'] ?? $item['key'],
                    'type'        => $item['type'] ?? 'text',
                    'group'       => $item['group'] ?? 'general',
                    'options'     => $item['options'] ?? null,
                    'value'       => $item['value'] ?? null,
                    'placeholder' => $item['placeholder'] ?? null,
                    'help_text'   => $item['help_text'] ?? null,
                    'sort_order'  => $item['sort_order'] ?? 0
                ]
            );
        }

        SettingsHelper::clearCache();

        return redirect()->back()->with('success', 'সেটিংস ইমপোর্ট করা হয়েছে!');
    }
}
