<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // For MySQL, alter table to change enum is often best done with a raw query if doctrine/dbal has issues with enums
        DB::statement("ALTER TABLE dec_product_spec_rows MODIFY COLUMN value_type ENUM('text', 'richtext', 'chips') DEFAULT 'text'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reverting back to original enum (note: if data has 'chips', it may be truncated or cause errors, so ideally data should be updated first)
        DB::statement("ALTER TABLE dec_product_spec_rows MODIFY COLUMN value_type ENUM('text', 'richtext') DEFAULT 'text'");
    }
};
