<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use Auth;
use App\Models\Announcement;
use App\Models\Subject;

class HomeController extends Controller
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

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        $announcements = [];

        if (!$user->isTeacher()) {
            $studentSubjectIds = $user->subjects->pluck('id');

            $announcements = Announcement::with('subject')
                                    ->whereIn('subject_id', $studentSubjectIds)
                                    ->orderBy('announcements.created_at', 'DESC')
                                    ->get();
        } else {
            $teacherSubjects = Subject::where('teacher_id', $user->id)->get();

            if ($teacherSubjects) {
                $announcements = Announcement::with('subject')
                                    ->whereIn('subject_id', $teacherSubjects->pluck('id'))
                                    ->orderBy('created_at', 'DESC')
                                    ->get();
            }
        }

        return view('home', compact('announcements'));
    }
}
