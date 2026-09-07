<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EventImage extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'event_images';
    protected $guarded = [
        'id'
    ];
    public function event()
    {
        return $this->belongsTo('App\Models\Event', 'event_id');
    }
}
