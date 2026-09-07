<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class CollectionCompositionItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'compositions_section_id',
        'image',
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
     * Relationship: Composition item belongs to a compositions section
     */
    public function compositionsSection()
    {
        return $this->belongsTo(CollectionCompositionsSection::class);
    }

    /**
     * Accessor: Get full image URL
     */
    public function getImageUrlAttribute()
    {
        return $this->image ? asset('storage/' . $this->image) : null;
    }
}
