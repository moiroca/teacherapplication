<?php

namespace App\Http\Controllers\Student;

use Auth;
use App\Models\Subject;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AnnouncementController extends Controller
{
	public function index(Request $request)
	{
		$student = Auth::user();
    	return view('students.announcements.index', compact('student'));
	}

	public function list(Request $request, $subject_id)
	{
		$subject = Subject::find($subject_id);

		return view('students.announcements.list', compact('subject'));
	}
}
