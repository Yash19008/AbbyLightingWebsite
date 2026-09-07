<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CollectionParameterItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'parameters_section_id',
        'small_text',
        'title',
        'description',
        'bg_color',
        'hover_bg_color',
        'order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Relationship: Parameter item belongs to a parameters section
     */
    public function parametersSection()
    {
        return $this->belongsTo(CollectionParametersSection::class);
    }
}
