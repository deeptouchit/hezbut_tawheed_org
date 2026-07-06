<?php

namespace Database\Seeders;

use App\Models\Theme;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ThemeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Truncate existing themes
        DB::table('themes')->truncate();

        Theme::create([
            'name' => 'Hezbut Tawheed',
            'folder' => 'hezbut-tawheed',
            'version' => '1.0.0',
            'author' => 'Antigravity',
            'description' => 'A smart, professional, responsive political portal theme for Hezbut Tawheed.',
            'is_active' => true,
            'is_core' => true,
            'status' => 'activated',
            'settings' => [],
            'requires' => ['php' => '>=8.1', 'laravel' => '>=10.0']
        ]);
    }
}
