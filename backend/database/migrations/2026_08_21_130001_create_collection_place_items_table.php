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
        Schema::create('collection_place_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('places_section_id')->constrained('collection_places_sections')->onDelete('cascade');
            $table->string('image'); // stored image path for the room/place
            $table->string('place_name'); // e.g., "Living Room", "Bedroom", "Kitchen"
            $table->text('description'); // description of how products are used in this space
            $table->string('products'); // product names (e.g., "Symphony II · Symphony VII")
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
        Schema::dropIfExists('collection_place_items');
    }
};
