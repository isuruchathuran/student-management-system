<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    /**
     * Display subject list with optional search.
     */
    public function index(Request $request)
    {
        $search = $request->search;

        $subjects = Subject::with('teacher');

        if ($search) {
            $subjects->where('subject_code', 'LIKE', "%{$search}%")
                ->orWhere('subject_name', 'LIKE', "%{$search}%")
                ->orWhere('semester', 'LIKE', "%{$search}%");
        }

        $subjects = $subjects->orderBy('id', 'desc')->get();
        $teachers = Teacher::orderBy('Teacher_Name')->get();

        return view('subjects.index', compact('subjects', 'teachers'));
    }

    /**
     * Store a new subject record.
     */
    public function store(Request $request)
    {
        $request->validate([
            'subject_code' => 'required|string|max:50|unique:subjects,subject_code',
            'subject_name' => 'required|string|max:255',
            'credits'      => 'required|integer|min:1|max:10',
            'semester'     => 'required|string|max:50',
        ], [
            'subject_code.unique' => 'This subject code already exists.',
        ]);

        try {
            $subject = Subject::query()->create([
                'subject_code' => strtoupper($request->subject_code),
                'subject_name' => $request->subject_name,
                'subject_type' => 'Core',
                'credit_hours' => $request->credits,
                'semester'     => $request->semester,
                'credits'      => $request->credits,
            ]);
            
            // Automatically enroll all existing students in the new subject
            $subject->students()->sync(\App\Models\Student::pluck('id')->toArray());

            return redirect()->route('admin.subjects.index')
                ->with('success', 'Subject added successfully!')
                ->with('title', 'Added!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to add subject: ' . $e->getMessage());
        }
    }

    /**
     * Update an existing subject record.
     */
    public function update(Request $request)
    {
        $request->validate([
            'subject_code' => 'required|string|max:50|unique:subjects,subject_code,' . $request->id,
            'subject_name' => 'required|string|max:255',
            'credits'      => 'required|integer|min:1|max:10',
            'semester'     => 'required|string|max:50',
        ], [
            'subject_code.unique' => 'This subject code already exists.',
        ]);

        try {
            Subject::query()->where('id', $request->id)->update([
                'subject_code' => strtoupper($request->subject_code),
                'subject_name' => $request->subject_name,
                'credit_hours' => $request->credits,
                'semester'     => $request->semester,
                'credits'      => $request->credits,
            ]);

            return redirect()->route('admin.subjects.index')
                ->with('success', 'Subject updated successfully!')
                ->with('title', 'Updated!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Update failed: ' . $e->getMessage());
        }
    }

    /**
     * Delete a subject record.
     */
    public function delete($id)
    {
        try {
            Subject::query()->where('id', $id)->delete();

            return redirect()->route('admin.subjects.index')
                ->with('success', 'Subject deleted successfully!')
                ->with('title', 'Deleted!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Delete failed: ' . $e->getMessage());
        }
    }

    /**
     * Get next subject code for Add Subject modal.
     */
    public function nextSubjectCode()
    {
        $lastSubject = Subject::query()
            ->where('subject_code', 'like', 'SUB%')
            ->orderBy('id', 'desc')
            ->first();

        if ($lastSubject) {
            $lastCode = $lastSubject->subject_code;
            $number = (int) substr($lastCode, 3);
            $newNumber = str_pad($number + 1, 3, '0', STR_PAD_LEFT);
            $newCode = 'SUB' . $newNumber;
        } else {
            $newCode = 'SUB001';
        }

        return response()->json(['subject_code' => $newCode]);
    }
}
