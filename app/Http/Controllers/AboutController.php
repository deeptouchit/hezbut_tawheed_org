<?php

namespace App\Http\Controllers;

use App\Helpers\SettingsHelper;
use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\TeamMember;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;

class AboutController extends Controller
{
    /**
     * About Us পেজ দেখান
     */
    public function index()
    {
        return redirect()->to('/about-us');
    }

    /**
     * About পেজের সব ডাটা সংগ্রহ করুন
     */
    private function getAboutData()
    {
        // ==========================================
        // ১. টিম মেম্বার (ডাটাবেস থেকে)
        // ==========================================
        $teamMembers = TeamMember::where('is_active', true)
            ->orderBy('sort_order', 'asc')
            ->orderBy('name', 'asc')
            ->get();

        // ==========================================
        // ২. টেস্টিমোনিয়াল (ডাটাবেস থেকে)
        // ==========================================
        $testimonials = Testimonial::where('is_active', true)
            ->orderBy('sort_order', 'asc')
            ->orderBy('rating', 'desc')
            ->take(8)
            ->get();

        // ==========================================
        // ৩. স্ট্যাটিস্টিক্স (ডাটাবেস থেকে)
        // ==========================================
        $stats = $this->getStats();

        // ==========================================
        // ৪. সেটিংস থেকে স্ট্যাটিক ডাটা
        // ==========================================
        $settings = new SettingsHelper();

        // Hero Section
        $aboutHero = [
            'badge'       => $settings->get('about_hero_badge', 'আমাদের সম্পর্কে'),
            'title'       => $settings->get('about_hero_title', 'হেজবুত <span>তওহীদ</span>'),
            'description' => $settings->get('about_hero_description', 'একটি অরাজনৈতিক আন্দোলন যা সমাজ সংস্কার, মানবতার কল্যাণ ও শান্তি প্রতিষ্ঠায় নিবেদিত।'),
            'features'    => explode(',', $settings->get('about_hero_features', 'অসাম্প্রদায়িকতা,মানবকল্যাণ,শান্তি প্রতিষ্ঠা')),
        ];

        // Mission Section
        $aboutMission = [
            'subtitle'            => 'আমাদের লক্ষ্য',
            'title'               => 'মিশন ও ভিশন',
            'description'         => 'আমরা যা অর্জন করতে চাই',
            'mission_title'       => $settings->get('about_mission_mission_title', 'আমাদের মিশন'),
            'mission_description' => $settings->get('about_mission_mission_desc', 'ইসলামের প্রকৃত ও বিকৃতমুক্ত আদর্শ প্রচারের মাধ্যমে সমাজে বিদ্যমান অনৈক্য ও কুসংস্কার দূর করে শান্তি ও ন্যায়বিচার প্রতিষ্ঠা করা।'),
            'mission_features'    => explode(',', $settings->get('about_mission_features', 'ধর্মীয় ঐক্য,মানবসেবা,কুসংস্কার দূরীকরণ')),
            'icon'                => 'fa-bullseye'
        ];

        // Vision Section
        $aboutVision = [
            'vision_title'       => $settings->get('about_vision_title', 'আমাদের ভিশন'),
            'vision_description' => $settings->get('about_vision_desc', 'এমন একটি সমাজ গঠন করা যেখানে প্রতিটি মানুষ ধর্মীয় বা জাতিগত সংকীর্ণতা ভুলে মানবতার বন্ধনে ঐক্যবদ্ধ থাকবে।'),
            'vision_features'    => explode(',', $settings->get('about_vision_features', 'অসাম্প্রদায়িক সমাজ,সামাজিক সাম্য,ভ্রাতৃত্ব')),
            'icon'               => 'fa-eye'
        ];

        // Values Section
        $aboutValues = [
            'subtitle'    => 'আমরা যা বিশ্বাস করি',
            'title'       => 'আমাদের মূল্যবোধ',
            'description' => 'যে মূল্যবোধ আমাদের পরিচালিত করে',
            'items'       => $this->getValuesFromSettings($settings),
        ];

        // Why Choose Section
        $aboutWhyChoose = [
            'subtitle'    => 'আমাদের বিশেষত্ব',
            'title'       => 'কেন আমাদের বেছে নেবেন?',
            'description' => 'আমরা যা অফার করি যা আমাদের আলাদা করে',
            'items'       => $this->getWhyChooseFromSettings($settings),
        ];

        // CTA Section
        $aboutCTA = [
            'title'       => $settings->get('about_cta_title', 'আজই শুরু করুন আপনার কেনাকাটা'),
            'description' => $settings->get('about_cta_desc', 'তাজা পণ্য, সেরা দাম এবং দ্রুত ডেলিভারি পেতে এখনই অর্ডার করুন'),
        ];

        // মিশন ও ভিশন (পুরনো ভ্যারিয়েবল সাপোর্টের জন্য)
        $missionData = [
            'title'       => $aboutMission['mission_title'],
            'description' => $aboutMission['mission_description'],
            'icon'        => $aboutMission['icon']
        ];

        $visionData = [
            'title'       => $aboutVision['vision_title'],
            'description' => $aboutVision['vision_description'],
            'icon'        => $aboutVision['icon']
        ];

        $values = $aboutValues['items'];
        $whyChoose = $aboutWhyChoose['items'];

        return compact(
            'teamMembers',
            'testimonials',
            'stats',
            'aboutHero',
            'aboutMission',
            'aboutVision',
            'aboutValues',
            'aboutWhyChoose',
            'aboutCTA',
            'missionData',
            'visionData',
            'values',
            'whyChoose'
        );
    }

