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
            $table->text('values')->after('attribute_name')->nullable();
        });
    }
};
