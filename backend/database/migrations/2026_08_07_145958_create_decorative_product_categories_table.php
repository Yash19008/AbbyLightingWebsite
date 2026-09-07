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
        Schema::create('decorative_product_categories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('decorative_product_id');
            $table->unsignedBigInteger('decorative_category_id');
            $table->timestamps();

            $table->foreign('decorative_product_id', 'fk_dec_prod_cat_prod_id')
                  ->references('id')->on('decorative_products')->onDelete('cascade');
                  
            $table->foreign('decorative_category_id', 'fk_dec_prod_cat_cat_id')
                  ->references('id')->on('decorative_categories')->onDelete('cascade');
                  
            $table->unique(['decorative_product_id', 'decorative_category_id'], 'uk_dec_prod_cat');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('decorative_product_categories');
    }
};
