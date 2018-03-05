<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
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

    public function create(Request $request)
    {
    	$subjects = Subject::where('teacher_id', Auth::user()->id)->get();

    	return view('quiz.create', compact('subjects'));
    }

    public function save(Request $request)
    {
    	try {
    		Subject::create([
	    		'name'			=> $request->get('name'),
	    		'teacher_id'	=> $request->get('teacher_id')
	    	]);

	    	return redirect()->route('quiz.create')->with('isSuccess', true);
    	} catch (\Exception $e) {
    		return redirect()->route('quiz.create')->with('isSuccess', false);
    	}
    }
}
