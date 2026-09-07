<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DecorativeProductAttribute extends Model
{
    use HasFactory;

    protected $fillable = [
        'decorative_product_id', 
        'decorative_attribute_id', 
        'is_variation', 
        'display_order'
    ];

    public function product()
    {
        return $this->belongsTo(DecorativeProduct::class, 'decorative_product_id');
    }

    public function attribute()
    {
        return $this->belongsTo(DecorativeAttribute::class, 'decorative_attribute_id');
    }

    public function values()
    {
        return $this->hasMany(DecorativeProductAttributeValue::class, 'decorative_product_attribute_id');
    }
}
