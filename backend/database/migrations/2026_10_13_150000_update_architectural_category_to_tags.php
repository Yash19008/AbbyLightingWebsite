<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Add is_featured to tags table
        if (Schema::hasTable('tags') && !Schema::hasColumn('tags', 'is_featured')) {
            Schema::table('tags', function (Blueprint $table) {
                $table->boolean('is_featured')->default(0)->after('show_categories')->index();
            });
        }

        // 2. Seed default architectural tags as featured
        $architecturalSlugs = [
            'spots-&-accents',
            'downlights',
            'profiles',
            'track-lights',
            'washers-grazers',
        ];

        if (Schema::hasTable('tags')) {
            DB::table('tags')
                ->whereIn('slug', $architecturalSlugs)
                ->orWhereIn('id', [1, 2, 3, 4, 12])
                ->update(['is_featured' => 1]);
        }

        // 3. Remove is_featured from categories table
        if (Schema::hasTable('categories') && Schema::hasColumn('categories', 'is_featured')) {
            Schema::table('categories', function (Blueprint $table) {
                $table->dropColumn('is_featured');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Restore is_featured on categories table
        if (Schema::hasTable('categories') && !Schema::hasColumn('categories', 'is_featured')) {
            Schema::table('categories', function (Blueprint $table) {
                $table->boolean('is_featured')->default(0)->nullable();
            });
        }

        // Drop is_featured from tags table
        if (Schema::hasTable('tags') && Schema::hasColumn('tags', 'is_featured')) {
            Schema::table('tags', function (Blueprint $table) {
                $table->dropColumn('is_featured');
            });
        }
    }
};
