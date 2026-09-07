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
            $table->dropColumn('body_finish_color_id');
            $table->dropColumn('dimming_option_id');
        });
        
        Schema::dropIfExists('body_finish_colors');
        Schema::dropIfExists('dimming_options');
    }
};
