<?php

namespace App\Http\Controllers\Student;

use Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Subject;

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

    public function enrollSubjects(Request $request)
    {
        $student = Auth::user();
        $enrolledSubjectIds = $student->subjects->pluck('id');

        $subjects = Subject::with('teacher')->whereNotIn('id', $enrolledSubjectIds)->get();

        return view('students.subjects.enroll_subjects', compact('subjects'));
    }
}
