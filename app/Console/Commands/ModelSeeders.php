<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Quiz;
use App\Models\QuizItem;
use App\Models\QuizOption;
use App\Models\Student;
use App\Models\QuizItemPivot;
use App\Models\Subject;
use App\Models\User;
use App\Models\Teacher;
use Faker\Generator;
use App\Models\Attempt;
use App\Models\SubjectStudent;
use App\Models\SchoolYear;
use App\Models\Announcement;

class ModelSeeders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'model:seed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seed Models With Data';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Generator $faker)
    {
        parent::__construct();
        $this->faker = $faker;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->seedAdmin();
        $this->seedTeachers();
        $this->seedStudents();
        $this->seedSchoolYear();
        $this->seedSubjects();
        $this->seedJohnDoeData();
        $this->enrollMikeFromDoeSubject();
        $this->seedAnnouncements();
    }

    private function seedAdmin()
    {
        // Default Admin
        User::create([
            'name' => 'Angel Macabangon',
            'username' => 'admin@example.com',
            'role'  => 3,
            'is_confirmed'  => 1,
            'password' => bcrypt('password'),
        ]);
        echo "\n Admin Seeded\n";
    }

    private function seedTeachers($count = 10)
    {
        // Default Teacher
        User::create([
            'name' => 'John Doe',
            'username' => 'john.doe@example.com',
            'role'  => 1,
            'is_confirmed'  => 1,
            'password' => bcrypt('password'),
        ]);

        for ($i=1; $i <= $count; $i++) { 
            $isConfirmed = rand(0,1);
            User::create([
                'name' => $this->faker->name,
                'username' => $this->faker->safeEmail,
                'role'  => 1,
                'is_confirmed' => $isConfirmed,
                'password' => bcrypt('password'),
            ]);
            echo '.';
        }

        echo "\n Teachers Seeded\n";
    }

    private function seedStudents($count = 10)
    {
        // Default Student
        User::create([
            'name' => 'Mike Gibson',
            'username' => '42424242',
            'role'  => 2,
            'is_confirmed' => 1,
            'password' => bcrypt('password'),
        ]);

        for ($i=1; $i <= $count; $i++) { 
            $isConfirmed = rand(0,1);
            User::create([
                'name' => $this->faker->name,
                'username' => uniqid(),
                'role'  => 2,
                'is_confirmed' => $isConfirmed,
                'password' => bcrypt('password'),
            ]);
            echo '.';
        }

        echo "\n Students Seeded\n";
    }

    private function seedSubjects($count = 5)
    {
        // Seed Only First 10 Teachers
        $teachers = User::where('role', 1)->where('is_confirmed', User::CONFIRMED)->limit(10)->get();

        $subjectTitle = ['Programming', 'IT Elective', 'Database Management', 'Linux Administration'];

        foreach ($teachers as $key => $teacher) {
            for ($i=0; $i < $count; $i++) { 
                Subject::create([
                    'name'  => $subjectTitle[rand(0,3)] . ' ' . ($i + 1),
                    'teacher_id'        => $teacher->id,
                    'school_year_id'    => 1,
                    'semester'          => 1,
                    'period'            => 1,
                    'enrollment_key'    => uniqid()
                ]);
                echo '.';
            }
        }

        echo "\n Teacher Subjects Seeded\n";
    }

    private function seedJohnDoeData()
    {
        $subjects = Subject::where('teacher_id', 2)->get();
        $expiration = \Carbon\Carbon::now()->addHours(3);

        $quizData = [
            'quiz_type' => Quiz::IDENTIFICATION,
            'status'    => Quiz::PUBLISHED,
            'duration' => 3,
            'expiration' => $expiration,
        ];

        $quizItems = [
            [
                'question'          => '1 + 1 is?',
                'quiz_item_type'    => QuizItem::IDENTIFICATION
            ],
            [
                'question'          => '1 + 2 is?',
                'quiz_item_type'    => QuizItem::IDENTIFICATION
            ],
        ];

        foreach ($subjects as $key => $subject) {
            $quizData['subject_id'] = $subject->id;
            $quizData['title']      = "Quiz " . " " . ($key + 1);

            $quiz = Quiz::create($quizData);

            for ($i=1; $i <= 2; $i++) { 
                $created_at = \Carbon\Carbon::now()->addDays($i);

                $attempt = Attempt::create([
                    'quiz_id'       => $quiz->id,
                    'created_at'    => $created_at,
                    'updated_at'    => $created_at,
                    'expiration'    => $expiration
                ]);
            }

            foreach ($quizItems as $key => $quizItemData) {
                $quizItem = QuizItem::create($quizItemData);

                $quizOption = QuizOption::create([
                    'quiz_item_id'  => $quizItem->id,
                    'content'       => 2,
                    'is_correct'    => 1
                ]);

                QuizItemPivot::create([
                    'quiz_id'   => $quiz->id,
                    'item_id'   => $quizItem->id
                ]);
                echo ".";
            }
        }

        echo "\n Teacher Subject Quiz Seeded\n";
    }

    private function enrollMikeFromDoeSubject($value='')
    {
        $subjects = Subject::where('teacher_id', 2)->get();

        foreach ($subjects as $key => $subject) {

            SubjectStudent::create([
                'subject_id'    => $subject->id,
                'student_id'    => 13
            ]);

            echo ".";
        }

        echo "\n Student Subject Enrolled Seeded\n";
    }

    private function seedSchoolYear()
    {
        $currentYear = \Carbon\Carbon::now()->year;

        for ($currentYear; $currentYear < config('app.max_year'); $currentYear++) { 
            SchoolYear::create([
                'from'  => $currentYear,
                'to'    => $currentYear + 1
            ]);
            echo ".";
        }

        echo "\n School Year Seeded \n";
    }

    public function seedAnnouncements()
    {
        $now = \Carbon\Carbon::now();

        for ($i=1; $i <= 3; $i++) { 
            Announcement::create([
                'content'       => $this->faker->realText(300, true),
                'subject_id'    => 1,
                'created_at'    => $now->subMinutes($i)
            ]);

            echo ".";
        }

        echo "\n Announcement Seeded \n";
    }
}
