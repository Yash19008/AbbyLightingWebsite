<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('color_masters', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->enum('type', ['solid', 'gradient'])->default('solid');
            $table->string('hex_code', 7)->nullable();
            $table->string('gradient_start', 7)->nullable();
            $table->string('gradient_end', 7)->nullable();
            $table->string('gradient_type')->nullable();
            $table->string('gradient_direction')->nullable();
            $table->string('description')->nullable();
            $table->string('category')->nullable();
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('type');
            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('color_masters');
    }
};