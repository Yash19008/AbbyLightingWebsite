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
        Schema::create('decorative_product_specifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('section_id');
            $table->foreign('section_id', 'fk_dp_specs_sec_id')->references('id')->on('decorative_product_spec_sections')->onDelete('cascade');
            $table->string('label');
            $table->string('value');
            $table->integer('display_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('decorative_product_specifications');
    }
};
