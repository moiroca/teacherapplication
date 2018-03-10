<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Quiz;
use App\Models\QuizItem;
use App\Models\QuizItemPivot;
use App\Models\QuizOption;

class QuizItemController extends Controller
{
    const   IDENTIFICATION = 1,
            MULTIPLE_CHOICE = 2;
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
        
        if ($quiz->quiz_type == self::IDENTIFICATION) {
            return view('quiz.items.indentification', compact('quiz'));
        } 

    	return view('quiz.items.multiplechoice', compact('quiz'));
    }

    public function save(Request $request, $quiz_id)
    {
    	$options = $request->get('options');
    	$item    = $request->get('item');
    	$answer  = $request->get('answer');

        $quiz = Quiz::find($quiz_id);

        $quizItem = QuizItem::create([
            'question'  => $item,
            'quiz_item_type' => $quiz->quiz_type
        ]);

        // Save QuizItemPivot
        QuizItemPivot::create([
            'quiz_id'   => $quiz->id,
            'item_id'   => $quizItem->id
        ]);

        if ($quiz->quiz_type == self::IDENTIFICATION) {
            QuizOption::create([
                'quiz_item_id'  => $quizItem->id,
                'content'       => $answer,
                'is_correct'    => 1
            ]);
        } else {
            foreach ($options as $key => $option) {
                QuizOption::create([
                    'quiz_item_id'  => $quizItem->id,
                    'content'       => $option,
                    'is_correct'=> ($key == $answer) ? 1 : 0
                ]);
            }
        }

    	return redirect()->route('quiz.items.create', [$quiz_id]);
    }
}
