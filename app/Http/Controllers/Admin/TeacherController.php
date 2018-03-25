<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Subject;
use App\Http\Requests\AdminTeacherPostRequest;

class TeacherController extends Controller
{
    public function index(Request $request)
    {
    	$teachers = User::where('role', 1)
    					->orderBy('is_confirmed', 'DESC')
    					->get();

    	return view('admin.teachers.list', compact('teachers'));
    }

    public function create(Request $request)
    {
        return view('admin.teachers.create');
    }

    public function subjects(Request $request, $teacher_id)
    {
    	$subjects = Subject::with('students')
    					->where('teacher_id', $teacher_id)
    					->get();

    	return view('admin.teachers.subjects.list', compact('subjects'));
    }

    public function save(AdminTeacherPostRequest $request)
    {
        $name = $request->get('name');
        $username = $request->get('username');

        User::create([
            'name'      => $name,
            'username'  => $username,
            'password'  => bcrypt($username),
            'role'      => 1,
            'is_confirmed' => 1
        ]);

        return redirect()->route('admin.teachers');
    }
}
