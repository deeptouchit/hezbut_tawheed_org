<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->command->info('Starting database seeding...');
        $this->command->info('================================');

        if (app()->environment() !== 'production') {
            $this->command->info('Cleaning up existing demo data...');
            \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();
            
            $tables = [
                'blogs', 'blog_categories', 'testimonials', 'team_members', 'sliders',
                'settings', 'upazilas', 'districts', 'divisions', 'themes'
            ];

            foreach ($tables as $table) {
                if (\Illuminate\Support\Facades\Schema::hasTable($table)) {
                    \Illuminate\Support\Facades\DB::table($table)->truncate();
                }
            }
            
            \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();
            $this->command->info('✓ Cleaned up existing demo data successfully!');
        }

        // Step 1: Create roles and permissions
        $this->command->info('Step 1: Seeding roles and permissions...');
        $this->call(RolePermissionSeeder::class);

        // Step 2: Divisions, Districts, Upazilas
        $this->command->info('Step 2: Seeding divisions...');
        $this->call(DivisionSeeder::class);

        $this->command->info('Step 3: Seeding districts...');
        $this->call(DistrictSeeder::class);

        $this->command->info('Step 4: Seeding upazilas...');
        $this->call(UpazilaSeeder::class);

        // Step 5: Seeding settings...
        $this->command->info('Step 5: Seeding settings...');
        $this->call(SettingsTableSeeder::class);

        // Step 6: Seeding Sliders, Team, Testimonials
        $this->command->info('Step 6: Seeding sliders...');
        $this->call(SliderSeeder::class);

        $this->command->info('Step 7: Seeding team members...');
        $this->call(TeamMemberSeeder::class);

        $this->command->info('Step 8: Seeding testimonials...');
        $this->call(TestimonialSeeder::class);

        // Step 9: Seeding Blogs & Blog Categories
        $this->command->info('Step 9: Seeding blog categories...');
        $this->call(BlogCategorySeeder::class);

        $this->command->info('Step 10: Seeding blogs...');
        $this->call(BlogSeeder::class);

        // Step 11: Seeding Theme
        $this->command->info('Step 11: Seeding themes...');
        $this->call(ThemeSeeder::class);

        $this->command->info('================================');
        $this->command->info('Database seeding completed!');
    }
}
