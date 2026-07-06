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
        Schema::create('banners', function (Blueprint $table) {
            $table->id();

            // Basic Info
            $table->string('title');
            $table->string('subtitle')->nullable();
            $table->text('description')->nullable();

            // Images
            $table->string('image')->nullable();
            $table->string('mobile_image')->nullable();
            $table->string('thumbnail')->nullable();

            // Link & Button
            $table->string('link')->nullable();
            $table->string('button_text')->nullable();
            $table->string('button_color')->default('#17a549');

            // Position & Display
            $table->enum('position', ['home', 'home_top', 'home_middle', 'home_bottom', 'category', 'product', 'sidebar'])->default('home');
            $table->enum('banner_type', ['slider', 'static', 'popup'])->default('slider');
            $table->integer('sort_order')->default(0);

            // Target & Conditions
            $table->enum('target_type', ['all', 'guest', 'logged_in', 'new_user'])->default('all');
            $table->string('device_type')->nullable(); // desktop, mobile, both
            $table->string('page_url')->nullable(); // specific page URL

            // Date Range (for time-limited banners)
            $table->dateTime('start_date')->nullable();
            $table->dateTime('end_date')->nullable();

            // Analytics
            $table->integer('click_count')->default(0);
            $table->integer('view_count')->default(0);

            // Status & Settings
            $table->boolean('status')->default(true);
            $table->boolean('open_in_new_tab')->default(false);
            $table->boolean('show_close_button')->default(true);
            $table->integer('popup_delay')->default(3); // seconds

            // Styling (optional)
            $table->string('background_color')->nullable();
            $table->string('text_color')->nullable();
            $table->json('custom_css')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Indexes for performance
            $table->index(['position', 'status']);
            $table->index('sort_order');
            $table->index('start_date');
            $table->index('end_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('banners');
    }
};
