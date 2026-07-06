<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DistrictSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $districts = [
            ['id' => 1, 'division_id' => 1, 'name' => 'কুমিল্লা'],
            ['id' => 2, 'division_id' => 1, 'name' => 'ফেনী'],
            ['id' => 3, 'division_id' => 1, 'name' => 'ব্রাহ্মণবাড়িয়া'],
            ['id' => 4, 'division_id' => 1, 'name' => 'রাঙ্গামাটি'],
            ['id' => 5, 'division_id' => 1, 'name' => 'নোয়াখালী'],
            ['id' => 6, 'division_id' => 1, 'name' => 'চাঁদপুর'],
            ['id' => 7, 'division_id' => 1, 'name' => 'লক্ষ্মীপুর'],
            ['id' => 8, 'division_id' => 1, 'name' => 'চট্টগ্রাম'],
            ['id' => 9, 'division_id' => 1, 'name' => 'কক্সবাজার'],
            ['id' => 10, 'division_id' => 1, 'name' => 'খাগড়াছড়ি'],
            ['id' => 11, 'division_id' => 1, 'name' => 'বান্দরবান'],
            ['id' => 12, 'division_id' => 2, 'name' => 'সিরাজগঞ্জ'],
            ['id' => 13, 'division_id' => 2, 'name' => 'পাবনা'],
            ['id' => 14, 'division_id' => 2, 'name' => 'বগুড়া'],
            ['id' => 15, 'division_id' => 2, 'name' => 'রাজশাহী'],
            ['id' => 16, 'division_id' => 2, 'name' => 'নাটোর'],
            ['id' => 17, 'division_id' => 2, 'name' => 'জয়পুরহাট'],
            ['id' => 18, 'division_id' => 2, 'name' => 'চাঁপাইনবাবগঞ্জ'],
            ['id' => 19, 'division_id' => 2, 'name' => 'নওগাঁ'],
            ['id' => 20, 'division_id' => 3, 'name' => 'যশোর'],
            ['id' => 21, 'division_id' => 3, 'name' => 'সাতক্ষীরা'],
            ['id' => 22, 'division_id' => 3, 'name' => 'মেহেরপুর'],
            ['id' => 23, 'division_id' => 3, 'name' => 'নড়াইল'],
            ['id' => 24, 'division_id' => 3, 'name' => 'চুয়াডাঙ্গা'],
            ['id' => 25, 'division_id' => 3, 'name' => 'কুষ্টিয়া'],
            ['id' => 26, 'division_id' => 3, 'name' => 'মাগুরা'],
            ['id' => 27, 'division_id' => 3, 'name' => 'খুলনা'],
            ['id' => 28, 'division_id' => 3, 'name' => 'বাগেরহাট'],
            ['id' => 29, 'division_id' => 3, 'name' => 'ঝিনাইদহ'],
            ['id' => 30, 'division_id' => 4, 'name' => 'ঝালকাঠি'],
            ['id' => 31, 'division_id' => 4, 'name' => 'পটুয়াখালী'],
            ['id' => 32, 'division_id' => 4, 'name' => 'পিরোজপুর'],
            ['id' => 33, 'division_id' => 4, 'name' => 'বরিশাল'],
            ['id' => 34, 'division_id' => 4, 'name' => 'ভোলা'],
            ['id' => 35, 'division_id' => 4, 'name' => 'বরগুনা'],
            ['id' => 36, 'division_id' => 5, 'name' => 'সিলেট'],
            ['id' => 37, 'division_id' => 5, 'name' => 'মৌলভীবাজার'],
            ['id' => 38, 'division_id' => 5, 'name' => 'হবিগঞ্জ'],
            ['id' => 39, 'division_id' => 5, 'name' => 'সুনামগঞ্জ'],
            ['id' => 40, 'division_id' => 6, 'name' => 'নরসিংদী'],
            ['id' => 41, 'division_id' => 6, 'name' => 'গাজীপুর'],
            ['id' => 42, 'division_id' => 6, 'name' => 'শরীয়তপুর'],
            ['id' => 43, 'division_id' => 6, 'name' => 'নারায়ণগঞ্জ'],
            ['id' => 44, 'division_id' => 6, 'name' => 'টাঙ্গাইল'],
            ['id' => 45, 'division_id' => 6, 'name' => 'কিশোরগঞ্জ'],
            ['id' => 46, 'division_id' => 6, 'name' => 'মানিকগঞ্জ'],
            ['id' => 47, 'division_id' => 6, 'name' => 'ঢাকা'],
            ['id' => 48, 'division_id' => 6, 'name' => 'মুন্সিগঞ্জ'],
            ['id' => 49, 'division_id' => 6, 'name' => 'রাজবাড়ী'],
            ['id' => 50, 'division_id' => 6, 'name' => 'মাদারীপুর'],
            ['id' => 51, 'division_id' => 6, 'name' => 'গোপালগঞ্জ'],
            ['id' => 52, 'division_id' => 6, 'name' => 'ফরিদপুর'],
            ['id' => 53, 'division_id' => 7, 'name' => 'পঞ্চগড়'],
            ['id' => 54, 'division_id' => 7, 'name' => 'দিনাজপুর'],
            ['id' => 55, 'division_id' => 7, 'name' => 'লালমনিরহাট'],
            ['id' => 56, 'division_id' => 7, 'name' => 'নীলফামারী'],
            ['id' => 57, 'division_id' => 7, 'name' => 'গাইবান্ধা'],
            ['id' => 58, 'division_id' => 7, 'name' => 'ঠাকুরগাঁও'],
            ['id' => 59, 'division_id' => 7, 'name' => 'রংপুর'],
            ['id' => 60, 'division_id' => 7, 'name' => 'কুড়িগ্রাম'],
            ['id' => 61, 'division_id' => 8, 'name' => 'শেরপুর'],
            ['id' => 62, 'division_id' => 8, 'name' => 'ময়মনসিংহ'],
            ['id' => 63, 'division_id' => 8, 'name' => 'জামালপুর'],
            ['id' => 64, 'division_id' => 8, 'name' => 'নেত্রকোণা'],
        ];

        DB::table('districts')->insert($districts);
    }
}
