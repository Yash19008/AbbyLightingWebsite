<?php

namespace App\Models\Decorative;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DecProductSize extends Model
{
    use HasFactory;

    protected $table = 'dec_product_sizes';
    protected $guarded = ['id'];

    public function product()
    {
        return $this->belongsTo(DecProduct::class, 'product_id');
    }

    public function specRows()
    {
        return $this->hasMany(DecProductSpecRow::class, 'size_id')->orderBy('order');
    }
}
