<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = [
        'subject_code',
        'subject_name',
        'subject_type',
        'credit_hours',
        'semester',
        'credits',
    ];

    public function teacher()
    {
        return $this->hasOne(Teacher::class, 'subject_id');
    }

    public function students()
    {
        return $this->belongsToMany(Student::class, 'student_subject', 'subject_id', 'student_id');
    }

    public function questions()
    {
        return $this->hasMany(Question::class, 'subject_id');
    }

    public function quizzes()
    {
        return $this->hasMany(Quiz::class, 'subject_id');
    }
}
