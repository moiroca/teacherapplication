<?php

namespace App\Http\Controllers\Student;

use Illuminate\Http\Request;
use App\Models\Subject;
use App\Http\Controllers\Controller;

class ModuleController extends Controller
{
	public function index(Request $request)
	{
		$user = \Auth::user();
		$subjects = $user->subjects;

		return view('students.modules.subject', compact('subjects'));
	}

	public function subjectModules(Request $request, $subject_id)
	{
		$subject = Subject::with('modules')->find($subject_id);

		return view('students.modules.list', compact('subject'));
	}
}
