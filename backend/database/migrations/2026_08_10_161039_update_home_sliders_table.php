<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('home_sliders', function (Blueprint $table) {
            if (!Schema::hasColumn('home_sliders', 'mobile_path')) {
                $table->string('mobile_path')->nullable()->after('path');
            }
            if (!Schema::hasColumn('home_sliders', 'tablet_path')) {
                $table->string('tablet_path')->nullable()->after('mobile_path');
            }
            if (!Schema::hasColumn('home_sliders', 'heading')) {
                $table->string('heading')->nullable()->after('tablet_path');
            }
            if (!Schema::hasColumn('home_sliders', 'heading_highlight')) {
                $table->string('heading_highlight')->nullable()->after('heading');
            }
            if (!Schema::hasColumn('home_sliders', 'description')) {
                $table->text('description')->nullable()->after('heading_highlight');
            }
            if (!Schema::hasColumn('home_sliders', 'button_text')) {
                $table->string('button_text')->nullable()->after('description');
            }
            if (!Schema::hasColumn('home_sliders', 'button_link')) {
                $table->string('button_link')->nullable()->after('button_text');
            }
            if (!Schema::hasColumn('home_sliders', 'is_active')) {
                $table->boolean('is_active')->default(1)->after('sort_order')->index();
            }
        });
    }

    public function down(): void
    {
        Schema::table('home_sliders', function (Blueprint $table) {
            $cols = ['mobile_path', 'tablet_path', 'heading', 'heading_highlight', 'description', 'button_text', 'button_link', 'is_active'];
            foreach ($cols as $col) {
                if (Schema::hasColumn('home_sliders', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};