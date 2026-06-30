<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TeacherController extends Controller
{
    /**
     * Display teacher list with optional search.
     */
    public function index(Request $request)
    {
        $search = $request->search;

        $teachers = Teacher::query();

        if ($search) {
            $teachers->where('teacher_id', 'LIKE', "%{$search}%")
                ->orWhere('Teacher_Name', 'LIKE', "%{$search}%")
                ->orWhere('email', 'LIKE', "%{$search}%")
                ->orWhere('mobile_no', 'LIKE', "%{$search}%")
                ->orWhere('subject', 'LIKE', "%{$search}%")
                ->orWhere('qualification', 'LIKE', "%{$search}%");
        }

        $teachers = $teachers->orderBy('id', 'desc')->get();

        return view('teachers.index', compact('teachers'));
    }

    /**
     * Store a new teacher record.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'required|email|max:255|unique:teachers,email',
            'password'      => 'required|string|min:6',
            'mobile_no'     => 'required|string|max:20',
            'subject'       => 'required|string|max:255',
            'qualification' => 'required|string|max:255',
            'address'       => 'required|string|max:500',
        ], [
            'email.unique'   => 'This email address is already registered to another teacher.',
            'password.min'   => 'Password must be at least 6 characters.',
        ]);

        try {
            DB::transaction(function () use ($request) {
                $teacherId = $this->generateTeacherId();

                Teacher::query()->create([
                    'teacher_id'    => $teacherId,
                    'Teacher_Name'  => $request->name,
                    'email'         => $request->email,
                    'password'      => $request->password,
                    'mobile_no'     => $request->mobile_no,
                    'subject'       => $request->subject,
                    'qualification' => $request->qualification,
                    'address'       => $request->address,
                    // Legacy fields with defaults
                    'gender'        => 'N/A',
                    'dob'           => now()->format('Y-m-d'),
                    'age'           => 0,
                    'salary'        => 0,
                    'join_date'     => now()->format('Y-m-d'),
                ]);
            });

            return redirect()->route('teachers.index')
                ->with('success', 'Teacher added successfully!')
                ->with('title', 'Added!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to add teacher: ' . $e->getMessage());
        }
    }

    /**
     * Update an existing teacher record.
     */
    public function update(Request $request)
    {
        $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'required|email|max:255|unique:teachers,email,' . $request->id,
            'password'      => 'required|string|min:6',
            'mobile_no'     => 'required|string|max:20',
            'subject'       => 'required|string|max:255',
            'qualification' => 'required|string|max:255',
            'address'       => 'required|string|max:500',
        ], [
            'email.unique'  => 'This email address is already registered to another teacher.',
            'password.min'  => 'Password must be at least 6 characters.',
        ]);

        try {
            Teacher::query()->where('id', $request->id)->update([
                'Teacher_Name'  => $request->name,
                'email'         => $request->email,
                'password'      => $request->password,
                'mobile_no'     => $request->mobile_no,
                'subject'       => $request->subject,
                'qualification' => $request->qualification,
                'address'       => $request->address,
            ]);

            return redirect()->route('teachers.index')
                ->with('success', 'Teacher updated successfully!')
                ->with('title', 'Updated!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Update failed: ' . $e->getMessage());
        }
    }

    /**
     * Delete a teacher record.
     */
    public function delete($id)
    {
        try {
            Teacher::query()->where('id', $id)->delete();

            return redirect()->route('teachers.index')
                ->with('success', 'Teacher deleted successfully!')
                ->with('title', 'Deleted!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Delete failed: ' . $e->getMessage());
        }
    }

    /**
     * Generate a unique teacher ID (e.g. TCH001).
     */
    private function generateTeacherId(): string
    {
        $latest = Teacher::query()
            ->lockForUpdate()
            ->orderBy('id', 'desc')
            ->first();

        if ($latest && preg_match('/TCH(\d+)/', $latest->teacher_id ?? '', $matches)) {
            $nextNumber = (int) $matches[1] + 1;
        } else {
            $nextNumber = 1;
        }

        return 'TCH' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
    }

    /**
     * JSON endpoint — returns next Teacher ID preview.
     */
    public function nextTeacherId()
    {
        $latest = Teacher::query()->orderBy('id', 'desc')->first();

        if ($latest && preg_match('/TCH(\d+)/', $latest->teacher_id ?? '', $matches)) {
            $nextNumber = (int) $matches[1] + 1;
        } else {
            $nextNumber = 1;
        }

        return response()->json([
            'teacher_id' => 'TCH' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT),
        ]);
    }
}
