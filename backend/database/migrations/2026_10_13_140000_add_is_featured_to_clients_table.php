<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('clients', function (Blueprint $table) {
            if (!Schema::hasColumn('clients', 'is_featured')) {
                $table->boolean('is_featured')->default(0)->after('path')->index();
            }
        });

        // Seed latest 16 clients to is_featured = 1 for immediate smooth experience
        $latestIds = DB::table('clients')
            ->whereNull('deleted_at')
            ->orderBy('id', 'desc')
            ->limit(16)
            ->pluck('id');

        if ($latestIds->isNotEmpty()) {
            DB::table('clients')
                ->whereIn('id', $latestIds)
                ->update(['is_featured' => 1]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('clients', function (Blueprint $table) {
            if (Schema::hasColumn('clients', 'is_featured')) {
                $table->dropIndex(['is_featured']);
                $table->dropColumn('is_featured');
            }
        });
    }
};
