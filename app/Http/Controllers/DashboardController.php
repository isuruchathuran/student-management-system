<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display the main dashboard with summary statistics and recent records.
     */
    public function index()
    {
        $totalStudents = Student::count();
        $totalTeachers = Teacher::count();
        $totalSubjects = Subject::count();
        $activeCourses = Subject::count(); // Using subjects as active courses

        // Recent 5 students
        $recentStudents = Student::orderBy('created_at', 'desc')->take(5)->get();

        // Recent 5 teachers
        $recentTeachers = Teacher::orderBy('created_at', 'desc')->take(5)->get();

        // Monthly student registrations for the past 6 months
        $monthlyData = [];
        $monthlyLabels = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthlyLabels[] = $date->format('M Y');
            $monthlyData[] = Student::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();
        }

        // Recent activities (last 6 students + teachers combined)
        $recentActivities = collect();

        Student::orderBy('created_at', 'desc')->take(4)->get()->each(function ($s) use (&$recentActivities) {
            $recentActivities->push([
                'type'  => 'student',
                'name'  => $s->Name,
                'label' => 'New student registered',
                'time'  => $s->created_at,
                'icon'  => 'fa-user-graduate',
                'color' => 'blue',
            ]);
        });

        Teacher::orderBy('created_at', 'desc')->take(3)->get()->each(function ($t) use (&$recentActivities) {
            $recentActivities->push([
                'type'  => 'teacher',
                'name'  => $t->Teacher_Name,
                'label' => 'New teacher added',
                'time'  => $t->created_at,
                'icon'  => 'fa-chalkboard-user',
                'color' => 'green',
            ]);
        });

        $recentActivities = $recentActivities->sortByDesc('time')->take(6)->values();

        return view('dashboard', compact(
            'totalStudents',
            'totalTeachers',
            'totalSubjects',
            'activeCourses',
            'recentStudents',
            'recentTeachers',
            'monthlyData',
            'monthlyLabels',
            'recentActivities'
        ));
    }
}
