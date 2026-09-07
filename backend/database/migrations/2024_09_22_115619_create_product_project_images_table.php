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
        Schema::create('sub_tag_project_images', function (Blueprint $table) {
            $table->engine = "InnoDB";
            $table->increments('id')->unsigned();
            $table->integer('sub_tag_id')->unsigned();
            $table->foreign('sub_tag_id')->references('id')->on('sub_tags');
            $table->integer('project_id')->unsigned();
            $table->foreign('project_id')->references('id')->on('projects');
            $table->string('project_slug')->nullable();
            $table->integer('project_image_id')->unsigned();
            $table->foreign('project_image_id')->references('id')->on('project_images');
            $table->string('project_name');
            $table->string('project_image_name');
            $table->softDeletes()->nullable();
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
        Schema::dropIfExists('sub_tag_project_images');
    }
};
