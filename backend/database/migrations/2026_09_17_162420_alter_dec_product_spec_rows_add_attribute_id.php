<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\Decorative\DecSpecAttribute;

return new class extends Migration
{
    public function up(): void
    {
        // Add the new column
        Schema::table('dec_product_spec_rows', function (Blueprint $table) {
            $table->foreignId('dec_spec_attribute_id')->nullable()->constrained('dec_spec_attributes')->nullOnDelete();
        });

        // Port existing data
        $rows = DB::table('dec_product_spec_rows')->whereNotNull('label')->get();
        foreach ($rows as $row) {
            if (empty(trim($row->label))) continue;
            
            $attribute = DB::table('dec_spec_attributes')->where('name', trim($row->label))->first();
            if (!$attribute) {
                $id = DB::table('dec_spec_attributes')->insertGetId([
                    'name' => trim($row->label),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                $id = $attribute->id;
            }
            
            DB::table('dec_product_spec_rows')->where('id', $row->id)->update([
                'dec_spec_attribute_id' => $id
            ]);
        }

        // Drop old column
        Schema::table('dec_product_spec_rows', function (Blueprint $table) {
            $table->dropColumn('label');
        });
    }

    public function down(): void
    {
        Schema::table('dec_product_spec_rows', function (Blueprint $table) {
            $table->string('label')->nullable();
        });

        $rows = DB::table('dec_product_spec_rows')->whereNotNull('dec_spec_attribute_id')->get();
        foreach ($rows as $row) {
            $attribute = DB::table('dec_spec_attributes')->where('id', $row->dec_spec_attribute_id)->first();
            if ($attribute) {
                DB::table('dec_product_spec_rows')->where('id', $row->id)->update([
                    'label' => $attribute->name
                ]);
            }
        }

        Schema::table('dec_product_spec_rows', function (Blueprint $table) {
            $table->dropForeign(['dec_spec_attribute_id']);
            $table->dropColumn('dec_spec_attribute_id');
        });
    }
};
