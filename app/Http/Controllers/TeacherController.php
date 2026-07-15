<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\ActivityLog;

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
                ->orWhereHas('subject', function ($q) use ($search) {
                    $q->where('subject_name', 'LIKE', "%{$search}%");
                })
                ->orWhere('qualification', 'LIKE', "%{$search}%");
        }

        $teachers = $teachers->with('subject')->orderBy('id', 'desc')->get();
        
        // Only show subjects that are not assigned to any teacher
        $availableSubjects = Subject::whereDoesntHave('teacher')->get();
        // The view also needs all subjects to correctly populate the select when editing
        $allSubjects = Subject::all();

        return view('teachers.index', compact('teachers', 'availableSubjects', 'allSubjects'));
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
            'subject_id'    => 'required|exists:subjects,id|unique:teachers,subject_id',
            'qualification' => 'required|string|max:255',
            'address'       => 'required|string|max:500',
        ], [
            'email.unique'       => 'This email address is already registered to another teacher.',
            'subject_id.unique'  => 'This subject is already assigned to another teacher.',
            'password.min'       => 'Password must be at least 6 characters.',
        ]);

        try {
            DB::transaction(function () use ($request) {
                $teacherId = $this->generateTeacherId();

                $teacher = Teacher::create([
                    'teacher_id'    => $teacherId,
                    'Teacher_Name'  => $request->name,
                    'email'         => $request->email,
                    'password'      => Hash::make($request->password),
                    'mobile_no'     => $request->mobile_no,
                    'qualification' => $request->qualification,
                    'address'       => $request->address,
                    'subject_id'    => $request->subject_id,
                    // Legacy fields with defaults
                    'gender'        => 'N/A',
                    'dob'           => now()->format('Y-m-d'),
                    'age'           => 0,
                    'salary'        => 0,
                    'join_date'     => now()->format('Y-m-d'),
                ]);

                ActivityLog::create([
                    'user_type' => 'Admin',
                    'user_id' => auth()->guard('admin')->id(),
                    'action' => "Added new teacher: {$teacher->Teacher_Name} ({$teacherId})",
                ]);
            });

            return redirect()->route('admin.teachers.index')
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
            'id'            => 'required|exists:teachers,id',
            'name'          => 'required|string|max:255',
            'email'         => 'required|email|max:255|unique:teachers,email,' . $request->id,
            'password'      => 'nullable|string|min:6',
            'mobile_no'     => 'required|string|max:20',
            'subject_id'    => 'required|exists:subjects,id|unique:teachers,subject_id,' . $request->id,
            'qualification' => 'required|string|max:255',
            'address'       => 'required|string|max:500',
        ], [
            'email.unique'       => 'This email address is already registered to another teacher.',
            'subject_id.unique'  => 'This subject is already assigned to another teacher.',
            'password.min'       => 'Password must be at least 6 characters.',
        ]);

        try {
            DB::transaction(function () use ($request) {
                $teacher = Teacher::findOrFail($request->id);
                
                $data = [
                    'Teacher_Name'  => $request->name,
                    'email'         => $request->email,
                    'mobile_no'     => $request->mobile_no,
                    'qualification' => $request->qualification,
                    'address'       => $request->address,
                    'subject_id'    => $request->subject_id,
                ];

                if (!empty($request->password)) {
                    $data['password'] = Hash::make($request->password);
                }

                $teacher->update($data);

                ActivityLog::create([
                    'user_type' => 'Admin',
                    'user_id' => auth()->guard('admin')->id(),
                    'action' => "Updated teacher: {$teacher->Teacher_Name}",
                ]);
            });

            return redirect()->route('admin.teachers.index')
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
            $teacher = Teacher::findOrFail($id);
            $name = $teacher->Teacher_Name;
            $teacher->delete();

            ActivityLog::create([
                'user_type' => 'Admin',
                'user_id' => auth()->guard('admin')->id(),
                'action' => "Deleted teacher: {$name}",
            ]);

            return redirect()->route('admin.teachers.index')
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
