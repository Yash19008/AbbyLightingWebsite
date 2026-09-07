<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class CollectionPlaceItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'places_section_id',
        'image',
        'place_name',
        'description',
        'products',
        'order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected $appends = ['image_url'];

    /**
     * Relationship: Place item belongs to a places section
     */
    public function placesSection()
    {
        return $this->belongsTo(CollectionPlacesSection::class, 'places_section_id');
    }

    /**
     * Accessor: Get full image URL
     */
    public function getImageUrlAttribute()
    {
        return $this->image ? asset('storage/' . $this->image) : null;
    }
}
