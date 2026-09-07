<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CollectionSpreadDropSection extends Model
{
    use HasFactory;

    protected $fillable = [
        'collection_id',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Relationship: Section belongs to a collection
     */
    public function collection()
    {
        return $this->belongsTo(Collection::class);
    }
}
