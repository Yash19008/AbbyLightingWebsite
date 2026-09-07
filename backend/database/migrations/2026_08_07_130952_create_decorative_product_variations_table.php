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
        Schema::create('decorative_product_variations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('decorative_product_id')->constrained()->onDelete('cascade');
            $table->string('sku')->unique();
            $table->string('image')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('decorative_variation_attribute_values', function (Blueprint $table) {
            $table->id();
            
            $table->unsignedBigInteger('decorative_product_variation_id');
            $table->unsignedBigInteger('decorative_attribute_value_id');
            
            $table->foreign('decorative_product_variation_id', 'dv_attr_val_var_fk')
                  ->references('id')
                  ->on('decorative_product_variations')
                  ->onDelete('cascade');
                  
            $table->foreign('decorative_attribute_value_id', 'dv_attr_val_value_fk')
                  ->references('id')
                  ->on('decorative_attribute_values')
                  ->onDelete('cascade');
                  
            $table->timestamps();
            
            $table->unique(['decorative_product_variation_id', 'decorative_attribute_value_id'], 'dec_var_attr_val_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('decorative_variation_attribute_values');
        Schema::dropIfExists('decorative_product_variations');
    }
};
