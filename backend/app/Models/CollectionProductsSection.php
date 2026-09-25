<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CollectionProductsSection extends Model
{
    use HasFactory;

    protected $fillable = [
        'collection_id',
        'heading',
        'subtitle',
        'view_more_text',
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
