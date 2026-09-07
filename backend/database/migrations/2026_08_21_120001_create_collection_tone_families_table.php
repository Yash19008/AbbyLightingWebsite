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
        Schema::create('collection_tone_families', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tones_section_id')->constrained('collection_tones_sections')->onDelete('cascade');
            $table->string('image'); // Moodboard image for the family
            $table->string('title'); // e.g., "The Earths", "The Metallics"
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('collection_tone_families');
    }
};