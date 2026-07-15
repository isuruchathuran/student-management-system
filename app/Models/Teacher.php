<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Teacher extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'teacher_id',
        'Teacher_Name',
        'email',
        'password',
        'mobile_no',
        'qualification',
        'address',
        'gender',
        'dob',
        'age',
        'salary',
        'join_date',
        'subject_id',
    ];

    protected $hidden = [
        'password',
    ];

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    public function questions()
    {
        return $this->hasMany(Question::class, 'teacher_id');
    }

    public function quizzes()
    {
        return $this->hasMany(Quiz::class, 'teacher_id');
    }
}
