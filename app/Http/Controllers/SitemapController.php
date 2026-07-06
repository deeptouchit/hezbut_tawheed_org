<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\BlogCategory;
use Illuminate\Http\Request;

class SitemapController extends Controller
{
    /**
     * সাইটম্যাপ পেজ দেখান (HTML)
     */
    public function index()
    {
        $categories = BlogCategory::where('status', true)
            ->orderBy('name')
            ->get();

        $blogs = Blog::where('status', 'published')
            ->orderBy('created_at', 'desc')
            ->take(100)
            ->get();

        $staticPages = [
            ['url' => route('home'), 'title' => 'হোম', 'priority' => '1.0', 'frequency' => 'daily'],
            ['url' => route('about'), 'title' => 'আমাদের সম্পর্কে', 'priority' => '0.8', 'frequency' => 'monthly'],
            ['url' => route('contact'), 'title' => 'যোগাযোগ', 'priority' => '0.8', 'frequency' => 'monthly'],
            ['url' => route('blog'), 'title' => 'ব্লগ', 'priority' => '0.7', 'frequency' => 'weekly'],
            ['url' => route('privacy'), 'title' => 'প্রাইভেসি পলিসি', 'priority' => '0.4', 'frequency' => 'yearly'],
            ['url' => route('terms'), 'title' => 'টার্মস এন্ড কন্ডিশন', 'priority' => '0.4', 'frequency' => 'yearly'],
        ];

        return view('theme::pages.sitemap', compact('categories', 'blogs', 'staticPages'));
    }

    /**
     * XML সাইটম্যাপ জেনারেট করুন (Google-এর জন্য)
     */
    public function xml()
    {
        // স্ট্যাটিক পেজসমূহ
        $staticPages = [
            ['loc' => route('home'), 'priority' => '1.0', 'changefreq' => 'daily'],
            ['loc' => route('about'), 'priority' => '0.8', 'changefreq' => 'monthly'],
            ['loc' => route('contact'), 'priority' => '0.8', 'changefreq' => 'monthly'],
            ['loc' => route('blog'), 'priority' => '0.7', 'changefreq' => 'weekly'],
            ['loc' => route('privacy'), 'priority' => '0.4', 'changefreq' => 'yearly'],
            ['loc' => route('terms'), 'priority' => '0.4', 'changefreq' => 'yearly'],
        ];

        // ক্যাটাগরি URL
        $categories = BlogCategory::where('status', true)
            ->select('slug', 'updated_at')
            ->get()
            ->map(function ($category) {
                return [
                    'loc' => route('blog.category', $category->slug),
                    'priority' => '0.8',
                    'changefreq' => 'weekly',
                    'lastmod' => $category->updated_at->toIso8601String()
                ];
            });

        // ব্লগ URL
        $blogs = Blog::where('status', 'published')
            ->select('slug', 'updated_at')
            ->orderBy('id', 'desc')
            ->take(1000)
            ->get()
            ->map(function ($blog) {
                return [
                    'loc' => route('blog.detail', $blog->slug),
                    'priority' => '0.6',
                    'changefreq' => 'weekly',
                    'lastmod' => $blog->updated_at->toIso8601String()
                ];
            });

        // সব URL একত্রিত করুন
        $urls = collect($staticPages)
            ->concat($categories)
            ->concat($blogs);

        // XML রেসপন্স রিটার্ন করুন
        return response()
            ->view('theme::sitemap', compact('urls'))
            ->header('Content-Type', 'text/xml');
    }

    /**
     * গুগল সার্চ কনসোলে সাইটম্যাপ সাবমিট করার জন্য
     */
    public function pingGoogle()
    {
        $sitemapUrl = url('/sitemap.xml');
        $googlePingUrl = "https://www.google.com/ping?sitemap=" . urlencode($sitemapUrl);

        try {
            $ch = curl_init($googlePingUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($httpCode == 200) {
                return response()->json(['success' => true, 'message' => 'সাইটম্যাপ গুগলে সাবমিট করা হয়েছে']);
            }
        } catch (\Exception $e) {
            // লগ করুন কিন্তু ইউজারকে দেখাবেন না
        }

        return response()->json(['success' => false, 'message' => 'সাইটম্যাপ সাবমিট করতে সমস্যা হয়েছে']);
    }
}
