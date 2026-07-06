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
        Schema::create('testimonials', function (Blueprint $table) {
            $table->id();
            
            // Client Information
            $table->string('name', 100);
            $table->string('designation', 100)->nullable();
            $table->string('company', 100)->nullable();
            $table->string('email', 100)->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('avatar')->nullable();
            
            // Testimonial Content
            $table->text('content');
            $table->integer('rating')->default(5)->comment('1-5 stars');
            
            // Status & Order
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            
            // SEO
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('meta_keywords')->nullable();
            
            $table->timestamps();
            
            // Indexes
            $table->index('is_active');
            $table->index('sort_order');
            $table->index('rating');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('testimonials');
    }
};