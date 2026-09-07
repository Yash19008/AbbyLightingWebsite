<?php

use App\Models\ProductMaster;
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
        Schema::table('product_masters', function (Blueprint $table) {
            $table->string('slug')->after('title')->nullable()->unique();
        });

        ProductMaster::all()->each(function ($product_master) {
            $slug = Str::slug($product_master->title);
            $slug = ProductMaster::where('slug', $slug)->exists() ? $slug . '-' . $product_master->id : $slug;
            $product_master->slug = $slug;
            $product_master->save();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_masters', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
};
