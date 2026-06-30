<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;

    protected $fillable = [
        'teacher_id',
        'Teacher_Name',
        'email',
        'password',
        'mobile_no',
        'subject',
        'qualification',
        'address',
        'gender',
        'dob',
        'age',
        'salary',
        'join_date',
    ];
}
