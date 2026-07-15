<?php

use Illuminate\Support\Facades\Route;

// Auth Controllers
use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\Auth\TeacherAuthController;
use App\Http\Controllers\Auth\StudentAuthController;

// Admin Controllers
use App\Http\Controllers\DashboardController as AdminDashboardController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\StudentController;

// Teacher Controllers
use App\Http\Controllers\Teacher\DashboardController as TeacherDashboardController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\Teacher\QuizController;

// Student Controllers
use App\Http\Controllers\Student\DashboardController as StudentDashboardController;
use App\Http\Controllers\StudentExamController;
use App\Http\Controllers\ResultController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
})->name('home');

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login']);
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');

    Route::middleware(['admin', 'prevent-back-history'])->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        
        // Activity Logs
        Route::get('/activity-logs', [\App\Http\Controllers\ActivityLogController::class, 'index'])->name('activity-logs');
        
        // Quizzes (Readonly for Admin)
        Route::get('/quizzes', [\App\Http\Controllers\AdminQuizController::class, 'index'])->name('quizzes.index');
        
        // Results (Admin view)
        Route::get('/results', [\App\Http\Controllers\ResultController::class, 'adminIndex'])->name('results.index');
        Route::get('/results/export-pdf', [\App\Http\Controllers\ResultController::class, 'exportPdf'])->name('results.export.pdf');
        Route::get('/results/export-excel', [\App\Http\Controllers\ResultController::class, 'exportExcel'])->name('results.export.excel');

        // Subjects
        Route::prefix('subjects')->name('subjects.')->group(function () {
            Route::get('/',            [SubjectController::class, 'index'])->name('index');
            Route::post('/save',       [SubjectController::class, 'store'])->name('store');
            Route::get('/delete/{id}', [SubjectController::class, 'delete'])->name('delete');
            Route::post('/update',     [SubjectController::class, 'update'])->name('update');
            Route::get('/next-subject-code', [SubjectController::class, 'nextSubjectCode'])->name('next-subject-code');
        });

        // Teachers
        Route::prefix('teachers')->name('teachers.')->group(function () {
            Route::get('/',              [TeacherController::class, 'index'])->name('index');
            Route::post('/save',         [TeacherController::class, 'store'])->name('store');
            Route::get('/delete/{id}',   [TeacherController::class, 'delete'])->name('delete');
            Route::post('/update',       [TeacherController::class, 'update'])->name('update');
            Route::get('/next-teacher-id', [TeacherController::class, 'nextTeacherId'])->name('next-teacher-id');
        });

        // Students
        Route::prefix('students')->name('students.')->group(function () {
            Route::get('/',            [StudentController::class, 'index'])->name('index');
            Route::post('/save',       [StudentController::class, 'store'])->name('store');
            Route::get('/delete/{id}', [StudentController::class, 'delete'])->name('delete');
            Route::get('/edit/{id}',   [StudentController::class, 'edit'])->name('edit');
            Route::post('/update',     [StudentController::class, 'update'])->name('update');
            Route::get('/export-pdf',   [StudentController::class, 'exportPdf'])->name('export.pdf');
            Route::get('/export-excel', [StudentController::class, 'exportExcel'])->name('export.excel');
            Route::post('/import',      [StudentController::class, 'importExcel'])->name('import');
            Route::get('/next-reg-no', [StudentController::class, 'nextRegNo'])->name('next-reg-no');
            Route::get('/{id}/details', [StudentController::class, 'show'])->name('show');
        });
    });
});

/*
|--------------------------------------------------------------------------
| Teacher Routes
|--------------------------------------------------------------------------
*/
Route::prefix('teacher')->name('teacher.')->group(function () {
    Route::get('/login', [TeacherAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [TeacherAuthController::class, 'login']);
    Route::post('/logout', [TeacherAuthController::class, 'logout'])->name('logout');

    Route::middleware(['teacher', 'prevent-back-history'])->group(function () {
        Route::get('/dashboard', [TeacherDashboardController::class, 'index'])->name('dashboard');

        Route::prefix('questions')->name('questions.')->group(function () {
            Route::get('/', [QuestionController::class, 'index'])->name('index');
            Route::post('/store', [QuestionController::class, 'store'])->name('store');
            Route::post('/update/{id}', [QuestionController::class, 'update'])->name('update');
            Route::get('/delete/{id}', [QuestionController::class, 'destroy'])->name('delete');
            Route::post('/publish/{id}', [QuestionController::class, 'publish'])->name('publish');
        });

        Route::prefix('quizzes')->name('quizzes.')->group(function () {
            Route::get('/', [QuizController::class, 'index'])->name('index');
            Route::get('/create', [QuizController::class, 'create'])->name('create');
            Route::post('/store', [QuizController::class, 'store'])->name('store');
            Route::post('/update/{id}', [QuizController::class, 'update'])->name('update');
            Route::get('/delete/{id}', [QuizController::class, 'destroy'])->name('delete');
            Route::get('/{id}/questions', [QuizController::class, 'questions'])->name('questions');
        });
        
        Route::get('/results', [ResultController::class, 'teacherIndex'])->name('results.index');
    });
});

/*
|--------------------------------------------------------------------------
| Student Routes
|--------------------------------------------------------------------------
*/
Route::prefix('student')->name('student.')->group(function () {
    Route::get('/login', [StudentAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [StudentAuthController::class, 'login']);
    Route::post('/logout', [StudentAuthController::class, 'logout'])->name('logout');

    Route::middleware(['student', 'prevent-back-history'])->group(function () {
        Route::get('/dashboard', [StudentDashboardController::class, 'index'])->name('dashboard');

        Route::prefix('exam')->name('exam.')->group(function () {
            Route::get('/', [StudentExamController::class, 'index'])->name('index');
            Route::get('/{quiz_id}', [StudentExamController::class, 'show'])->name('show');
            Route::post('/{quiz_id}/submit', [StudentExamController::class, 'submitExam'])->name('submit');
        });

        Route::get('/results', [ResultController::class, 'studentIndex'])->name('results.index');
    });
});
