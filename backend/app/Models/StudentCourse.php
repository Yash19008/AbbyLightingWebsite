<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentCourse extends Model
{
    use HasFactory;
    protected $table = 'student_courses';
    protected $guarded = [
        'id'
    ];
    public function user(){
        return $this->belongsTo('App\Models\User', 'student_id');
    }
    public function course(){
        return $this->belongsTo('App\Models\Course', 'course_id');
    }
    public function status(){
        return $this->belongsTo('App\Models\StatusMaster', 'status_id');
    }
}
