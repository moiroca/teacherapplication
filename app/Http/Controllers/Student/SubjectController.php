<?php

namespace App\Http\Controllers\Student;

use Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SubjectController extends Controller
{
	/**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
    	$student = Auth::user();
    	return view('students.subjects.index', compact('student'));
    }
}
