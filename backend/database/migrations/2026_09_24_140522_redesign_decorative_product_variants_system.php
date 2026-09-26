<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Create dec_product_colors table
        Schema::create('dec_product_colors', function (Blueprint $table) {
            $table->engine = "InnoDB";
            $table->id();
            $table->foreignId('product_id')->constrained('dec_products')->cascadeOnDelete();
            $table->foreignId('color_master_id')->nullable()->constrained('color_masters')->nullOnDelete();
            $table->string('main_image')->nullable();
            $table->string('lighton_image')->nullable();
            $table->integer('order')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });

        // 2. Create dec_product_sizes table
        Schema::create('dec_product_sizes', function (Blueprint $table) {
            $table->engine = "InnoDB";
            $table->id();
            $table->foreignId('product_id')->constrained('dec_products')->cascadeOnDelete();
            $table->string('label');
            $table->integer('order')->default(0);
            $table->timestamps();
        });

        // 3. Migrate data from dec_product_variants to dec_product_colors and extract distinct sizes
        $variants = DB::table('dec_product_variants')->get();
        
        $insertedSizes = [];

        foreach ($variants as $variant) {
            // Migrate color
            if ($variant->color_master_id) {
                // Check if this product already has this color (since variants used to combine color+size, there might be duplicates if a product had the same color in 2 sizes)
                $existingColor = DB::table('dec_product_colors')
                    ->where('product_id', $variant->product_id)
                    ->where('color_master_id', $variant->color_master_id)
                    ->first();
                    
                if (!$existingColor) {
                    DB::table('dec_product_colors')->insert([
                        'product_id' => $variant->product_id,
                        'color_master_id' => $variant->color_master_id,
                        'main_image' => $variant->main_image,
                        'lighton_image' => $variant->lighton_image,
                        'order' => $variant->order,
                        'created_at' => $variant->created_at,
                        'updated_at' => $variant->updated_at,
                    ]);
                } else {
                    // if it already exists, maybe update images if they were null
                    if (!$existingColor->main_image && $variant->main_image) {
                        DB::table('dec_product_colors')->where('id', $existingColor->id)->update(['main_image' => $variant->main_image]);
                    }
                    if (!$existingColor->lighton_image && $variant->lighton_image) {
                        DB::table('dec_product_colors')->where('id', $existingColor->id)->update(['lighton_image' => $variant->lighton_image]);
                    }
                }
            } else if ($variant->main_image || $variant->lighton_image) {
                // If no color but has images, keep it? Maybe not strictly necessary but let's be safe.
                DB::table('dec_product_colors')->insert([
                    'product_id' => $variant->product_id,
                    'color_master_id' => null,
                    'main_image' => $variant->main_image,
                    'lighton_image' => $variant->lighton_image,
                    'order' => $variant->order,
                    'created_at' => $variant->created_at,
                    'updated_at' => $variant->updated_at,
                ]);
            }
            
            // Migrate sizes
            if (!empty($variant->size)) {
                $sizeLabel = trim($variant->size);
                $key = $variant->product_id . '_' . strtolower($sizeLabel);
                
                if (!isset($insertedSizes[$key])) {
                    $sizeId = DB::table('dec_product_sizes')->insertGetId([
                        'product_id' => $variant->product_id,
                        'label' => $sizeLabel,
                        'order' => $variant->order,
                        'created_at' => $variant->created_at,
                        'updated_at' => $variant->updated_at,
                    ]);
                    $insertedSizes[$key] = $sizeId;
                }
            }
        }

        // 4. Alter dec_product_spec_rows table
        Schema::table('dec_product_spec_rows', function (Blueprint $table) {
            $table->foreignId('product_id')->nullable()->after('id');
            $table->foreignId('size_id')->nullable()->after('product_id')->constrained('dec_product_sizes')->nullOnDelete();
        });

        // 5. Backfill product_id and size_id in dec_product_spec_rows
        $specRows = DB::table('dec_product_spec_rows')->get();
        $productSpecsSeen = []; // To avoid duplicating basic specs

        foreach ($specRows as $row) {
            $variant = DB::table('dec_product_variants')->where('id', $row->variant_id)->first();
            
            if ($variant) {
                $sizeId = null;
                if (!empty($variant->size)) {
                    $sizeLabel = trim($variant->size);
                    $key = $variant->product_id . '_' . strtolower($sizeLabel);
                    $sizeId = $insertedSizes[$key] ?? null;
                }

                if ($row->section === 'basic_specifications') {
                    // For basic specs, we only want to keep one per product + attribute combination
                    // Because previously they were copied per variant
                    $specKey = $variant->product_id . '_' . $row->dec_spec_attribute_id;
                    if (isset($productSpecsSeen[$specKey])) {
                        // Already kept this spec for this product, delete this redundant row
                        DB::table('dec_product_spec_rows')->where('id', $row->id)->delete();
                        continue;
                    }
                    $productSpecsSeen[$specKey] = true;
                }
                
                DB::table('dec_product_spec_rows')->where('id', $row->id)->update([
                    'product_id' => $variant->product_id,
                    // If it's a dimension, link it to the size. Basic specs shouldn't have a size_id.
                    'size_id' => ($row->section === 'dimensions') ? $sizeId : null
                ]);
            } else {
                // If variant is missing, delete the spec row to clean up
                DB::table('dec_product_spec_rows')->where('id', $row->id)->delete();
            }
        }

        // 6. Make product_id required and drop variant_id
        Schema::table('dec_product_spec_rows', function (Blueprint $table) {
            $table->foreign('product_id')->references('id')->on('dec_products')->cascadeOnDelete();
            
            // Note: Doctrine DBAL requires dropping foreign key first
            $table->dropForeign(['variant_id']);
            $table->dropColumn('variant_id');
        });

        // 7. Drop dec_product_variants
        Schema::dropIfExists('dec_product_variants');
    }

    public function down(): void
    {
        // This is a destructive migration that removes old table.
        // A complete reverse is difficult since variants merged color and size.
        // But for completeness, we'd need to recreate the variants table.
        // Leaving basic reverse for tables.
        
        Schema::table('dec_product_spec_rows', function (Blueprint $table) {
            $table->foreignId('variant_id')->nullable()->constrained('dec_product_variants')->cascadeOnDelete();
            $table->dropForeign(['product_id']);
            $table->dropForeign(['size_id']);
            $table->dropColumn(['product_id', 'size_id']);
        });

        Schema::dropIfExists('dec_product_sizes');
        Schema::dropIfExists('dec_product_colors');
    }
};
