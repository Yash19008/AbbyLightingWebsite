<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('composition_products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('composition_id');
            $table->unsignedBigInteger('product_id'); // This will reference dec_products
            $table->timestamps();

            $table->foreign('composition_id')->references('id')->on('compositions')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('dec_products')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('composition_products');
    }
};
