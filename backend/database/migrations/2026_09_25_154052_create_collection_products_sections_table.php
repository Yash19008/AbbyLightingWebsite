<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('collection_products_sections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('collection_id')->constrained()->onDelete('cascade');
            $table->string('heading', 255)->nullable();
            $table->text('subtitle')->nullable();
            $table->string('view_more_text', 100)->default('View all');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('collection_products_sections');
    }
};
