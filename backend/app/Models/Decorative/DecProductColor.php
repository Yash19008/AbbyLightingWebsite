<?php

namespace App\Models\Decorative;

use App\Models\ColorMaster;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DecProductColor extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'dec_product_colors';
    protected $guarded = ['id'];

    public function product()
    {
        return $this->belongsTo(DecProduct::class, 'product_id');
    }

    public function colorMaster()
    {
        return $this->belongsTo(ColorMaster::class, 'color_master_id');
    }
}
