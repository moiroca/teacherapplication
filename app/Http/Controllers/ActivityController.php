<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attempt;
use App\Models\Quiz;
use App\Models\StudentQuizAnswer;
use App\Models\StudentQuiz;
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

	public function forceSubmit(Request $request)
	{
		$student_quiz_id = $request->get('student_quiz_id');

		$studentQuiz = StudentQuiz::find($student_quiz_id);
		$attempt     = Attempt::find($studentQuiz->attempt_id);
		$quiz 		 = Quiz::with('items')->find($attempt->quiz_id);

		$quizItems  =  $quiz->items;

		// Get Quiz Item With Answer 
		$studentQuizAnswers  = StudentQuizAnswer::where('student_quiz_id', $student_quiz_id)->get();

		$quizItemsWithAnswer = ($studentQuizAnswers) ? $studentQuizAnswers->pluck('quiz_item_id')->toArray() : [];

		foreach ($quizItems as $key => $quizItem) {
			 
			if (!in_array($quizItem->id, $quizItemsWithAnswer)) {
			 	StudentQuizAnswer::create([
					'quiz_item_id' 		=> $quizItem->id,
					'student_quiz_id' 	=> $student_quiz_id,
					'answer' 			=> null, 
				]);
			}
		}

		$studentQuiz->fill([
			'status' => Attempt::COMPLETED
		]);

		$studentQuiz->update();

		return response()->json([
			'success' => true,
			'redirect' => route('students.quizzes.score', [
				'student_quiz_id' => $student_quiz_id
			])
		]);
	}

	public function updateActivityDuration(Request $request)
	{
		$activity_id 	= $request->get('activity_id');
		$activity_type  = $request->get('activity_type');
		$duration 		= $request->get('duration');

		$activity = Quiz::find($activity_id);
		$activity->duration = $duration;
		$activity->update();

		if ($activity_type == Quiz::EXAM) {
			return redirect()->route('exams.index');
		}
		return redirect()->route('quiz.index');
	}
}
