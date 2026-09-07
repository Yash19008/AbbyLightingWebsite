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
        Schema::create('decorative_variation_images', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('decorative_product_variation_id');
            $table->string('image');
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->foreign('decorative_product_variation_id', 'fk_dec_var_images_var_id')
                  ->references('id')
                  ->on('decorative_product_variations')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('decorative_variation_images');
    }
};
