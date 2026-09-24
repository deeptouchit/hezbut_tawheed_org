<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('banners', function (Blueprint $table) {
            $table->id();

            $table->string('title');
            $table->string('subtitle')->nullable();
            $table->text('description')->nullable();

            $table->string('image')->nullable();
            $table->string('mobile_image')->nullable();
            $table->string('thumbnail')->nullable();

            $table->string('link')->nullable();
            $table->string('button_text')->nullable();
            $table->string('button_color')->default('#17a549');

            $table->enum('position', ['home', 'home_top', 'home_middle', 'home_bottom', 'category', 'product', 'sidebar'])->default('home');
            $table->enum('banner_type', ['slider', 'static', 'popup'])->default('slider');
            $table->integer('sort_order')->default(0);

            $table->enum('target_type', ['all', 'guest', 'logged_in', 'new_user'])->default('all');
            $table->string('device_type')->nullable();
            $table->string('page_url')->nullable();

            $table->dateTime('start_date')->nullable();
            $table->dateTime('end_date')->nullable();

            $table->integer('click_count')->default(0);
            $table->integer('view_count')->default(0);

            $table->boolean('status')->default(true);
            $table->boolean('open_in_new_tab')->default(false);
            $table->boolean('show_close_button')->default(true);
            $table->integer('popup_delay')->default(3);

            $table->string('background_color')->nullable();
            $table->string('text_color')->nullable();
            $table->json('custom_css')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index(['position', 'status']);
            $table->index('sort_order');
            $table->index('start_date');
            $table->index('end_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('banners');
    }
};
