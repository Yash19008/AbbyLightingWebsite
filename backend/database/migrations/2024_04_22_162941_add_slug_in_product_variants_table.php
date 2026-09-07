<?php

use App\Models\ProductVariant;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_variants', function (Blueprint $table) {
            $table->string('slug')->after('product_id')->nullable()->unique();
        });

        ProductVariant::all()->each(function ($product_variant) {
            if ($product_variant->product == null) {
                return;
            }
            $slug = Str::slug($product_variant->product->slug."-".$product_variant->variant_name);
            $slug = ProductVariant::where('slug', $slug)->exists() ? $slug . '-' . $product_variant->id : $slug;
            $product_variant->slug = $slug;
            $product_variant->save();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_variants', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
};
