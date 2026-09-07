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
        Schema::create('collection_hero_sections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('collection_id')->constrained('collections')->onDelete('cascade');
            
            // Hero Section Fields (matching your frontend image)
            $table->string('background_image')->nullable(); // Hero background image
            $table->string('title_prefix')->nullable(); // e.g., "The"
            $table->string('title_highlight')->nullable(); // e.g., "Symphony Collection" (italic/emphasized part)
            $table->text('description')->nullable(); // Full hero description paragraph
            
            // Breadcrumb Navigation
            $table->string('breadcrumb_parent_text')->default('Decorative'); // Parent link text
            $table->string('breadcrumb_parent_link')->default('/decorative'); // Parent link URL
            
            // Status
            $table->boolean('is_active')->default(true);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('collection_hero_sections');
    }
};
