<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DecorativeProductVariation extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'decorative_product_id',
        'sku',
        'image',
        'status',
        'sort_order'
    ];

    public function product()
    {
        return $this->belongsTo(DecorativeProduct::class, 'decorative_product_id');
    }

    public function attributeValues()
    {
        return $this->belongsToMany(DecorativeAttributeValue::class, 'decorative_variation_attribute_values', 'decorative_product_variation_id', 'decorative_attribute_value_id');
    }

    public function galleryImages()
    {
        return $this->hasMany(DecorativeVariationImage::class, 'decorative_product_variation_id');
    }

    public function specificationSections()
    {
        return $this->hasMany(DecorativeProductSpecSection::class, 'decorative_product_variation_id');
    }
}
