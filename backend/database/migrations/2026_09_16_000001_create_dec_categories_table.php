<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dec_categories', function (Blueprint $table) {
            $table->engine = "InnoDB";
            $table->id();
            $table->string('name', 255);
            $table->string('slug', 255)->unique();
            $table->text('description')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->timestamps();

            $table->index('is_featured');
            $table->index('slug');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dec_categories');
    }
};