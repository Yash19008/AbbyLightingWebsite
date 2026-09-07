<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CollectionPlacesSection extends Model
{
    use HasFactory;

    protected $fillable = [
        'collection_id',
        'title',
        'subtitle',
        'is_active',
        'order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Relationship: Places section belongs to a collection
     */
    public function collection()
    {
        return $this->belongsTo(Collection::class);
    }

    /**
     * Relationship: Places section has many place items
     */
    public function items()
    {
        return $this->hasMany(CollectionPlaceItem::class, 'places_section_id')->orderBy('order');
    }
}
