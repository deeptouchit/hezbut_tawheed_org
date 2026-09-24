<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class DivisionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $divisions = array(
            array('id' => '1','name' => 'চট্টগ্রাম'),
            array('id' => '2','name' => 'রাজশাহী'),
            array('id' => '3','name' => 'খুলনা'),
            array('id' => '4','name' => 'বরিশাল'),
            array('id' => '5','name' => 'সিলেট'),
            array('id' => '6','name' => 'ঢাকা'),
            array('id' => '7','name' => 'রংপুর'),
            array('id' => '8','name' => 'ময়মনসিংহ')
        );

        DB::table('divisions')->insertOrIgnore($divisions);
    }
}
