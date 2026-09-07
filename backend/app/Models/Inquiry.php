<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Inquiry extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'inquiries';

    protected $fillable  = ['full_name','company','email','phone','position','city','country','website','profession','interested_in','industry_of_interest','i_message'];
    protected $guarded = [
        'id'
    ];
}
