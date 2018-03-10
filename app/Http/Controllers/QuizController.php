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
}
