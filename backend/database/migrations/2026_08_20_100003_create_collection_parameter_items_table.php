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
        Schema::create('collection_parameter_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parameters_section_id')->constrained('collection_parameters_sections')->onDelete('cascade');
            $table->string('small_text', 100)->nullable();
            $table->string('title');
            $table->text('description');
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
        Schema::dropIfExists('collection_parameter_items');
    }
};
