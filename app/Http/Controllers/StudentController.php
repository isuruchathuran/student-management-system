<?php

namespace App\Http\Controllers;

use App\Exports\StudentsExport;
use App\Models\Student;
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

        $students = Student::query();

        if ($search) {
            $students->where('reg_No', 'LIKE', "%{$search}%")
                ->orWhere('Name', 'LIKE', "%{$search}%")
                ->orWhere('email', 'LIKE', "%{$search}%")
                ->orWhere('phone', 'LIKE', "%{$search}%")
                ->orWhere('date_of_birth', 'LIKE', "%{$search}%");
        }

        $students = $students->get();

        return view('component.student_list', compact('students'));
    }

    /**
     * Show the student registration / dashboard page.
     */
    public function dashboard()
    {
        // Pre-compute the next registration number so the form can display it
        $nextRegNo = $this->previewNextRegNo();

        return view('dashboard', compact('nextRegNo'));
    }

    /**
     * Generate the next sequential registration number.
     * Format: STU001, STU002, STU003 ...
     * Uses a DB transaction with lock to prevent duplicate reg numbers.
     */
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
     * Validate and store a new student record.
     * Registration number is auto-generated (not user-supplied).
     */
    public function store(Request $request)
    {
        // Validate incoming request fields
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|max:255|unique:students,email',
            'phone'    => 'required|string|max:20',
            'bod'      => 'required|date',
            'password' => 'required|string|min:6',
            'address'  => 'required|string|max:500',
        ], [
            'email.unique'    => 'This email address is already registered to another student.',
            'password.min'    => 'Password must be at least 6 characters.',
        ]);

        try {
            DB::transaction(function () use ($request) {
                // Auto-generate a unique registration number inside the transaction
                $regNo = $this->generateRegNo();

                Student::query()->create([
                    'reg_No'        => $regNo,
                    'Name'          => $request->name,
                    'email'         => $request->email,
                    'password'      => $request->password,
                    'phone'         => $request->phone,
                    'date_of_birth' => $request->bod,
                    'address'       => $request->address,
                ]);
            });

            return redirect()->route('student.list')
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
        $student = Student::find($id);

        return view('student_update', compact('student'));
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
            'email'    => 'required|email|max:255|unique:students,email,' . $request->id,
            'phone'    => 'required|string|max:20',
            'bod'      => 'required|date',
            'password' => 'required|string|min:6',
            'address'  => 'required|string|max:500',
        ], [
            'email.unique'    => 'This email address is already registered to another student.',
            'password.min'    => 'Password must be at least 6 characters.',
        ]);

        try {
            Student::query()
                ->where('id', $request->id)
                ->update([
                    'Name'          => $request->name,
                    'email'         => $request->email,
                    'password'      => $request->password,
                    'phone'         => $request->phone,
                    'date_of_birth' => $request->bod,
                    'address'       => $request->address,
                ]);

            return redirect()->route('student.list')
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
            Student::query()
                ->where('id', $id)
                ->delete();

            return redirect()->route('student.list')
                ->with('success', 'Student deleted successfully!')
                ->with('title', 'Deleted!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Delete failed: ' . $e->getMessage());
        }
    }

    /**
     * Export all student records as a downloadable PDF.
     */
    public function exportPdf()
    {
        $students    = Student::orderBy('reg_No')->get();
        $generatedAt = now()->format('F d, Y \a\t h:i A');

        $pdf = Pdf::loadView('pdf.student_pdf', compact('students', 'generatedAt'))
            ->setPaper('a4', 'Portrait');

        return $pdf->download('student-report-' . now()->format('Y-m-d') . '.pdf');
    }

    /**
     * Export all student records as a downloadable Excel (.xlsx) file.
     * Columns: Registration No, Full Name, Email, Phone No, Address, Created Date.
     */
    public function exportExcel()
    {
        $filename = 'students-report-' . now()->format('Y-m-d') . '.xlsx';

        return Excel::download(new StudentsExport(), $filename);
    }
}
