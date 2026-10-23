<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('collection_tone_families', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tones_section_id')->constrained('collection_tones_sections')->onDelete('cascade');
            $table->string('title');
            $table->string('image')->nullable();
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('collection_tone_families');
    }
};