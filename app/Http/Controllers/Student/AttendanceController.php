<?php

namespace App\Http\Controllers\Student;

use Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Subject;

class AttendanceController extends Controller
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
		$subject = Subject::with('attendances')->find($subject_id);

		$studenStubjectAttendances = \DB::table('attendance')
								->selectRaw('attendance.id, DATE_FORMAT(attendance.date, "%Y-%m-%d") as date')
								->selectSub('
									select count(*) as is_present from students_attendance
									where students_attendance.student_id = '. Auth::user()->id .' 
									and students_attendance.attendance_id = attendance.id', 'is_present')
								->where('attendance.subject_id', $subject_id)
								->orderBy('date', 'DESC')
								->get();
								
		return view('students.attendances.index', compact('subject', 'studenStubjectAttendances'));
	}
}
