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
        Schema::table('galleries', function (Blueprint $table) {
            // Drop old is_active column
            if (Schema::hasColumn('galleries', 'is_active')) {
                $table->dropColumn('is_active');
            }

            // Add new target columns
            $table->boolean('show_on_homepage')->default(false);
            $table->boolean('show_on_gallery')->default(false);
            $table->integer('gallery_page_order')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('galleries', function (Blueprint $table) {
            $table->boolean('is_active')->default(false);
            $table->dropColumn(['show_on_homepage', 'show_on_gallery', 'gallery_page_order']);
        });
    }
};
