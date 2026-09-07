<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('sub_tag_mappings');
        Schema::create('sub_tag_mappings', function (Blueprint $table) {
            $table->engine = "InnoDB";
            $table->increments('id')->unsigned();
            $table->integer('sub_tag_id')->unsigned();
            $table->foreign('sub_tag_id')->references('id')->on('sub_tags');
            $table->integer('tag_id')->unsigned();
            $table->foreign('tag_id')->references('id')->on('tags');
            $table->softDeletes()->nullable();
            $table->timestamps();
        });

        DB::statement('INSERT INTO sub_tag_mappings (sub_tag_id, tag_id) 
            SELECT id, tag_id FROM sub_tags where deleted_at is null;');

        Schema::table('sub_tags', function ($table) {
            $table->dropForeign('sub_tags_tag_id_foreign');
            $table->dropColumn('tag_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sub_tag_mappings');
    }
};
