<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CollectionTonesSection extends Model
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
     * Relationship: Tones section belongs to a collection
     */
    public function collection()
    {
        return $this->belongsTo(Collection::class);
    }

    /**
     * Relationship: Tones section has many tone families
     */
    public function families()
    {
        return $this->hasMany(CollectionToneFamily::class, 'tones_section_id');
    }
}