<?php

namespace App\Http\Controllers\Student;

use Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Models\Quiz;
use App\Models\QuizItem;
use App\Models\StudentQuiz;
use App\Models\StudentQuizAnswer;

class QuizController extends Controller
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

    public function index(Request $request, $subject_id)
    {
    	$subject = Subject::find($subject_id);

    	return view('students.quizzes.index', compact('subject'));
    }

    /**
     * @todo Check if Quiz is in Draft Status
     * @todo Check if Student Has answered already specific Quiz Item
     *
     * @param Request $request
     * @param Int $quiz_id
     */
    public function take(Request $request, $quiz_id)
    {
    	$quiz = Quiz::find($quiz_id);

        $studentQuiz = $this->saveStudentQuiz($quiz->id);

        // Fetch Answered Items By Student
        $answeredQuizItems = StudentQuizAnswer::where('student_quiz_id', $studentQuiz->id)->get()->pluck('quiz_item_id');

        // Fetch Not Answered Quiz Item
        $currentQuizItem   = QuizItem::where('quiz_id', $quiz->id)->whereNotIn('id', $answeredQuizItems->toArray())->first();

        if (empty($currentQuizItem)) {
            return redirect()->route('students.quizzes.score', ['student_quiz_id' => $studentQuiz->id]);
        }

    	return view('students.quizzes.take', compact('currentQuizItem', 'quiz', 'studentQuiz'));
    }

    public function answer(Request $request, $quiz_id)
    {
        $studentQuizId  = $request->get('student_quiz_id');
        $quizOptionId   = $request->get('quiz_option_id');
        $quizItemId     = $request->get('quiz_item_id');

        StudentQuizAnswer::create([
            'student_quiz_id'   => $studentQuizId,
            'quiz_option_id'    => $quizOptionId,
            'quiz_item_id'      => $quizItemId
        ]);

        return redirect()->route('students.quizzes.take', ['quiz_id' => $quiz_id]);
    }

    public function score(Request $request, $student_quiz_id)
    {
        // Get All Student Quiz Answers
        $studentQuiz = StudentQuiz::find($student_quiz_id);
        $quiz = Quiz::find($studentQuiz->quiz_id);

        $studentQuizAnswers = \DB::table(\DB::raw('(
                select
                    quiz_items.quiz_id, 
                    quiz_items.id as quiz_item_id, 
                    quiz_items.question,
                    quiz_options.id as correct_answer,
                    quiz_options.content as content
                from
                    quiz_items
                left join
                    quiz_options on quiz_items.id = quiz_options.quiz_item_id 
                where
                    quiz_options.is_correct = 1 
                and 
                    quiz_items.quiz_id = ' . $quiz->id . '
                group by quiz_items.id 
            ) as question_answers'))
            ->selectRaw('
                question_answers.quiz_id, 
                question_answers.quiz_item_id, 
                question_answers.question, 
                question_answers.correct_answer,
                question_answers.content,
                student_quiz_answers.quiz_option_id,
                if(question_answers.correct_answer = student_quiz_answers.quiz_option_id, true, false) as is_correct
            ')
            ->leftJoin(
                    'student_quiz_answers', 
                    'student_quiz_answers.quiz_item_id', 
                    '=', 
                    'question_answers.quiz_item_id')
            ->where('student_quiz_answers.student_quiz_id', $student_quiz_id)
            ->get();

        $score = 0;
        
        return view('students.quizzes.score', compact('studentQuizAnswers', 'quiz', 'score'));
    }

    private function saveStudentQuiz($quiz_id)
    {
        return StudentQuiz::firstOrCreate([
            'quiz_id'   => $quiz_id,
            'student_id'=> Auth::user()->id
        ]);
    }
}
