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
        Schema::table('suggestions', function (Blueprint $table) {
            if (!Schema::hasColumn('suggestions', 'allow_publish')) {
                $table->boolean('allow_publish')->default(false)->after('message');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('suggestions', function (Blueprint $table) {
            if (Schema::hasColumn('suggestions', 'allow_publish')) {
                $table->dropColumn('allow_publish');
            }
        });
    }
};
