<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tag extends Model
{
    use HasFactory,SoftDeletes;
    protected $guarded = [
        'id'
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'show_categories' => 'boolean',
    ];

    /**
     * Scope to filter featured tags.
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', 1);
    }

    /**
     * Scope to filter active tags.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', 'yes');
    }

    /**
     * SubTag mappings for this tag.
     */
    public function subTagMappings()
    {
        return $this->hasMany(SubTagMapping::class, 'tag_id');
    }
}

