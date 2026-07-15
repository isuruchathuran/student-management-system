<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\Question;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\Result;
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
        $totalQuestions = Question::count();
        $totalQuizzes = Quiz::count();
        $publishedQuizzes = Quiz::where('status', 'Published')->count();
        $quizAttempts = QuizAttempt::count();
        $activeCourses = Subject::count();

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

        // Recent activities from ActivityLog
        $recentActivities = \App\Models\ActivityLog::orderBy('created_at', 'desc')->take(6)->get();

        return view('dashboard', compact(
            'totalStudents',
            'totalTeachers',
            'totalSubjects',
            'totalQuestions',
            'totalQuizzes',
            'publishedQuizzes',
            'quizAttempts',
            'activeCourses',
            'recentStudents',
            'recentTeachers',
            'monthlyData',
            'monthlyLabels',
            'recentActivities'
        ));
    }
}
