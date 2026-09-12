<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatalogueCategory extends Model
{
    use HasFactory;

    protected $table = 'catalogue_categories';

    protected $guarded = ['id'];

    /**
     * Relationship with Catalogues
     */
    public function catalogues()
    {
        return $this->hasMany(Catalogue::class, 'catalogue_category_id');
    }

    /**
     * Scope for active categories
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope for ordered categories
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order', 'asc')->orderBy('id', 'asc');
    }
}
