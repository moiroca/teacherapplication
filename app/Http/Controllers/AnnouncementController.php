<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use App\Models\Subject;
use App\Models\Module;
use Illuminate\Support\Facades\Storage;

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

    public function download(Request $request, $module_id)
    {
        $module = Module::find($module_id);
        return response()->download(storage_path('/app/' . $module->path));
    }
}
