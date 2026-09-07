<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class DecorativeCategory extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    public function parent()
    {
        return $this->belongsTo(DecorativeCategory::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(DecorativeCategory::class, 'parent_id');
    }

    public function products()
    {
        return $this->belongsToMany(DecorativeProduct::class, 'decorative_product_categories', 'decorative_category_id', 'decorative_product_id');
    }
}
