<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone')->unique()->nullable();
            $table->string('password');

            $table->string('image')->nullable();

            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();

            $table->string('role')->nullable();

            $table->enum('status', [
                'active',
                'inactive',
                'banned'
            ])->default('active');

            $table->timestamp('last_login_at')->nullable();
            $table->string('last_login_ip', 45)->nullable();
            $table->string('last_login_user_agent')->nullable();

            $table->integer('failed_login_attempts')->default(0);
            $table->timestamp('locked_until')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index(['email', 'status']);
            $table->index('role');
            $table->index('phone');
            $table->index('status');
            $table->index('last_login_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
