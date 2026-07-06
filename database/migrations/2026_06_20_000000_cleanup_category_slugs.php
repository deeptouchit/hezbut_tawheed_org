<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * বিদ্যমান সাবক্যাটাগরির স্লাগ থেকে পাথ ক্লিন করা।
     */
    public function up(): void
    {
        // Table categories does not exist in this database
    }

    /**
     * Reverse migrations.
     */
    public function down(): void
    {
        //
    }
};
