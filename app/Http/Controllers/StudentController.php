<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;

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

    public function store(Request $request){
        try {
            Student::query()->create([
                'reg_No'=>$request->reg_No,
                'Name'=>$request->name,
                'email'=>$request->email,
                'password'=>$request->password,
                'phone'=>$request->phone,
                'date_of_birth'=>$request->bod,
                'address'=>$request->address,
            ]);


            return redirect()->route('student.list')
                ->with('success', 'Student registered successfully!')
                ->with('title', 'Registered!');
        }
        catch (\Exception $e) {
            return $e;
        }
    }

//    public function edit($id)
//    {
//        $student = Student::query()
//            ->where('id', $id)
//            ->find();
//
//        return view('student_update', compact('student'));
//    }

    public function edit($id)
    {
        $student = Student::find($id);

        return view('student_update', compact('student'));
    }

    public function update(Request $request){
        try {

             Student::query()
                ->where('id', $request->id)
                 ->update([
                     'reg_No'=>$request->reg_No,
                     'Name'=>$request->name,
                     'email'=>$request->email,
                     'password'=>$request->password,
                     'phone'=>$request->phone,
                     'date_of_birth'=>$request->bod,
                     'address'=>$request->address
                 ]);


            return redirect()->route('student.list')
                ->with('success', 'Student updated successfully!')
                ->with('title', 'Updated!');
        }
        catch (\Exception $e) {
            return $e;
        }
    }

    public function delete($id)
    {
        try {
            Student::query()
                ->where('id', $id)
                ->delete();

            return redirect()->route('student.list')
                ->with('success', 'Student deleted successfully!');
        }
        catch (\Exception $e) {
            return $e;
        }
    }



}
