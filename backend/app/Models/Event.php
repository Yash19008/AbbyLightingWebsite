<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'events';
    protected $guarded = [
        'id'
    ];

    public function eventImages()
    {
        return $this->hasMany('App\Models\EventImage');
    }
}
