<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectImage extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'project_images';
    protected $guarded = [
        'id'
    ];
    public function project()
    {
        return $this->belongsTo('App\Models\Project', 'project_id');
    }
}
