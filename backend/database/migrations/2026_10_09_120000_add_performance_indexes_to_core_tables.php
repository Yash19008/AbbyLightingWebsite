<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Check if an index already exists.
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

    /**
     * Safely add an index if it doesn't already exist.
     */
    private function addIndex(string $table, string|array $columns, string $indexName): void
    {
        if (Schema::hasTable($table) && !$this->hasIndex($table, $indexName)) {
            Schema::table($table, function (Blueprint $t) use ($columns, $indexName) {
                $t->index($columns, $indexName);
            });
        }
    }

    /**
     * Safely drop an index if it exists.
     */
    private function dropIndex(string $table, string $indexName): void
    {
        if (Schema::hasTable($table) && $this->hasIndex($table, $indexName)) {
            Schema::table($table, function (Blueprint $t) use ($indexName) {
                $t->dropIndex($indexName);
            });
        }
    }

    public function up(): void
    {
        // sub_tags — filtered by is_active, show_on_home_page, lookup by slug
        $this->addIndex('sub_tags', 'is_active', 'sub_tags_is_active_idx');
        $this->addIndex('sub_tags', 'show_on_home_page', 'sub_tags_show_on_home_page_idx');
        $this->addIndex('sub_tags', 'slug', 'sub_tags_slug_idx');

        // product_masters — listing and filter queries
        $this->addIndex('product_masters', 'is_active', 'product_masters_is_active_idx');
        $this->addIndex('product_masters', 'show_as_new_arrival', 'product_masters_show_as_new_arrival_idx');
        $this->addIndex('product_masters', ['category_id', 'is_active'], 'product_masters_cat_active_idx');

        // inquiries — DataTables sorting and filtering
        $this->addIndex('inquiries', 'type', 'inquiries_type_idx');
        $this->addIndex('inquiries', 'created_at', 'inquiries_created_at_idx');

        // catalog_downloads — DataTables sorting and filtering
        $this->addIndex('catalog_downloads', 'catalogue_id', 'catalog_downloads_catalogue_id_idx');
        $this->addIndex('catalog_downloads', 'created_at', 'catalog_downloads_created_at_idx');

        // blogs — listing, category and status filters
        $this->addIndex('blogs', 'status', 'blogs_status_idx');
        $this->addIndex('blogs', 'is_featured', 'blogs_is_featured_idx');
        $this->addIndex('blogs', 'published_at', 'blogs_published_at_idx');
        $this->addIndex('blogs', 'sort_order', 'blogs_sort_order_idx');

        // collections — menu and listing queries
        $this->addIndex('collections', 'is_active', 'collections_is_active_idx');
        $this->addIndex('collections', 'show_in_menu', 'collections_show_in_menu_idx');
        $this->addIndex('collections', 'order', 'collections_order_idx');

        // dec_products — decorative products listing
        $this->addIndex('dec_products', 'status', 'dec_products_status_idx');
        $this->addIndex('dec_products', 'is_featured', 'dec_products_is_featured_idx');
        $this->addIndex('dec_products', 'order', 'dec_products_order_idx');

        // categories — main product categories
        $this->addIndex('categories', 'is_active', 'categories_is_active_idx');
        $this->addIndex('categories', 'is_featured', 'categories_is_featured_idx');
        $this->addIndex('categories', 'in_menu', 'categories_in_menu_idx');
    }

    public function down(): void
    {
        // sub_tags
        $this->dropIndex('sub_tags', 'sub_tags_is_active_idx');
        $this->dropIndex('sub_tags', 'sub_tags_show_on_home_page_idx');
        $this->dropIndex('sub_tags', 'sub_tags_slug_idx');

        // product_masters
        $this->dropIndex('product_masters', 'product_masters_is_active_idx');
        $this->dropIndex('product_masters', 'product_masters_show_as_new_arrival_idx');
        $this->dropIndex('product_masters', 'product_masters_cat_active_idx');

        // inquiries
        $this->dropIndex('inquiries', 'inquiries_type_idx');
        $this->dropIndex('inquiries', 'inquiries_created_at_idx');

        // catalog_downloads
        $this->dropIndex('catalog_downloads', 'catalog_downloads_catalogue_id_idx');
        $this->dropIndex('catalog_downloads', 'catalog_downloads_created_at_idx');

        // blogs
        $this->dropIndex('blogs', 'blogs_status_idx');
        $this->dropIndex('blogs', 'blogs_is_featured_idx');
        $this->dropIndex('blogs', 'blogs_published_at_idx');
        $this->dropIndex('blogs', 'blogs_sort_order_idx');

        // collections
        $this->dropIndex('collections', 'collections_is_active_idx');
        $this->dropIndex('collections', 'collections_show_in_menu_idx');
        $this->dropIndex('collections', 'collections_order_idx');

        // dec_products
        $this->dropIndex('dec_products', 'dec_products_status_idx');
        $this->dropIndex('dec_products', 'dec_products_is_featured_idx');
        $this->dropIndex('dec_products', 'dec_products_order_idx');

        // categories
        $this->dropIndex('categories', 'categories_is_active_idx');
        $this->dropIndex('categories', 'categories_is_featured_idx');
        $this->dropIndex('categories', 'categories_in_menu_idx');
    }
};
