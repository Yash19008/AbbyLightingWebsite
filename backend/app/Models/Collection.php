<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Collection extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug',
        'name',
        'short_description',
        'description',
        'meta_title',
        'meta_description',
        'is_active',
        'order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * Relationship: A collection has one hero section
     */
    public function heroSection()
    {
        return $this->hasOne(CollectionHeroSection::class);
    }

    /**
     * Relationship: A collection has one parameters section
     */
    public function parametersSection()
    {
        return $this->hasOne(CollectionParametersSection::class);
    }

    /**
     * Relationship: A collection has one compositions section
     */
    public function compositionsSection()
    {
        return $this->hasOne(CollectionCompositionsSection::class);
    }

    /**
     * Relationship: A collection has one tones section
     */
    public function tonesSection()
    {
        return $this->hasOne(CollectionTonesSection::class);
    }

    /**
     * Relationship: A collection has one places section
     */
    public function placesSection()
    {
        return $this->hasOne(CollectionPlacesSection::class);
    }

    /**
     * Relationship: A collection has one spread & drop section
     */
    public function spreadDropSection()
    {
        return $this->hasOne(CollectionSpreadDropSection::class);
    }

    /**
     * Relationship: A collection has one catalogue section
     */
    public function catalogueSection()
    {
        return $this->hasOne(CollectionCatalogueSection::class);
    }

    /**
     * Relationship: A collection belongs to many products
     */
    public function products()
    {
        return $this->belongsToMany(ProductMaster::class, 'collection_products', 'collection_id', 'product_id')
            ->select('product_masters.*')
            ->withTimestamps();
    }



    /**
     * Relationship: A collection belongs to many master compositions
     */
    public function compositions()
    {
        return $this->belongsToMany(Composition::class, 'collection_composition');
    }

    /**
     * Scope: Get only active collections
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope: Order by display order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order', 'asc');
    }
}
