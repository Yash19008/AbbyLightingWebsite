<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class CollectionCatalogueSection extends Model
{
    use HasFactory;

    protected $fillable = [
        'collection_id',
        'background_image',
        'image_tablet',
        'image_mobile',
        'title',
        'title_highlight',
        'button_text',
        'button_link',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function collection()
    {
        return $this->belongsTo(Collection::class);
    }

    public function getBackgroundImageUrlAttribute(): ?string
    {
        if (!$this->background_image) {
            return null;
        }

        if (filter_var($this->background_image, FILTER_VALIDATE_URL)) {
            return $this->background_image;
        }

        return Storage::url($this->background_image);
    }

    public function getImageTabletUrlAttribute(): ?string
    {
        if (!$this->image_tablet) {
            return null;
        }

        if (filter_var($this->image_tablet, FILTER_VALIDATE_URL)) {
            return $this->image_tablet;
        }

        return Storage::url($this->image_tablet);
    }

    public function getImageMobileUrlAttribute(): ?string
    {
        if (!$this->image_mobile) {
            return null;
        }

        if (filter_var($this->image_mobile, FILTER_VALIDATE_URL)) {
            return $this->image_mobile;
        }

        return Storage::url($this->image_mobile);
    }
}
