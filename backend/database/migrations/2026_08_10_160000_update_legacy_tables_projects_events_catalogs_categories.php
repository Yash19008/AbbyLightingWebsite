<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. projects: make type_id nullable if exists, add type & is_featured if missing
        Schema::table('projects', function (Blueprint $table) {
            if (Schema::hasColumn('projects', 'type_id')) {
                $hasFk = count(\Illuminate\Support\Facades\DB::select("
                    SELECT CONSTRAINT_NAME 
                    FROM information_schema.TABLE_CONSTRAINTS 
                    WHERE TABLE_SCHEMA = DATABASE() 
                      AND TABLE_NAME = 'projects' 
                      AND CONSTRAINT_TYPE = 'FOREIGN KEY'
                      AND CONSTRAINT_NAME = 'projects_type_id_foreign'
                ")) > 0;

                if ($hasFk) {
                    $table->dropForeign(['type_id']);
                }
                $table->integer('type_id')->unsigned()->nullable()->change();
            }

            if (!Schema::hasColumn('projects', 'type')) {
                $table->string('type', 255)->nullable();
            }
            if (!Schema::hasColumn('projects', 'is_featured')) {
                $table->boolean('is_featured')->default(0)->after('is_active');
            }
        });

        // 2. events: add publication source & link
        Schema::table('events', function (Blueprint $table) {
            if (!Schema::hasColumn('events', 'source')) {
                $table->string('source', 255)->after('name')->nullable();
            }
            if (!Schema::hasColumn('events', 'source_link')) {
                $table->text('source_link')->after('source')->nullable();
            }
        });

        // 3. catalog_downloads: add catalogue tracking fields
        Schema::table('catalog_downloads', function (Blueprint $table) {
            if (!Schema::hasColumn('catalog_downloads', 'catalogue_name')) {
                $table->string('catalogue_name', 255)->nullable()->after('id');
            }
            if (!Schema::hasColumn('catalog_downloads', 'catalogue_id')) {
                $table->unsignedBigInteger('catalogue_id')->nullable()->after('catalogue_name');
            }
            if (!Schema::hasColumn('catalog_downloads', 'mobile')) {
                $table->string('mobile', 100)->nullable()->after('email');
            } else {
                $table->string('mobile', 100)->nullable()->change();
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

        // 4. categories: add is_featured
        Schema::table('categories', function (Blueprint $table) {
            if (!Schema::hasColumn('categories', 'is_featured')) {
                $table->boolean('is_featured')->default(0)->after('is_active');
            }
        });
    }

    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            if (Schema::hasColumn('categories', 'is_featured')) {
                $table->dropColumn('is_featured');
            }
        });

        Schema::table('catalog_downloads', function (Blueprint $table) {
            $cols = ['catalogue_name', 'catalogue_id', 'city', 'company', 'role'];
            foreach ($cols as $col) {
                if (Schema::hasColumn('catalog_downloads', $col)) {
                    $table->dropColumn($col);
                }
            }
        });

        Schema::table('events', function (Blueprint $table) {
            if (Schema::hasColumn('events', 'source')) {
                $table->dropColumn(['source', 'source_link']);
            }
        });

        Schema::table('projects', function (Blueprint $table) {
            if (Schema::hasColumn('projects', 'type')) {
                $table->dropColumn('type');
            }
            if (Schema::hasColumn('projects', 'is_featured')) {
                $table->dropColumn('is_featured');
            }
        });
    }
};