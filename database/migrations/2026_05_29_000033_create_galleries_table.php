<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('galleries', function (Blueprint $blueprint) {
            $blueprint->id();
            $blueprint->string('title')->nullable();
            $blueprint->string('image_path');
            $blueprint->unsignedBigInteger('blog_id')->nullable();
            $blueprint->integer('gallery_order')->default(0);
            $blueprint->boolean('is_custom')->default(false);
            $blueprint->boolean('show_on_homepage')->default(false);
            $blueprint->boolean('show_on_gallery')->default(false);
            $blueprint->integer('gallery_page_order')->default(0);
            $blueprint->timestamps();

            $blueprint->foreign('blog_id')
                ->references('id')
                ->on('blogs')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('galleries');
    }
};