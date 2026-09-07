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
        Schema::create('product_variants', function (Blueprint $table) {
            $table->engine = "InnoDB";
            $table->increments('id')->unsigned();
            $table->integer('product_id')->unsigned();
            $table->foreign('product_id')->references('id')->on('product_masters');
            $table->string('led_fitted',255)->nullable();
            $table->string('co_related_color',255)->nullable();
            $table->string('lumens',255)->nullable();
            $table->string('efficacy',255)->nullable();
            $table->string('beam_angle',255)->nullable();
            $table->string('led_power_watts',255)->nullable();
            $table->string('system_power_watts',255)->nullable();
            $table->string('operating_voltage',255)->nullable();
            $table->string('power_factor',255)->nullable();
            $table->string('line_diagram',255)->nullable();
            $table->string('variant_name',255)->nullable();
            $table->string('photometry_file',255)->nullable();
            $table->enum('is_active', ['yes', 'no'])->default('yes');
            $table->smallInteger('created_by')->nullable();
            $table->smallInteger('updated_by')->nullable();
            $table->smallInteger('deleted_by')->nullable();
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
        Schema::dropIfExists('product_variants');
    }
};
