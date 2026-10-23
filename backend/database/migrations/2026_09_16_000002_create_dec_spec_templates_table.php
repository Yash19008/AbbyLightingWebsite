<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dec_spec_templates', function (Blueprint $table) {
            $table->engine = "InnoDB";
            $table->id();
            $table->string('name', 255);
            $table->json('structure')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dec_spec_templates');
    }
};