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
        Schema::create('join_requests', function (Blueprint $table) {
            $table->id();
            $table->string('membership_type', 50); // primary, pledge
            $table->string('name', 100);
            $table->string('join_date', 50)->nullable();
            $table->string('father_husband', 100)->nullable();
            $table->string('age', 50)->nullable();
            $table->string('occupation', 100)->nullable();
            $table->string('education', 100)->nullable();
            $table->string('phone', 25);
            $table->string('current_unit_amir', 200)->nullable();
            $table->text('present_address');
            $table->text('permanent_address')->nullable();
            $table->text('experience')->nullable();
            $table->string('how_knew', 100);
            $table->string('person_name', 100)->nullable();
            $table->string('person_phone', 25)->nullable();
            $table->string('status', 20)->default('unread'); // unread, read, approved, rejected
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('join_requests');
    }
};
