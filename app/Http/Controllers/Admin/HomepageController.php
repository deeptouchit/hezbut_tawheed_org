<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class HomepageController extends Controller
{
    /**
     * Display the Homepage Builder interface.
     */
    public function index()
    {
        // Fetch homepage settings
        $layoutSetting = Setting::where('key', 'homepage_sections_layout')->first();
        $contentSetting = Setting::where('key', 'homepage_sections_content')->first();
        $cssSetting = Setting::where('key', 'homepage_custom_css')->first();

        // Fallback default configurations if database doesn't have them
        $sections = $layoutSetting ? json_decode($layoutSetting->value, true) : [];
        $content = $contentSetting ? json_decode($contentSetting->value, true) : [];
        $customCss = $cssSetting ? $cssSetting->value : '';

        // Sort sections by their active order
        usort($sections, function ($a, $b) {
            return ($a['order'] ?? 99) <=> ($b['order'] ?? 99);
        });

        return view('admin.homepage.index', compact('sections', 'content', 'customCss'));
    }

    /**
     * Update the homepage settings (Layout, Spacing, Backgrounds, Text, Images, Custom CSS).
     */
    public function update(Request $request)
    {
        // 1. Process Layout Settings (Order, Visibility, Style, Spacing)
        $layoutInput = $request->input('layout', []);
        $layoutSetting = Setting::where('key', 'homepage_sections_layout')->first();
        $existingLayout = $layoutSetting ? json_decode($layoutSetting->value, true) : [];
        
        $finalLayout = [];
        $layoutFiles = $request->file('layout', []);

        foreach ($layoutInput as $sectionId => $data) {
            // Check if this section is deleted
            if (filter_var($data['is_deleted'] ?? false, FILTER_VALIDATE_BOOLEAN)) {
                // If it was a default section and had background images, delete them
                $existingSec = collect($existingLayout)->firstWhere('id', $sectionId);
                $bgImage = $existingSec['bg_image'] ?? '';
                if ($bgImage && file_exists(public_path($bgImage))) {
                    @unlink(public_path($bgImage));
                }
                continue;
            }

            // Fetch existing data for name & background image fallback
            $existingSec = collect($existingLayout)->firstWhere('id', $sectionId);
            $bgImage = $existingSec['bg_image'] ?? '';
            $sectionName = $data['name'] ?? $existingSec['name'] ?? $sectionId;

            // Handle Section Background Image Upload via direct array check
            if (isset($layoutFiles[$sectionId]['bg_image'])) {
                $file = $layoutFiles[$sectionId]['bg_image'];
                $fileName = 'section_bg_' . $sectionId . '_' . time() . '.' . $file->getClientOriginalExtension();
                
                $uploadDir = public_path('uploads/homepage');
                if (!file_exists($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                
                // Delete old file if exists
                if ($bgImage && file_exists(public_path($bgImage))) {
                    @unlink(public_path($bgImage));
                }

                $file->move($uploadDir, $fileName);
                $bgImage = 'uploads/homepage/' . $fileName;
            } elseif ($request->input("layout.{$sectionId}.remove_bg_image") == '1') {
                if ($bgImage && file_exists(public_path($bgImage))) {
                    @unlink(public_path($bgImage));
                }
                $bgImage = '';
            }

            $finalLayout[] = [
                'id' => $sectionId,
                'name' => $sectionName,
                'is_active' => filter_var($data['is_active'] ?? false, FILTER_VALIDATE_BOOLEAN),
                'order' => intval($data['order'] ?? 99),
                'bg_color' => $data['bg_color'] ?? '#ffffff',
                'bg_image' => $bgImage,
                'padding_top' => intval($data['padding_top'] ?? 0),
                'padding_bottom' => intval($data['padding_bottom'] ?? 0),
                'margin_top' => intval($data['margin_top'] ?? 0),
                'margin_bottom' => intval($data['margin_bottom'] ?? 0),
            ];
        }

        // Save layout settings
        if (!$layoutSetting) {
            $layoutSetting = new Setting(['key' => 'homepage_sections_layout', 'label' => 'হোমপেজ সেকশন লেআউট', 'group' => 'homepage', 'type' => 'json']);
        }
        $layoutSetting->value = json_encode($finalLayout, JSON_UNESCAPED_UNICODE);
        $layoutSetting->save();

        // 2. Process Section Content Settings
        $contentInput = $request->input('content', []);
        $contentSetting = Setting::where('key', 'homepage_sections_content')->first();
        $existingContent = $contentSetting ? json_decode($contentSetting->value, true) : [];

        $finalContent = $existingContent;
        $contentFiles = $request->file('content', []);

        // Process image uploads inside content keys (e.g. founder_image, emam_image)
        foreach ($contentInput as $sectionId => $fields) {
            if (!isset($finalContent[$sectionId])) {
                $finalContent[$sectionId] = [];
            }
            foreach ($fields as $key => $val) {
                // If it is an image removal flag
                if (str_ends_with($key, '_remove_image') && $val == '1') {
                    $originalKey = str_replace('_remove_image', '', $key);
                    $oldImage = $finalContent[$sectionId][$originalKey] ?? '';
                    if ($oldImage && !str_starts_with($oldImage, 'http') && file_exists(public_path($oldImage))) {
                        @unlink(public_path($oldImage));
                    }
                    $finalContent[$sectionId][$originalKey] = '';
                    continue;
                }

                $finalContent[$sectionId][$key] = $val;
            }

            // Upload files for specific fields via direct array check
            $uploadDir = public_path('uploads/homepage');
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            // Handle Founder Image
            if (isset($contentFiles[$sectionId]['founder_image'])) {
                $file = $contentFiles[$sectionId]['founder_image'];
                $fileName = 'founder_' . time() . '.' . $file->getClientOriginalExtension();
                
                $oldImg = $finalContent[$sectionId]['founder_image'] ?? '';
                if ($oldImg && !str_starts_with($oldImg, 'http') && file_exists(public_path($oldImg))) {
                    @unlink(public_path($oldImg));
                }

                $file->move($uploadDir, $fileName);
                $finalContent[$sectionId]['founder_image'] = 'uploads/homepage/' . $fileName;
            }

            // Handle Emam Image
            if (isset($contentFiles[$sectionId]['emam_image'])) {
                $file = $contentFiles[$sectionId]['emam_image'];
                $fileName = 'emam_' . time() . '.' . $file->getClientOriginalExtension();

                $oldImg = $finalContent[$sectionId]['emam_image'] ?? '';
                if ($oldImg && !str_starts_with($oldImg, 'http') && file_exists(public_path($oldImg))) {
                    @unlink(public_path($oldImg));
                }

                $file->move($uploadDir, $fileName);
                $finalContent[$sectionId]['emam_image'] = 'uploads/homepage/' . $fileName;
            }
        }

        if (!$contentSetting) {
            $contentSetting = new Setting(['key' => 'homepage_sections_content', 'label' => 'হোমপেজ সেকশন কন্টেন্ট', 'group' => 'homepage', 'type' => 'json']);
        }
        $contentSetting->value = json_encode($finalContent, JSON_UNESCAPED_UNICODE);
        $contentSetting->save();

        // 3. Process Custom CSS
        $cssInput = $request->input('custom_css', '');
        $cssSetting = Setting::where('key', 'homepage_custom_css')->first();
        if (!$cssSetting) {
            $cssSetting = new Setting(['key' => 'homepage_custom_css', 'label' => 'হোমপেজ কাস্টম সিএসএস', 'group' => 'homepage', 'type' => 'textarea']);
        }
        $cssSetting->value = $cssInput;
        $cssSetting->save();

        // Clear View & Cache
        \Illuminate\Support\Facades\Artisan::call('view:clear');
        \Illuminate\Support\Facades\Artisan::call('cache:clear');

        return redirect()->route('admin.homepage-builder.index')->with('success', 'হোমপেজ লেআউট ও কন্টেন্ট সফলভাবে সংরক্ষণ করা হয়েছে!');
    }
}
