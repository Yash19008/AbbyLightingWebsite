<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('collection_catalogue_sections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('collection_id')->constrained()->onDelete('cascade');
            $table->string('background_image', 255)->nullable();
            $table->string('title', 255)->default('See the whole');
            $table->string('title_highlight', 100)->nullable()->default('collection.');
            $table->string('button_text', 100)->nullable()->default('Download catalogue');
            $table->string('button_link', 255)->nullable()->default('/catalogues');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('collection_catalogue_sections');
    }
};
