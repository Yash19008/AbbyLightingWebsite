<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dec_product_spec_rows', function (Blueprint $table) {
            $table->engine = "InnoDB";
            $table->id();
            $table->foreignId('product_id')->constrained('dec_products')->cascadeOnDelete();
            $table->foreignId('size_id')->nullable()->constrained('dec_product_sizes')->nullOnDelete();
            $table->foreignId('dec_spec_attribute_id')->nullable()->constrained('dec_spec_attributes')->nullOnDelete();
            $table->enum('section', ['basic_specifications', 'dimensions']);
            $table->text('value')->nullable();
            $table->enum('value_type', ['text', 'richtext', 'chips'])->default('text');
            $table->text('note')->nullable();
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dec_product_spec_rows');
    }
};