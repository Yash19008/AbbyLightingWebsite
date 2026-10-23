<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('inquiries', function (Blueprint $table) {
            if (!Schema::hasColumn('inquiries', 'type')) {
                $table->string('type')->default('general')->after('id');
            }
            if (!Schema::hasColumn('inquiries', 'reference')) {
                $table->string('reference')->nullable()->after('type');
            }

            if (Schema::hasColumn('inquiries', 'full_name')) {
                $table->renameColumn('full_name', 'name');
            }
            if (Schema::hasColumn('inquiries', 'position')) {
                $table->renameColumn('position', 'role');
            }
            if (Schema::hasColumn('inquiries', 'i_message')) {
                $table->renameColumn('i_message', 'message');
            }

            // Drop deprecated legacy columns safely
            $legacyDrop = ['country', 'website', 'profession', 'interested_in', 'industry_of_interest', 'is_active', 'created_by', 'updated_by', 'deleted_by'];
            foreach ($legacyDrop as $col) {
                if (Schema::hasColumn('inquiries', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }

    public function down(): void
    {
        Schema::table('inquiries', function (Blueprint $table) {
            if (Schema::hasColumn('inquiries', 'type')) {
                $table->dropColumn(['type', 'reference']);
            }
            if (Schema::hasColumn('inquiries', 'name')) {
                $table->renameColumn('name', 'full_name');
            }
            if (Schema::hasColumn('inquiries', 'role')) {
                $table->renameColumn('role', 'position');
            }
            if (Schema::hasColumn('inquiries', 'message')) {
                $table->renameColumn('message', 'i_message');
            }
        });
    }
};