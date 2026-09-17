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
        Schema::table('dec_categories', function (Blueprint $table) {
            $table->boolean('is_featured')->default(false)->after('description');
            $table->index('is_featured');
            $table->index('slug');
        });

        Schema::table('dec_spec_attributes', function (Blueprint $table) {
            $table->index('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dec_categories', function (Blueprint $table) {
            $table->dropIndex(['slug']);
            $table->dropIndex(['is_featured']);
            $table->dropColumn('is_featured');
        });

        Schema::table('dec_spec_attributes', function (Blueprint $table) {
            $table->dropIndex(['name']);
        });
    }
};
