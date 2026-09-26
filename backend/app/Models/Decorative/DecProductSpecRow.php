<?php

namespace App\Models\Decorative;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DecProductSpecRow extends Model
{
    use HasFactory;

    protected $table = 'dec_product_spec_rows';
    protected $guarded = ['id'];

    public function product()
    {
        return $this->belongsTo(DecProduct::class, 'product_id');
    }

    public function size()
    {
        return $this->belongsTo(DecProductSize::class, 'size_id');
    }

    public function attribute()
    {
        return $this->belongsTo(DecSpecAttribute::class, 'dec_spec_attribute_id');
    }
}
