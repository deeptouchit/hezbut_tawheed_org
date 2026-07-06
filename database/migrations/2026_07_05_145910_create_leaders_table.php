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
        Schema::create('leaders', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('english_name');
            $table->string('slug')->unique();
            $table->string('designation');
            $table->string('category')->default('central')->comment('central, advisory, executive, regional');
            $table->string('image');
            $table->string('signature_image')->nullable()->comment('Digital signature png path');
            $table->string('speech_video_url')->nullable()->comment('YouTube embed or audio clip link');
            $table->text('quote')->nullable();
            $table->text('bio')->nullable();
            $table->string('facebook_url')->nullable();
            $table->string('twitter_url')->nullable();
            $table->string('linkedin_url')->nullable();
            $table->string('email')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_founder')->default(false);
            $table->timestamps();

            $table->index('slug', 'idx_slug');
            $table->index('is_founder', 'idx_is_founder');
            $table->index('sort_order', 'idx_sort_order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leaders');
    }
};
