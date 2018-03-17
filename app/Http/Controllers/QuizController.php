<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use App\Models\Quiz;
use App\Models\Subject;

class QuizController extends Controller
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
        $subjects = Subject::where('teacher_id', Auth::user()->id)->get()->pluck('id');
    	$quizzes  = Quiz::with('subject')->where('quiz_type', '!=', Quiz::EXAM)->whereIn('id', $subjects)->get();

    	return view('quiz.index', compact('quizzes'));
    }

    public function create(Request $request)
    {
    	$subjects = Subject::where('teacher_id', Auth::user()->id)->get();
    	return view('quiz.create', compact('subjects'));
    }

    public function save(Request $request)
    {
    	try {
    		$quiz = Quiz::create([
	    		'title'			=> $request->get('title'),
	    		'subject_id'	=> $request->get('subject_id'),
                'quiz_type'     => $request->get('type')
	    	]);

	    	return redirect()->route('quiz.items.create', [ 'quiz_id' => $quiz->id ])->with('isSuccess', true);
    	} catch (\Exception $e) {
    		return redirect()->route('quiz.items.create', [ 'quiz_id' => $quiz->id ])->with('isSuccess', false);
    	}
    }

    public function subjects(Request $request)
    {
        // Show List Of Subject That Has Quiz
        $subjects = Subject::where('teacher_id', Auth::user()->id)
                        ->addSelect(\DB::raw('count(quizzes.id) as exam_count, subjects.id as id, subjects.name as name'))
                        ->join('quizzes', 'quizzes.subject_id', '=', 'subjects.id')
                        ->groupBy('subjects.id')
                        ->where('quizzes.quiz_type', '!=', Quiz::EXAM)
                        ->get();

        return view('quiz.outputs.subjects', compact('subjects'));
    }

    public function subjectsQuizzes(Request $request, $subject_id)
    {
        // Show Quizzes Of Subjects
        $subject = Subject::find($subject_id);
        $exams   = Quiz::with('items')
                        ->where('quiz_type', '!=', Quiz::EXAM)
                        ->where('subject_id', $subject_id)
                        ->get();

        return view('quiz.outputs.list', compact('exams', 'subject'));
    }

    public function subjectsQuizResults(Request $request, $subject_id, $exam_id)
    {
        $exam = Quiz::with('items')->find($exam_id); // Show Result of Exam

        if (!$exam) { abort(404); }

        $subject = Subject::with('students')->find($subject_id);
        $enrolledStudents = $subject->students;

        $studentExamResult = \DB::table(\DB::raw('users'))
                                ->leftJoin(\DB::raw('(
                                    SELECT 
                                        users.id as student_id, student_quizzes.id as student_quiz_id from users
                                    LEFT JOIN 
                                        student_quizzes on student_quizzes.student_id = users.id
                                    WHERE 
                                        student_quizzes.quiz_id = '.$exam->id.'
                                ) as students_took_the_quizzes'), 'students_took_the_quizzes.student_id', '=', 'users.id')
                                ->leftJoin(\DB::raw('(
                                    SELECT 
                                        student_quiz_answers.student_quiz_id as student_quiz_id,
                                        sum(if(question_answers.correct_answer = student_quiz_answers.answer, 1, 0)) as total
                                    FROM (
                                        SELECT
                                            quiz_items.id as exam_item_id,
                                            if (quiz_items.quiz_item_type = 1, quiz_options.content, quiz_options.id) as correct_answer
                                        FROM
                                            quiz_items
                                        LEFT JOIN
                                            quiz_options on quiz_items.id = quiz_options.quiz_item_id
                                        JOIN
                                            quiz_items_pivot on quiz_items_pivot.item_id = quiz_items.id 
                                        WHERE
                                            quiz_options.is_correct = 1
                                        AND
                                            quiz_items_pivot.quiz_id = '. $exam->id .'
                                    ) AS question_answers 
                                    LEFT JOIN 
                                        `student_quiz_answers` ON `student_quiz_answers`.`quiz_item_id` = `question_answers`.`exam_item_id` 
                                    GROUP BY student_quiz_answers.student_quiz_id
                                ) as total_score'), 'total_score.student_quiz_id', '=', 'students_took_the_quizzes.student_quiz_id')
                                ->selectRaw('
                                    users.id as student_id,
                                    users.name as student_name,
                                    students_took_the_quizzes.student_quiz_id as student_quiz_id,
                                    total_score.total AS score 
                                ')
                                ->whereIn('users.id', $enrolledStudents->pluck('id'))
                                ->get();

        return view('quiz.outputs.result', compact('studentExamResult', 'exam'));
    }
}
