<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests\UpdateUserInformation;
use App\Http\Requests\UserUpdateInfoPostRequest;

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

	/**
	 * Admin Tries To Update
	 */
	public function update(Request $request, $user_id)
	{
		$user = User::find($user_id);

		return view('admin.users.update', compact('user'));
	}

	/**
	 * User Tries To Update Own Profile
	 */
	public function userUpdate(Request $request)
	{
		$user = \Auth::user();

		return view('admin.users.info', compact('user'));
	}

	/**
	 * Save User Tries To Update Own Profile
	 */
	public function saveUserUpdate(UserUpdateInfoPostRequest $request)
	{
		$name 		= $request->get('name');
		$username	= $request->get('username');
		$password	= $request->get('password');

		$data = [
			'name'		=> $name,
			'password'	=> bcrypt($password)
		];

		$user = \Auth::user();
		$userWithEmail = User::where('username', $username)->first();

		if ($user->username != $username && $userWithEmail) {
			return redirect()->back()->withErrors(['username' => 'Username already in use.']);
		}

		if ($user->username != $username && !$userWithEmail) {
			$data['username'] = $username;
		}

		$user->fill($data);
		$user->save();

		return redirect()->back();
	}

	public function saveUpdate(UpdateUserInformation $request)
	{
		$user_id	= $request->get('user_id');
		$name 		= $request->get('name');
		$username		= $request->get('username');
		$is_confirmed = $request->get('is_confirmed');

		$data = [
			'name' 			=> $name,
			'is_confirmed'	=> $is_confirmed,
		];

		$user = User::find($user_id);
		$userWithEmail = User::where('username', $username)->first();

		if ($user->username != $username && $userWithEmail) {
			return redirect()->back()->withErrors(['username' => 'Email already in use.']);
		}

		if ($user->username != $username && !$userWithEmail) {
			$data['username'] = $username;
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
