<?php

namespace App\Models\Decorative;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DecSpecAttribute extends Model
{
    use HasFactory;

    protected $table = 'dec_spec_attributes';
    protected $guarded = ['id'];
}
