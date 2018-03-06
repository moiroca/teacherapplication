<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use App\Models\Subject;

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
    	$subjects = Subject::where('teacher_id', Auth::user()->id)->get();

    	return view('subject.index', compact('subjects'));
    }

    public function create(Request $request)
    {
    	return view('subject.create', compact('subjects'));
    }

    public function save(Request $request)
    {
    	try {
    		Subject::create([
	    		'name'			=> $request->get('name'),
	    		'teacher_id'	=> Auth::user()->id,
	    	]);

	    	return redirect()->route('subject.create')->with('isSuccess', true);
    	} catch (\Exception $e) {
    		return redirect()->route('subject.create')->with('isSuccess', false);
    	}
    }
}
