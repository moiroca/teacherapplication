<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;

class StudentController extends Controller
{
    public function index(Request $request)
    {
    	$student_id = $request->get('student_id');

    	$students = User::where('role', 2)->orderBy('is_confirmed', 'DESC');

    	if ($student_id) {
    		$students = $students->where('username', 'like', "%$student_id%");
    	}

    	$students = $students->paginate(10);

    	return view('admin.students.list', compact('students', 'student_id'));
    }
}
