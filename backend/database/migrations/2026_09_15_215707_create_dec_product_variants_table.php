<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('dec_product_variants', function (Blueprint $table) {
            $table->engine = "InnoDB";
            $table->id();
            $table->foreignId('product_id')->constrained('dec_products')->cascadeOnDelete();
            $table->string('name', 255);
            $table->string('sku', 100)->nullable();
            
            // Re-using the existing architectural `color_masters` table
            $table->foreignId('color_master_id')->nullable()->constrained('color_masters')->nullOnDelete();
            
            $table->string('size', 100)->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->string('main_image')->nullable();
            $table->string('lighton_image')->nullable();
            $table->integer('order')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('dec_product_variants');
    }
};
