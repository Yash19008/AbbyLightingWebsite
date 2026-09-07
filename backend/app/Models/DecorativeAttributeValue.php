<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DecorativeAttributeValue extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['attribute_id', 'name', 'slug', 'hex_code'];

    public function attribute()
    {
        return $this->belongsTo(DecorativeAttribute::class, 'attribute_id');
    }
}
