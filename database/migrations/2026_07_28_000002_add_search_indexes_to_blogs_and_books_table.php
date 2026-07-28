<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        try {
            Schema::table('blogs', function (Blueprint $table) {
                $table->index('category_id', 'blogs_category_id_index');
            });
        } catch (\Exception $e) {}

        try {
            Schema::table('blogs', function (Blueprint $table) {
                $table->index('views', 'blogs_views_index');
            });
        } catch (\Exception $e) {}

        try {
            Schema::table('blogs', function (Blueprint $table) {
                $table->index(['category_id', 'published_at'], 'blogs_cat_pub_index');
            });
        } catch (\Exception $e) {}

        try {
            Schema::table('books', function (Blueprint $table) {
                $table->index('writer', 'books_writer_index');
            });
        } catch (\Exception $e) {}

        try {
            Schema::table('books', function (Blueprint $table) {
                $table->index('category_id', 'books_category_id_index');
            });
        } catch (\Exception $e) {}

        // Add Fulltext Indexes if supported by MySQL engine
        try {
            DB::statement('ALTER TABLE blogs ADD FULLTEXT INDEX blogs_fulltext_search (title, short_description, content, meta_keywords)');
        } catch (\Exception $e) {}

        try {
            DB::statement('ALTER TABLE books ADD FULLTEXT INDEX books_fulltext_search (title, writer, description)');
        } catch (\Exception $e) {}
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        try {
            Schema::table('blogs', function (Blueprint $table) {
                $table->dropIndex('blogs_category_id_index');
                $table->dropIndex('blogs_views_index');
                $table->dropIndex('blogs_cat_pub_index');
            });
        } catch (\Exception $e) {}

        try {
            Schema::table('books', function (Blueprint $table) {
                $table->dropIndex('books_writer_index');
                $table->dropIndex('books_category_id_index');
            });
        } catch (\Exception $e) {}

        try {
            DB::statement('ALTER TABLE blogs DROP INDEX blogs_fulltext_search');
            DB::statement('ALTER TABLE books DROP INDEX books_fulltext_search');
        } catch (\Exception $e) {}
    }
};
