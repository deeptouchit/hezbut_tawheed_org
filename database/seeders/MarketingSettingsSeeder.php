<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class MarketingSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Facebook Pixel ID Setting
        Setting::updateOrCreate(
            ['key' => 'facebook_pixel_id'],
            [
                'value' => '',
                'type' => 'text',
                'group' => 'seo',
                'label' => 'Facebook Pixel ID',
                'placeholder' => 'e.g. 123456789012345',
                'help_text' => 'আপনার ফেসবুক পিক্সেল আইডিটি এখানে বসান (যেমন: 123456789012345)',
                'sort_order' => 50,
                'is_active' => true
            ]
        );

        // Google Analytics Measurement ID Setting
        Setting::updateOrCreate(
            ['key' => 'google_analytics_id'],
            [
                'value' => '',
                'type' => 'text',
                'group' => 'seo',
                'label' => 'Google Analytics Measurement ID (GA4)',
                'placeholder' => 'e.g. G-XXXXXXXXXX',
                'help_text' => 'আপনার গুগল অ্যানালিটিক্স মেজারমেন্ট আইডিটি এখানে বসান (যেমন: G-XXXXXXXXXX)',
                'sort_order' => 51,
                'is_active' => true
            ]
        );
    }
}
