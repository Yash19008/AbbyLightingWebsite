<?php

namespace App\Models\Decorative;

use App\Models\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DecProduct extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'dec_products';
    protected $guarded = ['id'];

    public function collection()
    {
        return $this->belongsTo(Collection::class, 'collection_id');
    }

    public function category()
    {
        return $this->belongsTo(DecCategory::class, 'category_id');
    }

    public function variants()
    {
        return $this->hasMany(DecProductVariant::class, 'product_id')->orderBy('order', 'asc');
    }

    public function galleries()
    {
        return $this->hasMany(DecProductGallery::class, 'product_id')->orderBy('order', 'asc');
    }

    public function relatedProducts()
    {
        return $this->belongsToMany(DecProduct::class, 'dec_product_related', 'product_id', 'related_product_id')
                    ->withPivot('order')
                    ->orderByPivot('order');
    }

    public function compositions()
    {
        return $this->belongsToMany(\App\Models\Composition::class, 'composition_products', 'product_id', 'composition_id');
    }
}
