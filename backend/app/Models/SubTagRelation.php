<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubTagRelation extends Model
{
    use HasFactory;
    
    protected $table = 'sub_tag_relations';

    protected $fillable = [
        'sub_tag_id',
        'linked_sub_tag_id',
        'is_active',
        'created_by',
    ];

    public function subTag()
    {
        return $this->belongsTo(SubTag::class, 'sub_tag_id');
    }

    public function linkedSubTag()
    {
        return $this->belongsTo(SubTag::class, 'linked_sub_tag_id');
    }
}
