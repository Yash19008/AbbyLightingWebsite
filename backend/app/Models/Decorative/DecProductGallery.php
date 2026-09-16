<?php

namespace App\Models\Decorative;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DecProductGallery extends Model
{
    use HasFactory;

    protected $table = 'dec_product_galleries';
    protected $guarded = ['id'];

    public function product()
    {
        return $this->belongsTo(DecProduct::class, 'product_id');
    }
}
