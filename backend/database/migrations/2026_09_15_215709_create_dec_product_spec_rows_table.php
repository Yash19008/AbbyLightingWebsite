<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('dec_product_spec_rows', function (Blueprint $table) {
            $table->engine = "InnoDB";
            $table->id();
            $table->foreignId('variant_id')->constrained('dec_product_variants')->cascadeOnDelete();
            $table->enum('section', ['basic_specifications', 'dimensions']);
            $table->string('label', 255);
            $table->text('value')->nullable();
            $table->enum('value_type', ['text', 'richtext'])->default('text');
            $table->text('note')->nullable();
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('dec_product_spec_rows');
    }
};
