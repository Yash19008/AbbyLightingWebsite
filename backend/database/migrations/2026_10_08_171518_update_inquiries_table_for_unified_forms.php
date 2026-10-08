<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('inquiries', function (Blueprint $table) {
            $table->string('type')->default('general')->after('id');
            $table->string('reference')->nullable()->after('type');
            $table->renameColumn('full_name', 'name');
            $table->renameColumn('position', 'role');
            $table->renameColumn('i_message', 'message');
            $table->dropColumn(['country', 'website', 'profession', 'interested_in', 'industry_of_interest']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inquiries', function (Blueprint $table) {
            $table->dropColumn(['type', 'reference']);
            $table->renameColumn('name', 'full_name');
            $table->renameColumn('role', 'position');
            $table->renameColumn('message', 'i_message');
            $table->text('country')->nullable();
            $table->text('website')->nullable();
            $table->text('profession')->nullable();
            $table->text('interested_in')->nullable();
            $table->text('industry_of_interest')->nullable();
        });
    }
};
