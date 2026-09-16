<?php

namespace App\Models\Decorative;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DecSpecTemplate extends Model
{
    use HasFactory;

    protected $table = 'dec_spec_templates';
    protected $guarded = ['id'];
    
    protected $casts = [
        'structure' => 'array',
    ];
}
