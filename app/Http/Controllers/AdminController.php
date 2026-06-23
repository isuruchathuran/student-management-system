<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

//Dashboard View Admin controller
class AdminController extends Controller
{
    public function home()
    {
        return view('dashboard');
    }
    public function about()
    {
        return view('about');
    }



}
