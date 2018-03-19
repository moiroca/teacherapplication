<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;

class StudentController extends Controller
{
    public function index(Request $request)
    {
    	$students = User::where('role', 2)->orderBy('is_confirmed', 'DESC')->get();

    	return view('admin.students.list', compact('students'));
    }
}
