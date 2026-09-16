<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'projects';
    protected $guarded = [
        'id'
    ];
    protected $casts = [
        'is_featured' => 'boolean',
        'sequence' => 'integer',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', 'yes');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', 1);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sequence', 'ASC')->orderBy('id', 'DESC');
    }

    public function projectImages()
    {
        return $this->hasMany('App\Models\ProjectImage');
    }
    public function projectSubTags()
    {
        return $this->hasMany('App\Models\ProjectSubTag');
    }
}
