<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dec_product_sizes', function (Blueprint $table) {
            $table->engine = "InnoDB";
            $table->id();
            $table->foreignId('product_id')->constrained('dec_products')->cascadeOnDelete();
            $table->string('label');
            $table->string('code')->nullable();
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dec_product_sizes');
    }
};