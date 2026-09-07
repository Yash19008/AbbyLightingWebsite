<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectSubTag extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'project_sub_tags';
    protected $guarded = [
       // 'id'
    ];
    public function sub_tag()
    {
        return $this->belongsTo('App\Models\SubTag');
    }
}
