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
        Schema::table('catalog_downloads', function (Blueprint $table) {
            if (!Schema::hasColumn('catalog_downloads', 'mobile')) {
                $table->string('mobile', 100)->nullable()->after('email');
            } else {
                $table->string('mobile', 100)->nullable()->change();
            }

            if (!Schema::hasColumn('catalog_downloads', 'catalogue_name')) {
                $table->string('catalogue_name', 255)->nullable()->after('id');
            }

            if (!Schema::hasColumn('catalog_downloads', 'catalogue_id')) {
                $table->unsignedBigInteger('catalogue_id')->nullable()->after('catalogue_name');
            }

            if (!Schema::hasColumn('catalog_downloads', 'city')) {
                $table->string('city', 255)->nullable()->after('mobile');
            }

            if (!Schema::hasColumn('catalog_downloads', 'company')) {
                $table->string('company', 255)->nullable()->after('city');
            }

            if (!Schema::hasColumn('catalog_downloads', 'role')) {
                $table->string('role', 255)->nullable()->after('company');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('catalog_downloads', function (Blueprint $table) {
            $columns = ['catalogue_name', 'catalogue_id', 'city', 'company', 'role'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('catalog_downloads', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
