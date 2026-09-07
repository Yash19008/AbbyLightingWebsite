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
        // Pivot table to link tone families with color masters
        Schema::create('collection_tone_family_colors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tone_family_id')->constrained('collection_tone_families')->onDelete('cascade');
            $table->foreignId('color_master_id')->constrained('color_masters')->onDelete('cascade');
            $table->integer('order')->default(0); // Order of colors within the family
            $table->timestamps();
            
            // Ensure no duplicate color in same family
            $table->unique(['tone_family_id', 'color_master_id'], 'tone_family_color_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('collection_tone_family_colors');
    }
};