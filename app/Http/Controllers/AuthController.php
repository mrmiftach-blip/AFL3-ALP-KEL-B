<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        if ($request->isMethod('post')) {
            // TODO: Implementasikan logika login
            return redirect()->route('home');
        }
        return view('auth.login');
    }

    public function register(Request $request)
    {
        if ($request->isMethod('post')) {
            // TODO: Implementasikan logika pendaftaran
            return redirect()->route('login');
        }
        return view('auth.register');
    }

    public function logout(Request $request)
    {
        // TODO: Implementasikan logika logout
        return redirect()->route('login');
    }
}
