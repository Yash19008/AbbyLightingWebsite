<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CollectionCatalogueSection extends Model
{
    use HasFactory;

    protected $fillable = [
        'collection_id',
        'background_image',
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
}
