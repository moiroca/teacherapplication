<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Quiz;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;

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
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // $this->seedTeachers();
        // $this->seedStudents();
        // $this->seedSubjects();
        // $this->seedQuiz();
    }

    private function seedTeachers()
    {
        factory(Teacher::class, 10)->create();

        echo "10 Teachers Seeded\n";
    }

    private function seedStudents()
    {
        factory(Student::class, 10)->create();

        echo "10 Students Seeded\n";
    }

    private function seedSubjects()
    {
        factory(Subject::class, 10)->create();

        echo "10 Subjects Seeded\n";
    }

    private function seedQuiz()
    {
        factory(Quiz::class, 10)->create();

        echo "10 quizzes Seeded\n";
    }
}
