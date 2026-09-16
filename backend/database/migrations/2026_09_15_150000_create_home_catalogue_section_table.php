<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('home_catalogue_section', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255)->default('Find the right catalogue.')->comment('Main heading');
            $table->string('title_highlight', 100)->nullable()->default('catalogue.')->comment('Highlighted/italic part of title');
            $table->text('description')->nullable()->comment('Section description/paragraph');
            $table->string('button_text', 100)->nullable()->default('Browse the Library')->comment('CTA button text');
            $table->string('button_link', 255)->nullable()->default('/#contact')->comment('Button URL/link');
            $table->string('background_image', 255)->nullable()->comment('Background image path');
            $table->enum('is_active', ['yes', 'no'])->default('yes');
            $table->smallInteger('created_by')->nullable();
            $table->smallInteger('updated_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('home_catalogue_section');
    }
};
