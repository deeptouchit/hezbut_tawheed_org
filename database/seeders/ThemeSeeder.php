<?php

namespace Database\Seeders;

use App\Models\Theme;
use Illuminate\Database\Seeder;

class ThemeSeeder extends Seeder
{
    public function run(): void
    {
        $themes = [
            [
                'folder' => 'hezbut-tawheed',
                'name' => 'Hezbut Tawheed Classic',
                'version' => '1.0.0',
                'author' => 'Antigravity',
                'description' => 'A smart, professional, responsive political portal theme for Hezbut Tawheed.',
                'is_active' => true,
                'is_core' => true,
                'status' => 'activated',
                'settings' => [],
                'requires' => ['php' => '>=8.1', 'laravel' => '>=10.0']
            ],
            [
                'folder' => 'default',
                'name' => 'Default Theme',
                'version' => '1.0.0',
                'author' => 'Antigravity',
                'description' => 'A clean, modern, blue-themed default theme.',
                'is_active' => false,
                'is_core' => false,
                'status' => 'installed',
                'settings' => [
                    'colors' => [
                        'primary' => '#0284c7',
                        'primary_dark' => '#0369a1',
                        'primary_light' => '#e0f2fe'
                    ]
                ],
                'requires' => ['php' => '>=8.1', 'laravel' => '>=10.0']
            ],
            [
                'folder' => 'dark-theme',
                'name' => 'Hezbut Tawheed Dark Mode',
                'version' => '1.0.0',
                'author' => 'Antigravity',
                'description' => 'A beautiful dark-mode theme for Hezbut Tawheed.',
                'is_active' => false,
                'is_core' => false,
                'status' => 'installed',
                'settings' => [
                    'colors' => [
                        'primary' => '#10b981',
                        'primary_dark' => '#059669',
                        'primary_light' => '#064e3b'
                    ],
                    'features' => [
                        'dark_mode' => true
                    ]
                ],
                'requires' => ['php' => '>=8.1', 'laravel' => '>=10.0']
            ]
        ];

        foreach ($themes as $themeData) {
            Theme::updateOrCreate(
                ['folder' => $themeData['folder']],
                $themeData
            );
        }
    }
}
