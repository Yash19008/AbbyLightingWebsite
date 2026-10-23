<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('collection_place_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('places_section_id')->constrained('collection_places_sections')->onDelete('cascade');
            $table->string('place_name');
            $table->string('image');
            $table->text('description')->nullable();
            $table->string('products')->nullable();
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('collection_place_items');
    }
};