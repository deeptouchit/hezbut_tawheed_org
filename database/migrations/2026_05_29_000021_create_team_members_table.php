<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('team_members', function (Blueprint $table) {
            $table->id();

            $table->string('name', 100);
            $table->string('designation', 100)->nullable();
            $table->text('bio')->nullable();
            $table->string('image')->nullable();
            $table->string('email', 100)->nullable();
            $table->string('phone', 20)->nullable();

            $table->json('social_links')->nullable();

            $table->string('experience', 50)->nullable();
            $table->string('education', 200)->nullable();
            $table->string('skills')->nullable();
            $table->string('department')->nullable();

            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('meta_keywords')->nullable();

            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);

            $table->timestamps();

            $table->index('is_active');
            $table->index('sort_order');
            $table->index('department');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('team_members');
    }
};
