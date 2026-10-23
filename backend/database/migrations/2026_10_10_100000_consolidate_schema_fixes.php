<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Fix 1: news_section – add missing sort_order
        if (Schema::hasTable('news_section') && !Schema::hasColumn('news_section', 'sort_order')) {
            Schema::table('news_section', function (Blueprint $table) {
                $table->integer('sort_order')->default(0)->after('is_active');
            });
        }

        // Fix 5: dec_spec_attributes – drop duplicate non-unique index on name
        $this->dropIndexIfExists('dec_spec_attributes', 'dec_spec_attributes_name_index');

        // Fix 6: dec_categories – drop duplicate non-unique index on slug
        $this->dropIndexIfExists('dec_categories', 'dec_categories_slug_index');

        // Removed as it was fixed in the original migration

        // Fix 8: collections – add cover_image
        if (Schema::hasTable('collections') && !Schema::hasColumn('collections', 'cover_image')) {
            Schema::table('collections', function (Blueprint $table) {
                $table->string('cover_image')->nullable()->after('name');
            });
        }

        // Fix 9: compositions – add sort_order and is_active
        if (Schema::hasTable('compositions')) {
            Schema::table('compositions', function (Blueprint $table) {
                if (!Schema::hasColumn('compositions', 'sort_order')) {
                    $table->integer('sort_order')->default(0)->after('is_showcase');
                }
                if (!Schema::hasColumn('compositions', 'is_active')) {
                    $table->boolean('is_active')->default(true)->after('sort_order');
                }
            });
        }

        // Fix 10: menu_items – add icon and open_in_new_tab
        if (Schema::hasTable('menu_items')) {
            Schema::table('menu_items', function (Blueprint $table) {
                if (!Schema::hasColumn('menu_items', 'icon')) {
                    $table->string('icon')->nullable()->after('title');
                }
                if (!Schema::hasColumn('menu_items', 'open_in_new_tab')) {
                    $table->boolean('open_in_new_tab')->default(false)->after('url');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('news_section') && Schema::hasColumn('news_section', 'sort_order')) {
            Schema::table('news_section', fn(Blueprint $t) => $t->dropColumn('sort_order'));
        }

        if (Schema::hasTable('compositions')) {
            Schema::table('compositions', function (Blueprint $table) {
                foreach (['sort_order', 'is_active'] as $col) {
                    if (Schema::hasColumn('compositions', $col)) $table->dropColumn($col);
                }
            });
        }

        if (Schema::hasTable('collections') && Schema::hasColumn('collections', 'cover_image')) {
            Schema::table('collections', fn(Blueprint $t) => $t->dropColumn('cover_image'));
        }

        if (Schema::hasTable('menu_items')) {
            Schema::table('menu_items', function (Blueprint $table) {
                foreach (['icon', 'open_in_new_tab'] as $col) {
                    if (Schema::hasColumn('menu_items', $col)) $table->dropColumn($col);
                }
            });
        }

        // Fixed in original migration
    }

    private function dropIndexIfExists(string $table, string $index): void
    {
        if (!Schema::hasTable($table)) return;
        $db = config('database.connections.' . config('database.default') . '.database');
        $cnt = DB::selectOne(
            "SELECT COUNT(*) AS cnt FROM information_schema.statistics WHERE table_schema=? AND table_name=? AND index_name=?",
            [$db, $table, $index]
        );
        if (($cnt->cnt ?? 0) > 0) {
            Schema::table($table, fn(Blueprint $t) => $t->dropIndex($index));
        }
    }
};
