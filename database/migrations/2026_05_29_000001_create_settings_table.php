<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->enum('type', ['text', 'textarea', 'number', 'email', 'url', 'image', 'file', 'color', 'select', 'boolean', 'json'])->default('text');
            $table->string('group')->default('general');
            $table->string('label');
            $table->text('options')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_encrypted')->default(false);
            $table->text('validation_rules')->nullable();
            $table->string('placeholder')->nullable();
            $table->text('help_text')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('group');
            $table->index('key');
            $table->index('is_active');
        });
    }

    public function down()
    {
        Schema::dropIfExists('settings');
    }
};
