<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GroupMaster extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'group_masters';
    protected $guarded = [
        'id'
    ];
}
