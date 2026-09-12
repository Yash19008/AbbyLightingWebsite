<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Catalogue extends Model
{
    use HasFactory;

    protected $table = 'catalogues';

    protected $guarded = ['id'];

    protected $casts = [
        'is_featured' => 'boolean',
    ];

    /**
     * Relationship with CatalogueCategory
     */
    public function category()
    {
        return $this->belongsTo(CatalogueCategory::class, 'catalogue_category_id');
    }

    /**
     * Relationship with CatalogDownload leads
     */
    public function downloads()
    {
        return $this->hasMany(CatalogDownload::class, 'catalogue_id');
    }


    /**
     * Scope for active catalogues
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope for featured catalogues
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', 1);
    }

    /**
     * Scope for ordered catalogues
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order', 'asc')->orderBy('id', 'asc');
    }
}
