<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LightWorld extends Model
{
    protected $fillable = [
        'name',
        'link',
        'light_of_image',
        'light_on_image',
        'sort_order',
    ];
}
