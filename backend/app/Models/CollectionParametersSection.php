<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CollectionParametersSection extends Model
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
     * Relationship: Parameters section belongs to a collection
     */
    public function collection()
    {
        return $this->belongsTo(Collection::class);
    }

    /**
     * Relationship: Parameters section has many items
     */
    public function items()
    {
        return $this->hasMany(CollectionParameterItem::class, 'parameters_section_id')->orderBy('order');
    }
}
