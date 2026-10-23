<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubTagMapping extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'sub_tag_mappings';
    protected $guarded = [
        'id'
    ];
    public function tag()
    {
        return $this->belongsTo('App\Models\Tag');
    }

    public function subTag()
    {
        return $this->belongsTo('App\Models\SubTag', 'sub_tag_id');
    }
}
