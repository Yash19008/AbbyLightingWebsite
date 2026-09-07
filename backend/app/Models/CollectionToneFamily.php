<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class CollectionToneFamily extends Model
{
    use HasFactory;

    protected $fillable = [
        'tones_section_id',
        'image',
        'title',
        'order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected $appends = ['image_url'];

    /**
     * Relationship: Tone family belongs to a tones section
     */
    public function tonesSection()
    {
        return $this->belongsTo(CollectionTonesSection::class, 'tones_section_id');
    }

    /**
     * Relationship: Many-to-many with ColorMaster through pivot table
     */
    public function colors()
    {
        return $this->belongsToMany(
            ColorMaster::class,
            'collection_tone_family_colors',
            'tone_family_id',
            'color_master_id'
        )
        ->withTimestamps()
        ->withPivot('order')
        ->orderBy('collection_tone_family_colors.order');
    }

    /**
     * Accessor: Get full image URL
     */
    public function getImageUrlAttribute()
    {
        return $this->image ? asset('storage/' . $this->image) : null;
    }
}
