<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('sliders', function (Blueprint $table) {
            $table->string('target')->default('_self')->after('button_link');
            $table->string('alt_text')->nullable()->after('target');
        });
    }

    public function down()
    {
        Schema::table('sliders', function (Blueprint $table) {
            $table->dropColumn(['target', 'alt_text']);
        });
    }
};
