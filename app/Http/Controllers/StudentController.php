<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function dashboard()
    {
        return view('student.dashboard');
    }

    public function profile(Request $request)
    {
        if ($request->isMethod('post')) {
            // TODO: Implementasikan logika pembaruan profil dan CV
        }
        return view('student.profile');
    }

    public function applications(Request $request)
    {
        return view('student.application');
    }
}
