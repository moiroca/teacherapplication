<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\Subject;
use App\Models\Quiz;
use App\Models\QuizItem;
use App\Models\QuizItemPivot;
use Illuminate\Http\Request;

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
		$subjects = Subject::where('teacher_id', Auth::user()->id)->get()->pluck('id');

    	$exams  = Quiz::with('subject')->where('quiz_type', Quiz::EXAM)->whereIn('id', $subjects)->get();

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

  //   	select quizzes.subject_id as id from quizzes
		// join quiz_items_pivot on quiz_items_pivot.quiz_id = quizzes.id
		// group by id
		$subjects = Subject::where('teacher_id', Auth::user()->id)->whereIn('subjects.id', $subjectWithQuizItems)->get();
    	return view('exams.create', compact('subjects'));
	}

	public function save(Request $request)
	{
		try {
    		$exam = Quiz::create([
	    		'title'			=> $request->get('title'),
	    		'subject_id'	=> $request->get('subject_id'),
                'quiz_type'     => $request->get('type')
	    	]);

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

	public function saveItems(Request $request, $exam_id)
	{
		$items = $request->get('items');
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
}
