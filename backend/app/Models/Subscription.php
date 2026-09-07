<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subscription extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'subscriptions';

    protected $fillable  = ['name', 'email', 'verified_at', 'verification_code'];
    protected $guarded = [
        'id'
    ];
}
