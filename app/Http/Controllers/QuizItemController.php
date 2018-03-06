<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Quiz;
use App\Models\QuizItem;
use App\Models\QuizOption;

class QuizItemController extends Controller
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

    public function create(Request $request, $quiz_id)
    {
    	$quiz = Quiz::with('items', 'items.options')->find($quiz_id);

    	return view('quiz.items.create', compact('quiz'));
    }

    public function save(Request $request, $quiz_id)
    {
    	$options = $request->get('options');
    	$item    = $request->get('item');
    	$answer  = $request->get('answer');

    	$quizItem = QuizItem::create([
    		'quiz_id'	=> $quiz_id,
    		'question'		=> $item
    	]);

    	foreach ($options as $key => $option) {

    		QuizOption::create([
	    		'quiz_item_id'	=> $quizItem->id,
	    		'content'		=> $option,
	    		'is_correct'=> ($key == $answer) ? 1 : 0
	    	]);
    	}

    	return redirect()->route('quiz.items.create', [$quiz_id]);
    }
}
