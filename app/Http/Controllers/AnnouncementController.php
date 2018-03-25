<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use App\Models\Subject;
use App\Models\Module;
use App\Models\Announcement;
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

    public function list(Request $request, $subject_id)
    {
        $subject = Subject::find($subject_id);
        $announcements = Announcement::where('subject_id', $subject->id)
                                    ->orderBy('created_at', 'DESC')
                                    ->get();

        return view('announcements.index', compact('announcements', 'subject'));
    }

    public function create(Request $request, $subject_id)
    {
        $subject = Subject::find($subject_id);

        return view('announcements.create', compact('subject'));
    }

    public function save(Request $request, $subject_id)
    {
        Announcement::create([
            'content'   => $request->get('content'),
            'subject_id'=> $subject_id
        ]);

        return redirect()->route('announcements.list', ['subject_id' => $subject_id]);
    }
}
