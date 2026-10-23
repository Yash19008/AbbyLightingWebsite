<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('composition_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('composition_id')->constrained('compositions')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('dec_products')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('composition_products');
    }
};