<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('events', function (Blueprint $table) {
            $table->string('source', 255)->after('name')->nullable()->comment('Publication/Source name like ARCHITECTURAL DIGEST, ELLE DECOR INDIA');
            $table->text('source_link')->after('source')->nullable()->comment('External article URL/link');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn(['source', 'source_link']);
        });
    }
};
