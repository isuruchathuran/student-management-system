<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * Display reports overview with statistics and charts.
     */
    public function index()
    {
        $totalStudents = Student::count();
        $totalTeachers = Teacher::count();
        $totalSubjects = Subject::count();

        // Monthly student registrations — past 12 months
        $monthlyStudentData  = [];
        $monthlyTeacherData  = [];
        $monthlyLabels       = [];

        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthlyLabels[]      = $date->format('M Y');
            $monthlyStudentData[] = Student::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)->count();
            $monthlyTeacherData[] = Teacher::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)->count();
        }

        // All students for table
        $students = Student::orderBy('created_at', 'desc')->get();
        $teachers = Teacher::orderBy('created_at', 'desc')->get();
        $subjects = Subject::with('teacher')->orderBy('created_at', 'desc')->get();

        return view('reports.index', compact(
            'totalStudents',
            'totalTeachers',
            'totalSubjects',
            'monthlyLabels',
            'monthlyStudentData',
            'monthlyTeacherData',
            'students',
            'teachers',
            'subjects'
        ));
    }
}
