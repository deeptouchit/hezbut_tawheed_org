<?php

namespace Database\Seeders;

use App\Models\BlogCategory;
use Illuminate\Database\Seeder;

class BlogCategorySeeder extends Seeder
{
    public function run(): void
    {
        if (app()->environment('production')) {
            return;
        }

        $categories = [
            [
                'name' => 'জাতীয় সংবাদ',
                'slug' => 'national-news',
                'description' => 'দেশের রাজনৈতিক ও সামাজিক সমসাময়িক গুরুত্বপুর্ণ ঘটনাবলি নিয়ে বিশ্লেষণ ও খবর',
                'image' => null,
                'meta_title' => 'জাতীয় সংবাদ - হেজবুত তওহীদ',
                'meta_description' => 'দেশ ও বিদেশের সমসাময়িক রাজনৈতিক ও সামাজিক গুরুত্বপূর্ণ সংবাদ ও প্রতিবেদন',
                'status' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'সাংগঠনিক কার্যক্রম',
                'slug' => 'organizational-activities',
                'description' => 'হেজবুত তওহীদের বিভিন্ন শাখার সম্মেলন, সেমিনার ও কর্মসূচির বিবরণ',
                'image' => null,
                'meta_title' => 'সাংগঠনিক কার্যক্রম - হেজবুত তওহীদ',
                'meta_description' => 'হেজবুত তওহীদের সাংগঠনিক সভা, সেমিনার, সম্মেলন ও সমাজ কল্যাণমূলক কার্যক্রম',
                'status' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'ইসলামের প্রকৃত আদর্শ',
                'slug' => 'true-spirit-of-islam',
                'description' => 'বিকৃতিমুক্ত সত্য ও শান্তির ইসলামের যৌক্তিক ও সঠিক দিকনির্দেশনা',
                'image' => null,
                'meta_title' => 'ইসলামের প্রকৃত আদর্শ - হেজবুত তওহীদ',
                'meta_description' => 'ইসলামের অসাম্প্রদায়িক ও শান্তির বাণী প্রচার এবং ভ্রান্ত ধারণার অপনোদন',
                'status' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'সমাজ সংস্কার ও মানবকল্যাণ',
                'slug' => 'social-reform',
                'description' => 'সমাজে ঐক্য, কুসংস্কার দূরীকরণ ও মানবতার সেবায় আমাদের বিভিন্ন উদ্যোগ',
                'image' => null,
                'meta_title' => 'সমাজ সংস্কার ও মানবকল্যাণ - হেজবুত তওহীদ',
                'meta_description' => 'কুসংস্কারমুক্ত সমাজ গঠন এবং মানবতার সেবায় হেজবুত তওহীদের কার্যক্রম',
                'status' => true,
                'sort_order' => 4,
            ],
            [
                'name' => 'মতাদর্শ ও গবেষণা',
                'slug' => 'ideology-research',
                'description' => 'ধর্ম ও দর্শনের উপর মৌলিক গবেষণা ও আদর্শিক প্রবন্ধসমূহ',
                'image' => null,
                'meta_title' => 'মতাদর্শ ও গবেষণা - হেজবুত তওহীদ',
                'meta_description' => 'ধর্মীয় চরমপন্থা ও সংকীর্ণতার বিরুদ্ধে বুদ্ধিবৃত্তিক ও গবেষণামূলক আলোচনা',
                'status' => true,
                'sort_order' => 5,
            ],
            [
                'name' => 'প্রেস বিজ্ঞপ্তি',
                'slug' => 'press-releases',
                'description' => 'হেজবুত তওহীদের পক্ষ থেকে গণমাধ্যমে প্রেরিত আনুষ্ঠানিক বক্তব্য ও বিবৃতি',
                'image' => null,
                'meta_title' => 'প্রেস বিজ্ঞপ্তি - হেজবুত তওহীদ',
                'meta_description' => 'হেজবুত তওহীদের আনুষ্ঠানিক বিবৃতি ও গণমাধ্যম বিজ্ঞপ্তি',
                'status' => true,
                'sort_order' => 6,
            ],
        ];

        foreach ($categories as $category) {
            BlogCategory::updateOrCreate(
                ['slug' => $category['slug']],
                $category
            );
        }
    }
}