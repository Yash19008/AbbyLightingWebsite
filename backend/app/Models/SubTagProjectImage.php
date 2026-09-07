<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubTagProjectImage extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'sub_tag_project_images';
    protected $guarded = [
        'id'
    ];
    public function project()
    {
        return $this->belongsTo('App\Models\Project');
    }
}
