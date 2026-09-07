<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DecorativeProductSpecSection extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function variation()
    {
        return $this->belongsTo(DecorativeProductVariation::class, 'decorative_product_variation_id');
    }

    public function specifications()
    {
        return $this->hasMany(DecorativeProductSpecification::class, 'section_id');
    }
}
