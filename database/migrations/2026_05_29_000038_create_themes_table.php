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
        Schema::create('themes', function (Blueprint $table) {
            $table->id();

            // থিমের মৌলিক তথ্য
            $table->string('name')->comment('থিমের নাম (যেমন: নীল আধুনিক)');
            $table->string('folder')->unique()->comment('ইউনিক ফোল্ডার নাম (যেমন: blue-modern)');
            $table->string('version')->default('1.0.0')->comment('ভার্সন নম্বর');
            $table->string('author')->nullable()->comment('ডেভেলপার বা কোম্পানির নাম');
            $table->text('description')->nullable()->comment('থিমের বিবরণ');

            // থিমের ফাইল ও ইমেজ
            $table->string('preview_image')->nullable()->comment('প্রিভিউ ইমেজ পাথ');
            $table->string('screenshot')->nullable()->comment('স্ক্রিনশট পাথ');

            // স্ট্যাটাস ও সেটিংস
            $table->boolean('is_active')->default(false)->comment('একটিভ থিম (শুধু ১টি সত্য হতে পারবে)');
            $table->boolean('is_core')->default(false)->comment('কোর থিম (ডিলিট করা যাবে না)');
            $table->enum('status', ['installed', 'activated', 'deactivated'])->default('installed')->comment('থিমের বর্তমান স্ট্যাটাস');

            // বিকল্প সেটিংস (JSON)
            $table->json('settings')->nullable()->comment('থিম স্পেসিফিক সেটিংস');
            $table->json('requires')->nullable()->comment('PHP, Laravel ভার্সন রিকোয়ারমেন্ট');

            // টাইমস্ট্যাম্প
            $table->timestamps();

            // ইনডেক্স
            $table->index('is_active');
            $table->index('status');
            $table->index('folder');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('themes');
    }
};
