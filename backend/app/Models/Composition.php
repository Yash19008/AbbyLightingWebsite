<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Composition extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'kicker',
        'image',
        'is_showcase',
        'category_id', // Added category_id
    ];

    public function category_rel()
    {
        return $this->belongsTo(CompositionCategory::class, 'category_id');
    }

    public function products()
    {
        return $this->belongsToMany(\App\Models\Decorative\DecProduct::class, 'composition_products', 'composition_id', 'product_id');
    }

    public function collections()
    {
        return $this->belongsToMany(Collection::class, 'collection_composition');
    }
}
