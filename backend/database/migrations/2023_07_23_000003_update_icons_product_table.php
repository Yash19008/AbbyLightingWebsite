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
        Schema::table('product_masters', function ($table) {
            $table->dropColumn('icon_id');
            $table->text('optional_icons')->nullable();
            $table->text('icons')->nullable();
        });
    }
};
