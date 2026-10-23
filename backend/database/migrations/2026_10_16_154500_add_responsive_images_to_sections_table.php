<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Home Catalogue Section
        if (Schema::hasTable('home_catalogue_section')) {
            Schema::table('home_catalogue_section', function (Blueprint $table) {
                if (!Schema::hasColumn('home_catalogue_section', 'image_tablet')) {
                    $table->string('image_tablet', 255)->nullable()->after('background_image');
                }
                if (!Schema::hasColumn('home_catalogue_section', 'image_mobile')) {
                    $table->string('image_mobile', 255)->nullable()->after('image_tablet');
                }
            });
        }

        // 2. Inspiration Hero Section
        if (Schema::hasTable('inspiration_hero_section')) {
            Schema::table('inspiration_hero_section', function (Blueprint $table) {
                if (!Schema::hasColumn('inspiration_hero_section', 'image_tablet')) {
                    $table->string('image_tablet', 255)->nullable()->after('background_image');
                }
                if (!Schema::hasColumn('inspiration_hero_section', 'image_mobile')) {
                    $table->string('image_mobile', 255)->nullable()->after('image_tablet');
                }
            });
        }

        // 3. Collection Hero Sections
        if (Schema::hasTable('collection_hero_sections')) {
            Schema::table('collection_hero_sections', function (Blueprint $table) {
                if (!Schema::hasColumn('collection_hero_sections', 'image_tablet')) {
                    $table->string('image_tablet', 255)->nullable()->after('background_image');
                }
                if (!Schema::hasColumn('collection_hero_sections', 'image_mobile')) {
                    $table->string('image_mobile', 255)->nullable()->after('image_tablet');
                }
            });
        }

        // 4. Collection Catalogue Sections
        if (Schema::hasTable('collection_catalogue_sections')) {
            Schema::table('collection_catalogue_sections', function (Blueprint $table) {
                if (!Schema::hasColumn('collection_catalogue_sections', 'image_tablet')) {
                    $table->string('image_tablet', 255)->nullable()->after('background_image');
                }
                if (!Schema::hasColumn('collection_catalogue_sections', 'image_mobile')) {
                    $table->string('image_mobile', 255)->nullable()->after('image_tablet');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('home_catalogue_section')) {
            Schema::table('home_catalogue_section', function (Blueprint $table) {
                $table->dropColumn(['image_tablet', 'image_mobile']);
            });
        }

        if (Schema::hasTable('inspiration_hero_section')) {
            Schema::table('inspiration_hero_section', function (Blueprint $table) {
                $table->dropColumn(['image_tablet', 'image_mobile']);
            });
        }

        if (Schema::hasTable('collection_hero_sections')) {
            Schema::table('collection_hero_sections', function (Blueprint $table) {
                $table->dropColumn(['image_tablet', 'image_mobile']);
            });
        }

        if (Schema::hasTable('collection_catalogue_sections')) {
            Schema::table('collection_catalogue_sections', function (Blueprint $table) {
                $table->dropColumn(['image_tablet', 'image_mobile']);
            });
        }
    }
};
