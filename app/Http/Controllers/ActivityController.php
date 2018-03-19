<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Quiz;
use Carbon\Carbon;

/**
 * An Activity is either a quiz or an exam
 */
class ActivityController extends Controller
{
	public function publish(Request $request)
	{
		$activity_id = $request->get('activity_id');

		$activity = Quiz::find($activity_id);

		$activity->fill([
			'status' => Quiz::PUBLISHED
		]);

		$activity->update();

		return redirect()->back();
	}
}
