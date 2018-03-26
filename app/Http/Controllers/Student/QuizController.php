<?php

namespace App\Http\Controllers\Student;

use Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Attempt;
use App\Models\Subject;
use App\Models\Quiz;
use App\Models\QuizItem;
use App\Models\StudentQuiz;
use App\Models\StudentQuizAnswer;
use Carbon\Carbon;

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

        $activities = \DB::table('quizzes')
                        ->leftJoin(\DB::raw('(
                            SELECT 
                                count(*) as attempts, 
                                attempts.quiz_id as quiz_id
                            FROM student_quizzes
                            JOIN attempts ON attempts.id = student_quizzes.attempt_id
                            WHERE student_quizzes.student_id = ' . Auth::user()->id . '
                            AND student_quizzes.status = '. Attempt::COMPLETED .' 
                            GROUP BY quiz_id
                        ) as student_quiz_attempts'), 'student_quiz_attempts.quiz_id', '=', 'quizzes.id')
                        ->join('attempts', 'attempts.quiz_id', 'quizzes.id')
                        ->selectRaw('
                            IF (student_quiz_attempts.attempts IS NULL, 0, student_quiz_attempts.attempts) as attempts, 
                            quizzes.id, 
                            quizzes.title,
                            quizzes.expiration,
                            quizzes.time,
                            quizzes.duration,
                            count(attempts.id) as total_attempt
                        ')
                        ->where('subject_id', $subject_id)
                        ->where('status', Quiz::PUBLISHED)
                        ->groupBy('quizzes.id')
                        ->orderBy('quizzes.expiration', 'DESC')
                        ->get();    

    	return view('students.quizzes.index', compact('subject', 'activities'));
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
    	$quiz = Quiz::with('attempts')->find($quiz_id);

        $studentQuizAttempts    = $this->getStudentQuizAttempts($quiz->id);

        $quizAttemptIds         = [];

        if ($studentQuizAttempts) {
            $quizAttemptIds = $studentQuizAttempts->pluck('attempt_id');
        }

        // Get Current Attempted Quiz By Student
        $currentAttemptedQuiz = StudentQuiz::join('attempts', 'attempts.id', 'student_quizzes.attempt_id')
                                            ->where('attempts.quiz_id', $quiz->id)
                                            ->where('status', Attempt::NOT_COMPLETED)
                                            ->first();
        $studentQuizData = [
            'student_id'    => Auth::user()->id,
            'status'        => Attempt::NOT_COMPLETED
        ];

        if ($currentAttemptedQuiz) {
            $attempt = Attempt::find($currentAttemptedQuiz->attempt_id);
        } else {
            $attempt = $this->getLatestQuizAttempt($quiz_id, $quizAttemptIds);
            

            if (2 == $quiz->time) {
                $end_datetime = \Carbon\Carbon::now()->addMinutes($quiz->duration);
            } else {
                // Uncomment Later
                $end_datetime = \Carbon\Carbon::now()->addHours($quiz->duration);
            }

            $studentQuizData['end_datetime'] = $end_datetime;
        }

        if (!$attempt) {
            dd("YOU DONT HAVE QUIZ ATTEMPTS REMAINING");
        }


        $studentQuizData['attempt_id'] = $attempt->id;

        $studentQuiz = $this->saveStudentQuiz($studentQuizData);

        // Fetch Answered Items By Student
        $answeredQuizItems = StudentQuizAnswer::where('student_quiz_id', $studentQuiz->id)->get()->pluck('quiz_item_id');

        // Fetch Not Answered Quiz Item
        $currentQuizItem   = QuizItem::where('quiz_items_pivot.quiz_id', $quiz->id)
                                        ->select('quiz_items.id','quiz_items.question','quiz_items.quiz_item_type')
                                        ->join('quiz_items_pivot', 'quiz_items_pivot.item_id', '=', 'quiz_items.id')
                                        ->where('quiz_items_pivot.quiz_id', $quiz->id)
                                        ->whereNotIn('quiz_items.id', $answeredQuizItems->toArray())
                                        ->first();

        if (empty($currentQuizItem)) {
            $studentQuiz = StudentQuiz::find($studentQuiz->id);

            $studentQuiz->update([
                'status'    => Attempt::COMPLETED
            ]);

            return redirect()->route('students.quizzes.score', ['student_quiz_id' => $studentQuiz->id]);
        }

        $now = \Carbon\Carbon::now();
        $quizEndDateTime = \Carbon\Carbon::parse($studentQuiz->end_datetime);
        $secondsRemaining = $quizEndDateTime->diffInSeconds($now);

        if ($quizEndDateTime->lt($now)) {
            dd("Expired");
        }

    	return view('students.quizzes.take', compact('currentQuizItem', 'quiz', 'studentQuiz'));
    }

    public function answer(Request $request, $quiz_id)
    {
        $studentQuizId  = $request->get('student_quiz_id');
        $quizOptionId   = $request->get('quiz_option_id');
        $quizItemId     = $request->get('quiz_item_id');
        $answer         = $request->get('answer');

        $quizItem = QuizItem::find($quizItemId);

        $studentAnswer = [
            'student_quiz_id'   => $studentQuizId,
            'quiz_item_id'      => $quizItemId
        ];

        if ($quizItem->quiz_item_type == QuizItem::IDENTIFICATION) {
            $studentAnswer['answer']    = $answer;
        } else {
            $studentAnswer['answer']    = $quizOptionId;
        }

        StudentQuizAnswer::create($studentAnswer);

        return redirect()->route('students.quizzes.take', ['quiz_id' => $quiz_id]);
    }

    public function score(Request $request, $student_quiz_id)
    {
        // Get All Student Quiz Answers
        $studentQuiz = StudentQuiz::find($student_quiz_id);

        $attempt    = Attempt::find($studentQuiz->attempt_id);
        $quiz       = Quiz::find($attempt->quiz_id);

        $studentQuizAttempts = $this->getStudentQuizAttempts($quiz->id);

        $studentQuizAnswers = \DB::table(\DB::raw('(
                select
                    quiz_items_pivot.quiz_id,
                    quiz_items.id as quiz_item_id,
                    quiz_items.question,
                    quiz_options.content as correct_answer,
                    quiz_options.content as content,
                    quiz_items.quiz_item_type
                from quiz_items
                left join quiz_options on quiz_items.id = quiz_options.quiz_item_id
                join quiz_items_pivot on quiz_items_pivot.item_id = quiz_items.id 
                where quiz_options.is_correct = 1
                and quiz_items.quiz_item_type = 1
                and quiz_items_pivot.quiz_id = ' . $quiz->id . '
                group by quiz_items.id

                UNION

                select
                    quiz_items_pivot.quiz_id,
                    quiz_items.id as quiz_item_id,
                    quiz_items.question,
                    quiz_options.id as correct_answer,
                    quiz_options.content as content,
                    quiz_items.quiz_item_type
                from quiz_items
                left join quiz_options on quiz_items.id = quiz_options.quiz_item_id
                join quiz_items_pivot on quiz_items_pivot.item_id = quiz_items.id 
                where quiz_options.is_correct = 1
                and quiz_items.quiz_item_type = 2
                and quiz_items_pivot.quiz_id = ' . $quiz->id . '
            ) as question_answers'))
            ->selectRaw('
                question_answers.quiz_id, 
                question_answers.quiz_item_id, 
                question_answers.question, 
                question_answers.correct_answer,
                question_answers.content,
                student_quiz_answers.answer,
                if(question_answers.quiz_item_type = 2, quiz_options.content, student_quiz_answers.answer) as student_answer,
                if(question_answers.correct_answer = student_quiz_answers.answer, 1, 0) as is_correct
            ')
            ->leftJoin(
                'student_quiz_answers', 
                'student_quiz_answers.quiz_item_id', 
                '=', 
                'question_answers.quiz_item_id'
            )
            ->leftJoin(
                'quiz_options',
                'quiz_options.id',
                '=',
                'student_quiz_answers.answer'
            )
            ->join('student_quizzes', 'student_quizzes.id', '=', 'student_quiz_answers.student_quiz_id')
            ->where('student_quiz_answers.student_quiz_id', $student_quiz_id)
            ->where('student_quizzes.attempt_id', $attempt->id)
            ->get();

        $score = 0;
        
        return view('students.quizzes.score', compact('studentQuizAnswers', 'quiz', 'score'));
    }

    public function viewQuizAttemptsResult(Request $request, $quiz_id)
    {
        $quiz = Quiz::find($quiz_id);

        if (!$quiz) { abort(404); }

        $quizAttempts = $this->getStudentQuizAttempts($quiz->id);

        $studentActivityResult = \DB::table('attempts')
                                    ->selectRaw("
                                        attempts.id AS attempt_id,
                                        attempts.quiz_id AS attempt_quiz_id,
                                        attempts.expiration AS attempt_expiration,
                                        student_quiz_attempts.student_quiz_id,
                                        student_quiz.total AS score
                                    ")
                                    ->leftJoin(\DB::raw('(
                                        SELECT 
                                            attempts.id AS attempt_id,
                                            student_quizzes.id AS student_quiz_id
                                        FROM
                                            attempts
                                        LEFT JOIN
                                            student_quizzes ON attempts.id = student_quizzes.attempt_id
                                        WHERE
                                            attempts.quiz_id = '. $quiz->id .'
                                        AND student_quizzes.student_id = '. Auth::user()->id .'
                                    ) as student_quiz_attempts'), 'student_quiz_attempts.attempt_id', '=', 'attempts.id')
                                    ->leftJoin(\DB::raw('(
                                        SELECT 
                                            student_quiz_answers.student_quiz_id AS student_quiz_id,
                                            SUM(IF(question_answers.correct_answer = student_quiz_answers.answer, 1, 0)) AS total
                                        FROM
                                            (
                                            SELECT 
                                                quiz_items.id AS exam_item_id,
                                                IF(quiz_items.quiz_item_type = 1, quiz_options.content, quiz_options.id) AS correct_answer
                                            FROM
                                                quiz_items
                                            LEFT JOIN quiz_options ON quiz_items.id = quiz_options.quiz_item_id
                                            JOIN quiz_items_pivot ON quiz_items_pivot.item_id = quiz_items.id
                                            WHERE
                                                quiz_options.is_correct = 1
                                            AND quiz_items_pivot.quiz_id = '. $quiz->id .'
                                        ) AS question_answers
                                        LEFT JOIN
                                            `student_quiz_answers` 
                                        ON `student_quiz_answers`.`quiz_item_id` = `question_answers`.`exam_item_id`
                                        GROUP BY student_quiz_answers.student_quiz_id 
                                    ) as student_quiz'), 'student_quiz.student_quiz_id', '=', 'student_quiz_attempts.student_quiz_id')
                                    ->where('attempts.quiz_id', $quiz->id)
                                    ->get();

        // Get Score Per Quiz Attempt
        return view('students.quizzes.attempts_result', compact('studentActivityResult', 'quiz'));
    }

    private function getStudentQuizAttempts($quizId)
    {
        return Attempt::where('attempts.quiz_id', $quizId)
                                ->join('student_quizzes', 'student_quizzes.attempt_id', '=', 'attempts.id')
                                ->where('student_quizzes.student_id', Auth::user()->id)
                                ->where('student_quizzes.status', Attempt::COMPLETED)
                                ->selectRaw('attempts.id as attempt_id')
                                ->get();
    }

    private function getLatestQuizAttempt($quizId, $attemptIds = [])
    {
        return Attempt::whereNotIn('attempts.id', $attemptIds)->where('quiz_id', $quizId)->first();
    }

    private function saveStudentQuiz($data)
    {
        return StudentQuiz::firstOrCreate($data);
    }
}
