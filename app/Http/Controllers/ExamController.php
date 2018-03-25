<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\Attempt;
use App\Models\Subject;
use App\Models\Quiz;
use App\Models\QuizItem;
use App\Models\QuizItemPivot;
use App\Models\StudentQuiz;
use Illuminate\Http\Request;
use App\Http\Requests\CreateExamPostRequest;

class ExamController extends Controller
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
		$subjects = Subject::where('teacher_id', Auth::user()->id)
							->get()
							->pluck('id');

    	$exams  = Quiz::with('subject')
    					->where('quiz_type', Quiz::EXAM)
    					->whereIn('subject_id', $subjects)
    					->get();

    	$subjectQuizItemCount = \DB::table('quizzes')
    								->select('*')
    								->join('quiz_items_pivot', 'quiz_items_pivot.quiz_id', '=', 'quizzes.id')
    								->whereIn('quizzes.subject_id', $subjects)
    								->get();

		return view('exams.index', compact('subjects', 'exams', 'subjectQuizItemCount'));
	}
	
	public function create(Request $request)
	{
		$subjectWithQuizItems = \DB::table('quizzes')
    								->selectRaw('quizzes.subject_id as id')
    								->join('quiz_items_pivot', 'quiz_items_pivot.quiz_id', '=', 'quizzes.id')
    								->groupBy('id')
    								->get()
    								->pluck('id')
    								->toArray();

		$subjects = Subject::where('teacher_id', Auth::user()->id)->whereIn('subjects.id', $subjectWithQuizItems)->get();

    	return view('exams.create', compact('subjects'));
	}

	public function save(Request $request)
	{
		try {
    		$exam = Quiz::create([
	    		'title'			=> $request->get('title'),
	    		'period'		=> $request->get('period'),
	    		'subject_id'	=> $request->get('subject_id'),
                'quiz_type'     => $request->get('type'),
                'duration'      => $request->get('duration'),
                'expiration'    => $request->get('expiration')
	    	]);

    		$attempts = $request->get('attempts');

    		for ($i=1; $i <= $attempts ; $i++) { 
                Attempt::create([
                    'quiz_id'   => $exam->id
                ]);
            }
	    	return redirect()->route('exams.items.create', [ 'exam_id' => $exam->id ])->with('isSuccess', true);
    	} catch (\Exception $e) {
    		return redirect()->route('exams.items.create', [ 'exam_id' => $exam->id ])->with('isSuccess', false);
    	}
	}

	public function createItems(Request $request, $exam_id)
	{
		$exam = Quiz::with('items', 'items.options')->find($exam_id);

		$collatedSubjectQuestions = QuizItem::select('quiz_items.id', 'quiz_items.question', 'quiz_items.quiz_item_type')
										->with('options')
										->join('quiz_items_pivot', 'quiz_items_pivot.item_id', '=', 'quiz_items.id')
										->whereIn('quiz_items_pivot.quiz_id', function($query) use ($exam) {
											$query->select('id')->from('quizzes')->where('subject_id', $exam->subject_id);
										})
										->groupBy('quiz_items.id')
										->get();

        return view('exams.items.create', compact('exam', 'collatedSubjectQuestions', 'exam'));
	}

	public function saveItems(CreateExamPostRequest $request, $exam_id)
	{
		$items = $request->get('items', []);

		$exam = Quiz::find($exam_id);

		// Delete Past Items
		QuizItemPivot::where('quiz_id', $exam->id)->delete();
		
		foreach ($items as $key => $item) {
			QuizItemPivot::create([
				'quiz_id'	=> $exam->id,
				'item_id'	=> $item
			]);
		}

		return redirect()->route('exams.items.create', [ 'exam_id' => $exam->id ])->with('isSuccess', true);
	}

	public function subjects(Request $request)
	{
		$subjects = Subject::where('teacher_id', Auth::user()->id)
						->addSelect(\DB::raw('count(quizzes.id) as exam_count, subjects.id as id, subjects.name as name'))
						->join('quizzes', 'quizzes.subject_id', '=', 'subjects.id')
						->groupBy('subjects.id')
						->where('quizzes.quiz_type', Quiz::EXAM)
						->get();

		return view('exams.outputs.subjects', compact('subjects'));
	}

	public function subjectsExams(Request $request, $subject_id)
	{
		$subject = Subject::find($subject_id);

		$exams  = \DB::table('quizzes')
						->selectRaw(\DB::raw("quizzes.title, quizzes.id, quizzes.allow_review, quizzes.subject_id, quiz_count_table.exam_item_count"))
						->join(\DB::raw('
							(
								SELECT 
									count(quiz_items_pivot.id) as exam_item_count, 
									quiz_items_pivot.quiz_id 
								FROM quiz_items_pivot
								GROUP BY quiz_id
						 	) as quiz_count_table'), 'quiz_count_table.quiz_id', '=', 'quizzes.id')
						->where('quizzes.subject_id', $subject->id)
						->where('quiz_type', Quiz::EXAM)
						->get();
						
		return view('exams.outputs.list', compact('exams', 'subject'));
	}

	public function subjectsExamResults(Request $request, $subject_id, $exam_id)
	{
		$exam = Quiz::with('items')->find($exam_id);	// Show Result of Exam

		if (!$exam) { abort(404); }

		$subject = Subject::with('students')->find($subject_id);
		$enrolledStudents = $subject->students;

		// $studentExamResult = \DB::table(\DB::raw('users'))
		// 						->leftJoin(\DB::raw('(
		// 							SELECT 
		// 								users.id as student_id, student_quizzes.id as student_quiz_id from users
		// 							LEFT JOIN 
		// 								student_quizzes on student_quizzes.student_id = users.id
		// 							WHERE 
		// 								student_quizzes.quiz_id = '.$exam->id.'
		// 						) as students_took_the_quizzes'), 'students_took_the_quizzes.student_id', '=', 'users.id')
		// 						->leftJoin(\DB::raw('(
		// 							SELECT 
		// 								student_quiz_answers.student_quiz_id as student_quiz_id,
		// 								sum(if(question_answers.correct_answer = student_quiz_answers.answer, 1, 0)) as total
		// 							FROM (
		// 								SELECT
		// 									quiz_items.id as exam_item_id,
		// 									if (quiz_items.quiz_item_type = 1, quiz_options.content, quiz_options.id) as correct_answer
		// 								FROM
		// 									quiz_items
		// 								LEFT JOIN
		// 									quiz_options on quiz_items.id = quiz_options.quiz_item_id
		// 								JOIN
		// 									quiz_items_pivot on quiz_items_pivot.item_id = quiz_items.id 
		// 								WHERE
		// 									quiz_options.is_correct = 1
		// 								AND
		// 									quiz_items_pivot.quiz_id = '. $exam->id .'
		// 							) AS question_answers 
		// 							LEFT JOIN 
		// 								`student_quiz_answers` ON `student_quiz_answers`.`quiz_item_id` = `question_answers`.`exam_item_id` 
		// 							GROUP BY student_quiz_answers.student_quiz_id
		// 						) as total_score'), 'total_score.student_quiz_id', '=', 'students_took_the_quizzes.student_quiz_id')
		// 						->selectRaw('
		// 							users.id as student_id,
		// 							users.name as student_name,
		// 							students_took_the_quizzes.student_quiz_id as student_quiz_id,
		// 							total_score.total AS score 
		// 						')
		// 						->whereIn('users.id', $enrolledStudents->pluck('id'))
		// 						->get();

		return view('exams.outputs.result', compact('enrolledStudents', 'exam'));
	}
}
