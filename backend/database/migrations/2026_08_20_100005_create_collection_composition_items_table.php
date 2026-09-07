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
        Schema::create('collection_composition_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('compositions_section_id')->constrained('collection_compositions_sections')->onDelete('cascade');
            $table->string('image'); // stored image path
            $table->text('description');
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
        Schema::dropIfExists('collection_composition_items');
    }
};
