<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;

    protected $fillable = [
        'Teacher_Name',
        'address',
        'email',
        'mobile_no',
        'gender',
        'dob',
        'age',
        'qualification',
        'subject',
        'salary',
        'join_date',
    ];
}
