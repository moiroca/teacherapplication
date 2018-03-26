<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Subject;
use App\Models\SubjectStudent;
use Auth;

class EnrollmentController extends Controller
{
	public function create(Request $request, $subject_id)
	{
		$subject = Subject::find($subject_id);

		$students = \DB::table('users')
					->selectRaw('
						users.id,
						users.username, 
						users.name, 
						count(student_subjects.subject_id) as is_enrolled
					')
					->leftJoin(\DB::raw('(
						(SELECT * from student_subjects where student_subjects.subject_id = ' . $subject->id . ') as student_subjects 
					)'), 'student_subjects.student_id', '=', 'users.id')
					->where('users.role', 2)
					->where('users.is_confirmed', 1)
					->groupBy('users.id')
					->orderBy('is_enrolled', "DESC")
					->get();
		return view('enrollments.subject', compact('subject', 'students'));
	}

	public function save(Request $request, $subject_id)
	{
		$student_id = $request->get('student_id');

		SubjectStudent::create([
			'subject_id' => $subject_id,
			'student_id' => $student_id
		]);

		return redirect()->route('enrollment.subject', [ 'subject_id' => $subject_id ]);
	}

	public function delete(Request $request, $subject_id)
	{
		$student_id = $request->get('student_id');

		SubjectStudent::where('subject_id', $subject_id)->where('student_id', $student_id)->delete();

		return redirect()->route('enrollment.subject', [ 'subject_id' => $subject_id ]);
	}

	public function saveStudentEnrollment(Request $request)
	{
		$enrollmentKey 	= $request->get('enrollment_key');
		$subjectId 		= $request->get('subject_id');

		$subject 		= Subject::find($subjectId);

		if ($enrollmentKey == $subject->enrollment_key) {
			SubjectStudent::create([
				'subject_id' => $subjectId,
				'student_id' => Auth::user()->id
			]);

			return redirect()->route('students.subjects');
		}

		return redirect()->back()->withErrors(['enrollment_key' => 'Enrollment Key Does Not Match.']);
	}
}
