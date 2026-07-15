<?php

namespace App\Http\Controllers;

use App\Exports\StudentsExport;
use App\Imports\StudentsImport;
use App\Models\Student;
use App\Models\Subject;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class StudentController extends Controller
{
    /**
     * Display the student list with optional search filtering.
     */
    public function index(Request $request)
    {
        $search = $request->search;

        $students = Student::with('subjects');

        if ($search) {
            $students->where('reg_No', 'LIKE', "%{$search}%")
                ->orWhere('Name', 'LIKE', "%{$search}%")
                ->orWhere('email', 'LIKE', "%{$search}%")
                ->orWhere('phone', 'LIKE', "%{$search}%")
                ->orWhere('date_of_birth', 'LIKE', "%{$search}%");
        }

        $students = $students->get();
        $subjects = Subject::all();

        return view('students.index', compact('students', 'subjects'));
    }



    /**
     * JSON endpoint — returns the next registration number preview.
     * Used by the Register Student modal to display the auto-generated Reg No.
     */
    public function nextRegNo()
    {
        return response()->json([
            'reg_no' => $this->previewNextRegNo(),
        ]);
    }

    /**
     * Preview the next registration number without a transaction lock.
     * Used only for displaying in the form before submission.
     */
    private function previewNextRegNo(): string
    {
        $latest = Student::query()
            ->orderBy('id', 'desc')
            ->first();

        if ($latest && preg_match('/STU(\d+)/', $latest->reg_No, $matches)) {
            $nextNumber = (int) $matches[1] + 1;
        } else {
            $nextNumber = 1;
        }

        return 'STU' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
    }

    private function generateRegNo(): string
    {
        // Get the latest student by ID (highest ID = most recently created)
        $latest = Student::query()
            ->lockForUpdate()
            ->orderBy('id', 'desc')
            ->first();

        if ($latest && preg_match('/STU(\d+)/', $latest->reg_No, $matches)) {
            $nextNumber = (int) $matches[1] + 1;
        } else {
            // No students exist yet — start from 1
            $nextNumber = 1;
        }

        return 'STU' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Build a filtered student query from the request search param.
     * Shared by index(), exportPdf(), and exportExcel().
     */
    private function buildFilteredQuery(Request $request)
    {
        $search = $request->search;

        $query = Student::query();

        if ($search) {
            $query->where('reg_No', 'LIKE', "%{$search}%")
                ->orWhere('Name', 'LIKE', "%{$search}%")
                ->orWhere('email', 'LIKE', "%{$search}%")
                ->orWhere('phone', 'LIKE', "%{$search}%")
                ->orWhere('date_of_birth', 'LIKE', "%{$search}%")
                ->orWhere('address', 'LIKE', "%{$search}%");
        }

        return $query;
    }

    /**
     * Validate and store a new student record.
     * Registration number is auto-generated (not user-supplied).
     */
    public function store(Request $request)
    {
        // Validate incoming request fields (no subject limitation)
        $request->validate([
            'name'     => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:students,username',
            'email'    => 'required|email|max:255|unique:students,email',
            'phone'    => 'required|string|max:20',
            'bod'      => 'required|date',
            'password' => 'required|string|min:6',
            'address'  => 'required|string|max:500',
        ], [
            'email.unique'    => 'This email address is already registered to another student.',
            'username.unique' => 'This username is already taken.',
            'password.min'    => 'Password must be at least 6 characters.',
        ]);

        try {
            DB::transaction(function () use ($request) {
                // Auto-generate a unique registration number inside the transaction
                $regNo = $this->generateRegNo();

                $student = Student::create([
                    'reg_No'        => $regNo,
                    'Name'          => $request->name,
                    'username'      => $request->username,
                    'email'         => $request->email,
                    'password'      => \Illuminate\Support\Facades\Hash::make($request->password),
                    'phone'         => $request->phone,
                    'date_of_birth' => $request->bod,
                    'address'       => $request->address,
                ]);
                
                // Automatically enroll the student in ALL available subjects
                $student->subjects()->sync(Subject::pluck('id')->toArray());

                \App\Models\ActivityLog::create([
                    'user_type' => 'Admin',
                    'user_id' => auth()->guard('admin')->id(),
                    'action' => "Added new student: {$student->Name} ({$regNo})",
                ]);
            });

            return redirect()->route('admin.students.index')
                ->with('success', 'Student registered successfully!')
                ->with('title', 'Registered!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Registration failed: ' . $e->getMessage());
        }
    }

    /**
     * Show the edit form for a specific student.
     */
    public function edit($id)
    {
        $student = Student::with('subjects')->find($id);
        $subjects = Subject::all();

        return view('students.edit', compact('student', 'subjects'));
    }

    /**
     * Fetch student details and statistics for the profile modal.
     */
    public function show($id)
    {
        $student = Student::with('subjects')->findOrFail($id);
        
        $attempts = \App\Models\QuizAttempt::where('student_id', $id);
        
        $totalAttempts = $attempts->count();
        $completedAttempts = (clone $attempts)->where('status', 'completed')->count();
        $avgMarks = (clone $attempts)->where('status', 'completed')->avg('percentage') ?? 0;
        
        $latestResults = \App\Models\QuizAttempt::with(['quiz.subject'])
            ->where('student_id', $id)
            ->where('status', 'completed')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get()
            ->map(function($attempt) {
                return [
                    'quiz_title' => $attempt->quiz->title,
                    'subject' => $attempt->quiz->subject->subject_name ?? 'Unknown',
                    'percentage' => $attempt->percentage,
                    'date' => $attempt->created_at->format('M d, Y')
                ];
            });

        return response()->json([
            'student' => $student,
            'stats' => [
                'total_attempts' => $totalAttempts,
                'completed_quizzes' => $completedAttempts,
                'average_marks' => round($avgMarks, 1)
            ],
            'latest_results' => $latestResults
        ]);
    }

    /**
     * Validate and update an existing student record.
     * Registration number cannot be changed.
     */
    public function update(Request $request)
    {
        // Validate — email must be unique but ignore the current student's own record
        $request->validate([
            'name'     => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:students,username,' . $request->id,
            'email'    => 'required|email|max:255|unique:students,email,' . $request->id,
            'phone'    => 'required|string|max:20',
            'bod'      => 'required|date',
            'password' => 'nullable|string|min:6',
            'address'  => 'required|string|max:500',
        ], [
            'email.unique'    => 'This email address is already registered to another student.',
            'username.unique' => 'This username is already taken.',
            'password.min'    => 'Password must be at least 6 characters.',
        ]);

        try {
            DB::transaction(function () use ($request) {
                $student = Student::findOrFail($request->id);
                $data = [
                    'Name'          => $request->name,
                    'username'      => $request->username,
                    'email'         => $request->email,
                    'phone'         => $request->phone,
                    'date_of_birth' => $request->bod,
                    'address'       => $request->address,
                ];

                if (!empty($request->password)) {
                    $data['password'] = \Illuminate\Support\Facades\Hash::make($request->password);
                }

                $student->update($data);

                \App\Models\ActivityLog::create([
                    'user_type' => 'Admin',
                    'user_id' => auth()->guard('admin')->id(),
                    'action' => "Updated student: {$student->Name}",
                ]);
            });

            return redirect()->route('admin.students.index')
                ->with('success', 'Student updated successfully!')
                ->with('title', 'Updated!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Update failed: ' . $e->getMessage());
        }
    }

    /**
     * Delete a student record by ID.
     */
    public function delete($id)
    {
        try {
            $student = Student::query()
                ->where('id', $id)
                ->firstOrFail();
            $name = $student->Name;
            $student->delete();

            \App\Models\ActivityLog::create([
                'user_type' => 'Admin',
                'user_id' => auth()->guard('admin')->id(),
                'action' => "Deleted student: {$name}",
            ]);

            return redirect()->route('admin.students.index')
                ->with('success', 'Student deleted successfully!')
                ->with('title', 'Deleted!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Delete failed: ' . $e->getMessage());
        }
    }

    /**
     * Export filtered student records as a downloadable PDF.
     * Respects the ?search= query parameter to export only matching records.
     */
    public function exportPdf(Request $request)
    {
        $students    = $this->buildFilteredQuery($request)->orderBy('reg_No')->get();
        $generatedAt = now()->format('F d, Y \a\t h:i A');
        $search      = $request->search;

        $pdf = Pdf::loadView('pdf.student_pdf', compact('students', 'generatedAt', 'search'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('student-report-' . now()->format('Y-m-d') . '.pdf');
    }

    /**
     * Export filtered student records as a downloadable Excel (.xlsx) file.
     * Respects the ?search= query parameter to export only matching records.
     * Columns: Registration No, Full Name, Email, Phone No, Birthday, Address.
     */
    public function exportExcel(Request $request)
    {
        $students = $this->buildFilteredQuery($request)->orderBy('reg_No')->get();
        $filename = 'students-report-' . now()->format('Y-m-d') . '.xlsx';

        return Excel::download(new StudentsExport($students), $filename);
    }

    /**
     * Handle Excel file upload and import students.
     * Returns flash messages with import statistics.
     */
    public function importExcel(Request $request)
    {
        // Validate the uploaded file
        $validated = $request->validate([
            'excel_file' => 'required|file|mimes:xlsx,xls|max:5120',
        ], [
            'excel_file.required' => 'Please select an Excel file to upload.',
            'excel_file.mimes'    => 'Only .xlsx and .xls files are allowed.',
            'excel_file.max'      => 'File size must not exceed 5 MB.',
        ]);

        try {
            $import = new StudentsImport();
            Excel::import($import, $request->file('excel_file'));

            return redirect()->route('admin.students.index')
                ->with('success', "Students imported successfully. ({$import->importedCount} records)")
                ->with('title', 'Success!');

        } catch (\Exception $e) {
            $message = $e->getMessage();
            \Log::error('Excel Import Error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            if (!str_contains($message, 'Import Failed')) {
                $message = 'Import failed. Please try again. Error: ' . $message;
            }
            return redirect()->route('admin.students.index')
                ->with('error', $message)
                ->with('error_title', 'Error!');
        }
    }
}
