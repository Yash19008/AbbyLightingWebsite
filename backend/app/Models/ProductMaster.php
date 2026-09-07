<?php

namespace App\Models;

use App\Models\SubTag;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductMaster extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'product_masters';

    protected $guarded = ['id'];

    /**
     * Relationships
     */
    public function category()
    {
        return $this->belongsTo('App\Models\Category', 'category_id');
    }

    public function productImages()
    {
        return $this->hasMany('App\Models\ProductImage', 'product_id');
    }

    public function variants()
    {
        return $this->hasMany('App\Models\ProductVariant', 'product_id');
    }

    public function attributes()
    {
        return $this->hasMany('App\Models\ProductAttribute', 'product_id');
    }

    public function collections()
    {
        return $this->belongsToMany('App\Models\Collection', 'collection_products', 'product_id', 'collection_id')->withTimestamps();
    }


}
