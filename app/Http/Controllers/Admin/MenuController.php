<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\MenuHelper;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use App\Models\BlogCategory;

class MenuController extends Controller
{
    /**
     * মেনু ম্যানেজমেন্ট পেজ দেখান
     */
    public function index()
    {
        try {
            $menuData = MenuHelper::getMenuData();
            $menuFile = resource_path('data/menu.json');

            // Fetch blog categories for selection panel
            $categories = BlogCategory::select('id', 'name', 'slug')->orderBy('name')->get();

            return view('admin.menu.index', compact('menuData', 'menuFile', 'categories'));
        } catch (\Exception $e) {
            Log::error('Menu index error: ' . $e->getMessage());
            return back()->with('error', 'মেনু লোড করতে সমস্যা হয়েছে: ' . $e->getMessage());
        }
    }

    /**
     * সম্পূর্ণ মেনু আপডেট করুন
     */
    public function update(Request $request)
    {
        $request->validate([
            'menu_data' => 'nullable|array',
            'section' => 'nullable|string',
            'items' => 'nullable|array',
        ]);

        try {
            $menuPath = resource_path('data/menu.json');

            // ব্যাকআপ তৈরি করুন
            if (File::exists($menuPath)) {
                $backupPath = resource_path('data/menu_backup_' . date('Y-m-d_H-i-s') . '.json');
                File::copy($menuPath, $backupPath);

                // শুধু শেষ 5টি ব্যাকআপ রাখুন
                $this->cleanOldBackups();
            }

            // জেসন থেকে বর্তমান ডাটা লোড করুন
            $menuData = File::exists($menuPath) ? json_decode(File::get($menuPath), true) : [];
            if (!is_array($menuData)) {
                $menuData = [];
            }

            // সেকশন ভিত্তিক অথবা সম্পূর্ণ ডাটা আপডেট করুন
            if ($request->has('section') && $request->has('items')) {
                $section = $request->section;
                $menuData[$section] = $request->items;
            } elseif ($request->has('menu_data')) {
                $menuData = array_merge($menuData, $request->menu_data);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'কোনো ডাটা পাঠানো হয়নি!'
                ], 400);
            }

            $menuData['last_updated'] = now()->toISOString();
            $menuData['version'] = '1.0.1';

