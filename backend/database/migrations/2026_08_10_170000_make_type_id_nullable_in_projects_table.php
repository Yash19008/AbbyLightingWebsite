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
        Schema::table('projects', function (Blueprint $table) {
            // Drop the foreign key constraint first
            $table->dropForeign(['type_id']);
            
            // Make type_id nullable
            $table->integer('type_id')->unsigned()->nullable()->change();
            
            // Add type as text field
            $table->string('type', 255)->nullable()->after('type_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn('type');
            
            $table->integer('type_id')->unsigned()->nullable(false)->change();
            
            $table->foreign('type_id')->references('id')->on('project_types');
        });
    }
};
