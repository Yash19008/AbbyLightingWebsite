<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('collection_tone_family_colors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tone_family_id')->constrained('collection_tone_families')->onDelete('cascade');
            $table->foreignId('color_master_id')->constrained('color_masters')->onDelete('cascade');
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('collection_tone_family_colors');
    }
};