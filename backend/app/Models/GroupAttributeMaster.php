<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GroupAttributeMaster extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'group_attribute_masters';
    protected $guarded = [
        'id'
    ];
    public function group(){
        return $this->belongsTo('App\Models\GroupMaster', 'group_id');
    }   
    public function productAtt(){
        return $this->belongsTo('App\Models\ProductAttribute', 'id');
    }
}
