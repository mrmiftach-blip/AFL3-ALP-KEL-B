<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class JobController extends Controller
{
    public function explore(Request $request)
    {
        return view('job.list');
    }

    public function single(string $id, Request $request)
    {
        if ($request->isMethod('post')) {
            // TODO: Implementasikan logika 1-Click Apply
            return redirect()->back();
        }
        return view('job.single');
    }
}
