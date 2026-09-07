<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DecorativeProductAttributeValue extends Model
{
    use HasFactory;

    protected $fillable = [
        'decorative_product_attribute_id', 
        'decorative_attribute_value_id'
    ];

    public function productAttribute()
    {
        return $this->belongsTo(DecorativeProductAttribute::class, 'decorative_product_attribute_id');
    }

    public function attributeValue()
    {
        return $this->belongsTo(DecorativeAttributeValue::class, 'decorative_attribute_value_id');
    }
}
