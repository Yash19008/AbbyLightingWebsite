<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Truncate first to avoid foreign key constraint errors on existing data
        \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        \Illuminate\Support\Facades\DB::table('decorative_product_specifications')->truncate();
        \Illuminate\Support\Facades\DB::table('decorative_product_spec_sections')->truncate();
        \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        Schema::table('decorative_product_spec_sections', function (Blueprint $table) {
            // Drop existing product FK
            $table->dropForeign('fk_dp_spec_sec_dp_id');
            $table->dropColumn('decorative_product_id');

            // Add variation FK instead
            $table->unsignedBigInteger('decorative_product_variation_id')->after('id');
            $table->foreign('decorative_product_variation_id', 'fk_dp_spec_sec_var_id')
                  ->references('id')
                  ->on('decorative_product_variations')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('decorative_product_spec_sections', function (Blueprint $table) {
            $table->dropForeign('fk_dp_spec_sec_var_id');
            $table->dropColumn('decorative_product_variation_id');

            $table->unsignedBigInteger('decorative_product_id')->after('id');
            $table->foreign('decorative_product_id', 'fk_dp_spec_sec_dp_id')
                  ->references('id')
                  ->on('decorative_products')
                  ->onDelete('cascade');
        });
    }
};
