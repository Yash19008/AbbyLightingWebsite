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
        Schema::table('group_attribute_masters', function ($table) {
            $table->text('codes')->after('values')->nullable();
        });
        Schema::table('product_variants', function ($table) {
            $table->string('co_related_color_code', 255)->after('co_related_color')->nullable();
            $table->string('beam_angle_code', 255)->after('beam_angle')->nullable();
        });
    }
};
