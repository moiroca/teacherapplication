<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SubjectStudent;
use App\Models\Attendance;
use App\Models\Subject;
use App\Models\StudentAttendance;

class AttendanceController extends Controller
{
	public function index(Request $request, $subject_id)
	{
        $subject = Subject::find($subject_id);
		$attendances = Attendance::with('inattendance')->where('subject_id', $subject_id)->get();

		return view('attendance.index', compact('attendances', 'subject'));
	}

	public function show(Request $request, $attendance_id)
	{
		$attendance = Attendance::find($attendance_id);

		$subjectStudents = SubjectStudent::with('student')
    								->where('subject_id', $attendance->subject_id)
    								->get();
                                    
    	$studentAttendance = StudentAttendance::where('attendance_id', $attendance->id)->get()->pluck('student_id');

		return view('attendance.show', [
			'attendance'		=> $attendance,
			'subjectStudents'	=> $subjectStudents,
			'studentAttendance' => $studentAttendance->toArray()
		]);
	}

    public function create(Request $request, $subject_id)
    {
    	$subjectStudents = SubjectStudent::with('student')
    								->where('subject_id', $subject_id)
    								->get();

		return view('attendance.create', compact('subjectStudents', 'subject_id'));
    }

    public function save(Request $request, $subject_id)
    {
    	try {
    		$date = $request->get('date');
    		$present = $request->get('present');

    		$attendance  = Attendance::create([
    			'subject_id'	=> $subject_id,
    			'date'			=> $date
    		]);

    		foreach ($present as $student_id => $on) {
    			StudentAttendance::create([
	    			'student_id'	=> $student_id,
	    			'attendance_id'	=> $attendance->id
    			]);
    		}

    		return redirect()
    					->route('subject.students.attendance.index', ['subject_id' => $subject_id])
    					->with('isSuccess', true);
    	} catch (\Exception $e) {
    		return redirect()
    					->route('subject.students.attendance.index', ['subject_id' => $subject_id])
    					->with('isSuccess', false);
    	}
    }

    public function toggle(Request $request, $attendance_id)
    {
        $attendance = Attendance::find($attendance_id);
        $student_id = $request->get('student_id');
        $value      = $request->get('value');
        $data       = [
            'student_id'    => $student_id,
            'attendance_id' => $attendance->id
        ];

        $studentAttendance = StudentAttendance::where($data)->first();

        if ($studentAttendance) {
            $studentAttendance->delete();
        } else {
            StudentAttendance::create($data);
        }

        return response()->json([
            'success'   => true
        ]);
    }
}
