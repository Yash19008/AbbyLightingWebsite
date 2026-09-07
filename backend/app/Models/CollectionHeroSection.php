<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class CollectionHeroSection extends Model
{
    use HasFactory;

    protected $fillable = [
        'collection_id',
        'background_image',
        'title_prefix',
        'title_highlight',
        'description',
        'breadcrumb_parent_text',
        'breadcrumb_parent_link',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Relationship: Hero section belongs to a collection
     */
    public function collection()
    {
        return $this->belongsTo(Collection::class);
    }

    /**
     * Get the full URL for the background image
     */
    public function getBackgroundImageUrlAttribute(): ?string
    {
        if (!$this->background_image) {
            return null;
        }

        // If it's already a full URL, return it
        if (filter_var($this->background_image, FILTER_VALIDATE_URL)) {
            return $this->background_image;
        }

        // Otherwise, generate storage URL
        return Storage::url($this->background_image);
    }
}
