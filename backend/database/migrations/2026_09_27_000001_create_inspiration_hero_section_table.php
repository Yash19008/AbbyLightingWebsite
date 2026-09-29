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
        Schema::create('inspiration_hero_section', function (Blueprint $table) {
            $table->id();
            $table->string('title')->default('Ideas, stories & inspiration');
            $table->string('title_highlight')->nullable()->default('Insights');
            $table->string('breadcrumb_parent_text')->nullable()->default('Home');
            $table->string('breadcrumb_parent_link')->nullable()->default('/');
            $table->string('breadcrumb_current_text')->nullable()->default('Inspiration');
            $table->string('background_image')->nullable();
            $table->boolean('is_active')->default(true);
            $table->smallInteger('created_by')->nullable();
            $table->smallInteger('updated_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inspiration_hero_section');
    }
};