    /**
     * স্ট্যাটিস্টিক্স ডাটা
     */
    private function getStats()
    {
        return Cache::remember('about_stats', 3600 * 24, function () {
            $postCount        = Blog::published()->count();
            $categoryCount    = BlogCategory::where('status', true)->count();
            $teamCount        = TeamMember::where('is_active', true)->count();
            $testimonialCount = Testimonial::where('is_active', true)->count();

            return [
                ['number' => number_format($postCount), 'label' => 'প্রকাশিত সংবাদ/নিবন্ধ', 'icon' => 'fa-file-alt', 'color' => '#006A4E'],
                ['number' => number_format($categoryCount), 'label' => 'সংবাদ বিভাগ', 'icon' => 'fa-folder', 'color' => '#D4AF37'],
                ['number' => number_format($teamCount), 'label' => 'পরিষদ সদস্য', 'icon' => 'fa-user-tie', 'color' => '#006A4E'],
                ['number' => number_format($testimonialCount), 'label' => 'শুভাকাঙ্ক্ষী মন্তব্য', 'icon' => 'fa-quote-left', 'color' => '#D4AF37']
            ];
        });
    }

    /**
     * Settings থেকে Values নেওয়া
     */
    private function getValuesFromSettings($settings)
    {
        $defaultValues = [
            ['icon' => 'fa-shield-alt', 'title' => 'বিশ্বাস', 'description' => 'আমরা সৎ ও স্বচ্ছ ব্যবসায় বিশ্বাস করি। প্রতিটি পণ্যের মান নিশ্চিত করি।', 'color' => '#00A65A'],
            ['icon' => 'fa-clock', 'title' => 'দ্রুত সেবা', 'description' => 'অর্ডার পাওয়ার সাথে সাথে প্রক্রিয়াকরণ ও দ্রুত ডেলিভারি আমাদের অঙ্গীকার।', 'color' => '#3b82f6'],
            ['icon' => 'fa-hand-holding-heart', 'title' => 'গ্রাহক সন্তুষ্টি', 'description' => 'গ্রাহকের সন্তুষ্টি আমাদের প্রধান লক্ষ্য। আমরা সবসময় গ্রাহকের পাশে আছি।', 'color' => '#f59e0b'],
            ['icon' => 'fa-leaf', 'title' => 'পরিবেশবান্ধব', 'description' => 'আমরা পরিবেশবান্ধব প্যাকেজিং ও টেকসই ব্যবসায় বিশ্বাস করি।', 'color' => '#8b5cf6'],
            ['icon' => 'fa-rocket', 'title' => 'ইনোভেশন', 'description' => 'প্রযুক্তি ব্যবহার করে গ্রাহকের অভিজ্ঞতাকে আরও উন্নত করা আমাদের লক্ষ্য।', 'color' => '#ef4444'],
            ['icon' => 'fa-handshake', 'title' => 'সততা', 'description' => 'আমরা সবসময় সৎ ও নৈতিক ব্যবসায় বিশ্বাস করি। গ্রাহকের সাথে সৎ সম্পর্ক রাখি।', 'color' => '#06b6d4']
        ];

        try {
            $valuesJson = $settings->get('about_values_items', '');
            if ($valuesJson) {
                $values = json_decode($valuesJson, true);
                if (is_array($values) && count($values) > 0) {
                    return $values;
                }
            }
        } catch (\Exception $e) {
            // JSON ডিকোড করতে সমস্যা হলে ডিফল্ট ব্যবহার
        }

        return $defaultValues;
    }

