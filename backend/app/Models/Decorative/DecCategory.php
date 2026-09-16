<?php

namespace App\Models\Decorative;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DecCategory extends Model
{
    use HasFactory;

    protected $table = 'dec_categories';
    protected $guarded = ['id'];

    public function products()
    {
        return $this->hasMany(DecProduct::class, 'category_id');
    }
}
