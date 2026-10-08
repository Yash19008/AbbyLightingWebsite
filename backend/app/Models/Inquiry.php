<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Inquiry extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'inquiries';

    protected $fillable  = ['type', 'reference', 'name', 'phone', 'email', 'city', 'company', 'role', 'message'];
    protected $guarded = [
        'id'
    ];
}
