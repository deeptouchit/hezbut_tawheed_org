<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('leaders', function (Blueprint $blueprint) {
            $blueprint->unsignedBigInteger('parent_id')->nullable()->after('id');
            
            $blueprint->foreign('parent_id')
                      ->references('id')
                      ->on('leaders')
                      ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leaders', function (Blueprint $blueprint) {
            $blueprint->dropForeign(['parent_id']);
            $blueprint->dropColumn('parent_id');
        });
    }
};
