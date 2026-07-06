<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Slider;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;

class SliderSeeder extends Seeder
{
    public function run(): void
    {
        // Truncate existing sliders
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Slider::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Target directory for sliders
        $targetDir = public_path('uploads/sliders');
        File::ensureDirectoryExists($targetDir);

        $sliderData = [
            [
                'title' => 'হোসাইন মোহাম্মদ সেলিম',
                'sub_title' => 'মাননীয় এমাম, হেযবুত তওহীদ',
                'target_image' => 'slider_hero.webp',
                'link' => '/leadership/hossain-mohammad-selim',
                'link_text' => 'জীবনী পড়ুন',
                'button_text' => 'জীবনী পড়ুন',
                'button_link' => '/leadership/hossain-mohammad-selim',
                'button_color' => '#D4AF37',
                'sort_order' => 1,
                'alt_text' => 'হোসাইন মোহাম্মদ সেলিম',
            ],
            [
                'title' => 'উগ্রবাদ ও সন্ত্রাসবাদের বিরুদ্ধে ঐক্যবদ্ধ',
                'sub_title' => 'সমগ্র বাংলাদেশ জুড়ে জঙ্গিবাদ ও সাম্প্রদায়িকতার বিরুদ্ধে গণসচেতনতা কর্মসূচি।',
                'target_image' => 'slider_rally.webp',
                'link' => '/activities',
                'link_text' => 'আমাদের কার্যক্রম',
                'button_text' => 'আমাদের কার্যক্রম',
                'button_link' => '/activities',
                'button_color' => '#006A4E',
                'sort_order' => 2,
                'alt_text' => 'উগ্রবাদ ও সন্ত্রাসবাদের বিরুদ্ধে গণসচেতনতা',
            ],
            [
                'title' => 'শান্তি ও সত্যের বার্তা',
                'sub_title' => 'ধর্মীয় চরমপন্থা ও কুসংস্কার মুক্ত অসাম্প্রদায়িক ও প্রগতিশীল বাংলাদেশ গড়ার প্রত্যয়।',
                'target_image' => 'slider_conference.webp',
                'link' => '/declaration',
                'link_text' => 'ঘোষণাপত্র পড়ুন',
                'button_text' => 'আমাদের ঘোষণাপত্র',
                'button_link' => '/declaration',
                'button_color' => '#D4AF37',
                'sort_order' => 3,
                'alt_text' => 'হেযবুত তওহীদের ঘোষণাপত্র',
            ],
            [
                'title' => 'একটি অনন্য বিপ্লবী নেতৃত্ব',
                'sub_title' => 'যুগোপযোগী বুদ্ধিবৃত্তিক ও আদর্শিক সংগ্রামের এক অগ্রপথিক।',
                'target_image' => 'slider_biography.jpg',
                'link' => '/leadership',
                'link_text' => 'নেতৃত্ব পরিচিতি',
                'button_text' => 'নেতৃত্ব পরিচিতি',
                'button_link' => '/leadership',
                'button_color' => '#006A4E',
                'sort_order' => 4,
                'alt_text' => 'হেযবুত তওহীদের নেতৃত্ব',
            ],
            [
                'title' => 'হেযবুত তওহীদ',
                'sub_title' => 'মানবতার কল্যাণে একটি সুশৃঙ্খল ও অরাজনৈতিক আন্দোলন।',
                'target_image' => 'slider_group.png',
                'link' => '/join',
                'link_text' => 'যোগদান করুন',
                'button_text' => 'যোগদান করুন',
                'button_link' => '/join',
                'button_color' => '#006A4E',
                'sort_order' => 5,
                'alt_text' => 'হেযবুত তওহীদে যোগদান',
            ],
        ];

        foreach ($sliderData as $data) {
            // Create Slider record
            Slider::create([
                'title' => $data['title'],
                'sub_title' => $data['sub_title'],
                'image' => 'uploads/sliders/' . $data['target_image'],
                'mobile_image' => 'uploads/sliders/' . $data['target_image'],
                'link' => $data['link'],
                'link_text' => $data['link_text'],
                'button_text' => $data['button_text'],
                'button_link' => $data['button_link'],
                'button_color' => $data['button_color'],
                'sort_order' => $data['sort_order'],
                'position' => 'homepage',
                'status' => true,
                'target' => '_self',
                'alt_text' => $data['alt_text'],
            ]);

            $this->command->info("Seeded slider: {$data['title']}");
        }

        $this->command->info('Sliders seeded successfully!');
    }
}
