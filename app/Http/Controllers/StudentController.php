<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StudentController extends Controller
{
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

    public function dashboard()
    {
        return view('dashboard');
    }

    /**
     * Generate the next sequential registration number.
     * Uses a DB transaction with lock to prevent duplicate reg numbers.
     */
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
            $nextNumber = 1;
        }

        return 'STU' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
    }

    public function store(Request $request)
    {
        try {
            DB::transaction(function () use ($request) {
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

    public function edit($id)
    {
        $student = Student::find($id);

        return view('student_update', compact('student'));
    }

    public function update(Request $request)
    {
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
        $students = Student::orderBy('reg_No')->get();
        $generatedAt = now()->format('F d, Y \a\t h:i A');

        $pdf = Pdf::loadView('pdf.student_pdf', compact('students', 'generatedAt'))
            ->setPaper('a4', 'Portrait');

        return $pdf->download('student-report-' . now()->format('Y-m-d') . '.pdf');
    }
}
