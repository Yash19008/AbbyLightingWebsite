<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PasswordReset extends Model
{
    use HasFactory;
    protected $table = 'password_resets';
    protected $guarded = [
        'id'
    ];
    public $timestamps = false;
    public function user(){
        return $this->belongsTo('App\Models\User', 'user_id');
    }
}
