<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CollectionCompositionsSection extends Model
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
     * Relationship: Compositions section belongs to a collection
     */
    public function collection()
    {
        return $this->belongsTo(Collection::class);
    }

    /**
     * Relationship: Compositions section has many items
     */
    public function items()
    {
        return $this->hasMany(CollectionCompositionItem::class, 'compositions_section_id')->orderBy('order');
    }
}
