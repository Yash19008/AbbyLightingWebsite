<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('dec_products', function (Blueprint $table) {
            $table->boolean('show_as_new_arrival')->default(false)->after('is_featured');
            $table->index('show_as_new_arrival', 'dec_products_show_as_new_arrival_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dec_products', function (Blueprint $table) {
            $table->dropIndex('dec_products_show_as_new_arrival_idx');
            $table->dropColumn('show_as_new_arrival');
        });
    }
};
