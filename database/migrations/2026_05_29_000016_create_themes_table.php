<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('themes', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('folder')->unique();
            $table->string('version')->default('1.0.0');
            $table->string('author')->nullable();
            $table->text('description')->nullable();

            $table->string('preview_image')->nullable();
            $table->string('screenshot')->nullable();

            $table->boolean('is_active')->default(false);
            $table->boolean('is_core')->default(false);
            $table->enum('status', ['installed', 'activated', 'deactivated'])->default('installed');

            $table->json('settings')->nullable();
            $table->json('requires')->nullable();

            $table->timestamps();

            $table->index('is_active');
            $table->index('status');
            $table->index('folder');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('themes');
    }
};
