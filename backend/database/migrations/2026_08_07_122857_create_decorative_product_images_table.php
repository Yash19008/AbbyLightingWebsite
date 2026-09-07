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
        Schema::create('decorative_product_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('decorative_product_id');
            $table->foreign('decorative_product_id', 'fk_dp_images_dp_id')->references('id')->on('decorative_products')->onDelete('cascade');
            $table->string('image');
            $table->string('type')->default('GALLERY');
            $table->integer('sort_order')->default(0);
            $table->string('alt_text')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('decorative_product_images');
    }
};
