<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductAttribute extends Model
{
    use HasFactory;
    protected $table = 'product_attributes';
    protected $guarded = [
        'id'
    ];
    public function attribute(){
        return $this->belongsTo('App\Models\GroupAttributeMaster', 'attribute_id');
    }
}