            File::put($menuPath, json_encode($menuData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

            // ক্যাশ রিফ্রেশ করুন
            MenuHelper::refreshCache();

            return response()->json([
                'success' => true,
                'message' => 'মেনু সফলভাবে আপডেট করা হয়েছে!'
            ]);
        } catch (\Exception $e) {
            Log::error('Menu update error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'মেনু আপডেট করতে সমস্যা হয়েছে: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * পুরানো ব্যাকআপ ফাইল clean করুন
     */
    private function cleanOldBackups()
    {
        try {
            $backupFiles = glob(resource_path('data/menu_backup_*.json'));
            if (count($backupFiles) > 5) {
                $filesToDelete = array_slice($backupFiles, 5);
                foreach ($filesToDelete as $file) {
                    File::delete($file);
                }
            }
        } catch (\Exception $e) {
            Log::warning('Failed to clean old backups: ' . $e->getMessage());
        }
    }

    /**
     * ✅ সব সাপোর্টেড সেকশন
     */
    private function getSupportedSections()
    {
        return [
            'topbar_left',
            'topbar_right',
            'desktop_nav',
            'mobile_nav',
            'footer_quick_links',
            'footer_customer_service',
            'footer_about',
            'footer_contact'
        ];
    }

    /**
     * নতুন মেনু আইটেম যোগ করুন
     */
    public function addItem(Request $request)
    {
        $supportedSections = $this->getSupportedSections();

        $request->validate([
            'section' => 'required|in:' . implode(',', $supportedSections),
            'name' => 'required|string|max:100',
            'url' => 'nullable|string|max:255',
            'icon' => 'nullable|string|max:50',
            'type' => 'required|in:route,url,text',
        ]);

        try {
            $menuPath = resource_path('data/menu.json');

            if (!File::exists($menuPath)) {
                $menuData = [];
                foreach ($supportedSections as $section) {
                    $menuData[$section] = [];
                }
            } else {
                $menuData = json_decode(File::get($menuPath), true);
                // নিশ্চিত করুন সব সেকশন আছে
                foreach ($supportedSections as $section) {
                    if (!isset($menuData[$section])) {
                        $menuData[$section] = [];
                    }
                }
            }

            if (!isset($menuData[$request->section])) {
                $menuData[$request->section] = [];
            }

            // ইউনিক আইডি তৈরি করুন
            $newItem = [
                'id' => 'menu_' . uniqid() . '_' . rand(1000, 9999),
                'position' => count($menuData[$request->section]) + 1,
                'created_at' => now()->toISOString()
            ];

            // ✅ ফুটার সেকশন সহ সব নেভিগেশন সেকশন
            $navSections = array_merge(
                ['desktop_nav', 'mobile_nav'],
                ['footer_quick_links', 'footer_customer_service', 'footer_about']
            );

            if (in_array($request->section, $navSections)) {
                $newItem['name'] = $request->name;
                $newItem['type'] = $request->type;
                $newItem['url'] = $request->url ?? '#';
                if ($request->icon) {
                    $newItem['icon'] = $request->icon;
                }
                // প্যারামিটার হ্যান্ডলিং
                if ($request->type === 'route' && $request->has('params') && $request->params) {
                    if (is_string($request->params)) {
                        try {
                            $newItem['params'] = json_decode($request->params, true) ?? [];
                        } catch (\Exception $e) {
                            $newItem['params'] = [];
                        }
                    } else {
                        $newItem['params'] = $request->params;
                    }
                }
            }
            // ✅ টপবার সেকশন
            elseif (in_array($request->section, ['topbar_left', 'topbar_right'])) {
                $newItem['text'] = $request->name;
                $newItem['icon'] = $request->icon ?? 'fas fa-circle';
                $newItem['type'] = $request->type;
                if ($request->type !== 'text') {
                    $newItem['url'] = $request->url ?? '#';
                }
            }
            // ✅ ফুটার কন্টাক্ট সেকশন (টেক্সট বা URL)
            elseif ($request->section === 'footer_contact') {
                $newItem['name'] = $request->name;
                $newItem['type'] = $request->type;
                if ($request->icon) {
                    $newItem['icon'] = $request->icon;
                }
                if ($request->type !== 'text') {
                    $newItem['url'] = $request->url ?? '#';
                }
            }

            $menuData[$request->section][] = $newItem;

            File::put($menuPath, json_encode($menuData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
            MenuHelper::refreshCache();

            return response()->json([
                'success' => true,
                'message' => 'মেনু আইটেম যোগ করা হয়েছে!',
                'item' => $newItem
            ]);
        } catch (\Exception $e) {
            Log::error('Add item error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'আইটেম যোগ করতে সমস্যা হয়েছে: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * মেনু আইটেম আপডেট করুন
     */
    public function updateItem(Request $request, $id)
    {
        try {
            Log::info('Update Item Called', [
                'id' => $id,
                'section' => $request->input('section'),
                'all_data' => $request->except('_token')
            ]);

            $menuPath = resource_path('data/menu.json');

            if (!File::exists($menuPath)) {
                Log::error('Menu file not found at: ' . $menuPath);
                return response()->json([
                    'success' => false,
                    'message' => 'মেনু ফাইল পাওয়া যায়নি!'
                ], 404);
            }

            // JSON ফাইল থেকে ডাটা লোড করুন
            $fileContent = File::get($menuPath);
            $menuData = json_decode($fileContent, true);

            if (!is_array($menuData)) {
                $menuData = [];
            }

            $section = $request->input('section');

            if (!$section) {
                return response()->json([
                    'success' => false,
                    'message' => 'সেকশন তথ্য দেওয়া হয়নি!'
                ], 400);
            }

            if (!isset($menuData[$section])) {
                return response()->json([
                    'success' => false,
                    'message' => 'সেকশন পাওয়া যায়নি! সেকশন: ' . $section
                ], 404);
            }

            $found = false;

            // আইটেম খুঁজে আপডেট করুন
            foreach ($menuData[$section] as $key => &$item) {
                if (isset($item['id']) && $item['id'] === $id) {

                    Log::info('Item found, updating', ['old_item' => $item]);

                    // ✅ টপবার সেকশনের জন্য
                    if (in_array($section, ['topbar_left', 'topbar_right'])) {
                        if ($request->has('name')) {
                            $item['text'] = $request->name;
                        }
                        if ($request->has('url')) {
                            $item['url'] = $request->url;
                        }
                        if ($request->has('icon')) {
                            $item['icon'] = $request->icon;
                        }
                        if ($request->has('type')) {
                            $item['type'] = $request->type;
                        }
                    }
                    // ✅ ফুটার কন্টাক্ট সেকশন
                    elseif ($section === 'footer_contact') {
                        if ($request->has('name')) {
                            $item['name'] = $request->name;
                        }
                        if ($request->has('url')) {
                            $item['url'] = $request->url;
                        }
                        if ($request->has('icon')) {
                            $item['icon'] = $request->icon;
                        }
                        if ($request->has('type')) {
                            $item['type'] = $request->type;
                        }
                    }
                    // ✅ নেভিগেশন সেকশনের জন্য (desktop_nav, mobile_nav, footer_quick_links, footer_customer_service, footer_about)
                    else {
                        if ($request->has('name')) {
                            $item['name'] = $request->name;
                        }
                        if ($request->has('url')) {
                            $item['url'] = $request->url;
                        }
                        if ($request->has('icon')) {
                            $item['icon'] = $request->icon;
                        }
                        if ($request->has('type')) {
                            $item['type'] = $request->type;
                        }

                        // প্যারামিটার হ্যান্ডলিং
                        if ($request->has('params')) {
                            $params = $request->params;
                            if (is_string($params) && !empty($params)) {
                                try {
                                    $item['params'] = json_decode($params, true) ?? [];
                                } catch (\Exception $e) {
                                    $item['params'] = [];
                                }
                            } elseif (is_array($params)) {
                                $item['params'] = $params;
                            } else {
                                $item['params'] = [];
                            }
                        }
                    }

                    $item['updated_at'] = now()->toISOString();
                    $found = true;
                    break;
                }
            }

            if (!$found) {
                Log::warning('Item not found', ['id' => $id, 'section' => $section]);
                return response()->json([
                    'success' => false,
                    'message' => 'আইটেম পাওয়া যায়নি! আইডি: ' . $id
                ], 404);
            }

            // JSON ফাইলে সেভ করুন
            $jsonContent = json_encode($menuData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            File::put($menuPath, $jsonContent);

            // ক্যাশ রিফ্রেশ করুন
            MenuHelper::refreshCache();

            Log::info('Menu updated successfully');

            return response()->json([
                'success' => true,
                'message' => 'মেনু আইটেম আপডেট করা হয়েছে!'
            ]);

        } catch (\Exception $e) {
            Log::error('Update Item Exception: ' . $e->getMessage());
            Log::error($e->getTraceAsString());

            return response()->json([
                'success' => false,
                'message' => 'আপডেট করতে সমস্যা হয়েছে: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * মেনু আইটেম ডিলিট করুন
     */
    public function deleteItem($id, Request $request)
    {
        try {
            $menuPath = resource_path('data/menu.json');

            if (!File::exists($menuPath)) {
                return response()->json([
                    'success' => false,
                    'message' => 'মেনু ফাইল পাওয়া যায়নি!'
                ], 404);
            }

            $menuData = json_decode(File::get($menuPath), true);
            $section = $request->input('section');

            if (!$section || !isset($menuData[$section])) {
                return response()->json([
                    'success' => false,
                    'message' => 'সেকশন পাওয়া যায়নি!'
                ], 404);
            }

            $found = false;
            foreach ($menuData[$section] as $key => $item) {
                if (isset($item['id']) && $item['id'] === $id) {
                    unset($menuData[$section][$key]);
                    $found = true;
                    break;
                }
            }

            if (!$found) {
                return response()->json([
                    'success' => false,
                    'message' => 'আইটেম পাওয়া যায়নি!'
                ], 404);
            }

            // রি-ইন্ডেক্স ও পজিশন আপডেট
            $menuData[$section] = array_values($menuData[$section]);
            foreach ($menuData[$section] as $index => &$item) {
                $item['position'] = $index + 1;
            }

            File::put($menuPath, json_encode($menuData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
            MenuHelper::refreshCache();

            return response()->json([
                'success' => true,
                'message' => 'মেনু আইটেম ডিলিট করা হয়েছে!'
            ]);
        } catch (\Exception $e) {
            Log::error('Delete item error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'ডিলিট করতে সমস্যা হয়েছে: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * ✅ মেনু আইটেম রি-অর্ডার করুন (আপডেটেড)
     */
    public function reorder(Request $request)
    {
        $supportedSections = $this->getSupportedSections();

        $request->validate([
            'section' => 'required|string|in:' . implode(',', $supportedSections),
            'items' => 'required|array',
        ]);

        try {
            $menuPath = resource_path('data/menu.json');

            if (!File::exists($menuPath)) {
                return response()->json([
                    'success' => false,
                    'message' => 'মেনু ফাইল পাওয়া যায়নি!'
                ], 404);
            }

            $menuData = json_decode(File::get($menuPath), true);
            $section = $request->section;

            if (!isset($menuData[$section]) || !is_array($menuData[$section])) {
                return response()->json([
                    'success' => false,
                    'message' => 'সেকশন পাওয়া যায়নি!'
                ], 404);
            }

            // দ্রুত খোঁজার জন্য বর্তমান আইটেমগুলোকে একটি ম্যাপে নিন
            $currentItems = [];
            foreach ($menuData[$section] as $item) {
                if (isset($item['id'])) {
                    $currentItems[$item['id']] = $item;
                }
            }

            $reorderedItems = [];
            foreach ($request->items as $index => $itemId) {
                if (isset($currentItems[$itemId])) {
                    $item = $currentItems[$itemId];
                    $item['position'] = $index + 1;
                    $reorderedItems[] = $item;
                }
            }

            $menuData[$section] = $reorderedItems;

            File::put($menuPath, json_encode($menuData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
            MenuHelper::refreshCache();

            return response()->json([
                'success' => true,
                'message' => 'মেনু রি-অর্ডার করা হয়েছে!',
                'section' => $section,
                'items' => $reorderedItems
            ]);

        } catch (\Exception $e) {
            Log::error('Reorder error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'রি-অর্ডার করতে সমস্যা হয়েছে: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * মেনু ক্যাশ রিফ্রেশ করুন
     */
    public function refreshCache()
    {
        try {
            MenuHelper::refreshCache();
            return response()->json([
                'success' => true,
                'message' => 'মেনু ক্যাশ রিফ্রেশ করা হয়েছে!'
            ]);
        } catch (\Exception $e) {
            Log::error('Cache refresh error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'ক্যাশ রিফ্রেশ করতে সমস্যা হয়েছে: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * মেনু প্রিভিউ
     */
    public function preview()
    {
        try {
            $menuData = MenuHelper::getMenuData();
            return view('admin.menu.preview', compact('menuData'));
        } catch (\Exception $e) {
            Log::error('Preview error: ' . $e->getMessage());
            return back()->with('error', 'প্রিভিউ লোড করতে সমস্যা হয়েছে!');
        }
    }

    /**
     * ব্যাকআপ থেকে মেনু রিস্টোর করুন
     */
    public function restoreBackup(Request $request)
    {
        $request->validate([
            'backup_file' => 'required|string'
        ]);

        try {
            $backupPath = resource_path('data/' . $request->backup_file);

            if (!File::exists($backupPath)) {
                return response()->json([
                    'success' => false,
                    'message' => 'ব্যাকআপ ফাইল পাওয়া যায়নি!'
                ], 404);
            }

            // বর্তমান মেনুর ব্যাকআপ নিন
            $menuPath = resource_path('data/menu.json');
            if (File::exists($menuPath)) {
                $backupPath2 = resource_path('data/menu_backup_before_restore_' . date('Y-m-d_H-i-s') . '.json');
                File::copy($menuPath, $backupPath2);
            }

            // ব্যাকআপ রিস্টোর করুন
            File::copy($backupPath, $menuPath);
            MenuHelper::refreshCache();

            return response()->json([
                'success' => true,
                'message' => 'ব্যাকআপ সফলভাবে রিস্টোর করা হয়েছে!'
            ]);
        } catch (\Exception $e) {
            Log::error('Restore backup error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'ব্যাকআপ রিস্টোর করতে সমস্যা হয়েছে!'
            ], 500);
        }
    }

    /**
     * ব্যাকআপ লিস্ট দেখান
     */
    public function getBackups()
    {
        try {
            $backupFiles = glob(resource_path('data/menu_backup_*.json'));
            $backups = [];

            foreach ($backupFiles as $file) {
                $backups[] = [
                    'name' => basename($file),
                    'size' => round(filesize($file) / 1024, 2) . ' KB',
                    'date' => date('Y-m-d H:i:s', filemtime($file))
                ];
            }

            return response()->json([
                'success' => true,
                'backups' => $backups
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'ব্যাকআপ লিস্ট লোড করতে সমস্যা হয়েছে!'
            ], 500);
        }
    }
}
