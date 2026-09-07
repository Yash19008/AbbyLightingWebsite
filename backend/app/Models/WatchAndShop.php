<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WatchAndShop extends Model
{
    use HasFactory;

    protected $table = 'watch_and_shops';

    protected $fillable = [
        'title',
        'thumbnail',
        'video_type',
        'video_url',
        'product_name',
        'product_link',
        'display_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'display_order' => 'integer',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
