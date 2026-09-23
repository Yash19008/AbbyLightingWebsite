<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HomeSlider extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'path', 
        'mobile_path',
        'tablet_path',
        'for_mobile', 
        'sort_order', 
        'is_active',
        'url',
        'heading',
        'heading_highlight',
        'description',
        'button_text',
        'button_link'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'for_mobile' => 'boolean',
    ];
}
