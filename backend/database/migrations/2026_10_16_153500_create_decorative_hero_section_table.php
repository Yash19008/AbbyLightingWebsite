<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('decorative_hero_section', function (Blueprint $table) {
            $table->id();
            $table->string('heading')->default('Decorative');
            $table->string('styled_heading')->nullable()->default('Lights');
            $table->text('description')->nullable();
            $table->string('image_desktop')->nullable();
            $table->string('image_tablet')->nullable();
            $table->string('image_mobile')->nullable();
            $table->boolean('is_active')->default(true);
            $table->smallInteger('created_by')->nullable();
            $table->smallInteger('updated_by')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('decorative_hero_section');
    }
};
