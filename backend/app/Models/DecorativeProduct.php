<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DecorativeProduct extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    public function images()
    {
        return $this->hasMany(DecorativeProductImage::class, 'decorative_product_id');
    }

    public function categories()
    {
        return $this->belongsToMany(DecorativeCategory::class, 'decorative_product_categories', 'decorative_product_id', 'decorative_category_id');
    }

    public function primaryImage()
    {
        return $this->hasOne(DecorativeProductImage::class, 'decorative_product_id')->where('type', 'PRIMARY');
    }

    public function lightOnImage()
    {
        return $this->hasOne(DecorativeProductImage::class, 'decorative_product_id')->where('type', 'LIGHT_ON');
    }

    public function galleryImages()
    {
        return $this->hasMany(DecorativeProductImage::class, 'decorative_product_id')->where('type', 'GALLERY');
    }

    public function attributes()
    {
        return $this->hasMany(DecorativeProductAttribute::class, 'decorative_product_id');
    }

    public function variations()
    {
        return $this->hasMany(DecorativeProductVariation::class, 'decorative_product_id');
    }

    public function collections()
    {
        return $this->belongsToMany(Collection::class, 'collection_decorative_products', 'decorative_product_id', 'collection_id')->withTimestamps();
    }
}
