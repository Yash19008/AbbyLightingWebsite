<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('dec_product_related', function (Blueprint $table) {
            $table->enum('type', ['manual', 'recommended'])->default('manual')->after('related_product_id');
        });

        Schema::table('dec_products', function (Blueprint $table) {
            $table->boolean('show_family_section')->default(true)->after('is_featured');
        });
    }

    public function down()
    {
        Schema::table('dec_product_related', function (Blueprint $table) {
            $table->dropColumn('type');
        });

        Schema::table('dec_products', function (Blueprint $table) {
            $table->dropColumn('show_family_section');
        });
    }
};
