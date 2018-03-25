<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\Subject;
use App\Models\SubjectStudent;
use App\Models\SubjectModule;
use App\Models\Quiz;
use App\Models\QuizItemPivot;
use App\Models\SchoolYear;

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
        $subjects = Subject::with(['schoolyear', 'students'])->where('teacher_id', Auth::user()->id)->get();

        return view('subject.index', compact('subjects'));
    }

    public function create(Request $request)
    {
        $schoolYears = SchoolYear::all();

        return view('subject.create', compact('subjects', 'schoolYears'));
    }

    public function save(Request $request)
    {
        try {
            Subject::create([
                'name'              => $request->get('name'),
                'teacher_id'        => Auth::user()->id,
                'school_year_id'    => $request->get('school_year'),
                'semester'          => $request->get('semester'),
                'enrollment_key'    => uniqid()
            ]);

            return redirect()->route('subject.index')->with('isSuccess', true);
        } catch (\Exception $e) {
            return redirect()->route('subject.create')->with('isSuccess', false);
        }
    }

    public function delete(Request $request, $subject_id)
    {
        // $hasDependency = false;
        // $isForce = $request->get('is_force', false);
        // $subject = Subject::with(['quizzes', 'attendances', 'modules', 'students'])->find($subject_id);

        Subject::find($subject_id)->delete();

        return response()->json([
            'success' => true,
            'has_dependency' => false,
            'msg' => "Success"
        ]);

        /*
        if (!$isForce) {
            $hasQuizDependency          = ($subject->quizzes->count() != 0) ? true : false;
            $hasAttendanceDependency    = ($subject->attendances->count() != 0) ? true : false;
            $hasModuleDependency        = ($subject->modules->count() != 0) ? true : false;
            $hasEnrolleeDependency      = ($subject->students->count() != 0) ? true : false;

            $msg = '';

            if ($hasQuizDependency || $hasAttendanceDependency || $hasModuleDependency || $hasEnrolleeDependency) {
                $hasDependency = true;
            }

            if ($hasDependency) {
                return response()->json([
                    'success' => true,
                    'has_dependency' => $hasDependency,
                    'msg' => $msg
                ]);
            }

            Subject::find($subject_id)->delete();

            return response()->json([
                'success' => true,
                'has_dependency' => false,
                'msg' => "Success"
            ]);
        } else {
            if ($subject->quizzes->count() != 0) {
                $quizIds = Quiz::where('subject_id', $subject->id)->get()->pluck('id');
                QuizItemPivot::whereIn('quiz_id', $quizIds->toArray())->delete();

                Quiz::where('subject_id', $subject->id)->delete();
            }

            if ($subject->attendances->count() != 0) {
                Attendance::where('subject_id', $subject->id)->delete();
            }

            if ($subject->modules->count() != 0) {
                SubjectModule::where('subject_id', $subject->id)->delete();
            }

            if ($subject->students->count() != 0) {
                SubjectStudent::where('subject_id', $subject->id)->delete();
            }

            Subject::find($subject_id)->delete();
            
            return response()->json([
                'success' => true,
                'has_dependency' => false,
                'msg' => "Success"
            ]);
        }
        */
    }
}
