<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

//Dashboard View Admin controller
class AdminController extends Controller
{
    public function home()
    {
        return view('create_student');
    }
    public function about()
    {
        return view('about');
    }



}
