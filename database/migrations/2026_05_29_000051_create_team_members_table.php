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
        Schema::create('team_members', function (Blueprint $table) {
            $table->id();
            
            // Basic Information
            $table->string('name', 100);
            $table->string('designation', 100)->nullable();
            $table->text('bio')->nullable();
            $table->string('image')->nullable();
            $table->string('email', 100)->nullable();
            $table->string('phone', 20)->nullable();
            
            // Social Links (JSON)
            $table->json('social_links')->nullable();
            
            // Professional Details
            $table->string('experience', 50)->nullable()->comment('Years of experience');
            $table->string('education', 200)->nullable();
            $table->string('skills')->nullable()->comment('Comma separated skills');
            $table->string('department')->nullable();
            
            // SEO & Meta
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('meta_keywords')->nullable();
            
            // Status & Order
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            
            // Timestamps
            $table->timestamps();
            
            // Indexes
            $table->index('is_active');
            $table->index('sort_order');
            $table->index('department');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('team_members');
    }
};