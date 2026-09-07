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
        Schema::create('decorative_attributes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('decorative_attribute_values', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attribute_id')->constrained('decorative_attributes')->onDelete('cascade');
            $table->string('name');
            $table->string('slug');
            $table->string('hex_code')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            // Ensure unique slug per attribute
            $table->unique(['attribute_id', 'slug'], 'dec_attr_val_slug_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('decorative_attribute_values');
        Schema::dropIfExists('decorative_attributes');
    }
};
