<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('live_broadcasts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->enum('source_type', ['youtube', 'facebook'])->default('youtube');
            $table->string('video_id');
            $table->dateTime('schedule_time');
            $table->boolean('is_live')->default(false);
            $table->boolean('is_active')->default(true);
            $table->text('description')->nullable();
            $table->bigInteger('view_count')->unsigned()->default(0);
            $table->timestamps();

            $table->index('schedule_time', 'idx_schedule_time');
            $table->index('is_live', 'idx_is_live');
            $table->index('is_active', 'idx_is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('live_broadcasts');
    }
};