    /**
     * Settings থেকে Why Choose নেওয়া
     */
    private function getWhyChooseFromSettings($settings)
    {
        $defaultWhyChoose = [
            ['icon' => 'fa-truck', 'title' => 'দ্রুত ডেলিভারি', 'description' => 'অর্ডার করার ২৪ ঘন্টার মধ্যে পণ্য পৌঁছে দেওয়া হয়', 'color' => '#017e3d'],
            ['icon' => 'fa-shield-alt', 'title' => '১০০% নিরাপদ', 'description' => 'আমাদের পণ্য ১০০% নিরাপদ ও মানসম্পন্ন', 'color' => '#2563eb'],
            ['icon' => 'fa-undo-alt', 'title' => '৭ দিন রিটার্ন', 'description' => 'সন্তুষ্ট না হলে ৭ দিনের মধ্যে ফেরত দিতে পারবেন', 'color' => '#f59e0b'],
            ['icon' => 'fa-headset', 'title' => '২৪/৭ সাপোর্ট', 'description' => 'আমাদের টিম সবসময় আপনার জন্য প্রস্তুত', 'color' => '#8b5cf6'],
            ['icon' => 'fa-wallet', 'title' => 'ক্যাশ অন ডেলিভারি', 'description' => 'পণ্য হাতে পেয়ে টাকা দিতে পারেন', 'color' => '#ef4444'],
            ['icon' => 'fa-award', 'title' => 'গুণগত মান', 'description' => 'আমরা শুধু সেরা মানের পণ্য বিক্রি করি', 'color' => '#06b6d4']
        ];

        try {
            $whyJson = $settings->get('about_why_items', '');
            if ($whyJson) {
                $why = json_decode($whyJson, true);
                if (is_array($why) && count($why) > 0) {
                    return $why;
                }
            }
        } catch (\Exception $e) {
            // JSON ডিকোড করতে সমস্যা হলে ডিফল্ট ব্যবহার
        }

        return $defaultWhyChoose;
    }

    /**
     * ক্যাশ রিফ্রেশ (অ্যাডমিন কল করবে)
     */
    public function refreshCache()
    {
        Cache::forget('about_page_data');
        Cache::forget('about_stats');

        return response()->json([
            'success' => true,
            'message' => 'About পেজের ক্যাশ রিফ্রেশ করা হয়েছে!'
        ]);
    }

    /**
 * Bulk delete team members
 */
public function bulkDelete(Request $request)
{
    $validator = Validator::make($request->all(), [
        'ids'   => 'required|array',
        'ids.*' => 'required|integer|exists:team_members,id',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'message' => $validator->errors()->first()
        ], 422);
    }

    try {
        $members = TeamMember::whereIn('id', $request->ids)->get();

        foreach ($members as $member) {
            // Delete image (আপডেটেড)
            if ($member->image && file_exists(public_path($member->image))) {
                @unlink(public_path($member->image));
            }
        }

        TeamMember::whereIn('id', $request->ids)->delete();

        return response()->json([
            'success' => true,
            'message' => count($members) . ' টি টিম মেম্বার সফলভাবে মুছে ফেলা হয়েছে!'
        ]);

    } catch (\Exception $e) {
        Log::error('Bulk delete failed: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'বাল্ক ডিলিট করতে ব্যর্থ হয়েছে!'
        ], 500);
    }
}
}