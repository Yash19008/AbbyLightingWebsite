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
        Schema::create('decorative_product_attributes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('decorative_product_id')->constrained()->onDelete('cascade');
            $table->foreignId('decorative_attribute_id')->constrained()->onDelete('cascade');
            $table->boolean('is_variation')->default(false);
            $table->integer('display_order')->default(0);
            $table->timestamps();
            
            // A product can only have a specific attribute assigned once
            $table->unique(['decorative_product_id', 'decorative_attribute_id'], 'dec_prod_attr_unique');
        });

        Schema::create('decorative_product_attribute_values', function (Blueprint $table) {
            $table->id();
            
            $table->unsignedBigInteger('decorative_product_attribute_id');
            $table->unsignedBigInteger('decorative_attribute_value_id');
            
            $table->foreign('decorative_product_attribute_id', 'dp_attr_val_parent_fk')
                  ->references('id')
                  ->on('decorative_product_attributes')
                  ->onDelete('cascade');
                  
            $table->foreign('decorative_attribute_value_id', 'dp_attr_val_value_fk')
                  ->references('id')
                  ->on('decorative_attribute_values')
                  ->onDelete('cascade');
                  
            $table->timestamps();
            
            $table->unique(['decorative_product_attribute_id', 'decorative_attribute_value_id'], 'dec_prod_attr_val_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('decorative_product_attribute_values');
        Schema::dropIfExists('decorative_product_attributes');
    }
};
