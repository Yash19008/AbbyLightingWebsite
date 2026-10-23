<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('collection_hero_sections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('collection_id')->constrained('collections')->onDelete('cascade');
            $table->string('background_image')->nullable();
            $table->string('title_prefix')->nullable();
            $table->string('title_highlight')->nullable();
            $table->text('description')->nullable();
            $table->string('breadcrumb_parent_text')->nullable();
            $table->string('breadcrumb_parent_link')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('collection_hero_sections');
    }
};