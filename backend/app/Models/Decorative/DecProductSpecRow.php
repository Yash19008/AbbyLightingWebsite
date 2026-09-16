<?php

namespace App\Models\Decorative;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DecProductSpecRow extends Model
{
    use HasFactory;

    protected $table = 'dec_product_spec_rows';
    protected $guarded = ['id'];

    public function variant()
    {
        return $this->belongsTo(DecProductVariant::class, 'variant_id');
    }
}
