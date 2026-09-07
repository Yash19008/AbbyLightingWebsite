<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductVariant extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'product_variants';
    protected $guarded = [
        'id'
    ];
    public function product(){
        return $this->belongsTo('App\Models\ProductMaster', 'product_id');
    }
    public function variantFiles()
    {
        return $this->hasMany('App\Models\ProductVariantFile', 'product_variant_id');
    }
}
