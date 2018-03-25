<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use App\Http\Requests\UpdateQuizPostRequest;
use App\Models\Quiz;
use App\Models\QuizItem;
use App\Models\QuizOption;
use App\Models\Subject;
use App\Models\Attempt;

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

    public function index(Request $request)
    {
        $subjects = Subject::where('teacher_id', Auth::user()->id)->get()->pluck('id');

        $quizzes  = Quiz::with(['subject', 'attempts'])
                        ->where('quiz_type', '!=', Quiz::EXAM)
                        ->whereIn('subject_id', $subjects)->get();

        return view('quiz.index', compact('quizzes'));
    }

    public function create(Request $request)
    {
        $subjects = Subject::where('teacher_id', Auth::user()->id)->get();
        return view('quiz.create', compact('subjects'));
    }

    public function updateIdentificationQuiz(UpdateQuizPostRequest $request)
    {
        $itemId     = $request->get('item_id');
        $optionId   = $request->get('option_id');
        $question   = $request->get('question');
        $answer     = $request->get('answer');

        // Update Quiz Item Question
        $quizItem   = QuizItem::find($itemId);
        $quizItem   = $this->updateQuizItem($quizItem, [
                            'question'  => $question
                        ]);

        $quizOption = QuizOption::find($optionId);
        $quizOption = $this->updateQuizOption($quizOption, [
            'content'   => $answer
        ]);

        return response()->json([
            'success'   => true
        ]);
    }

    public function save(Request $request)
    {
        try {
            $attempts = $request->get('attempts');

            $quiz = Quiz::create([
                'title'         => $request->get('title'),
                'subject_id'    => $request->get('subject_id'),
                'quiz_type'     => $request->get('type'),
                'duration'      => $request->get('duration'),
                'expiration'    => $request->get('expiration')
            ]);

            for ($i=1; $i <= $attempts ; $i++) { 
                Attempt::create([
                    'quiz_id'       => $quiz->id,
                    'expiration'    =>  $request->get('expiration')
                ]);
            }

            return redirect()->route('quiz.items.create', [ 'quiz_id' => $quiz->id ])->with('isSuccess', true);
        } catch (\Exception $e) {
            return redirect()->route('quiz.items.create', [ 'quiz_id' => $quiz->id ])->with('isSuccess', false);
        }
    }

    public function subjects(Request $request)
    {
        // Show List Of Subject That Has Quiz
        $subjects = Subject::where('teacher_id', Auth::user()->id)
                        ->addSelect(\DB::raw('count(quizzes.id) as exam_count, subjects.id as id, subjects.name as name'))
                        ->join('quizzes', 'quizzes.subject_id', '=', 'subjects.id')
                        ->groupBy('subjects.id')
                        ->where('quizzes.quiz_type', '!=', Quiz::EXAM)
                        ->get();

        return view('quiz.outputs.subjects', compact('subjects'));
    }

    public function subjectsQuizzes(Request $request, $subject_id)
    {
        // Show Quizzes Of Subjects
        $subject = Subject::find($subject_id);
        $exams   = Quiz::with('items')
                        ->where('quiz_type', '!=', Quiz::EXAM)
                        ->where('subject_id', $subject_id)
                        ->get();

        return view('quiz.outputs.list', compact('exams', 'subject'));
    }

    public function subjectsQuizResults(Request $request, $subject_id, $exam_id)
    {
        $exam = Quiz::with('items')->find($exam_id); // Show Result of Exam

        if (!$exam) {
            abort(404);
        }

        $subject = Subject::with('students')->find($subject_id);

        $enrolledStudents = $subject->students;
        
        // dd($enrolledStudents);
        // $studentExamResult = \DB::table(\DB::raw('users'))
        //                         ->leftJoin(\DB::raw('(
        //                             SELECT 
        //                                 users.id as student_id, student_quizzes.id as student_quiz_id from users
        //                             LEFT JOIN 
        //                                 student_quizzes on student_quizzes.student_id = users.id
        //                             WHERE 
        //                                 student_quizzes.quiz_id = '.$exam->id.'
        //                         ) as students_took_the_quizzes'), 'students_took_the_quizzes.student_id', '=', 'users.id')
        //                         ->leftJoin(\DB::raw('(
        //                             SELECT 
        //                                 student_quiz_answers.student_quiz_id as student_quiz_id,
        //                                 sum(if(question_answers.correct_answer = student_quiz_answers.answer, 1, 0)) as total
        //                             FROM (
        //                                 SELECT
        //                                     quiz_items.id as exam_item_id,
        //                                     if (quiz_items.quiz_item_type = 1, quiz_options.content, quiz_options.id) as correct_answer
        //                                 FROM
        //                                     quiz_items
        //                                 LEFT JOIN
        //                                     quiz_options on quiz_items.id = quiz_options.quiz_item_id
        //                                 JOIN
        //                                     quiz_items_pivot on quiz_items_pivot.item_id = quiz_items.id 
        //                                 WHERE
        //                                     quiz_options.is_correct = 1
        //                                 AND
        //                                     quiz_items_pivot.quiz_id = '. $exam->id .'
        //                             ) AS question_answers 
        //                             LEFT JOIN 
        //                                 `student_quiz_answers` ON `student_quiz_answers`.`quiz_item_id` = `question_answers`.`exam_item_id` 
        //                             GROUP BY student_quiz_answers.student_quiz_id
        //                         ) as total_score'), 'total_score.student_quiz_id', '=', 'students_took_the_quizzes.student_quiz_id')
        //                         ->selectRaw('
        //                             users.id as student_id,
        //                             users.name as student_name,
        //                             students_took_the_quizzes.student_quiz_id as student_quiz_id,
        //                             total_score.total AS score 
        //                         ')
        //                         ->whereIn('users.id', $enrolledStudents->pluck('id'))
        //                         ->get();

        return view('quiz.outputs.result', compact('enrolledStudents', 'exam'));
    }

    public function subjectsQuizResultsPerStudent(Request $request, $quiz_id, $student_id)
    {
        $quiz = Quiz::find($quiz_id);

        if (!$quiz) { abort(404); }

        $quizAttempts = Attempt::where('attempts.quiz_id', $quiz->id)
                                ->join('student_quizzes', 'student_quizzes.attempt_id', '=', 'attempts.id')
                                ->where('student_quizzes.student_id', $student_id)
                                ->where('student_quizzes.status', Attempt::COMPLETED)
                                ->selectRaw('attempts.id as attempt_id')
                                ->get();

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
                                        AND student_quizzes.student_id = '. $student_id .'
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

    public function quizAllowReview(Request $request)
    {
        $quiz_id = $request->get('quiz_id');

        $quiz = Quiz::find($quiz_id);
        $quiz->allow_review = !$quiz->allow_review;
        $quiz->update();

        return response()->json([
            'success'   => true
        ]);
    }
    
    private function updateQuizItem($quizItem, $data)
    {
        $quizItem->fill($data);
        $quizItem->update();

        return $quizItem;
    }

    private function updateQuizOption($quizOption, $data)
    {
        $quizOption->fill($data);
        $quizOption->update();

        return $quizOption;
    }
}
