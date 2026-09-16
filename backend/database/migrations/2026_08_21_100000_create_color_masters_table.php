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
        Schema::create('color_masters', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Color name (e.g., "Warm White", "Ocean Blue")
            $table->string('code')->unique(); // Unique code (e.g., "warm-white", "ocean-blue")
            $table->enum('type', ['solid', 'gradient'])->default('solid'); // Color type
            
            // For solid colors
            $table->string('hex_code', 7)->nullable(); // e.g., "#FFFFFF"
            
            // For gradient colors
            $table->string('gradient_start', 7)->nullable(); // Start color hex
            $table->string('gradient_end', 7)->nullable(); // End color hex
            
            // Additional metadata
            $table->string('description')->nullable(); // Optional description
            $table->integer('order')->default(0); // Display order
            $table->boolean('is_active')->default(true);
            
            $table->timestamps();
            
            $table->index('type');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('color_masters');
    }
};
