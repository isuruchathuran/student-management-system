<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\Student;
use App\Models\Question;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Admin
        Admin::firstOrCreate(
            ['email' => 'admin123@gmail.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('admin123')
            ]
        );

        // 2. Subjects
        $s1 = Subject::firstOrCreate(['subject_code' => 'CS101'], ['subject_name' => 'Programming Fundamentals', 'subject_type' => 'Core', 'credit_hours' => 3, 'semester' => 1, 'credits' => 3]);
        $s2 = Subject::firstOrCreate(['subject_code' => 'CS102'], ['subject_name' => 'Database Management Systems', 'subject_type' => 'Core', 'credit_hours' => 3, 'semester' => 2, 'credits' => 3]);

        // 3. Teachers
        $t1 = Teacher::firstOrCreate(
            ['email' => 'teacher1@codxpress.lk'],
            [
                'teacher_id' => 'TCH002',
                'Teacher_Name' => 'Teacher One',
                'password' => Hash::make('password'),
                'mobile_no' => '0771234567',
                'subject_id' => $s1->id,
                'qualification' => 'MSc CS',
                'address' => 'Colombo',
                'gender' => 'Male',
                'dob' => '1990-01-01',
                'age' => 35,
                'salary' => 50000,
                'join_date' => '2020-01-01',
            ]
        );

        // 4. Student
        $stu = Student::firstOrCreate(
            ['email' => 'student1@codxpress.lk'],
            [
                'reg_No' => 'STU002',
                'Name' => 'Student One',
                'password' => Hash::make('password'),
                'phone' => '0712345678',
                'date_of_birth' => '2000-01-01',
                'address' => 'Kandy',
            ]
        );

        // 5. Sync subjects for student
        $stu->subjects()->syncWithoutDetaching([$s1->id]);

        // 6. Questions
        if (Question::count() == 0) {
            Question::create([
                'teacher_id' => $t1->id,
                'subject_id' => $s1->id,
                'question_text' => 'What is PHP?',
                'option_a' => 'Personal Home Page',
                'option_b' => 'Private Hosting Platform',
                'option_c' => 'PHP: Hypertext Preprocessor',
                'option_d' => 'Programing HTTP Protocol',
                'correct_option' => 'C',
            ]);
            Question::create([
                'teacher_id' => $t1->id,
                'subject_id' => $s1->id,
                'question_text' => 'What does HTML stand for?',
                'option_a' => 'Hyper Text Markup Language',
                'option_b' => 'Home Tool Markup Language',
                'option_c' => 'Hyperlinks and Text Markup Language',
                'option_d' => 'Hyper Tool Markup Language',
                'correct_option' => 'A',
            ]);
        }
    }
}
