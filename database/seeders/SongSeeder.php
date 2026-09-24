<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Song;

class SongSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $songs = [
            [
                'title' => 'হেযবুত তওহীদ দলীয় সঙ্গীত (এসো হে মানবজাতি)',
                'audio_file' => 'uploads/songs/audio/anthem.mp3', // local placeholder
                'video_file' => null,
                'category' => 'party_anthem',
                'is_active' => true,
                'sort_order' => 1,
                'lyrics' => "এসো হে মানবজাতি এক হও!\nসত্য ও ন্যায়ের পতাকাতলে হাত মেলাও।\n\nহিংসা-বিদ্বেষ ভুলে গিয়ে আজ,\nগড়ো সুন্দর এক শান্তিময় সমাজ।\nঅনাচার আর জুলুমের বিরুদ্ধে রুখে দাঁড়াও,\nপ্রকৃত ইসলামের আলোকবর্তিকা বুকে জ্বালাও।\n\nএসো হে মানবজাতি এক হও!\nসত্য ও ন্যায়ের পতাকাতলে হাত মেলাও।",
            ],
            [
                'title' => 'ইসলামের প্রকৃত আহ্বান (জাগরণী গান)',
                'audio_file' => 'uploads/songs/audio/jagoroni.mp3',
                'video_file' => null,
                'category' => 'awakening',
                'is_active' => true,
                'sort_order' => 2,
                'lyrics' => "জেগে ওঠো আজ হে মুসলিম উম্মাহ,\nভুলে যাও সব ভেদাভেদ আর শঙ্কা।\nপ্রকৃত সত্যের বাণী ছড়িয়ে দাও দিগন্তে,\nশান্তি আর মুক্তির পথ দেখাও নিখিল বিশ্বে।\n\nমিথ্যার কালো মেঘ কেটে যাবে একদিন,\nসত্যের আলোয় উদ্ভাসিত হবে প্রতিদিন।",
            ],
            [
                'title' => 'আমার সোনার বাংলাদেশ (দেশাত্মবোধক)',
                'audio_file' => 'uploads/songs/audio/patriotic.mp3',
                'video_file' => null,
                'category' => 'national',
                'is_active' => true,
                'sort_order' => 3,
                'lyrics' => "আমার সোনার বাংলাদেশ, আমার অহংকার,\nসবুজ শ্যামল এই মাটিতে শান্তির জয়কার।\nমায়ের মুখের মিষ্টি হাসি পরাণ জুড়ায় মোর,\nএই দেশেতেই আসুক নেমে নতুন আলোর ভোর।",
            ]
        ];

        foreach ($songs as $song) {
            Song::updateOrCreate(
                ['title' => $song['title']],
                $song
            );
        }
    }
}
