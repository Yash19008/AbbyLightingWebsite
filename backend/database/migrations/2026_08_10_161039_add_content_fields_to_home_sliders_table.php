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
        Schema::table('home_sliders', function (Blueprint $table) {
            $table->string('heading')->nullable()->after('path');
            $table->text('description')->nullable()->after('heading');
            $table->string('button_text')->nullable()->after('description');
            $table->string('button_link')->nullable()->after('button_text');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('home_sliders', function (Blueprint $table) {
            $table->dropColumn(['heading', 'description', 'button_text', 'button_link']);
        });
    }
};
