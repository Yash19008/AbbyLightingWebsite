<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dec_product_colors', function (Blueprint $table) {
            $table->engine = "InnoDB";
            $table->id();
            $table->foreignId('product_id')->constrained('dec_products')->cascadeOnDelete();
            $table->foreignId('color_master_id')->nullable()->constrained('color_masters')->nullOnDelete();
            $table->string('main_image')->nullable();
            $table->string('lighton_image')->nullable();
            $table->integer('order')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dec_product_colors');
    }
};