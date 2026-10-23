<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('compositions', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->string('kicker')->nullable();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->string('image')->nullable();
            $table->boolean('is_showcase')->default(0);
            $table->timestamps();

            $table->foreign('category_id')->references('id')->on('composition_categories')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('compositions');
    }
};