<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DecorativeVariationAttributeValue extends Model
{
    use HasFactory;

    protected $fillable = [
        'decorative_product_variation_id',
        'decorative_attribute_value_id'
    ];

    public function variation()
    {
        return $this->belongsTo(DecorativeProductVariation::class, 'decorative_product_variation_id');
    }

    public function attributeValue()
    {
        return $this->belongsTo(DecorativeAttributeValue::class, 'decorative_attribute_value_id');
    }
}
