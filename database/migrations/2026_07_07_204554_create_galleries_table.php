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
        Schema::create('galleries', function (Blueprint $blueprint) {
            $blueprint->id();
            $blueprint->string('title')->nullable();
            $blueprint->string('image_path');
            $blueprint->unsignedBigInteger('blog_id')->nullable();
            $blueprint->integer('gallery_order')->default(0);
            $blueprint->boolean('is_active')->default(false);
            $blueprint->boolean('is_custom')->default(false);
            $blueprint->timestamps();

            // Foreign key relation pointing to blogs table
            $blueprint->foreign('blog_id')
                ->references('id')
                ->on('blogs')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('galleries');
    }
};
