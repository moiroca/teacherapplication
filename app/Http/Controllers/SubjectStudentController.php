<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Subject;
use App\Models\SubjectStudent;

class SubjectStudentController extends Controller
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

	public function index(Request $request, $subject_id)
	{
        $subject = Subject::find($subject_id);

		$subjectStudents = SubjectStudent::with('student')
                                    ->where('subject_id', $subject_id)
                                    ->get();

        return view('subject.students.index', compact('subjectStudents', 'subject_id', 'subject'));
	}
}
