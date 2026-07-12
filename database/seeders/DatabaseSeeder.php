<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Core Seeders (Run in all environments)
        $this->call(RolePermissionSeeder::class);
        $this->call(DivisionSeeder::class);
        $this->call(DistrictSeeder::class);
        $this->call(UpazilaSeeder::class);
        $this->call(SettingsTableSeeder::class);
        $this->call(MarketingSettingsSeeder::class);
        $this->call(ThemeSeeder::class);

        // 2. Demo/Fake Seeders (Only run in local/testing/development environments)
        if (!app()->environment('production')) {
            $this->call(SliderSeeder::class);
            $this->call(TestimonialSeeder::class);
            $this->call(BlogCategorySeeder::class);
            $this->call(BlogSeeder::class);
            $this->call(BlogCommentSeeder::class);
            $this->call(PageSeeder::class);
            $this->call(LiveBroadcastSeeder::class);
            $this->call(SongSeeder::class);
        }
    }
}
