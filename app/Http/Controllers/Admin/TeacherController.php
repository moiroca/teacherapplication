<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Subject;

class TeacherController extends Controller
{
    public function index(Request $request)
    {
    	$teachers = User::where('role', 1)->get();

    	return view('admin.teachers.list', compact('teachers'));
    }

    public function subjects(Request $request, $teacher_id)
    {
    	$subjects = Subject::with('students')->where('teacher_id', $teacher_id)->get();

    	return view('admin.teachers.subjects.list', compact('subjects'));
    }
}
