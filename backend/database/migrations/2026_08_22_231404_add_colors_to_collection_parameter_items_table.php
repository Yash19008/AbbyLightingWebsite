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
        Schema::table('collection_parameter_items', function (Blueprint $table) {
            $table->string('bg_color')->nullable()->after('description');
            $table->string('hover_bg_color')->nullable()->after('bg_color');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('collection_parameter_items', function (Blueprint $table) {
            $table->dropColumn(['bg_color', 'hover_bg_color']);
        });
    }
};
