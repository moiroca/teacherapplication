<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use App\Models\Subject;

class AnnouncementController extends Controller
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

    	return view('announcements.subjects', compact('subjects'));
	}
}
