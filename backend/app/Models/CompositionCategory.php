<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompositionCategory extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug'];

    public function compositions()
    {
        return $this->hasMany(Composition::class, 'category_id');
    }
}
