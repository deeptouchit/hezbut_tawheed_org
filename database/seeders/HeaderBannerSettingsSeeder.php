<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Seeder;

class HeaderBannerSettingsSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Ensure "লাইভ আপডেট" Category exists
        $category = BlogCategory::firstOrCreate(
            ['slug' => 'live-update'],
            [
                'name'             => 'লাইভ আপডেট',
                'description'      => 'জরুরি ঘটনা ও সাম্প্রতিক ঘটনাবলির লাইভ আপডেটসমূহ',
                'image'            => null,
                'meta_title'       => 'লাইভ আপডেট - হেজবুত তওহীদ',
                'meta_description' => 'জরুরি ঘটনা ও সাম্প্রতিক ঘটনাবলির সর্বশেষ তথ্য ও লাইভ আপডেট',
                'status'           => true,
                'sort_order'       => 0,
            ]
        );

        // 2. Insert or Update Header Banner Settings
        $settings = [
            [
                'key'         => 'emergency_banner_enable',
                'value'       => '1',
                'label'       => 'জরুরি টপ ব্যানার প্রদর্শন',
                'type'        => 'boolean',
                'group'       => 'header_banner',
                'placeholder' => null,
                'help_text'   => 'হেডারের শীর্ষে লাল ইমার্জেন্সি ব্যানার চালু বা বন্ধ করুন',
                'sort_order'  => 1,
                'is_active'   => true,
            ],
            [
                'key'         => 'emergency_banner_text',
                'value'       => 'Brutal Attack on Hezbut Tawheed!',
                'label'       => 'ব্যানার বার্তা / টাইটেল',
                'type'        => 'text',
                'group'       => 'header_banner',
                'placeholder' => 'যেমন: Brutal Attack on Hezbut Tawheed!',
                'help_text'   => 'হেডারের ব্যানারে প্রদর্শিত সংবাদ বা নোটিশ টেক্সট',
                'sort_order'  => 2,
                'is_active'   => true,
            ],
            [
                'key'         => 'emergency_banner_btn_text',
                'value'       => 'Live Update ...',
                'label'       => 'বাটন লেবেল',
                'type'        => 'text',
                'group'       => 'header_banner',
                'placeholder' => 'যেমন: Live Update ...',
                'help_text'   => 'ব্যানারের বাটনের টেক্সট',
                'sort_order'  => 3,
                'is_active'   => true,
            ],
            [
                'key'         => 'emergency_banner_btn_url',
                'value'       => '/articles/category/live-update',
                'label'       => 'বাটন গন্তব্য URL / পেজ লিংক',
                'type'        => 'text',
                'group'       => 'header_banner',
                'placeholder' => '/articles/category/live-update',
                'help_text'   => 'বাটনে ক্লিক করলে যে পেজে রিডাইরেক্ট হবে',
                'sort_order'  => 4,
                'is_active'   => true,
            ],
            [
                'key'         => 'emergency_banner_bg_color',
                'value'       => '#dc2626',
                'label'       => 'ব্যানার ব্যাকগ্রাউন্ড কালার',
                'type'        => 'color',
                'group'       => 'header_banner',
                'placeholder' => '#dc2626',
                'help_text'   => 'ব্যানারের ব্যাকগ্রাউন্ড কালার হেক্স কোড (ডিফল্ট: #dc2626)',
                'sort_order'  => 5,
                'is_active'   => true,
            ],
            [
                'key'         => 'emergency_banner_open_new_tab',
                'value'       => '0',
                'label'       => 'নতুন ট্যাবে ওপেন হবে?',
                'type'        => 'boolean',
                'group'       => 'header_banner',
                'placeholder' => null,
                'help_text'   => 'হ্যাঁ দিলে বাটনে ক্লিক করলে নতুন ব্রাউজার ট্যাবে লিংক খুলবে',
                'sort_order'  => 6,
                'is_active'   => true,
            ],
        ];

        foreach ($settings as $item) {
            Setting::updateOrCreate(
                ['key' => $item['key']],
                $item
            );
        }

        // 3. Seed Production-Grade Demo Blog Posts for "Live Update" Category
        $authorId = User::first()?->id ?? 1;

        $demoPosts = [
            [
                'title'             => 'হেযবুত তওহীদের ওপর বর্বর হামলার ঘটনায় কেন্দ্রীয় কার্যালয়ে জরুরি সংবাদ সম্মেলন',
                'slug'              => 'press-briefing-on-brutal-attack-hezbut-tawheed',
                'short_description' => 'সাম্প্রতিক অনাকাঙ্ক্ষিত হামলার ঘটনা এবং মিথ্যা প্রচারণার বিরুদ্ধে হেযবুত তওহীদের পক্ষ থেকে জরুরি প্রেস ব্রিফিং অনুষ্ঠিত হয়েছে।',
                'content'           => '
                    <p>আজ হেযবুত তওহীদের কেন্দ্রীয় কার্যালয়ে এক জরুরি সংবাদ সম্মেলন অনুষ্ঠিত হয়। সংবাদ সম্মেলনে হেযবুত তওহীদের কেন্দ্রীয় স্থায়ী কমিটির সদস্যবৃন্দ ও জ্যেষ্ঠ নেতৃবৃন্দ উপস্থিত ছিলেন।</p>
                    
                    <h4 class="mt-4 mb-3">সংবাদ সম্মেলনের মূল দাবি ও বিষয়বস্তু:</h4>
                    <p>সংবাদ সম্মেলনে বক্তারা বলেন, হেযবুত তওহীদ দীর্ঘ তিন দশক ধরে ধর্মান্ধতা, ধর্মব্যবসা, জঙ্গিবাদ ও সাম্প্রদায়িক উসকানির বিরুদ্ধে আদর্শিক সংগ্রাম চালিয়ে আসছে। কিছু অসাধু ও স্বার্থান্বেষী মহল সংগঠনের অগ্রযাত্রাকে ব্যাহত করতে পরিকল্পিতভাবে বিভ্রান্তি ছড়াচ্ছে এবং সহিংস হামলা চালিয়েছে।</p>
                    
                    <ul class="my-3">
                        <li>হামলায় জড়িত প্রত্যক্ষ ও পরোক্ষ নির্দেশদাতাদের অবিলম্বে গ্রেফতারের দাবি।</li>
                        <li>আইনশৃঙ্খলা বাহিনীর হস্তক্ষেপে শান্তিশৃঙ্খলা বজায় রাখার আহ্বান।</li>
                        <li>যেকোনো উসকানি বা গুজবে কান না দেওয়ার জন্য দেশবাসীকে অনুরোধ।</li>
                    </ul>
                    
                    <p>সংগঠনের কেন্দ্রীয় নেতৃত্ব ঘোষণা করেন যে, হিংসা বা প্রতিশোধের পথে না হেঁটে আইনি প্রক্রিয়ার মাধ্যমে এই অন্যায়ের বিচার সুনিশ্চিত করা হবে।</p>
                ',
                'category_id'       => $category->id,
                'author_id'         => $authorId,
                'tags'              => ['লাইভ আপডেট', 'প্রেস ব্রিফিং', 'জরুরি তথ্য', 'হেযবুত তওহীদ'],
                'meta_title'        => 'হেযবুত তওহীদের ওপর বর্বর হামলার ঘটনায় জরুরি সংবাদ সম্মেলন',
                'meta_description' => 'সাম্প্রতিক হামলার বিরুদ্ধে হেযবুত তofহীদের জরুরি প্রেস ব্রিফিং ও সর্বশেষ তথ্যাবলি।',
                'meta_keywords'     => 'লাইভ আপডেট, প্রেস ব্রিফিং, সংবাদ সম্মেলন, হেযবুত তওহীদ',
                'views'             => 1250,
                'status'            => true,
                'published_at'      => now(),
                'sort_order'        => 1,
            ],
            [
                'title'             => 'গুজব ও সামাজিক বিভ্রান্তির বিরুদ্ধে হেযবুত তওহীদের আইনি পদক্ষেপ গ্রহণ',
                'slug'              => 'legal-steps-against-rumors-and-social-propaganda',
                'short_description' => 'সামাজিক যোগাযোগ মাধ্যমে ভিত্তিহীন ও উদ্দেশ্যপ্রণোদিত উসকানিমূলক কনটেন্ট ছড়ানোর অপরাধে ডজনখানেক আইডির বিরুদ্ধে আইনি নোটিশ প্রেরণ।',
                'content'           => '
                    <p>বিভিন্ন ডিজিটাল প্ল্যাটফর্ম ও সোশ্যাল মিডিয়ায় হেযবুত তofহীদের নামে ভিত্তিহীন অপপ্রচার চালানোর অভিযোগে আইনগত পদক্ষেপ জোরদার করা হয়েছে। সংগঠনের সাইবার মনিটরিং সেলের তদন্তের ভিত্তিতে সংশ্লিষ্ট অপরাধীদের চিহ্নিত করে আইনি নোটিশ ও ডিজিটাল নিরাপত্তা আইনে অভিযোগ দায়ের করা হয়েছে।</p>
                    
                    <h4 class="mt-4 mb-3">আইনি প্রক্রিয়ার হালনাগাদ:</h4>
                    <p>হেযবুত তওহীদের আইন বিষয়ক সম্পাদক জানান, "মুক্ত চিন্তার নামে বা ধর্মীয় অনুভূতিকে ব্যবহার করে কতিপয় কন্টেন্ট ক্রিয়েটর হিংসাত্মক বক্তব্য ছড়াচ্ছে। আমরা সকল প্রমাণাদি সংকলন করে সংশ্লিষ্ট কর্তৃপক্ষ ও আইন-শৃঙ্খলা রক্ষাকারী বাহিনীর নিকট হস্তান্তর করেছি।"</p>
                    
                    <p>সকল সদস্য ও শুভাকাঙ্ক্ষীদের সত্যতা যাচাই ব্যতিরেকে কোনো অনলাইন পোস্টে বিভ্রান্ত না হওয়ার আহ্বান জানানো হয়েছে।</p>
                ',
                'category_id'       => $category->id,
                'author_id'         => $authorId,
                'tags'              => ['আইনি ব্যবস্থা', 'লাইভ আপডেট', 'সাইবার নিরাপত্তা', 'অ্যান্টি প্রপাগান্ডা'],
                'meta_title'        => 'গুজব ও বিভ্রান্তির বিরুদ্ধে হেযবুত তওহীদের আইনি পদক্ষেপ গ্রহণ',
                'meta_description' => 'সামাজিক মাধ্যমে মিথ্যা প্রচারণার বিরুদ্ধে হেযবুত তওহীদের সাইবার সেলের আইনি ব্যবস্থা গ্রহণ।',
                'meta_keywords'     => 'সাইবার সেল, আইনি পদক্ষেপ, সংবাদ, লাইভ আপডেট',
                'views'             => 980,
                'status'            => true,
                'published_at'      => now()->subHours(5),
                'sort_order'        => 2,
            ],
            [
                'title'             => 'সামাজিক সৌহার্দ্য রক্ষা ও শান্তির দাবিতে সারাদেশে স্মারকলিপি প্রদান কর্মসূচি',
                'slug'              => 'memorandum-submission-program-for-social-harmony',
                'short_description' => 'ধর্মীয় সম্প্রীতি রক্ষা এবং উসকানিদাতাদের দৃষ্টান্তমূলক শাস্তির দাবিতে বিভিন্ন জেলা প্রশাসকের কার্যালয়ে স্মারকলিপি পেশ করা হয়েছে।',
                'content'           => '
                    <p>দেশব্যাপী সামাজিক সম্প্রীতি বজায় রাখা এবং স্থানীয় পর্যায়ে উসকানি প্রতিরোধে হেযবুত তওহীদের জেলা শাখা সমূহের উদ্যোগে জেলা প্রশাসকদের নিকট স্মারকলিপি পেশ করা হয়েছে।</p>
                    
                    <h4 class="mt-4 mb-3">কর্মসূচির প্রধান দিকসমূহ:</h4>
                    <p>উক্ত স্মারকলিপিতে স্থানীয় শান্তি-শৃঙ্খলা রক্ষা ও নিরাপত্তা নিশ্চিত করার পাশাপাশি উসকানিমূলক সভা-সমাবেশের বিরুদ্ধে প্রশাসনিক ব্যবস্থা নেওয়ার বিনীত অনুরোধ করা হয়। জেলা প্রশাসকগণ ইতিবাচক আশ্বাস প্রদান করেছেন এবং যেকোনো মূল্যে সম্প্রীতি রক্ষার প্রতিশ্রুতি দিয়েছেন।</p>
                ',
                'category_id'       => $category->id,
                'author_id'         => $authorId,
                'tags'              => ['স্মারকলিপি', 'শান্তি সম্মেলন', 'লাইভ আপডেট', 'সম্প্রীতি'],
                'meta_title'        => 'সামাজিক সৌহার্দ্য রক্ষা ও শান্তির দাবিতে স্মারকলিপি প্রদান কর্মসূচি',
                'meta_description' => 'সারাদেশে জেলা প্রশাসকদের নিকট হেযবুত তওহীদের শান্তি ও নিরাপত্তার দাবিতে স্মারকলিপি পেশ।',
                'meta_keywords'     => 'স্মারকলিপি, জেলা প্রশাসক, হেযবুত তওহীদ, লাইভ আপডেট',
                'views'             => 740,
                'status'            => true,
                'published_at'      => now()->subHours(12),
                'sort_order'        => 3,
            ],
        ];

        foreach ($demoPosts as $postData) {
            Blog::updateOrCreate(
                ['slug' => $postData['slug']],
                $postData
            );
        }
    }
}
