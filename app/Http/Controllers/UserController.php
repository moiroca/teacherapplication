<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests\UpdateUserInformation;

class UserController extends Controller
{
	public function confirm(Request $request)
	{
		$user_id = $request->input('student_id');
		$isConfirm = $request->get('is_confirm');

		$user = User::find($user_id);

		$user->fill([
			'is_confirmed' => $isConfirm 
		]);

		$user->save();

		return response()->json([
			'success' => true
		]);
	}

	public function update(Request $request, $user_id)
	{
		$user = User::find($user_id);

		return view('admin.users.update', compact('user'));
	}

	public function saveUpdate(UpdateUserInformation $request)
	{
		$user_id	= $request->get('user_id');
		$name 		= $request->get('name');
		$password 	= $request->get('password');
		$email		= $request->get('email');
		$is_confirmed = $request->get('is_confirmed');

		$data = [
			'name' 			=> $name,
			'is_confirmed'	=> $is_confirmed,
			'password' 		=> bcrypt($password),
		];

		$user = User::find($user_id);
		$userWithEmail = User::where('email', $email)->first();

		if ($user->email != $email && $userWithEmail) {
			return redirect()->back()->withErrors(['email' => 'Email already in use.']);
		}

		if ($user->email != $email && !$userWithEmail) {
			$data['email'] = $email;
		}

		$user->fill($data);
		$user->save();

		return redirect()->back();
	}

	public function deleteTeacher(Request $request)
	{
		$user_id	= $request->get('teacher_id');

		$teacher = User::find($user_id)->delete();

		return response()->json([
			'success' => true
		]);
	}

	public function deleteStudent(Request $request)
	{
		$user_id	= $request->get('student_id');

		$teacher = User::find($user_id)->delete();

		return response()->json([
			'success' => true
		]);
	}
}
