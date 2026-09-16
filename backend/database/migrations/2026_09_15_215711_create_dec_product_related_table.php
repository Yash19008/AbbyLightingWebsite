<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('dec_product_related', function (Blueprint $table) {
            $table->engine = "InnoDB";
            $table->id();
            $table->foreignId('product_id')->constrained('dec_products')->cascadeOnDelete();
            $table->foreignId('related_product_id')->constrained('dec_products')->cascadeOnDelete();
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('dec_product_related');
    }
};
