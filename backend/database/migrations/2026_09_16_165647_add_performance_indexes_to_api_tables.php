<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Check if an index already exists (safe to call from up/down directly).
     */
    private function hasIndex(string $table, string $indexName): bool
    {
        $db = config('database.connections.' . config('database.default') . '.database');
        $result = DB::select("
            SELECT COUNT(*) AS cnt
            FROM information_schema.statistics
            WHERE table_schema = ? AND table_name = ? AND index_name = ?
        ", [$db, $table, $indexName]);
        return ($result[0]->cnt ?? 0) > 0;
    }

    public function up(): void
    {
        // projects: filtered by is_active, is_featured; ordered by sequence
        if (!$this->hasIndex('projects', 'projects_is_active_index')) {
            Schema::table('projects', fn (Blueprint $t) => $t->index('is_active', 'projects_is_active_index'));
        }
        if (!$this->hasIndex('projects', 'projects_is_featured_index')) {
            Schema::table('projects', fn (Blueprint $t) => $t->index('is_featured', 'projects_is_featured_index'));
        }
        if (!$this->hasIndex('projects', 'projects_sequence_index')) {
            Schema::table('projects', fn (Blueprint $t) => $t->index('sequence', 'projects_sequence_index'));
        }

        // catalogues: filtered by status, is_featured, category
        if (!$this->hasIndex('catalogues', 'catalogues_status_index')) {
            Schema::table('catalogues', fn (Blueprint $t) => $t->index('status', 'catalogues_status_index'));
        }
        if (!$this->hasIndex('catalogues', 'catalogues_is_featured_index')) {
            Schema::table('catalogues', fn (Blueprint $t) => $t->index('is_featured', 'catalogues_is_featured_index'));
        }
        if (!$this->hasIndex('catalogues', 'catalogues_cat_id_index')) {
            Schema::table('catalogues', fn (Blueprint $t) => $t->index('catalogue_category_id', 'catalogues_cat_id_index'));
        }

        // decorative_products: filtered by status and is_new_arrival
        if (!$this->hasIndex('decorative_products', 'dec_products_status_index')) {
            Schema::table('decorative_products', fn (Blueprint $t) => $t->index('status', 'dec_products_status_index'));
        }
        if (Schema::hasColumn('decorative_products', 'is_new_arrival')
            && !$this->hasIndex('decorative_products', 'dec_products_new_arrival_index')) {
            Schema::table('decorative_products', fn (Blueprint $t) => $t->index('is_new_arrival', 'dec_products_new_arrival_index'));
        }

        // home_sliders: ordered by sort_order, filtered by is_active
        if (!$this->hasIndex('home_sliders', 'sliders_sort_order_index')) {
            Schema::table('home_sliders', fn (Blueprint $t) => $t->index('sort_order', 'sliders_sort_order_index'));
        }
        if (Schema::hasColumn('home_sliders', 'is_active')
            && !$this->hasIndex('home_sliders', 'sliders_is_active_index')) {
            Schema::table('home_sliders', fn (Blueprint $t) => $t->index('is_active', 'sliders_is_active_index'));
        }
    }

    public function down(): void
    {
        Schema::table('projects', function (Blueprint $t) {
            $t->dropIndexIfExists('projects_is_active_index');
            $t->dropIndexIfExists('projects_is_featured_index');
            $t->dropIndexIfExists('projects_sequence_index');
        });
        Schema::table('catalogues', function (Blueprint $t) {
            $t->dropIndexIfExists('catalogues_status_index');
            $t->dropIndexIfExists('catalogues_is_featured_index');
            $t->dropIndexIfExists('catalogues_cat_id_index');
        });
        Schema::table('decorative_products', function (Blueprint $t) {
            $t->dropIndexIfExists('dec_products_status_index');
            $t->dropIndexIfExists('dec_products_new_arrival_index');
        });
        Schema::table('home_sliders', function (Blueprint $t) {
            $t->dropIndexIfExists('sliders_sort_order_index');
            $t->dropIndexIfExists('sliders_is_active_index');
        });
    }
};
