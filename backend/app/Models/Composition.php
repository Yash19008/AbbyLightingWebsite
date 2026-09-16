<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Composition extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'kicker',
        'category',
        'image',
        'is_showcase',
    ];

    public function collections()
    {
        return $this->belongsToMany(Collection::class, 'collection_composition');
    }
}
