<?php

use Illuminate\Database\Migrations\Migration;
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
        Schema::table('sub_tags', function ($table) {
            $table->string('banner_image_5', 255)->after('banner_image')->nullable();
            $table->string('banner_image_4', 255)->after('banner_image')->nullable();
            $table->string('banner_image_3', 255)->after('banner_image')->nullable();
            $table->string('banner_image_2', 255)->after('banner_image')->nullable();
        });
    }
};
