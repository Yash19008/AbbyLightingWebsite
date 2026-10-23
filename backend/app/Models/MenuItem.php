<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
    protected $fillable = ['parent_id', 'type', 'title', 'url', 'location', 'order', 'is_active'];

    public function children()
    {
        return $this->hasMany(MenuItem::class, 'parent_id')->ordered();
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order', 'asc');
    }

    protected static function booted()
    {
        $clearCache = function () {
            \Illuminate\Support\Facades\Cache::forget('header_ssr_mega_items');
            \Illuminate\Support\Facades\Cache::forget('footer_ssr_col_1');
            \Illuminate\Support\Facades\Cache::forget('footer_ssr_col_2');
            \Illuminate\Support\Facades\Cache::forget('footer_ssr_col_3');
        };

        static::saved($clearCache);
        static::deleted($clearCache);
    }
}
