<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LiveBroadcast;

class LiveBroadcastSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $broadcasts = [
            [
                'title' => 'হেযবুত তওহীদের বিশেষ জুমার খুতবা ও আলোচনা সভা',
                'source_type' => 'youtube',
                'video_id' => 'PLMj5HuQALFXfW5beEmO_HEPKRRyg46rab', // Example or placeholder YouTube ID
                'schedule_time' => now()->subHours(2),
                'is_live' => false,
                'is_active' => true,
                'description' => 'দেশের চলমান অস্থিরতা ও ধর্মীয় অপব্যাখ্যার বিরুদ্ধে হেযবুত তওহীদের মাননীয় এমাম হোসাইন মোহাম্মদ সেলিমের দিকনির্দেশনামূলক জুমার বিশেষ খুতবা ও উন্মুক্ত আলোচনা সভা।',
                'view_count' => 12450,
            ],
            [
                'title' => 'লাইভ: সমসাময়িক জাতীয় ও আন্তর্জাতিক ইস্যুতে হেযবুত তওহীদের প্রেস কনফারেন্স',
                'source_type' => 'youtube',
                'video_id' => 'PLMj5HuQALFXddiuWwd3CnUdtFz-KqByoD',
                'schedule_time' => now()->addHours(5),
                'is_live' => true,
                'is_active' => true,
                'description' => 'দেশে শান্তি প্রতিষ্ঠা এবং উগ্রবাদ ও সাম্প্রদায়িক সহিংসতার বিরুদ্ধে হেযবুত তওহীদের পক্ষ থেকে লাইভ জাতীয় প্রেস কনফারেন্স ও মিডিয়া ব্রিফিং।',
                'view_count' => 0,
            ],
            [
                'title' => 'ধর্মীয় কুসংস্কার ও উগ্রবাদের বিরুদ্ধে সামাজিক সচেতনতা সৃষ্টিতে আমাদের করণীয়',
                'source_type' => 'facebook',
                'video_id' => 'https://www.facebook.com/watch/?v=123456789',
                'schedule_time' => now()->subDays(1),
                'is_live' => false,
                'is_active' => true,
                'description' => 'ধর্মের নামে অপরাজনীতি ও উগ্রবাদের কুফল সম্পর্কে সচেতনতা বাড়াতে আয়োজিত বিশেষ সেমিনার ও ফেসবুক লাইভ আলোচনা অনুষ্ঠান।',
                'view_count' => 8920,
            ],
            [
                'title' => 'লাইভ প্রশ্ন-উত্তর পর্ব: প্রকৃত ইসলাম ও হেযবুত তওহীদের আদর্শ',
                'source_type' => 'youtube',
                'video_id' => 'PLMj5HuQALFXfMsgyHmGG9A5Az3ZKByjdn',
                'schedule_time' => now()->addDays(1),
                'is_live' => false,
                'is_active' => true,
                'description' => 'হেযবুত তওহীদের উদ্দেশ্য, আদর্শ ও প্রকৃত ইসলামের মূল শিক্ষা নিয়ে সাধারণ দর্শকদের সরাসরি প্রশ্নের উত্তর দেওয়ার বিশেষ লাইভ অনুষ্ঠান।',
                'view_count' => 0,
            ]
        ];

        foreach ($broadcasts as $broadcast) {
            LiveBroadcast::updateOrCreate(
                ['title' => $broadcast['title']],
                $broadcast
            );
        }
    }
}
